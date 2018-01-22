<?php

namespace App\Repository;

use App\Entity\Attribute;
use App\Entity\AttributeLanguage;
use App\Entity\Product;
use App\Entity\ProductAttributeValue;
use App\Entity\ProductLanguage;
use App\Entity\Structure;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ProductRepository extends ServiceEntityRepository
{
    //TODO: проверять програмно
    public $validFilterQueryParameters = [
        'class', 'width', 'manufacturer', 'chamfer'
    ];

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function countProducts(Structure $structure, $query)
    {
        $products = $this->createQueryBuilder('p')
            ->select('count(p.id) as count')
            ->where('p.structure = :structure')->setParameter('structure', $structure);

        if (!empty($query)) {
            $isJoinsSets = false;
            foreach ($query as $key => $param) {
                if ($key === 'price') {
                    $products = $products
                        ->andWhere('p.price >= :priceFrom')->setParameter('priceFrom', explode('-', $param)[0])
                        ->andWhere('p.price <= :priceTo')->setParameter('priceTo', explode('-', $param)[1]);
                }

                if (in_array($key, $this->validFilterQueryParameters)) {
                    $uniqueCodeName = 'code' . $key;
                    $uniqueValueName = 'value' . $key;

                    if (!$isJoinsSets) {
                        $products = $products
                            ->leftJoin(ProductAttributeValue::class, 'av', \Doctrine\ORM\Query\Expr\Join::WITH, 'av.product = p.id')
                            ->leftJoin(Attribute::class, 'a', \Doctrine\ORM\Query\Expr\Join::WITH, 'a.id = av.attribute')
                        ;
                        $isJoinsSets = true;
                    }
                    $products = $products
                        ->andWhere('a.code = :'.$uniqueCodeName)->setParameter($uniqueCodeName, $key);
                    if (strpos($param, '-') !== false) {
                        $paramMultiple = explode('-', $param);
                        $products = $products->andWhere('av.value IN (:'.$uniqueValueName.')')->setParameter($uniqueValueName, $paramMultiple);
                    } else {
                        $products = $products->andWhere('av.value = :'.$uniqueValueName)->setParameter($uniqueValueName, $param);
                    }
                    break;
                }
            }
        }

        $products = $products
            ->getQuery()
            ->getResult();

        return +$products[0]['count'];
    }

    public function findProducts($params)
    {
        $itemsInPage = 12;

        $products = $this->createQueryBuilder('p')
            ->where('p.structure = :structure')->setParameter('structure', $params['structure']);

        if (!empty($params['query'])) {
            $isJoinsSets = false;
            foreach ($params['query'] as $key => $param) {
                if ($key === 'price') {
                    $products = $products
                        ->andWhere('p.price >= :priceFrom')->setParameter('priceFrom', explode('-', $param)[0])
                        ->andWhere('p.price <= :priceTo')->setParameter('priceTo', explode('-', $param)[1]);
                }

                if (in_array($key, $this->validFilterQueryParameters)) {
                    $uniqueCodeName = 'code' . $key;
                    $uniqueValueName = 'value' . $key;

                    if (!$isJoinsSets) {
                        $products = $products
                            ->leftJoin(ProductAttributeValue::class, 'av', \Doctrine\ORM\Query\Expr\Join::WITH, 'av.product = p.id')
                            ->leftJoin(Attribute::class, 'a', \Doctrine\ORM\Query\Expr\Join::WITH, 'a.id = av.attribute')
                        ;
                        $isJoinsSets = true;
                    }
                    $products = $products
                        ->andWhere('a.code = :'.$uniqueCodeName)->setParameter($uniqueCodeName, $key);
                    if (strpos($param, '-') !== false) {
                        $paramMultiple = explode('-', $param);
                        $products = $products->andWhere('av.value IN (:'.$uniqueValueName.')')->setParameter($uniqueValueName, $paramMultiple);
                    } else {
                        $products = $products->andWhere('av.value = :'.$uniqueValueName)->setParameter($uniqueValueName, $param);
                    }
                    break;
                }
            }
        }

        if (!empty($params['page']) && $params['page'] !== 1) {
            $products->setFirstResult(($params['page'] - 1) * $itemsInPage);
        }

        $products = $products
            ->orderBy('p.id', 'ASC')
            ->setMaxResults($itemsInPage)
            ->getQuery()
            ->getResult();

        /**
         * @var Product $product
         */
        foreach ($products as $key => $product) {
            foreach ($product->getLang() as $productLang) {
                if ($productLang->getLanguage() === $params['language']) {
                    $productLangCurrent = $productLang;
                    break;
                }
            }
            if (!empty($productLangCurrent)) {
                $products[$key]->setLang($productLangCurrent);
            } else {
                $products[$key]->setLang(new ProductLanguage());
            }

            foreach ($product->getProductAttributesValues() as  $attributeValue) {
                $attributeLang = null;
                foreach ($attributeValue->getAttribute()->getLang() as $lang) {
                    if ($lang->getLanguage() === $params['language']) {
                        $attributeLang = $lang;
                        break;
                    }
                }

                if (!empty($attributeLang)) {
                    $attributeValue->getAttribute()->setLang($attributeLang);
                }

//                var_dump($attributeValue->getAttribute()->getLang()->getName());
            }
//            var_dump($product->getProductAttributesValues()[0]->getAttribute()->getLang()->getName());
        }

//        var_dump($products[0]->getProductAttributesValues()[0]->getAttribute()->getLang()->getName());

        return $products;
    }

    public function getMinMaxPrice($structure)
    {
        $minMax = $this->createQueryBuilder('p')
            ->select('MIN(p.price) AS min, MAX(p.price) AS max')
            ->where('p.structure = :structure')->setParameter('structure', $structure)
            ->getQuery()
            ->getResult();

        return $minMax[0];
    }
}
