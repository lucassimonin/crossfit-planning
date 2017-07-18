<?php
namespace AppBundle\Helper;

use AppBundle\Entity\User;

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
}
