<?php
namespace AppBundle\Helper;

use AppBundle\Entity\Session;

/**
 * Created by PhpStorm.
 * User: lucas
 * Date: 06/07/2017
 * Time: 10:19
 */
class SessionHelper
{
    private $maxUserBySession;

    /**
     * SessionHelper constructor.
     * @param int $maxUserBySession
     */
    public function __construct(int $maxUserBySession)
    {
        $this->maxUserBySession = $maxUserBySession;
    }


    /**
     * @param Session $session
     * @return bool
     */
    public function isStarted(Session $session): bool
    {
        $currentHour = strtotime(date('H:i'));
        $currentDay = intval(date("w"));
        $sessionStartHour = strtotime(date('H:i', $session->getStartTime()));
        if ($session->getDay() < $currentDay || ($session->getDay() == $currentDay && $currentHour >= $sessionStartHour)) {
            return true;
        }

        return false;
    }

    public function isMaxUsers(Session $session): bool
    {
        return (count($session->getUsers()) === $this->maxUserBySession);
    }
}
