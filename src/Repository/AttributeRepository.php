<?php

namespace App\Repository;

use App\Entity\Attribute;
use App\Entity\Product;
use App\Entity\ProductAttributeValue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class AttributeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Attribute::class);
    }

    public function getForFilter($structure, $language)
    {
        $attributes = $this
            ->createQueryBuilder('a')
            ->leftJoin(ProductAttributeValue::class, 'av', \Doctrine\ORM\Query\Expr\Join::WITH, 'av.attribute = a.id')
            ->leftJoin(Product::class, 'p', \Doctrine\ORM\Query\Expr\Join::WITH, 'av.product = p.id')
            ->where('p.structure = :structure')->setParameter('structure', $structure)
            ->getQuery()
            ->getResult()
        ;

        foreach ($attributes as  $attribute) {
            $attributeLang = null;
            foreach ($attribute->getLang() as $lang) {
                if ($lang->getLanguage() === $language) {
                    $attributeLang = $lang;
                    break;
                }
            }

            if (!empty($attributeLang)) {
                $attribute->setLang($attributeLang);
            }
        }

        return $attributes;
    }

//    public function find($id, $lockMode = null, $lockVersion = null)
//    {
//        return $this->createQueryBuilder('a')
//            ->where('a.id = :id')->setParameter('id', $id)
//            ->join('a.lang', 'lang')->addSelect('lang')
//            ->leftJoin('language', 'l', \Doctrine\ORM\Query\Expr\Join::WITH, 'lang.language = l.id')
//            ->andWhere('l.iso2 = :' . $_SERVER['CURRENT_LANGUAGE'])
//            ->getFirstResult()
//            ;
//    }
}
