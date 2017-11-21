<?php

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->render('first/index.html.twig', ['testVar'=> 1355123123, 'arr' => ['1233', '456']]);
    }
}