<?php

namespace App\CMSBundle\Controller;

use App\Entity\AttributeLanguage;
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

        parent::preUpdateEntity($entity);
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
//            var_dump($removedImages);
            foreach ($removedImages as $image) {
                $this->em->remove($image);
                $this->em->flush($image);
            }
        }

        $this->em->flush($entity);
    }

//    protected function postInitializeStructureEntity()
//    {
////        var_dump(123);die;
//    }

//    protected function deleteAction()
//    {
//        $this->dispatch(EasyAdminEvents::PRE_DELETE);
//
//        if ('DELETE' !== $this->request->getMethod()) {
//            return $this->redirect($this->generateUrl('easyadmin', array('action' => 'list', 'entity' => $this->entity['name'])));
//        }
//
//        $id = $this->request->query->get('id');
//        $form = $this->createDeleteForm($this->entity['name'], $id);
//        $form->handleRequest($this->request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $easyadmin = $this->request->attributes->get('easyadmin');
//            $entity = $easyadmin['item'];
//
//            $this->dispatch(EasyAdminEvents::PRE_REMOVE, array('entity' => $entity));
//
//            $this->executeDynamicMethod('preRemove<EntityName>Entity', array($entity));
//
//            if ($this->beforeDeleteCheck($entity)) {
//                try {
//                    $this->em->remove($entity);
//                    $this->em->flush();
//                } catch (ForeignKeyConstraintViolationException $e) {
//                    throw new EntityRemoveException(array('entity_name' => $this->entity['name'], 'message' => $e->getMessage()));
//                }
//            } else {
//                $this->addFlash('error', 'Delete canceled');
//            }
//
//            $this->dispatch(EasyAdminEvents::POST_REMOVE, array('entity' => $entity));
//        }
//
//        $this->dispatch(EasyAdminEvents::POST_DELETE);
//
//        return $this->redirectToReferrer();
//    }
//
//    protected function beforeDeleteCheck($entity)
//    {
//        $isAllowed = true;
//        if ($entity instanceof Role) {
//            $users = $this->em->getRepository(User::class)->findBy(['role'=>$entity->getId()]);
//            if (!empty($users)) {
//                $isAllowed = false;
//            }
//        }
//
//        return $isAllowed;
//    }
//
//    protected function editStructureAction()
//    {
//        $this->dispatch(EasyAdminEvents::PRE_EDIT);
//
//        $id = $this->request->query->get('id');
//        $easyadmin = $this->request->attributes->get('easyadmin');
//        $entity = $easyadmin['item'];
//        $entityLang = $this->getDoctrine()->getRepository(StructureLanguage::class)->getLang($entity, $this->formLang);
//        if (!$entityLang instanceof StructureLanguage && is_array($entityLang) && count($entityLang) === 1) {
//            $entityLang = $entityLang[0];
//        }
//
//        if ($this->request->isXmlHttpRequest() && $property = $this->request->query->get('property')) {
//            $newValue = 'true' === mb_strtolower($this->request->query->get('newValue'));
//            $fieldsMetadata = $this->entity['list']['fields'];
//
//            if (!isset($fieldsMetadata[$property]) || 'toggle' !== $fieldsMetadata[$property]['dataType']) {
//                throw new \RuntimeException(sprintf('The type of the "%s" property is not "toggle".', $property));
//            }
//
//            $this->updateEntityProperty($entity, $property, $newValue);
//
//            // cast to integer instead of string to avoid sending empty responses for 'false'
//            return new Response((int) $newValue);
//        }
//        $entityLangArr = $this->get('easyadmin.config.manager')->getEntityConfiguration('StructureLanguage');
//
//        $fields = $this->entity['edit']['fields'];
//        $fieldsLang = $entityLangArr['edit']['fields'];
//
//        $editForm = $this->executeDynamicMethod('create<EntityName>EditForm', array($entity, $fields));
//        $editFormLang = $this->executeDynamicMethod('create<EntityName>EditForm', array($entityLang, $fieldsLang, true));
//        $deleteForm = $this->createDeleteForm($this->entity['name'], $id);
//
//        $editForm->handleRequest($this->request);
//        $editFormLang->handleRequest($this->request);
//        if ($editForm->isSubmitted() && $editForm->isValid()) {
//            $this->dispatch(EasyAdminEvents::PRE_UPDATE, array('entity' => $entity));
//
//            $this->executeDynamicMethod('preUpdate<EntityName>Entity', array($entity));
//            $this->em->flush();
//
//            $this->dispatch(EasyAdminEvents::POST_UPDATE, array('entity' => $entity));
//
//            return $this->redirectToReferrer();
//        }
//        if ($editFormLang->isSubmitted() && $editFormLang->isValid()) {
//            $this->dispatch(EasyAdminEvents::PRE_UPDATE, array('entity' => $entity));
//
//            $this->executeDynamicMethod('preUpdate<EntityName>Entity', array($entity));
//            $this->em->flush();
//
//            $this->dispatch(EasyAdminEvents::POST_UPDATE, array('entity' => $entity));
//
//            return $this->redirectToReferrer();
//        }
//
//        $this->dispatch(EasyAdminEvents::POST_EDIT);
//
//        return $this->render($this->entity['templates']['edit'], array(
//            'form' => $editForm->createView(),
//            'form_lang' => $editFormLang->createView(),
//            'entity_fields' => $fields,
//            'entity_fields_lang' => $fieldsLang,
//            'entity' => $entity,
//            'entity_lang' => $entityLang,
//            'delete_form' => $deleteForm->createView(),
//        ));
//    }
//
//    protected function createStructureEditForm($entity, array $entityProperties, $isLang = false)
//    {
//        if ($isLang) {
//            $entityProperties['isLang'] = $isLang;
//        }
//        return $this->createEntityForm($entity, $entityProperties, 'edit');
//    }
//
//    protected function createStructureEntityForm($entity, $entityProperties, $view)
//    {
////        if ($entity instanceof StructureLanguage && empty($entityProperties['isLang'])) {
////            $entityProperties['isLang'] = true;
////
//////            $entityProperties['data_class'] = StructureLanguage::class;
////        }
////        var_dump($entity);die;
////        var_dump($entity instanceof Structure);
////        if (!$entity instanceof Structure) {
////            var_dump($entity);
////        }
//
//        $entityName = empty($entityProperties['isLang']) ? $this->entity['name'] : $this->entity['name'] . 'Language';
//        $entityName = mb_strtolower($entityName);
//
//        $formOptions = $this->executeDynamicMethod('get<EntityName>EntityFormOptions', array($entity, $view));
//        empty($entityProperties['isLang']) ?: $formOptions['entity'] .= 'Language';
////        var_dump($entityProperties);die;
//        return $this->get('form.factory')->createNamedBuilder($entityName, LegacyFormHelper::getType('easyadmin'), $entity, $formOptions)->getForm();
//    }

}

