<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\Structure;
use App\Entity\StructureLanguage;
use App\Entity\StructureType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Config\Definition\Exception\Exception;

class StructureRepository extends ServiceEntityRepository
{
    private $em;

    public function __construct(RegistryInterface $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, Structure::class);
        $this->em = $em;
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

    public function countContentItems(Structure $structure, $query)
    {
        $categoryType = $this->em->getRepository(StructureType::class)->findOneBy(['name'=>'Category']);

        if ($structure->getType() !== $categoryType) {
            throw new Exception('Wrong structure');
        }

        $count = $this->em->getRepository(Product::class)->countProducts($structure, $query);

        return $count;
    }
}
