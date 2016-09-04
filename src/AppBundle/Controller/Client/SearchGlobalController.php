<?php

namespace AppBundle\Controller\Client;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class SearchGlobalController extends Controller
{
    /**
     * @Route("/search/global", name="_search_global")
     */
    public function offersAction()
    {
        // Create breadcrumbs
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem("Inicio", $this->get("router")->generate("_home"))
            ->addItem("Búsqueda global");

        $find = $this->get('request')->query->get('find');
        $text = $this->get('app.utils')->getSlug($find);


        $resultsBusiness = $this->get('app.query')->searchBusiness($text);
        $resultsCategory = $this->get('app.query')->searchCategory($text);
        $resultsProduct = $this->get('app.query')->searchProduct($text);


        // Render views
        return $this->render('AppBundle:search:search_global.html.twig', [
            'data' => $this->get('app.query')->getAppInfo(),
            'numOffer' => $this->get('app.query')->countOffers(),
            'topOffers' => $this->get('app.query')->findTopOffers(),
            'topComments' => $this->get('app.query')->findTopComments(),
            'string' => $find,
            'resultsBusiness' => $resultsBusiness,
            'resultsCategory' => $resultsCategory,
            'resultsProduct' => $resultsProduct
        ]);
    }

} 