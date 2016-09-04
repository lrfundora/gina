<?php

namespace AppBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AdminDefaultController extends Controller
{
    /**
     * @Route("/admin", name="_admin")
     */
    public function defaultAction()
    {
        // Check Offers
        $this->get('app.utils')->checkOffers($this->getDoctrine()->getManager());

        // Render view
        return $this->render('AppBundle:backend:default.html.twig', [
            'appConfig' => $this->get('app.query')->getConfig(),
            'menu' => 'index',
            'nNotice' => $this->get('app.query')->countUnreadNotice(),
            'newNotices' => $this->get('app.query')->findAllNoticesUnRead(),
            'users' => $this->getDoctrine()->getRepository('AppBundle:User')->findAll(),
            'notices' => $this->getDoctrine()->getRepository('AppBundle:Notice')->findAll()
        ]);
    }
}
