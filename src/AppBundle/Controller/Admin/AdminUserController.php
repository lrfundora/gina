<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\File;
use AppBundle\Entity\User;
use AppBundle\Form\Type\UserAddType;
use AppBundle\Form\Type\UserInfoType;
use AppBundle\Form\Type\UserPasswordType;
use AppBundle\Form\Type\FileType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AdminUserController
 * @package AppBundle\Controller\Admin
 * @Route("/admin")
 */
class AdminUserController extends Controller
{
    /**
     * @Route("/user", name="_admin_user")
     */
    public function listUserAction()
    {
        // Render view
        return $this->render('AppBundle:backend/user:listUser.html.twig', [
            'appConfig' => $this->get('app.query')->getConfig(),
            'menu' => 'user',
            'nNotice' => $this->get('app.query')->countUnreadNotice(),
            'newNotices' => $this->get('app.query')->findAllNoticesUnRead(),
            'users' => $this->get('app.query')->findAllUser()
        ]);
    }

    /**
     * @Route("/user/add", name="_admin_user_add")
     */
    public function addUserAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = new User();
        $form = $this->createForm(new UserAddType(), $entity);

        $form->handleRequest($request);
        if ($form->isValid()) {
            if (!$this->get('app.query')->findUser($entity->getUser())) {
                $role = $this->getDoctrine()->getRepository('AppBundle:Role')->findOneBy(['role' => 'ROLE_MODERATOR']);
                $this->get('app.utils')->setUserPassword($entity);
                $em->persist($entity);
                $entity->addRole($role);
                $em->flush();
                $this->get('session')->getFlashBag()->add('userAddSuccess', 'Usuario <strong>agregado con éxito!</strong>');
            } else {
                $msg = 'El nombre de usuario "<strong>' . $entity->getUser() . '</strong>" ya existe!';
                $this->get('session')->getFlashBag()->add('userAddError', $msg);
            }
        }

        // Render view
        return $this->render('AppBundle:backend/user:addUser.html.twig', [
            'appConfig' => $this->get('app.query')->getConfig(),
            'menu' => 'user',
            'nNotice' => $this->get('app.query')->countUnreadNotice(),
            'newNotices' => $this->get('app.query')->findAllNoticesUnRead(),
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/user/edit/{id}", name="_admin_user_edit")
     */
    public function editUserAction($id)
    {
        $entity = $this->get('app.query')->findUserById($id);

        $tab = $this->get('request')->query->get('tab');
        if (!$tab) {
            $tab = '1';
        }

        $formInfo = $this->createFormUserInfo($entity);
        $formPhoto = $this->createFormUserPhoto($id, new File());
        $formPass = $this->createFormUserPassword($entity);

        // Render view
        return $this->render('AppBundle:backend/user:editUser.html.twig', [
            'appConfig' => $this->get('app.query')->getConfig(),
            'menu' => 'user',
            'nNotice' => $this->get('app.query')->countUnreadNotice(),
            'newNotices' => $this->get('app.query')->findAllNoticesUnRead(),
            'user' => $entity,
            'formInfo' => $formInfo->createView(),
            'formPhoto' => $formPhoto->createView(),
            'formPass' => $formPass->createView(),
            'tab' => $tab
        ]);
    }

    /**
     * @Route("/user/edit/{id}/info", name="_admin_user_edit_info")
     */
    public function editUserInfoAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $this->get('app.query')->findUserById($id);

        $formInfo = $this->createFormUserInfo($entity);
        $formInfo->handleRequest($request);
        if ($formInfo->isValid()) {
            $em->flush();
            $this->get('session')->getFlashBag()->add('userInfo', 'Información de usuario <strong>actualizada con éxito!</strong>');
        }

        return new RedirectResponse($this->generateUrl('_admin_user_edit', ['id' => $entity->getId(), 'tab' => '1'], true));
    }

    /**
     * @Route("/user/edit/{id}/photo", name="_admin_user_edit_photo")
     */
    public function editUserPhotoAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $this->get('app.query')->findUserById($id);

        $photo = new File();
        $formPhoto = $this->createFormUserPhoto($id, $photo);
        $formPhoto->handleRequest($request);
        if ($formPhoto->isValid()) {
            if ($formPhoto['file']->getData() != null) {
                $em->persist($photo);
                $entity->setPhoto($photo);
                $em->flush();
                $this->get('session')->getFlashBag()->add('userPhoto', 'Imagen del perfil <strong>actualizada con éxito!</strong>');
            } else {
                $entity->setPhoto(null);
                $this->get('session')->getFlashBag()->add('userPhoto', 'Imagen del perfil <strong>eliminada con éxito!</strong>');
            }
        }

        return new RedirectResponse($this->generateUrl('_admin_user_edit', ['id' => $id, 'tab' => '2'], true));
    }

    /**
     * @Route("/user/edit/{id}/password", name="_admin_user_edit_pass")
     */
    public function editUserPasswordAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $this->get('app.query')->findUserById($id);

        $formPass = $this->createFormUserPassword($entity);
        $formPass->handleRequest($request);
        if ($formPass->isValid()) {
            $this->get('app.utils')->setUserPassword($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('userPass', 'Contraseña <strong>cambiada con éxito!</strong>');
        }

        return new RedirectResponse($this->generateUrl('_admin_user_edit', ['id' => $entity->getId(), 'tab' => '3'], true));
    }

    /**
     * @Route("/user/delete/{id}", name="_admin_user_delete")
     */
    public function deleteUserAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('app.query')->findUserById($id);

        if (!$user) {
            $this->get('session')->getFlashBag()->add('deleteError', 'El usuario que ha intentado eliminar <strong>no existe!</strong>');
            return new RedirectResponse($this->generateUrl('_admin_user'));
        }
        if ($user->getRoles()[0]->getRole() != 'ROLE_ADMIN') {
            $em->remove($user);
            $em->flush();
        } else {
            $this->get('session')->getFlashBag()->add('deleteError', 'El usuario Administrador <strong>no puede ser eliminado!</strong>');
            return new RedirectResponse($this->generateUrl('_admin_user'));
        }

        $this->get('session')->getFlashBag()->add('deleteSuccess', 'Usuario <strong>eliminado correctamente!</strong>');
        return new RedirectResponse($this->generateUrl('_admin_user'));
    }

    /**
     * @param User $entity
     * @return \Symfony\Component\Form\Form
     * Create form to change user basic info
     */
    private function createFormUserInfo(User $entity)
    {
        return $this->createForm(new UserInfoType(), $entity, [
            'action' => $this->generateUrl('_admin_user_edit_info', ['id' => $entity->getId()])
        ]);
    }

    /**
     * @param User $entity
     * @return \Symfony\Component\Form\Form
     * Create form to change user photo
     */
    private function createFormUserPhoto($id, File $file)
    {
        return $this->createForm(new FileType(), $file, [
            'action' => $this->generateUrl('_admin_user_edit_photo', ['id' => $id])
        ]);
    }

    /**
     * @param User $entity
     * @return \Symfony\Component\Form\Form
     * Create form to change user password
     */
    private function createFormUserPassword(User $entity)
    {
        return $this->createForm(new UserPasswordType(), $entity, [
            'action' => $this->generateUrl('_admin_user_edit_pass', ['id' => $entity->getId()])
        ]);
    }
}
