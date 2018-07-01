<?php

namespace App\SiteBundle\Controller;

use App\Entity\Content;
use App\Entity\Product;
use App\Entity\Structure;
use App\SiteBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductController extends BaseController
{
    /**
     * @Route("/{_locale}/product/{alias}", name="product", defaults={"_locale"="ua"}, requirements={"_locale"="ua|en|ru"})
     */
    public function productAction(Request $request, $alias)
    {
        $this->setup($request);

        /** @var Product $product */
        $product = $this
            ->em
            ->getRepository(Product::class)
            ->findOneBy(['alias' => $alias]);

        if (empty($product)) throw new NotFoundHttpException('Product not Fond!');

        $this->responseData['product'] = $product;
        $this->responseData['priceDescription'] = 'грн';

        if ($product->getStructure()->getAlias() === 'laminate') {
            $this->responseData['priceDescription'] = 'грн/м2';
        }

        $this->setupMeta($product);

        return $this->render('page/product.html.twig', $this->responseData);
    }
}
