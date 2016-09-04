<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="_home")
     */
    public function indexAction()
    {
        // Check Offers
        $this->get('app.utils')->checkOffers($this->getDoctrine()->getManager());

        // Render view
        return $this->render('AppBundle:default:index.html.twig', [
            'data' =>$this->get('app.query')->getAppInfo(),
            'numOffer' => $this->get('app.query')->countOffers(),
            'topOffers' => $this->get('app.query')->findTopOffers(),
            'topComments' => $this->get('app.query')->findTopComments()
        ]);
    }

}
