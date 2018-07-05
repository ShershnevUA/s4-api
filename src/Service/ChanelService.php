<?php
/**
 * Created by PhpStorm.
 * User: user13
 * Date: 04.07.18
 * Time: 13:15
 */

namespace App\Service;


use App\Entity\Chanel;
use App\Entity\User;
use App\Form\ChanelMemberType;
use App\Form\ChanelType;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\View\View;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Class ChanelService
 * @package App\Service
 */
class ChanelService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * ChanelService constructor.
     * @param EntityManagerInterface $entityManager
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        FormFactoryInterface $formFactory
    )
    {
        $this->entityManager = $entityManager;
        $this->formFactory = $formFactory;
    }

    /**
     * @param Request $request
     * @param User $owner
     * @return View
     */
    public function createChanel(Request $request, User $owner )
    {
        $chanel = new Chanel();
        $form = $this->formFactory->create(ChanelType::class, $chanel );

        $form->submit( $request->request->all() );
        if ( !$form->isValid() ) {
            return new View( $form->getErrors(), Response::HTTP_BAD_REQUEST );
        }
        $chanel->setOwner( $owner );
        $this->entityManager->persist( $chanel );
        $this->entityManager->flush();

        return new View( $chanel);
    }

    /**
     * @param Request $request
     * @param Chanel $chanel
     * @param User $owner
     * @return View
     */
    public function addMember( Request $request, Chanel $chanel, User $owner)
    {
        if( $chanel->getPrivate() === true && $chanel->getOwner() !== $owner ){
            throw new AccessDeniedHttpException();
        }

        $form = $this->formFactory->create(ChanelMemberType::class );

        $form->submit( $request->request->all() );
        if ( !$form->isValid() ) {
            return new View( $form->getErrors(), Response::HTTP_BAD_REQUEST );
        }
        /** @var User $member */
        $member = $form->getData()['member'];
        if( $chanel->inChanel( $member ) ){
            return new View(
                [
                    'message' => sprintf( '%s already added to the channel', $member->getUsername()),
                    'code' => Response::HTTP_OK
                ],
                Response::HTTP_OK
            );
        };
        $chanel->addMember( $member );
        $this->entityManager->flush();

        return new View(
            [
                'message' => sprintf( '%s added %s to the channel', $owner->getUsername(), $member->getUsername()),
                'code' => Response::HTTP_OK
            ],
            Response::HTTP_OK
        );
    }

    public function deleteMember(User $member, Chanel $chanel, User $owner)
    {
        if( $chanel->getOwner() !== $owner ){
            throw new AccessDeniedHttpException();
        }

        if( !$chanel->inChanel( $member ) ){
            return new View(
                [
                    'message' => sprintf( '%s is not a member of the channel', $member->getUsername()),
                    'code' => Response::HTTP_OK
                ],
                Response::HTTP_OK
            );
        };
        $chanel->removeMember($member);
        $this->entityManager->flush();
        return new View(
            [
                'message'   => sprintf( '%s has been remove from the channel', $member->getUsername()),
                'code'      => Response::HTTP_OK,
                'obj'       => $chanel
            ],
            Response::HTTP_OK
        );
    }

    public function searchMember( Request $request, Chanel $chanel)
    {
        $result = $this
            ->entityManager
            ->getRepository(User::class)
            ->searchInChanel( $request->query->get('q', ''), $chanel);
         return new View( $result, Response::HTTP_OK);
    }

    public function searchChanel($request)
    {
        $result = $this
            ->entityManager
            ->getRepository(Chanel::class)
            ->getChannels( $request->query->get('q', ''));

        return new View( $result, Response::HTTP_OK);
    }
}