<?php

namespace AppBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/admin/auth")
 */
class SecurityController extends Controller
{

    /**
     * @Route("/login", name="_login")
     */
    public function loginAction()
    {

        $authenticationUtils = $this->get('security.authentication_utils');
        $lastUsername = $authenticationUtils->getLastUsername();
        $error = $authenticationUtils->getLastAuthenticationError();
        if ($error) {
            $user = $this->get('app.query')->findUser($lastUsername);
            if (!$user) {
                $error = 'Este usuario <strong>no existe!</strong>';
            } elseif (!$user->getIsActive()) {
                $error = 'Tu usuario está <strong>inactivo!</strong>';
            } else {
                $error = 'Contraseña <strong>incorrecta!</strong>';
            }
            $this->get('session')->getFlashBag()->add('loginError', $error);
        } else {
            $lastUsername = '';
        }

        return $this->render('AppBundle:backend/security:login.html.twig', [
            'appConfig' => $this->get('app.query')->getConfig(),
            'last_username' => $lastUsername
        ]);

    }

    /**
     * @Route("/login_check", name="_check")
     */
    public function loginCheckAction()
    {
        // Don't need implement it
    }

    /**
     * @Route("/logout", name="_logout")
     */
    public function logoutAction()
    {
        // Don't need implement it
    }

} 