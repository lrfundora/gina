<?php

namespace AppBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AdminIndexController extends Controller
{
    /**
     * @Route("/admin/", name="_admin_index")
     */
    public function indexAction()
    {
        // Check Offers
        $this->get('app.utils')->checkOffers($this->getDoctrine()->getManager());
        // Render view
        return $this->render('AppBundle:backend:index.html.twig', [
            'appConfig' => $this->get('app.query')->getConfig(),
            'menu' => 'index',
            'nNotice' => $this->get('app.query')->countUnreadNotice(),
            'newNotices' => $this->get('app.query')->findAllNoticesUnRead(),
            'users' => $this->getDoctrine()->getRepository('AppBundle:User')->findAll(),
            'notices' => $this->getDoctrine()->getRepository('AppBundle:Notice')->findAll()
        ]);
    }
}
