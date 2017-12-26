<?php

namespace App\SiteBundle\Controller;

use App\Entity\Structure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class IndexController extends BaseController
{

    /**
     * @Route("/{_locale}", name="home", defaults={"_locale"="ua"}, requirements={"_locale"="ua|en|ru"})
     */
    public function indexAction(Request $request)
    {
        $this->setup($request);

        $structuresForHomePage = [];

        /**
         * @var Structure $structure
         */
        foreach ($this->responseData['structures'] as $structure) {
            if (in_array($structure->getAlias(), ['laminate', 'furniture', 'siding'])) {
                $structuresForHomePage[] = $structure;
            }
        }
        $this->responseData['structuresForHomePage'] = $structuresForHomePage;

        return $this->render('page/home.html.twig', $this->responseData);
    }
}