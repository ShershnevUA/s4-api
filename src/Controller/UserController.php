<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\UserService;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class RestUserController
 * @package App\Controller
 */
class UserController extends FOSRestController
{
    /**
     * @var UserService
     */
    private $userService;

    /**
     * RestUserController constructor.
     * @param UserService $userService
     */
    public function __construct( UserService $userService )
    {
        $this->userService = $userService;
    }

    /**
     * @Rest\Get("/users")
     * @Rest\View(templateVar="users", serializerGroups={"users_list"})
     * @param Request $request
     * @return View
     */
    public function cget( Request $request )
    {
        return $this->userService->getUsers( $request );
    }

    /**
     * @Rest\Get("/users/{user}")
     * @ParamConverter("user", class="App\Entity\User")
     * @Rest\View(templateVar="user", serializerGroups={"single_user"})
     * @param User $user
     * @return User
     */
    public function getUserById( User $user )
    {
        return $user;
    }

    /**
     * @Rest\Post("/registration")
     * @param Request $request
     * @return View
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function postUser( Request $request )
    {
        return $this->userService->registration($request);
    }

    /**
     * @Rest\Get("/my-profile/channels")
     * @Rest\View( serializerGroups={"list_channels"})
     * @return View
     */
    public function getMyChannels()
    {
        return $this->userService->getMyChannels( $this->getUser() );
    }
}
