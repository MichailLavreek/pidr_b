<?php

namespace App\SiteBundle\Controller;

use App\Entity\Structure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class IndexController extends BaseController
{

    /**
     * @Route("/{_locale}", name="home", defaults={"_locale"="ua"})
     */
    public function indexAction(Request $request)
    {
        $this->setup($request);

        $doctrine = $this->getDoctrine();
        $structureRepository = $doctrine->getRepository(Structure::class);

        return $this->render('page/home.html.twig', $this->responseData);
    }
}