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
        $session->setStartTime(41400);
        $session->setEndTime(45000);

        $manager->persist($session);
        $manager->flush();

        $session = new Session();
        $session->setDay(Session::MONDAY);
        $session->setStartTime(64840);
        $session->setEndTime(68400);

        $manager->persist($session);
        $manager->flush();

        $session = new Session();
        $session->setDay(Session::MONDAY);
        $session->setStartTime(61240);
        $session->setEndTime(64840);

        $manager->persist($session);
        $manager->flush();

        $session = new Session();
        $session->setDay(Session::TUESDAY);
        $session->setStartTime(41400);
        $session->setEndTime(45000);

        $manager->persist($session);
        $manager->flush();

        $session = new Session();
        $session->setDay(Session::TUESDAY);
        $session->setStartTime(64840);
        $session->setEndTime(68400);

        $manager->persist($session);
        $manager->flush();

        $session = new Session();
        $session->setDay(Session::TUESDAY);
        $session->setStartTime(61240);
        $session->setEndTime(64840);

        $manager->persist($session);
        $manager->flush();

        $session = new Session();
        $session->setDay(Session::WEDNESDAY);
        $session->setStartTime(41400);
        $session->setEndTime(45000);

        $manager->persist($session);
        $manager->flush();

        $session = new Session();
        $session->setDay(Session::WEDNESDAY);
        $session->setStartTime(64840);
        $session->setEndTime(68400);

        $manager->persist($session);
        $manager->flush();

        $session = new Session();
        $session->setDay(Session::WEDNESDAY);
        $session->setStartTime(61240);
        $session->setEndTime(64840);

        $manager->persist($session);
        $manager->flush();

        $session = new Session();
        $session->setDay(Session::THURSDAY);
        $session->setStartTime(41400);
        $session->setEndTime(45000);

        $manager->persist($session);
        $manager->flush();

        $session = new Session();
        $session->setDay(Session::THURSDAY);
        $session->setStartTime(64840);
        $session->setEndTime(68400);

        $manager->persist($session);
        $manager->flush();

        $session = new Session();
        $session->setDay(Session::THURSDAY);
        $session->setStartTime(61240);
        $session->setEndTime(64840);

        $manager->persist($session);
        $manager->flush();

        $session = new Session();
        $session->setDay(Session::FRIDAY);
        $session->setStartTime(41400);
        $session->setEndTime(45000);

        $manager->persist($session);
        $manager->flush();

        $session = new Session();
        $session->setDay(Session::FRIDAY);
        $session->setStartTime(64840);
        $session->setEndTime(68400);

        $manager->persist($session);
        $manager->flush();

        $session = new Session();
        $session->setDay(Session::FRIDAY);
        $session->setStartTime(61240);
        $session->setEndTime(64840);

        $manager->persist($session);
        $manager->flush();

        $session = new Session();
        $session->setDay(Session::SATURDAY);
        $session->setStartTime(41400);
        $session->setEndTime(45000);

        $manager->persist($session);
        $manager->flush();

        $session = new Session();
        $session->setDay(Session::SATURDAY);
        $session->setStartTime(64840);
        $session->setEndTime(68400);

        $manager->persist($session);
        $manager->flush();

        $session = new Session();
        $session->setDay(Session::SATURDAY);
        $session->setStartTime(61240);
        $session->setEndTime(64840);

        $manager->persist($session);
        $manager->flush();
    }
}
