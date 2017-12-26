<?php

namespace App\SiteBundle\Controller;

use App\Entity\Language;
use App\Entity\Structure;
use App\Entity\Variable;
use Doctrine\ORM\Repository\RepositoryFactory;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;

class BaseController extends Controller
{
    public $responseData = [];
    public $currentLanguage;

    /**
     * @var EntityManager $em
     */
    public $em;

    /**
     * @var Request $request
     */
    protected $request;

    public function __construct()
    {

    }

    public function setup(Request $request)
    {
        $this->request = $request;
        $this->em = $this->getDoctrine();

        $this->setupLanguage();
        $this->setupStructure();
        $this->setupVariables();

    }

    private function setupLanguage()
    {
        $languageRepository = $this->em->getRepository((string) Language::class);
        $languages = $languageRepository->findAll();

        $this->responseData['languages'] = $languages;

        foreach ($languages as $language) {
            /**
             * @var Language $language
             */
            if ($language->getIso2() === $this->request->getLocale()) {
                $this->responseData['currentLanguage'] = $language;
                break;
            }
        }
    }

    private function setupStructure()
    {
        $structureRepository = $this->em->getRepository(Structure::class);
        $structures = $structureRepository->findAllWithLang($this->request->getLocale());

        $this->responseData['structures'] = $structures;
    }

    private function setupVariables()
    {
        $variablesRepository = $this->em->getRepository(Variable::class);
        $variables = $variablesRepository->findAll();
        $formattedVariables = [];
        foreach ($variables as $variable) {
            $formattedVariables[$variable->getName()] = $variable->getValue();
        }

        $this->responseData['variables'] = $formattedVariables;
    }
}