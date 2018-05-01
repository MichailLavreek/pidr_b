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
        'class', 'width', 'manufacturer', 'chamfer', '3d-structure'
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
            $attributesResults = [];

            foreach ($query as $key => $param) {
                if ($key === 'price') {
                    $products = $products
                        ->andWhere('p.price >= :priceFrom')->setParameter('priceFrom', explode('-', $param)[0])
                        ->andWhere('p.price <= :priceTo')->setParameter('priceTo', explode('-', $param)[1]);
                    continue;
                }

                if (in_array($key, $this->validFilterQueryParameters)) {
                    $uniqueCodeName = 'code' . str_replace('-', '', $key);
                    $uniqueValueName = 'value' . str_replace('-', '', $key);

                    $result = $this->createQueryBuilder('p')
                        ->select('p.id')
                        ->where('p.structure = :structure')->setParameter('structure', $structure)
                        ->leftJoin(ProductAttributeValue::class, 'av', \Doctrine\ORM\Query\Expr\Join::WITH, 'av.product = p.id')
                        ->leftJoin(Attribute::class, 'a', \Doctrine\ORM\Query\Expr\Join::WITH, 'a.id = av.attribute')
                        ->andWhere('a.code = :'.$uniqueCodeName)->setParameter($uniqueCodeName, $key);

                    if (strpos($param, '-') !== false) {
                        $paramMultiple = explode('-', $param);
                        $result = $result->andWhere('av.value IN (:'.$uniqueValueName.')')->setParameter($uniqueValueName, $paramMultiple);
                    } else {
                        $result = $result->andWhere('av.value = :'.$uniqueValueName)->setParameter($uniqueValueName, $param);
                    }

                    $attributesResults[] = $result->getQuery()->getResult();
                }
            }
        }

        if (!empty($attributesResults)) {
            $attributesResults = array_map(function ($arr) {
                return array_map(function ($item) {
                    return $item['id'];
                }, $arr);
            }, $attributesResults);

            if (count($attributesResults) > 1) {
                $filteredByAttributesProductIds = array_intersect(...$attributesResults);
            } else {
                $filteredByAttributesProductIds = $attributesResults[0];
            }

            if (!empty($filteredByAttributesProductIds)) {
                $products->andWhere('p.id IN(:filteredProductIds)')->setParameter('filteredProductIds', $filteredByAttributesProductIds);
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
            $attributesResults = [];

            foreach ($params['query'] as $key => $param) {
                if ($key === 'price') {
                    $products = $products
                        ->andWhere('p.price >= :priceFrom')->setParameter('priceFrom', explode('-', $param)[0])
                        ->andWhere('p.price <= :priceTo')->setParameter('priceTo', explode('-', $param)[1]);
                    continue;
                }

                if (in_array($key, $this->validFilterQueryParameters)) {
                    $uniqueCodeName = 'code' . str_replace('-', '', $key);
                    $uniqueValueName = 'value' . str_replace('-', '', $key);

                    $result = $this->createQueryBuilder('p')
                        ->select('p.id')
                        ->where('p.structure = :structure')->setParameter('structure', $params['structure'])
                        ->leftJoin(ProductAttributeValue::class, 'av', \Doctrine\ORM\Query\Expr\Join::WITH, 'av.product = p.id')
                        ->leftJoin(Attribute::class, 'a', \Doctrine\ORM\Query\Expr\Join::WITH, 'a.id = av.attribute')
                        ->andWhere('a.code = :'.$uniqueCodeName)->setParameter($uniqueCodeName, $key);

                    if (strpos($param, '-') !== false) {
                        $paramMultiple = explode('-', $param);
                        $result = $result->andWhere('av.value IN (:'.$uniqueValueName.')')->setParameter($uniqueValueName, $paramMultiple);
                    } else {
                        $result = $result->andWhere('av.value = :'.$uniqueValueName)->setParameter($uniqueValueName, $param);
                    }

                    $attributesResults[] = $result->getQuery()->getResult();
                }
            }
        }

        if (!empty($params['page']) && $params['page'] !== 1) {
            $products->setFirstResult(($params['page'] - 1) * $itemsInPage);
        }

        if (!empty($attributesResults)) {
            $attributesResults = array_map(function ($arr) {
                return array_map(function ($item) {
                    return $item['id'];
                }, $arr);
            }, $attributesResults);

            if (count($attributesResults) > 1) {
                $filteredByAttributesProductIds = array_intersect(...$attributesResults);
            } else {
                $filteredByAttributesProductIds = $attributesResults[0];
            }

            if (!empty($filteredByAttributesProductIds)) {
                $products->andWhere('p.id IN(:filteredProductIds)')->setParameter('filteredProductIds', $filteredByAttributesProductIds);
            }
        }

        $products = $products
            ->orderBy('p.id', 'DESC')
            ->setMaxResults($itemsInPage)
            ->getQuery()
//        dump($products);die;
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
            }
        }

        return $products;
    }

    public function findProduct($alias, $locale)
    {
        $product = $this->createQueryBuilder('p')
            ->where('p.alias = :alias')->setParameter('alias', $alias)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        foreach ($product->getLang() as $productLang) {
            if ($productLang->getLanguage()->getIso2() === $locale) {
                $productLangCurrent = $productLang;
                break;
            }
        }
        if (!empty($productLangCurrent)) {
            $product->setLang($productLangCurrent);
        } else {
            $product->setLang(new ProductLanguage());
        }

        foreach ($product->getProductAttributesValues() as  $attributeValue) {
            $attributeLang = null;
            foreach ($attributeValue->getAttribute()->getLang() as $lang) {
                if ($lang->getLanguage()->getIso2() === $locale) {
                    $attributeLang = $lang;
                    break;
                }
            }

            if (!empty($attributeLang)) {
                $attributeValue->getAttribute()->setLang($attributeLang);
            }
        }

        return $product;
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
