<?php
/**
 * Created by PhpStorm.
 * User: user13
 * Date: 04.07.18
 * Time: 23:11
 */

namespace App\Service;


use App\Entity\Chanel;
use App\Entity\Message;
use App\Entity\User;
use App\Form\ChanelMessageType;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\View\View;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MessageService
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
     * @param Chanel $chanel
     * @param User $user
     * @return View
     */
    public function createMessage( Request $request, Chanel $chanel, User $user )
    {
        if( !$chanel->inChanel( $user ) ){
            return new View(
                [
                    'message' => sprintf( '%s is not a member of the channel', $member->getUsername()),
                    'code' => Response::HTTP_FORBIDDEN
                ],
                Response::HTTP_FORBIDDEN
            );
        }

        $message = new Message( $user );

        $form = $this->formFactory->create(ChanelMessageType::class, $message );

        $form->submit( $request->request->all() );
        if ( !$form->isValid() ) {
            return new View( $form->getErrors(), Response::HTTP_BAD_REQUEST );
        }

        $chanel->addMessage($message);
        $this->entityManager->flush();

        return new View([
                'message'   => "Message has been sent",
                'object'    => $message
            ], Response::HTTP_OK
        );
    }

    public function getMessages( Chanel $chanel, User $user )
    {
        if( !$chanel->inChanel( $user ) ){
            return new View(
                [
                    'message' => sprintf( '%s is not a member of the channel', $user->getUsername()),
                    'code' => Response::HTTP_FORBIDDEN
                ],
                Response::HTTP_FORBIDDEN
            );
        }
        $result = $this->entityManager->getRepository(Message::class)->getChanelMessages($chanel);

        return new View( $result, Response::HTTP_OK);
    }
}