<?php

namespace App\CMSBundle\Controller;

use App\Entity\Content;
use App\Entity\ContentLanguage;
use App\Entity\Language;
use App\Entity\Meta;
use App\Entity\MetaLanguage;
use App\Entity\Product;
use App\Entity\ProductImage;
use App\Entity\ProductLanguage;
use App\Entity\Structure;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController as EasyAdminController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends EasyAdminController
{
    protected $formLang;

    /**
     * @Route("/cms/check-auth", name="check-auth")
     */
    public function checkAuthAction(Request $request)
    {
        return new Response('true');
    }

    /**
     * @Route("/cms", name="easyadmin")
     */
    public function indexAction(Request $request)
    {
//        $_SESSION['ELFINDER_ACCESS'] = 'true';
//        setcookie('ELFINDER_ACCESS', 'true', strtotime('now + 30 minutes'), '/', null, true, true);
//        $response = new Response();
//        $response->headers->setCookie($cookie);
//        $session = $request->getSession();
//        $session->set('ELFINDER_ACCESS', 'true');


//        dump($request->cookies->all());
//        dump($request->getSession()->all());

        $request->setLocale('en');
        $this->formLang = $request->get('locale') ? $request->get('locale') : $request->getDefaultLocale();
        return parent::indexAction($request);
    }

    protected function initialize(Request $request)
    {
        parent::initialize($request);
        $maxResults = $request->cookies->get('cmsMaxResults');
        if (is_numeric($maxResults)) $this->config['list']['max_results'] = +$maxResults;
    }



    protected function prePersistEntity($entity)
    {
        if (method_exists($entity, 'getLang') && is_iterable($entity->getLang())) {
            foreach ($entity->getLang() as $lang) {
                $this->persistEntity($lang);
            }
        }

        parent::prePersistEntity($entity);
    }

    protected function preUpdateEntity($entity)
    {
        if (method_exists($entity, 'getLang')) {
            foreach ($entity->getLang() as $lang) {
                $this->persistEntity($lang);
            }
        }

        parent::preUpdateEntity($entity);
    }

    /**
     * @param $entity
     * @throws \Doctrine\ORM\ORMException
     */
    protected function prePersistContentEntity($entity)
    {
        $this->checkAndGenerateMeta($entity);
        $this->checkAndGenerateLang($entity);
        $this->prePersistEntity($entity);
    }

    /**
     * @param $entity
     * @throws \Doctrine\ORM\ORMException
     */
    protected function preUpdateContentEntity($entity)
    {
        $this->checkAndGenerateMeta($entity);
        $this->checkAndGenerateLang($entity);
        $this->preUpdateEntity($entity);
    }

    /**
     * @param $entity
     * @throws \Doctrine\ORM\ORMException
     */
    protected function prePersistProductEntity($entity)
    {
        $this->checkAndGenerateMeta($entity);
        $this->checkAndGenerateLang($entity);
        $this->prePersistEntity($entity);
    }

    /**
     * @param $entity
     * @throws \Doctrine\ORM\ORMException
     */
    protected function preUpdateProductEntity($entity)
    {
        $this->checkAndGenerateMeta($entity);
        $this->checkAndGenerateLang($entity);
        $this->preUpdateEntity($entity);
    }

    /**
     * @var Content|Product $entity
     * @throws \Doctrine\ORM\ORMException
     */
    protected function checkAndGenerateMeta($entity)
    {
        if (!method_exists($entity, 'getMeta') || !method_exists($entity, 'setMeta')) {
            return;
        }

        $isEntitiesChanged = false;

        if (!empty($entity->getMeta())) {
            $metaRepository = $this->em->getRepository(Meta::class);
            $meta = $metaRepository->find($entity->getMeta());
        }

        if (empty($meta)) {
            $meta = new Meta();
            $entity->setMeta($meta);
            $this->em->persist($meta);
            $isEntitiesChanged = true;
        }

        $metaLang = $meta->getLang() ?? [];
        $lang = $this->em->getRepository(Language::class)->findAll();

        if (count($metaLang) < count($lang)) {
            foreach ($lang as $language) {
                $metaLangExists = false;

                /** @var MetaLanguage $metaLanguage */
                foreach ($metaLang as $metaLanguage) {
                    if ($metaLanguage->getLanguage() === $language->getIso2()) {
                        $metaLangExists = true;
                        break;
                    }
                }

                if (!$metaLangExists) {
                    $metaLanguage = new MetaLanguage();
                    $metaLanguage->setMeta($meta);
                    $metaLanguage->setLanguage($language);
                    $isEntitiesChanged = true;

                    $this->em->persist($metaLanguage);
                }
            }
        }

        if ($isEntitiesChanged) {
            $this->em->flush();
        }
    }

    /**
     * @var Content|Product $entity
     * @throws \Doctrine\ORM\ORMException
     */
    protected function checkAndGenerateLang($entity)
    {
        if (!method_exists($entity, 'getLang') || !method_exists($entity, 'setMeta')) {
            return;
        }

        $isEntitiesChanged = false;

        $entityLang = $entity->getLang() ?? [];
        $lang = $this->em->getRepository(Language::class)->findAll();

        if (count($entityLang) === count($lang)) {
            return;
        }

        foreach ($lang as $language) {
            $entityLangExists = false;

            foreach ($entityLang as $entityLanguage) {
                if ($entityLanguage->getLanguage() === $language->getIso2()) {
                    $entityLangExists = true;
                    break;
                }
            }

            if (!$entityLangExists) {
                if (method_exists($entity, 'getPrice')) {
                    $entityLanguage = new ProductLanguage();
                    $entityLanguage->setProduct($entity);
                } else {
                    $entityLanguage = new ContentLanguage();
                    $entityLanguage->setContent($entity);
                }

                $entityLanguage->setLanguage($language);
                $isEntitiesChanged = true;

                $this->em->persist($entityLanguage);
            }
        }

        if ($isEntitiesChanged) {
            $this->em->flush();
        }
    }

    protected function updateEntity($entity)
    {
        if (method_exists($entity, 'getLang') && count($entity->getLang()) > 0 && $entity->getLang()[0]) {
            $langs = $this
                ->em
                ->getRepository($entity->getLang()[0]->getClassName())
                ->findBy([$entity->getLang()[0]->getParentPropertyName()=>$entity]);

            $submittedLangs = [];

            foreach ($entity->getLang() as $attr) {
                $submittedLangs[] = $attr;
            }

            $removedLangs = array_diff($langs, $submittedLangs);

            foreach ($removedLangs as $lang) {
                $this->em->remove($lang);
                $this->em->flush($lang);
            }
        }

        if (method_exists($entity, 'getMeta') && !empty($entity->getMeta())) {
            $metaLang = $entity->getMeta()->getLang();
            $submittedLang = [];
            $metaLangArray = [];

            foreach ($metaLang as $attr) {
                $this->em->persist($attr);
                $this->em->flush($attr);

                $submittedLang[] = $attr;
                $metaLangArray[] = $attr;
            }

            $removedLang = array_diff($metaLangArray, $submittedLang);

            foreach ($removedLang as $lang) {
                $this->em->remove($lang);
                $this->em->flush($lang);
            }
        }

//        if (method_exists($entity, 'getImages')) {
//            dump('getimages', $entity->getImages());
//            dump('getimagesc', count($entity->getImages()));
//
//            $images = $this
//                ->em
//                ->getRepository(ProductImage::class)
//                ->findBy(['product'=>$entity->getId()]);
//            dump('images', $images);
//            $submittedImages = [];
//            foreach ($entity->getImages() as $image) {
//                $submittedImages[] = $image;
//            }
//
//            $removedImages = array_diff($images, $submittedImages);
//            dump('rimages', $removedImages);
//            die;
//        }

        if (method_exists($entity, 'getImages')) {

            $images = $this
                ->em
                ->getRepository(ProductImage::class)
                ->findBy(['product'=>$entity->getId()]);

            $submittedImages = [];

            foreach ($entity->getImages() as $image) {
                $submittedImages[] = $image;
            }

            $removedImages = array_diff($images, $submittedImages);
            foreach ($removedImages as $image) {
                $this->em->remove($image);
                $this->em->flush($image);
            }
        }

        $this->em->flush($entity);
    }

    /** @var Structure $entity */
    protected function preRemoveStructureEntity($entity)
    {
        $contents = $this->em->getRepository(Content::class)->findBy(['structure' => $entity->getId()]);
        if (!empty($contents)) {
            /** @var Content $content */
            foreach ($contents as $content) {
                $this->em->remove($content);
            }
        }

        $products = $this->em->getRepository(Product::class)->findBy(['structure' => $entity->getId()]);
        if (!empty($products)) {
            /** @var Product $product */
            foreach ($products as $product) {
                $this->em->remove($product);
            }
        }
    }
}

