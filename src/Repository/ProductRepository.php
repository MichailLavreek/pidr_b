<?php

namespace App\Repository;

use App\Entity\AttributeLanguage;
use App\Entity\Product;
use App\Entity\ProductAttributeValue;
use App\Entity\ProductLanguage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ProductRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findProducts($params)
    {
        $itemsInPage = 12;

        $products = $this
            ->createQueryBuilder('s')
            ->where('s.structure = :structure')->setParameter('structure', $params['structure'])
            ->orderBy('s.id', 'ASC')
            ->setMaxResults($itemsInPage)
            ;
        if (!empty($params['page'])) {
            $products->setFirstResult($params['page'] * $itemsInPage);
        }

        $products = $products
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
}
