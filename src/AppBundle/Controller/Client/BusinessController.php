<?php

namespace AppBundle\Controller\Client;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BusinessController extends Controller
{
    /**
     * @Route("/business", name="_business")
     */
    public function businnessAction()
    {
        // Get variable find
        $business = $this->get('request')->query->get('find');

        // Loading data business
        if ($business == 'all') {
            $Business = $this->get('app.query')->findAllBusiness();
        } else {
            $Business = $this->get('app.query')->findBusinessBySlug($business);
        }

        // if Error
        if (!$Business) {
            // Render views
            return $this->render('AppBundle:errors:error404.html.twig', [
                'data' => $this->get('app.query')->getAppInfo(),
                'numOffer' => $this->get('app.query')->countOffers(),
                'topOffers' => $this->get('app.query')->findTopOffers(),
                'topComments' => $this->get('app.query')->findTopComments()
            ]);
            //throw new NotFoundHttpException();
        }

        // Create breadcrumbs
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem("Inicio", $this->get("router")->generate("_home"));
        if ($business == null || $business == 'all') {
            $breadcrumbs->addItem("Negocios");
            for ($i = 0; $i < sizeof($Business); $i++) {
                $arrNC[$i] = $this->get('app.query')->countCategoriesByBusinessSlug($Business[$i]->getSlug());
                $arrNP[$i] = $this->get('app.query')->countProductsByBusinessSlug($Business[$i]->getSlug());
            }

        } else {
            $breadcrumbs->addItem("Negocios", $this->get("router")->generate('_business', ["find" => "all"]))
                ->addItem($Business[0]->getName());
            $arrNC[0] = $this->get('app.query')->countCategoriesByBusinessSlug($Business[0]->getSlug());
            $arrNP[0] = $this->get('app.query')->countProductsByBusinessSlug($Business[0]->getSlug());
        }

        // Render views
        return $this->render('AppBundle:publicity:business.html.twig', [
            'data' => $this->get('app.query')->getAppInfo(),
            'numOffer' => $this->get('app.query')->countOffers(),
            'topOffers' => $this->get('app.query')->findTopOffers(),
            'topComments' => $this->get('app.query')->findTopComments(),
            'business' => $Business,
            'arrNC' => $arrNC,
            'arrNP' => $arrNP
        ]);
    }

} 