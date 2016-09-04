<?php

namespace AppBundle\Controller\Client;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\ParameterBag;

class OfferController extends Controller
{
    /**
     * @Route("/offers/offer", name="_offers")
     */
    public function offersAction()
    {
        // Check Offers
        $this->get('app.utils')->checkOffers($this->getDoctrine()->getManager());

        // Create breadcrumbs
        $breadcrumbs = $this->get("white_october_breadcrumbs");

        // Get var by $_GET
        $findOffer = $this->get('request')->query->get('find');

        // Order By
        $field = strtolower($this->get('request')->query->get('field'));
        $order = strtoupper($this->get('request')->query->get('order'));

        if ($findOffer == null || $findOffer == 'all') {
            if ($field != null && $order != null) {
                $offers = $this->get('app.query')->findAllOffers($field, $order);
            } else {
                $offers = $this->get('app.query')->findAllOffers();
            }

            $breadcrumbs->addItem("Inicio", $this->get("router")->generate("_home"))
                ->addItem("Ofertas");
        } else {
            if ($field != null && $order != null) {
                $offers = $this->get('app.query')->findAllOffersByBusinessSlug($findOffer, $field, $order);
            } else {
                $offers = $this->get('app.query')->findAllOffersByBusinessSlug($findOffer);
            }

            if (!$offers) {
                // Render view Error 404
                return $this->render('AppBundle:errors:error404.html.twig', [
                    'data' => $this->get('app.query')->getAppInfo(),
                    'numOffer' => $this->get('app.query')->countOffers(),
                    'topOffers' => $this->get('app.query')->findTopOffers(),
                    'topComments' => $this->get('app.query')->findTopComments()
                ]);
            }
            $breadcrumbs->addItem("Inicio", $this->get("router")->generate("_home"))
                ->addItem("Ofertas", $this->get('router')->generate("_offers", ["find" => "all", "field"=>"discount", "order"=>"desc"]))
                ->addItem($offers[0]->getCategory()->getBusiness()->getName());
        }

        // Array parameters order
        $arrOrder = [
            ['name' => 'Nombre', 'field' => 'name', 'order' => 'asc'],
            ['name' => 'Mayor descuento', 'field' => 'discount', 'order' => 'desc'],
            ['name' => 'Menor descuento', 'field' => 'discount', 'order' => 'asc']
        ];

        // Create Paginator
        if (!$this->get('request')->query->get('page')) {
            $page = 1;
        } else {
            $page = $this->get('request')->query->get('page');
        }
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $offers,
            $page, /*page number*/
            10 /*limit per page*/
        );

        // Render view
        return $this->render('AppBundle:publicity:offers.html.twig', [
            'data' => $this->get('app.query')->getAppInfo(),
            'numOffer' => $this->get('app.query')->countOffers(),
            'topOffers' => $this->get('app.query')->findTopOffers(),
            'topComments' => $this->get('app.query')->findTopComments(),
            'offers' => $pagination,
            'orderData' => $this->get('app.utils')->getOrderUri($this->get('request'), $arrOrder)
        ]);
    }

} 