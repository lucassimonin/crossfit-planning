<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Session;
use Doctrine\ORM\EntityRepository;

class SessionRepository extends EntityRepository
{
    public function findAllOrderBy(array $orderBy = []): array
    {
        return $this->findBy([], $orderBy);
    }

    public function getExistingSession(Session $session): array
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT t1 FROM AppBundle:Session t1 WHERE t1.day = :day AND t1.id IN (SELECT t2.id FROM AppBundle:Session t2 WHERE (t2.startTime > :startTime AND t2.endTime < :endTime) OR (t2.startTime < :startTime AND t2.endTime > :endTime) OR (t2.endTime > :startTime AND t2.startTime < :startTime) OR (t2.startTime < :endTime AND t2.endTime > :endTime))'
            )
            ->setParameter('day', $session->getDay())
            ->setParameter('startTime', $session->getStartTime())
            ->setParameter('endTime', $session->getEndTime())
            ->getResult();
    }
}
