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
    public function postAction(Request $request, \Swift_Mailer $mailer)
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

        $this->sendMail($feedback, $mailer);

        return new JsonResponse(['success' => true]);
    }

    protected function sendMail(Feedback $feedback, \Swift_Mailer $mailer)
    {
//        $message = (new \Swift_Message('Новое письмо с на сайте: Форма контактов'))
//            ->setFrom('site@pidrahuy.com.ua')
//            ->setTo('michail.lavreek@gmail.com')
//            ->setBody(
//                $this->renderView(
//                    'emails/feedback.html.twig',
//                    ['feedback' => $feedback]
//                ),
//                'text/html'
//            )
//        ;
//
//        $mailer->send($message);

        mail(
            'laminat_chernigiv@ukr.net',
            'Новое письмо на сайте: Форма контактов',
            $this->renderView(
                    'emails/feedback.html.twig',
                    ['feedback' => $feedback]
            ),
            "Content-type:text/html;charset=UTF-8" . "\r\n" . "From: <site@pidrahuy.com.ua>" . "\r\n"
        );
    }

    public function validate($data)
    {
        $data['errors'] = [];

        if (empty($data['name'])) $data['errors']['name'] = 'name.empty';
        if (empty($data['email'])) $data['errors']['email'] = 'email.empty';

        return $data;
    }
}
