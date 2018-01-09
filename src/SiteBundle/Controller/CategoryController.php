<?php

namespace App\SiteBundle\Controller;

use App\Entity\Content;
use App\Entity\Product;
use App\Entity\Structure;
use App\SiteBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CategoryController extends BaseController
{
    /**
     * @Route("/{_locale}/category/{alias}", name="main_category", defaults={"_locale"="ua"}, requirements={"_locale"="ua|en|ru","alias"="catalog"}) //TODO: fix hardcode
     */
    public function mainCategoryAction(Request $request, $alias)
    {
        $this->setup($request);

        $structure = $this->em->getRepository(Structure::class)->findBy(['alias'=>$alias]);
        if (empty($structure[0])) throw new NotFoundHttpException('Structure for alias ' . $alias . ' Not Fond!');
        $structure = $structure[0];

        $content = $this->em->getRepository(Content::class)->findWithLang(['structure'=>$structure->getId()], $request->getLocale());
        if (empty($content)) throw new NotFoundHttpException('Content for alias ' . $alias . ' Not Fond!');

        $this->responseData['structure'] = $structure;
        $this->responseData['content'] = $content;

        return $this->render('page/main-category.html.twig', $this->responseData);
    }

    /**
     * @Route("/{_locale}/category/{alias}", name="category", defaults={"_locale"="ua"}, requirements={"_locale"="ua|en|ru"})
     */
    public function categoryAction(Request $request, $alias)
    {
        $this->setup($request);

        $structure = $this->em->getRepository(Structure::class)->findBy(['alias'=>$alias]);
        if (empty($structure[0])) throw new NotFoundHttpException('Structure for alias ' . $alias . ' Not Fond!');
        $structure = $structure[0];

        $this->responseData['structure'] = $structure;

        if (!empty($structure->getChildren())) {
            $products = $this->em->getRepository(Product::class)->findBy(['structure'=>$structure]);
            if (!empty($products)) {
//                var_dump($products);die;
                return $this->categoryProductAction($request, $alias, $structure, $products);
            }
        }

        return $this->render('page/category.html.twig', $this->responseData);
    }

    public function categoryProductAction(Request $request, $alias, Structure $structure, $products)
    {
        $this->responseData['structure'] = $structure;
        $this->responseData['products'] = $products;

        return $this->render('page/product-category.html.twig', $this->responseData);
    }
}
