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

class ContactsController extends BaseController
{
    /**
     * @Route("/{_locale}/feedback/{alias}", name="contacts", requirements={"_locale"="ua|en|ru"}, methods={"get"})
     */
    public function indexAction(Request $request, $alias)
    {
        $this->setup($request);

        $structure = $this->em->getRepository(Structure::class)->findBy(['alias'=>$alias]);

        if (empty($structure[0])) throw new NotFoundHttpException('Structure for alias ' . $alias . ' Not Fond!');
        $structure = $structure[0];
        /**
         * @var Structure $structure
         */
        if ($structure->getType() != 'Contacts') throw new NotFoundHttpException('Structure for alias ' . $alias . ' Not Fond!');

        $this->responseData['structure'] = $structure;
//        $this->responseData['content'] = $this->em->getRepository(Content::class)->findBy(['structure'=>$structure]);

        $this->setupMeta($structure);
        return $this->render('page/contacts.html.twig', $this->responseData);
    }

    /**
     * @Route("/{_locale}/feedback/{alias}", name="contacts-post", requirements={"_locale"="ua|en|ru"}, methods={"post"})
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
