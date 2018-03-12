<?php

namespace App\SiteBundle\Controller;

use App\Entity\Attribute;
use App\Entity\Language;
use App\Entity\Meta;
use App\Entity\MetaLanguage;
use App\Entity\Product;
use App\Entity\Structure;
use App\Entity\Variable;
use Doctrine\ORM\Repository\RepositoryFactory;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\MakerBundle\Str;
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
        $languageRepository = $this->em->getRepository(Language::class);
        $languages = $languageRepository->findAll();

        $this->responseData['languages'] = $languages;

        foreach ($languages as $language) {
            /**
             * @var Language $language
             */
            if ($language->getIso2() === $this->request->getLocale()) {
                $this->responseData['currentLanguage'] = $language;
                $_SERVER['CURRENT_LOCALE'] = $language->getIso2();
                break;
            }
        }
    }

    private function setupStructure()
    {
        $structureRepository = $this->em->getRepository(Structure::class);
        $structures = $structureRepository->findAll();

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

    protected function setupMeta($entity)
    {
        if (empty($entity)) return;

        if (method_exists($entity, 'getMeta')) {
            $meta = $entity->getMeta()->getLangCurrent();
        }

        $entityLang = $entity->getLangCurrent();

        if (empty($meta)) {
            $meta = new MetaLanguage();
        }

        if (empty($meta->getTitle())) {
            if (method_exists($entityLang, 'getName')) {
                $meta->setTitle($entityLang->getName());
            }
        }

        if (empty($meta->getDescription())) {
            if (method_exists($entityLang, 'getBody')) {
                $meta->setDescription(substr(strip_tags($entityLang->getBody()), 0, 500));
            }
        }

        $this->get('twig')->addGlobal('meta', $meta);
    }

    protected function buildPagination(Structure $structure, $page, $query)
    {
        $parameters = [];

        $parameters['count'] = $this->em->getRepository(Structure::class)->countContentItems($structure, $query);
        $parameters['byPage'] = 12;
        $parameters['pages'] = ceil($parameters['count'] / $parameters['byPage']);
        $parameters['page'] = $page;


        return $this->render('block/pagination.html.twig', $parameters)->getContent();
    }

    protected function buildFilter(Structure $structure)
    {
        $parameters = [];

        $parameters['attributes'] = $this->em->getRepository(Attribute::class)->getForFilter($structure, $this->responseData['currentLanguage']);
        $parameters['price'] = $this->em->getRepository(Product::class)->getMinMaxPrice($structure);
        $parameters['query'] = $this->request->query->all();

        return $this->render('block/filter.html.twig', $parameters)->getContent();
    }
}