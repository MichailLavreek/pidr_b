<?php

namespace App\Repository;

use App\Entity\Content;
use App\Entity\ContentLanguage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ContentLanguageRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ContentLanguage::class);
    }

    public function getLang(Content $entity, string $lang)
    {
        $langItem = $this->_getLangItem($entity, $lang);
        return empty($langItem[0]) ? [] : $langItem[0];
    }

    private function _getLangItem($entity, $lang)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.content = :id')->setParameter('id', $entity->getId())
            ->andWhere('s.language = :lang')->setParameter('lang', $lang)
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
    }
}
