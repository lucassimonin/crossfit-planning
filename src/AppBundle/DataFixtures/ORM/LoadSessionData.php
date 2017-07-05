<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Session;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadSessionData implements FixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $session = new Session();
        $session->setDay(Session::MONDAY);
        $session->setStartTime(45000);
        $session->setEndTime(48600);

        $manager->persist($session);
        $manager->flush();

        $session = new Session();
        $session->setDay(Session::MONDAY);
        $session->setStartTime(68400);
        $session->setEndTime(72000);

        $manager->persist($session);
        $manager->flush();

        $session = new Session();
        $session->setDay(Session::MONDAY);
        $session->setStartTime(64800);
        $session->setEndTime(68400);

        $manager->persist($session);
        $manager->flush();

        $session = new Session();
        $session->setDay(Session::TUESDAY);
        $session->setStartTime(45000);
        $session->setEndTime(48600);

        $manager->persist($session);
        $manager->flush();

        $session = new Session();
        $session->setDay(Session::TUESDAY);
        $session->setStartTime(68400);
        $session->setEndTime(72000);

        $manager->persist($session);
        $manager->flush();

        $session = new Session();
        $session->setDay(Session::TUESDAY);
        $session->setStartTime(64800);
        $session->setEndTime(68400);

        $manager->persist($session);
        $manager->flush();

        $session = new Session();
        $session->setDay(Session::WEDNESDAY);
        $session->setStartTime(45000);
        $session->setEndTime(48600);

        $manager->persist($session);
        $manager->flush();

        $session = new Session();
        $session->setDay(Session::WEDNESDAY);
        $session->setStartTime(68400);
        $session->setEndTime(72000);

        $manager->persist($session);
        $manager->flush();

        $session = new Session();
        $session->setDay(Session::WEDNESDAY);
        $session->setStartTime(64800);
        $session->setEndTime(68400);

        $manager->persist($session);
        $manager->flush();

        $session = new Session();
        $session->setDay(Session::THURSDAY);
        $session->setStartTime(45000);
        $session->setEndTime(48600);

        $manager->persist($session);
        $manager->flush();

        $session = new Session();
        $session->setDay(Session::THURSDAY);
        $session->setStartTime(68400);
        $session->setEndTime(72000);

        $manager->persist($session);
        $manager->flush();

        $session = new Session();
        $session->setDay(Session::THURSDAY);
        $session->setStartTime(64800);
        $session->setEndTime(68400);

        $manager->persist($session);
        $manager->flush();

        $session = new Session();
        $session->setDay(Session::FRIDAY);
        $session->setStartTime(45000);
        $session->setEndTime(48600);

        $manager->persist($session);
        $manager->flush();

        $session = new Session();
        $session->setDay(Session::FRIDAY);
        $session->setStartTime(68400);
        $session->setEndTime(72000);

        $manager->persist($session);
        $manager->flush();

        $session = new Session();
        $session->setDay(Session::FRIDAY);
        $session->setStartTime(64800);
        $session->setEndTime(68400);

        $manager->persist($session);
        $manager->flush();

        $session = new Session();
        $session->setDay(Session::SATURDAY);
        $session->setStartTime(45000);
        $session->setEndTime(48600);

        $manager->persist($session);
        $manager->flush();

        $session = new Session();
        $session->setDay(Session::SATURDAY);
        $session->setStartTime(68400);
        $session->setEndTime(72000);

        $manager->persist($session);
        $manager->flush();

        $session = new Session();
        $session->setDay(Session::SATURDAY);
        $session->setStartTime(64800);
        $session->setEndTime(68400);

        $manager->persist($session);
        $manager->flush();


    }
}
