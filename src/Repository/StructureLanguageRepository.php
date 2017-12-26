<?php

namespace App\Repository;

use App\Entity\Language;
use App\Entity\Structure;
use App\Entity\StructureLanguage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\Query\Expr;


class StructureLanguageRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, StructureLanguage::class);
    }

    public function getLang(Structure $entity, string $lang)
    {
        $langItem = $this->_getLangItem($entity, $lang);

        return empty($langItem[0]) ? [] : $langItem[0];
    }

    private function _getLangItem($entity, $lang)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.structure = :id')
            ->andWhere('s.language = :lang')
            ->setParameter('lang', $lang)
            ->setParameter('id', $entity->getId())
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
    }

    public static function getForStructure(EntityRepository $repository)
    {
        return $repository->createQueryBuilder('c')
            ->leftJoin('App:RoleCheckpoint', 'rc', Expr\Join::WITH, 'rc.checkpoint = c.id')
            ->where('rc.id IS NULL')
//            ->orWhere('rc.role != :role')->setParameter('role', $roleId)
            ;

    }
}
