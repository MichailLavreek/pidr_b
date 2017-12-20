<?php

namespace App\Repository;

use App\Entity\Language;
use App\Entity\Structure;
use App\Entity\StructureLanguage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class StructureLanguageRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, StructureLanguage::class);
    }

    public function getLang(Structure $entity, string $lang)
    {
        $langItem = $this->_getLangItem($entity, $lang);
        if (!$langItem) {
            $langItem = new StructureLanguage();
            $langEntity = $this->getEntityManager()->getRepository(Language::class)->find($lang);
            $langItem->setStructure($entity);
            $langItem->setLanguage($langEntity);
            $this->getEntityManager()->persist($langItem);
            $this->getEntityManager()->flush();

            $langItem = $this->_getLangItem($entity, $lang);
        }
        return $langItem[0];
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
}
