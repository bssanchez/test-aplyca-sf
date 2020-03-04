<?php

namespace App\DataFixtures;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

use App\Entity\User;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $this->createUser($manager);
    }

    private function createUser(ObjectManager $manager)
    {
        $user = new User();

        $user->setEmail('user1@correo.com')
            ->setUsername('user1')
            ->setFullname('Usuario Uno')
            ->setRoles(['ROLE_USER']);

        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'Qwerty123'
        ));

        $manager->persist($user);
        $manager->flush();

        $this->addReference('user', $user);
    }
}
