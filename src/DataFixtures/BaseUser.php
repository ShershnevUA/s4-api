<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class BaseUser extends Fixture
{

    private $encoder;

    /**
     * BaseUser constructor.
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = (new User())
            ->setUsername('admin')
            ->setEmail('alex.shershnev.ua@gmail.com')
        ;

        $password = $this->encoder->encodePassword($user, '123321123');
        $user->setPassword($password);

        $manager->persist($user);
        $manager->flush();
    }
}
