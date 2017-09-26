<?php
/**
 * Created by PhpStorm.
 * User: lucas
 * Date: 26/09/2017
 * Time: 17:32
 */

namespace Tests\AppBundle\Helper;

use AppBundle\Entity\Session;
use AppBundle\Entity\User;
use AppBundle\Helper\SessionHelper;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


class SessionHelperTest extends KernelTestCase
{
    /**
     * @var SessionHelper
     */
    private $sessionHelper;

    /**
     * @var int
     */
    private $maxUserSession;

    protected function setUp()
    {
        self::bootKernel();
        $this->maxUserSession = self::$kernel->getContainer()->getParameter('max_user_by_session');

        $this->sessionHelper = new SessionHelper($this->maxUserSession);
    }

    public function provideSessions()
    {
        $session = new Session();
        $session->setDay(intval(date("w")));
        $session->setStartTime(strtotime("+1 day"));
        yield [true, $session];

        $session = new Session();
        $session->setDay(intval(date("w")) + 1);
        yield [false, $session];
    }

    /**
     * @dataProvider provideSessions
     */
    public function testSessionStarted($expected, $session)
    {
        $this->assertEquals($expected, $this->sessionHelper->isStarted($session));
    }

    public function testMaxUser()
    {
        $session = new Session();

        for ($i = 0; $i < $this->maxUserSession; $i++) {
            $session->addUser(new User());
        }
        $this->assertTrue($this->sessionHelper->isMaxUsers($session));
    }

}
