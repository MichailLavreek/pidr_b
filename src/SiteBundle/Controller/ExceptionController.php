<?php

namespace App\SiteBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ExceptionController extends BaseController
{
    public function showException(Request $request)
    {
        $this->setup($request);

        return $this->render('page/error-404.html.twig', $this->responseData);
    }
}
