<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\UserBundle\Util\UserManipulator;

class LoadUserData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('admin');
        $user->setEmail('admin@admin.com');
        $user->setPlainPassword('admin');
        $user->setLastName('admin');
        $user->setFirstName('admin');
        $user->setPhone('00');
        $user->setEnabled(true);
        $user->setSuperAdmin(true);

        $manager->persist($user);
        $manager->flush();
    }
}
