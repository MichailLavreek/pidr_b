<?php

namespace App\SiteBundle\Controller;

use App\Entity\Content;
use App\Entity\Feedback;
use App\Entity\Structure;
use App\SiteBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OrderController extends BaseController
{
    /**
     * @Route("/{_locale}/order", name="order-montage", requirements={"_locale"="ua|en|ru"}, methods={"get"})
     */
    public function indexAction(Request $request)
    {
        $this->setup($request);

        $this->responseData['structure'] = $this
            ->getDoctrine()
            ->getRepository(Structure::class)
            ->findOneBy(['alias' => 'montage']);

        return $this->render('page/order.html.twig', $this->responseData);
    }

    /**
     * @Route("/{_locale}/order", name="order-montage-post", requirements={"_locale"="ua|en|ru"}, methods={"post"})
     */
    public function postAction(Request $request)
    {
        $data = [];
        $data['name'] = $request->request->get('name');
        $data['email'] = $request->request->get('email');
        $data['message'] = $request->request->get('message');

        $data = $this->validate($data);

        if (!empty($data['errors'])) {
            return new JsonResponse(['errors' => $data['errors']]);
        }

        $feedback = new Feedback();
        $feedback->setName($data['name']);
        $feedback->setEmail($data['email']);
        $feedback->setIp($request->getClientIp());

        if (!empty($data['message'])) {
            $feedback->setMessage($data['message']);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($feedback);
        $em->flush($feedback);

        return new JsonResponse(['success' => true]);
    }

    public function validate($data)
    {
        $data['errors'] = [];

        if (empty($data['name'])) $data['errors']['name'] = 'name.empty';
        if (empty($data['email'])) $data['errors']['email'] = 'email.empty';

        return $data;
    }
}
