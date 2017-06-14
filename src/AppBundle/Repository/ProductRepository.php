<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ProductRepository extends EntityRepository
{
    public function findAllOrderBy($orderBy = [])
    {
        return $this->findBy([], $orderBy);
    }
}
