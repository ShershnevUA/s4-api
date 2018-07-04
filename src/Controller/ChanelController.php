<?php

namespace App\Controller;

use App\Entity\Chanel;
use App\Entity\User;
use App\Service\ChanelService;
use App\Service\UserService;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ChanelController extends FOSRestController
{
    /**
     * @var ChanelService
     */
    private $chanelService;
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * ChanelController constructor.
     * @param ChanelService $chanelService
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(
        ChanelService $chanelService,
        TokenStorageInterface $tokenStorage
    )
    {
        $this->chanelService = $chanelService;
        $this->tokenStorage = $tokenStorage;
    }


    /**
     * @Rest\Post("/channels")
     * @param Request $request
     * @return View
     */
    public function post( Request $request )
    {
        return $this->chanelService->createChanel( $request, $this->tokenStorage->getToken()->getUser() );
    }

    /**
     * @Rest\Post("/channels/{chanel}/add-member")
     * @ParamConverter("chanel", class="App\Entity\Chanel")
     * @param Request $request
     * @param Chanel $chanel
     * @return View
     */
    public function chanelAddMember( Request $request, Chanel $chanel )
    {
        return $this->chanelService->addMember( $request, $chanel, $this->tokenStorage->getToken()->getUser() );
    }

    /**
     * @Rest\Delete("/channels/{chanel}/delete-member/{member}")
     * @ParamConverter("chanel", class="App\Entity\Chanel")
     * @ParamConverter("member", class="App\Entity\User")
     * @param Chanel $chanel
     * @param User $member
     * @return View
     */
    public function chanelDeleteMember( Chanel $chanel, User $member )
    {
        return $this->chanelService->deleteMember( $member, $chanel, $this->tokenStorage->getToken()->getUser() );
    }

    /**
     * @Rest\Get("/channels/{chanel}/search-member")
     * @ParamConverter("chanel", class="App\Entity\Chanel")
     * @param Request $request
     * @param Chanel $chanel
     * @return
     */
    public function searchUsersForAdd(Request $request, Chanel $chanel){
        return $this->chanelService->searchMember($request, $chanel);
    }

    /**
     * @Rest\Get("/channels")
     * @param Request $request
     * @return
     */
    public function searchChanel(Request $request){
        return $this->chanelService->searchChanel($request);
    }

}
