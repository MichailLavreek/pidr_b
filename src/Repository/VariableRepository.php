<?php

namespace App\Repository;

use App\Entity\Variable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class VariableRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Variable::class);
    }

    /*
    public function findBySomething($value)
    {
        return $this->createQueryBuilder('v')
            ->where('v.something = :value')->setParameter('value', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
}
