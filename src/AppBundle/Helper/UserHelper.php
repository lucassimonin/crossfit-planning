<?php
namespace AppBundle\Helper;

use AppBundle\Entity\User;
use AppBundle\Entity\Strength;
use AppBundle\Entity\Wod;

/**
 * Created by PhpStorm.
 * User: lucas
 * Date: 06/07/2017
 * Time: 10:19
 */
class UserHelper
{
    /**
     * @param User $user
     * @param $sessionId
     * @return bool
     */
    public function isInThisSession(User $user, int $sessionId): bool
    {
        foreach ($user->getSessions() as $session) {
            if ($session->getId() === $sessionId) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param Strength|Wod $elementsNeededTest
     * @param \DateTime $date
     * @return bool
     */
    public function alreadyTraningAtThisDate($elementsNeededTest, $date)
    {
        foreach ($elementsNeededTest as $element) {
            if($date->getTimestamp() === $element->getDate()->getTimestamp()) {
                return true;
            }
        }

        return false;
    }
}
