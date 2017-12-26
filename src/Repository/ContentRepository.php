<?php

namespace App\Repository;

use App\Entity\Content;
use App\Entity\ContentLanguage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ContentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Content::class);
    }

    public function findWithLang($criteria, $locale)
    {
        $queryBuilder = $this->createQueryBuilder('c');
        foreach ($criteria as $key => $item) {
            $queryBuilder->where('c.' . $key . ' = :' . $key);
        }
        $content = $queryBuilder
            ->setParameters($criteria)
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()
        ;

        if (empty($content[0])) return [];
        $content = $content[0];

        $contentLang = $this->getEntityManager()->getRepository(ContentLanguage::class)
            ->getLang($content, $locale);

        if (empty($contentLang)) return [];

        $content->setLang($contentLang);

        return $content;
    }

    /*
    public function findBySomething($value)
    {
        return $this->createQueryBuilder('c')
            ->where('c.something = :value')->setParameter('value', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
}
