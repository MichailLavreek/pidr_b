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

class CategoryController extends BaseController
{
    /**
     * Страница "Каталог товаров"
     * @Route("/{_locale}/category/{alias}", name="main_category", defaults={"_locale"="ua"}, requirements={"_locale"="ua|en|ru","alias"="catalog"}) //TODO: fix hardcode
     */
    public function mainCategoryAction(Request $request, $alias)
    {
        $this->setup($request);

        $structure = $this->em->getRepository(Structure::class)->findBy(['alias'=>$alias]);
        if (empty($structure[0])) throw new NotFoundHttpException('Structure for alias ' . $alias . ' Not Fond!');
        $structure = $structure[0];

        $content = $this
            ->em
            ->getRepository(Content::class)
            ->findWithLang(['structure'=>$structure->getId()], $request->getLocale());

        if (empty($content)) throw new NotFoundHttpException('Content for alias ' . $alias . ' Not Fond!');

        $this->responseData['structure'] = $structure;
        $this->responseData['content'] = $content;

        $this->setupMeta($structure);

        return $this->render('page/main-category.html.twig', $this->responseData);
    }
    /**
     * Страница "Мебель" (основная категория с подразделами)
     * @Route("/{_locale}/category/{alias}", name="main_category_furniture", defaults={"_locale"="ua"}, requirements={"_locale"="ua|en|ru","alias"="furniture"}) //TODO: fix hardcode
     */
    public function mainCategoryFurnitureAction(Request $request, $alias)
    {
        $this->setup($request);

        $structure = $this->em->getRepository(Structure::class)->findBy(['alias'=>$alias]);
        if (empty($structure[0])) throw new NotFoundHttpException('Structure for alias ' . $alias . ' Not Fond!');
        $structure = $structure[0];

        $content = $this
            ->em
            ->getRepository(Content::class)
            ->findWithLang(['structure'=>$structure->getId()], $request->getLocale());

        if (empty($content)) throw new NotFoundHttpException('Content for alias ' . $alias . ' Not Fond!');

        $this->responseData['structure'] = $structure;
        $this->responseData['content'] = $content;

        $this->setupMeta($structure);

        return $this->render('page/main-category-furniture.html.twig', $this->responseData);
    }

    /**
     * @Route("/{_locale}/category/{alias}", name="category", defaults={"_locale"="ua"}, requirements={"_locale"="ua|en|ru"})
     */
    public function categoryAction(Request $request, $alias)
    {
        $this->setup($request);

        $structure = $this->em->getRepository(Structure::class)->findOneBy(['alias'=>$alias]);
        if (empty($structure)) throw new NotFoundHttpException('Structure for alias ' . $alias . ' Not Fond!');

        if($request->isXmlHttpRequest() && $request->query->get('onlyCount')) {
            $count = $this->em->getRepository(Structure::class)->countContentItems($structure, $request->query->all());
            return new JsonResponse(array('count' => $count));
        }

        $page = $request->query->get('page');
        if (empty($page)) $page = 1;

        $this->responseData['structure'] = $structure;

        if (!empty($structure->getChildren())) {
            $products = $this
                ->em
                ->getRepository(Product::class)
                ->findProducts(['structure'=>$structure, 'language'=>$this->responseData['currentLanguage'], 'page' => $page, 'query' => $request->query->all()]);

            if (!empty($products)) {
                $this->responseData['products'] = $products;
                $this->responseData['pagination'] = $this->buildPagination($structure, $page, $request->query->all());
                $this->responseData['filter'] = $this->buildFilter($structure);

                return $this->categoryProductAction($request);
            }
        }

        return $this->render('page/category.html.twig', $this->responseData);
    }

    public function categoryProductAction(Request $request)
    {
        return $this->render('page/product-category.html.twig', $this->responseData);
    }
}
