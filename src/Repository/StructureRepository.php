<?php

namespace App\Repository;

use App\Entity\Structure;
use App\Entity\StructureLanguage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class StructureRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Structure::class);
    }

    public function findAllWithLang($locale)
    {
        $structures = $this->createQueryBuilder('s')
            ->getQuery()
            ->getResult();

        foreach ($structures as $key => $structure) {
            $structureLang = $this->getEntityManager()->getRepository(StructureLanguage::class)
                ->getLang($structure, $locale);
            $structures[$key]->setLang($structureLang);
        }

        return $structures;
    }

    /*
    public function findBySomething($value)
    {
        return $this->createQueryBuilder('s')
            ->where('s.something = :value')->setParameter('value', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
}
