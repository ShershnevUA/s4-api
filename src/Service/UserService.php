<?php
/**
 * Created by PhpStorm.
 * User: user13
 * Date: 04.07.18
 * Time: 1:27
 */

namespace App\Service;


use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\View\View;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTManager;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Form\FormFactoryBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserService
{
    /**
     * @var EntityManager
     */
    private $entityManager;
    /**
     * @var FormFactoryBuilderInterface
     */
    private $formFactory;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;
    /**
     * @var JWTManager
     */
    private $JWTManager;


    /**
     * UserService constructor.
     * @param EntityManagerInterface $entityManager
     * @param FormFactoryInterface $formFactory
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param JWTTokenManagerInterface $JWTManager
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        FormFactoryInterface $formFactory,
        UserPasswordEncoderInterface $passwordEncoder,
        JWTTokenManagerInterface $JWTManager
    )
    {
        $this->entityManager = $entityManager;
        $this->formFactory = $formFactory;
        $this->passwordEncoder = $passwordEncoder;
        $this->JWTManager = $JWTManager;
    }


    /**
     * @param Request $request
     * @return \FOS\RestBundle\View\View
     */
    public function getUsers(Request $request)
    {
        $view = new View(
            $this->entityManager->getRepository(User::class)->findAll(),
            Response::HTTP_OK
        );
        return $view;
    }

    /**
     * @param Request $request
     * @return View
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function registration(Request $request )
    {
        $user = new User();
        $form = $this->formFactory->create(UserType::class, $user);

        $form->submit($request->request->all());
        if (!$form->isValid()) {
            return new View($form->getErrors(), Response::HTTP_BAD_REQUEST);
        }

        $password = $this->passwordEncoder->encodePassword($user, $user->getPlainPassword());
        $user->setPassword($password);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new View([
            'message' => "User has been created",
            "token" => $this->JWTManager->create($user)
            ], Response::HTTP_OK);
    }
}