<?php

namespace App\SiteBundle\Controller;

use App\Entity\Language;
use App\Entity\Structure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BaseController extends Controller
{
    public $responseData = [];
    public $currentLanguage;
    public $doctrine;

    public function __construct()
    {

    }

    public function setup(Request $request)
    {
        $this->doctrine = $this->getDoctrine();

        $this->setupLanguage($request);
    }

    public function setupLanguage(Request $request)
    {
        $languageRepository = $this->doctrine->getRepository(Language::class);
        $languages = $languageRepository->findAll();

        $this->responseData['languages'] = $languages;

        foreach ($languages as $language) {
            if ($language->getIso2() === $request->getLocale()) {
                $this->responseData['currentLanguage'] = $language;
                break;
            }
        }
    }
}