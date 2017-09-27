<?php
/**
 * Created by PhpStorm.
 * User: lucas
 * Date: 27/09/2017
 * Time: 17:23
 */

namespace AppBundle\Repository;


use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{

    /**
     * @param int $currentPage
     * @param int $limit
     * @return Paginator
     */
    public function getAllUsers(int $currentPage = 1, int $limit = 10): Paginator
    {
        $query = $this->createQueryBuilder('u')
            ->getQuery();

        return $this->paginate($query, $currentPage, $limit);
    }

    /**
     *
     * @param Query $dql
     * @param int $page
     * @param int $limit
     * @return Paginator
     */
    public function paginate(Query $dql, int $page = 1, int $limit = 10): Paginator
    {
        $paginator = new Paginator($dql);

        $paginator->getQuery()
            ->setFirstResult($limit * ($page - 1)) // Offset
            ->setMaxResults($limit); // Limit

        return $paginator;
    }

}
