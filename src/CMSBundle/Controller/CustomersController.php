<?php

namespace App\CMSBundle\Controller;

use App\Entity\Customer;
use DateTime;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController as EasyAdminController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomersController extends EasyAdminController
{
    protected $formLang;

    /**
     * @Route("/cms/ca-api/check-auth", name="ca-check-auth")
     */
    public function checkAuthAction(Request $request)
    {
        return new Response('true');
    }

    /**
     * @Route("/cms/", name="easyadmin")
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

    /**
     * @Route("/cms/ca-api/customer/{id}", methods={"GET"}, name="ca-customers-get-by-id", requirements={"id"="\d+"})
     */
    public function getCustomerAction($id)
    {
        $customer = $this->getDoctrine()->getRepository(Customer::class)->find($id);
        return new JsonResponse($customer);
    }

    /**
     * @Route("/cms/ca-api/customer/{id}", methods={"DELETE"}, name="ca-customers-delete-by-id", requirements={"id"="\d+"})
     */
    public function deleteCustomerAction($id)
    {
        $customer = $this->getDoctrine()->getRepository(Customer::class)->find($id);
        $this->getDoctrine()->getManager()->remove($customer);
        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse(['status' => 'ok']);
    }

    /**
     * @Route("/cms/ca-api/customers", methods={"GET"}, name="ca-customers-get")
     */
    public function getCustomersAction(Request $request)
    {
        $customers = $this->getDoctrine()->getRepository(Customer::class)->findAll();
        return new JsonResponse(['customers' => $customers]);
    }

    /**
     * @Route("/cms/ca-api/customers", methods={"POST"}, name="ca-customers-set")
     */
    public function setCustomerAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $customerFromRequest = json_decode($request->getContent(), true)['customer'];


        if (!empty($customerFromRequest['id'])) {
            $entity = $this->getDoctrine()->getRepository(Customer::class)->find($customerFromRequest['id']);
        } else {
            $entity = new Customer();
        }

        $date = new DateTime();
        $date->setTimestamp($customerFromRequest['date']);

        $entity->setDate($date);
        $entity->setAddress($customerFromRequest['address']);
        $entity->setDescription($customerFromRequest['description']);
        $entity->setName($customerFromRequest['name']);
        $entity->setPhones($customerFromRequest['phones']);
        $processed = $customerFromRequest['processed'] ? 1 : 0;
        $entity->setProcessed($processed);
        $entity->setRating(+$customerFromRequest['rating'] ? +$customerFromRequest['rating'] : null);
        $entity->setProcessedDescription($customerFromRequest['processedDescription']);

        $em->persist($entity);
        $em->flush();

        return new JsonResponse(['customer' => 'ok']);
    }
}

