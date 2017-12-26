<?php

namespace App\Repository;

use App\Entity\Structure;
use App\Entity\StructureLanguage;
use App\Entity\StructureType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
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

    public static function getForProduct(EntityRepository $repository)
    {
        $type = $repository->getEntityManager()->getRepository(StructureType::class)->findBy(['name'=>'Category']);
        if (empty($type[0])) return [];
        $type = $type[0];

        return $repository->createQueryBuilder('s')
            ->leftJoin('s.children', 'sc')
            ->where('s.type = :type')->setParameter('type', $type)
            ->andWhere('sc.id IS NULL')
            ;
    }
}
