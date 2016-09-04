<?php

namespace AppBundle\Controller\Client;

use AppBundle\Entity\Mail;
use AppBundle\Entity\Notice;
use AppBundle\Form\Type\ContactUsType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class SupportController extends Controller
{
    /**
     * @Route("/support/", name="_support")
     */
    public function indexAction()
    {
        return new RedirectResponse($this->generateUrl('_support_about-us'));
    }
//
//    /**
//     * @Route("/support/help", name="_support_help")
//     */
//    public function helpAction()
//    {
//        // Create breadcrumbs
//        $breadcrumbs = $this->get("white_october_breadcrumbs");
//        $breadcrumbs->addItem("Inicio", $this->get("router")->generate("_home"))
//            ->addItem("Soporte")
//            ->addItem("Ayuda");
//
//        // Render view
//        return $this->render('AppBundle:support:help.html.twig', [
//            'data' => $this->get('app.query')->getAppInfo(),
//            'numOffer' => $this->get('app.query')->countOffers(),
//            'topOffers' => $this->get('app.query')->findTopOffers(),
//            'topComments' => $this->get('app.query')->findTopComments()
//        ]);
//    }

//    /**
//     * @Route("/support/terms-and-conditions", name="_support_terms")
//     */
//    public function termsAction()
//    {
//        // Create breadcrumbs
//        $breadcrumbs = $this->get("white_october_breadcrumbs");
//        $breadcrumbs->addItem("Inicio", $this->get("router")->generate("_home"))
//            ->addItem("Soporte", $this->get("router")->generate("_support"))
//            ->addItem("Derechos y responsabilidades");
//
//        // Render view
//        return $this->render('AppBundle:support:terms-and-conditions.html.twig', [
//            'data' => $this->get('app.query')->getAppInfo(),
//            'numOffer' => $this->get('app.query')->countOffers(),
//            'topOffers' => $this->get('app.query')->findTopOffers(),
//            'topComments' => $this->get('app.query')->findTopComments()
//        ]);
//    }

    /**
     * @Route("/support/about-us", name="_support_about-us")
     */
    public function aboutUsAction()
    {
        // Create breadcrumbs
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem("Inicio", $this->get("router")->generate("_home"))
            ->addItem("Soporte", $this->get("router")->generate("_support"))
            ->addItem("Nosotros");

        // Render view
        return $this->render('AppBundle:support:about-us.html.twig', [
            'data' => $this->get('app.query')->getAppInfo(),
            'numOffer' => $this->get('app.query')->countOffers(),
            'topOffers' => $this->get('app.query')->findTopOffers(),
            'topComments' => $this->get('app.query')->findTopComments()
        ]);

    }

    /**
     * @Route("/support/contact-us", name="_support_contact-us")
     */
    public function contactUsAction(Request $request)
    {
        // Create breadcrumbs
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem("Inicio", $this->get("router")->generate("_home"))
            ->addItem("Soporte", $this->get("router")->generate("_support"))
            ->addItem("Contacto");

        // Create form
        $form = $this->createForm(new ContactUsType());

        $form->handleRequest($request);

        if ($form->isValid()) {

            // Get Data Form
            $formData = $form->getData();

            // Get Entity Manager
            $em = $this->getDoctrine()->getManager();

            // Get Notice Type
            $noticeType = $this->get('app.query')->findNoticeByType(['type' => 'email']);

            // Set Notice data
            $notice = new Notice();
            $notice
                ->setType($noticeType)
                ->setSubject($formData['subject'])
                ->setMessage($formData['message']);

            // Set Mail data
            $mail = new Mail();
            $mail
                ->setName($formData['name'])
                ->setEmail($formData['email'])
                ->setNotice($notice);

            // Persist data
            $em->persist($notice);
            $em->persist($mail);


            // Add Message to Users
            $users = $this->get('app.query')->findAllUser();
            foreach ($users as $user) {
                $user->addNotice($notice);
                $em->persist($user);
            }

            // Saving
            $em->flush();

            // Show Information to User
            $this->get('session')->getFlashBag()->add('contactInfo', '<strong>Gracias!!!</strong>, Tú mensaje fue enviado correctamente.');
            return new RedirectResponse($this->generateUrl('_support_contact-us'));
        }

        // Render view
        return $this->render('AppBundle:support:contact-us.html.twig', [
            'data' => $this->get('app.query')->getAppInfo(),
            'numOffer' => $this->get('app.query')->countOffers(),
            'topOffers' => $this->get('app.query')->findTopOffers(),
            'topComments' => $this->get('app.query')->findTopComments(),
            'form' => $form->createView(),
        ]);

    }

}
