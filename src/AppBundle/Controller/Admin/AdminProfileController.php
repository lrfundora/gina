<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\File;
use AppBundle\Entity\User;
use AppBundle\Form\Type\UserProfilePasswordType;
use AppBundle\Form\Type\UserInfoType;
use AppBundle\Form\Type\FileType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;

/**
 * Class AdminProfileController
 * @package AppBundle\Controller\Admin
 * @Route("/admin")
 */
class AdminProfileController extends Controller
{

    /**
     * @Route("/profile", name="_admin_profile")
     */
    public function profileUserAction()
    {
        $entity = $this->getUser();

        $tab = $this->get('request')->query->get('tab');
        if (!$tab) {
            $tab = '1';
        }

        $formInfo = $this->createFormUserInfo($entity);
        $formPhoto = $this->createFormUserPhoto(new File());
        $formPass = $this->createFormUserPassword();

        // Render view
        return $this->render('AppBundle:backend/profile:profileUser.html.twig', [
            'appConfig' => $this->get('app.query')->getConfig(),
            'menu' => '',
            'nNotice' => $this->get('app.query')->countUnreadNotice(),
            'newNotices' => $this->get('app.query')->findAllNoticesUnRead(),
            'formInfo' => $formInfo->createView(),
            'formPhoto' => $formPhoto->createView(),
            'formPass' => $formPass->createView(),
            'tab' => $tab
        ]);
    }

    /**
     * @Route("/profile/info", name="_admin_profile_info")
     */
    public function profileUserInfoAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $this->getUser();

        $formInfo = $this->createFormUserInfo($entity);
        $formInfo->handleRequest($request);
        if ($formInfo->isValid()) {
            $em->flush();
            $this->get('session')->getFlashBag()->add('userInfo', 'Información de usuario <strong>actualizada con exito!</strong>');
        }

        return new RedirectResponse($this->generateUrl('_admin_profile', ['tab' => '1']));
    }

    /**
     * @Route("/profile/photo", name="_admin_profile_photo")
     */
    public function profileUserPhotoAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $msgUpdate = 'Imagen del perfil <strong>actualizada con exito!</strong>';
        $msgDelete = 'Imagen del perfil <strong>eliminada con exito!</strong>';
        $entity = $this->getUser();
        $photo = new File();
        $formPhoto = $this->createFormUserPhoto($photo);
        $formPhoto->handleRequest($request);

        if ($formPhoto->isValid()) {

            if ($formPhoto['file']->getData() != null) {

                $em->persist($photo);
                $entity->setPhoto($photo);
                $this->get('session')->getFlashBag()->add('userPhoto', $msgUpdate);

            } else {

                $entity->setPhoto(null);
                $this->get('session')->getFlashBag()->add('userPhoto', $msgDelete);

            }

            $em->flush();
        }

        return new RedirectResponse($this->generateUrl('_admin_profile', ['tab' => '2']));
    }

    /**
     * @Route("/profile/password", name="_admin_profile_pass")
     */
    public function profileUserPasswordAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $encode = new BCryptPasswordEncoder(15);
        $entity = $this->getUser();
        $formPass = $this->createFormUserPassword();
        $msgSuccess = 'Contraseña <strong>cambiada con exito!</strong>';
        $msgError = 'La contraseña actual <strong>es incorrecta!</strong>';
        $msgWeak = 'La nueva contraseña <strong>es muy débil!</strong> Debe tener al menos <strong>5 caracteres</strong> compuestos por <strong>mayúscula, minúscula, números</strong> y opcionalmente signos o símbolos.';

        $formPass->handleRequest($request);
        $oldpassword = $encode->encodePassword($formPass['oldPassword']->getData(), $entity->getSalt());
        if ($formPass->isValid()) {
            if ($entity->getPassword() != $oldpassword) {
                $this->get('session')->getFlashBag()->add('userPassError', $msgError);
                return new RedirectResponse($this->generateUrl('_admin_profile', ['tab' => '3']));
            }
            $entity->setPassword($formPass['password']->getData());
            $this->get('app.utils')->setUserPassword($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('userPassSuccess', $msgSuccess);
        } else {

            if ($entity->getPassword() != $oldpassword) {
                $this->get('session')->getFlashBag()->add('userPassError', $msgError);
            }

            $this->get('session')->getFlashBag()->add('userPassWeak', $msgWeak);
        }

        return new RedirectResponse($this->generateUrl('_admin_profile', ['tab' => '3']));
    }

    /**
     * @param User $entity
     * @return \Symfony\Component\Form\Form
     * Create form to change user basic info
     */
    private function createFormUserInfo(User $entity)
    {
        return $this->createForm(new UserInfoType(), $entity, [
            'action' => $this->generateUrl('_admin_profile_info')
        ]);
    }

    /**
     * @param User $entity
     * @return \Symfony\Component\Form\Form
     * Create form to change user photo
     */
    private function createFormUserPhoto(File $file)
    {
        return $this->createForm(new FileType(), $file, [
            'action' => $this->generateUrl('_admin_profile_photo')
        ]);
    }

    /**
     * @param User $entity
     * @return \Symfony\Component\Form\Form
     * Create form to change user password
     */
    private function createFormUserPassword()
    {
        return $this->createForm(new UserProfilePasswordType(), null, [
            'action' => $this->generateUrl('_admin_profile_pass')
        ]);
    }
}
