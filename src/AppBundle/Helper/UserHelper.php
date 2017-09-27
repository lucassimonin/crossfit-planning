<?php
namespace AppBundle\Helper;

use AppBundle\Entity\User;
use AppBundle\Entity\Strength;
use AppBundle\Entity\Wod;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
/**
 * Created by PhpStorm.
 * User: lucas
 * Date: 06/07/2017
 * Time: 10:19
 */
class UserHelper
{
    /**
     * @var int
     */
    private $userByPage;

    /**
     * @var EntityRepository
     */
    private $userRepository;
    /**
     * UserHelper constructor.
     * @param int $userByPage
     * @param EntityManager $entityManager
     */
    public function __construct(int $userByPage, EntityManager $entityManager)
    {
        $this->userByPage = $userByPage;
        $this->userRepository = $entityManager->getRepository(User::class);
    }


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

    /**
     * @return int
     */
    public function countUser(): int
    {
        $qb = $this->userRepository->createQueryBuilder('u');
        $qb->select('COUNT(u)');
        // Don't count admin user
        return intval($qb->getQuery()->getSingleScalarResult()) - 1;
    }

    /**
     * @param int $page
     * @return array
     */
    public function CreatePagination(int $page = 1) : array
    {
        $paginator = $this->userRepository->getAllUsers($page, $this->userByPage);

        $maxPages = intval(ceil($paginator->count() / $this->userByPage));

        return ['users' => $paginator->getIterator(), 'maxPages' => $maxPages, 'thisPage' => $page];

    }
}
