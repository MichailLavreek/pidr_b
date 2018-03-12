<?php

namespace App\CMSBundle\Controller;

use App\Entity\AttributeLanguage;
use App\Entity\Content;
use App\Entity\Language;
use App\Entity\Meta;
use App\Entity\MetaLanguage;
use App\Entity\Product;
use App\Entity\ProductImage;
use App\Entity\Role;
use App\Entity\Structure;
use App\Entity\StructureLanguage;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController as EasyAdminController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use EasyCorp\Bundle\EasyAdminBundle\Exception\EntityRemoveException;
use EasyCorp\Bundle\EasyAdminBundle\Form\Util\LegacyFormHelper;
use Symfony\Component\HttpFoundation\Response;
use Twig\Error\Error;

class AdminController extends EasyAdminController
{
    protected $formLang;
    /**
     * @Route("/cms", name="easyadmin")
     */
    public function indexAction(Request $request)
    {
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
        if (method_exists($entity, 'getLang')) {
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
        $this->prePersistEntity($entity);
    }

    /**
     * @param $entity
     * @throws \Doctrine\ORM\ORMException
     */
    protected function preUpdateContentEntity($entity)
    {
        $this->checkAndGenerateMeta($entity);
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

    protected function updateEntity($entity)
    {
        if (method_exists($entity, 'getLang') && count($entity->getLang()) > 0) {
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

        if (method_exists($entity, 'getImages') && count($entity->getImages()) > 0) {

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


}

