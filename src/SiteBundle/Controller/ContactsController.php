<?php

namespace App\SiteBundle\Controller;

use App\Entity\Content;
use App\Entity\Structure;
use App\SiteBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ContactsController extends BaseController
{
    /**
     * @Route("/{_locale}/feedback/{alias}", name="contacts", requirements={"_locale"="ua|en|ru"})
     */
    public function index(Request $request, $alias)
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

        return $this->render('page/contacts.html.twig', $this->responseData);
    }
}
