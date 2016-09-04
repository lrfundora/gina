<?php

namespace AppBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class AdminNoticeController
 * @package AppBundle\Controller\Admin\Notice
 * @Route("/admin")
 */
class AdminNoticeController extends Controller
{

    /**
     * @Route("/notice", name="_admin_notice")
     */
    public function noticeAction()
    {
        // Render view
        return $this->render('AppBundle:backend/notice:notice.html.twig', [
            'appConfig' => $this->get('app.query')->getConfig(),
            'menu' => 'notice',
            'nNotice' => $this->get('app.query')->countUnreadNotice(),
            'newNotices' => $this->get('app.query')->findAllNoticesUnRead(),
            'notices' => $this->get('app.query')->findAllNotices()
        ]);
    }

    /**
     * @Route("/notice/")
     */
    public function indexAction()
    {
        return new RedirectResponse($this->generateUrl('_admin_notice'));
    }

    /**
     * @Route("/notice/show/{id}", name="_admin_notice_show")
     */
    public function showNoticeAction($id)
    {
        $notice = $this->get('app.query')->findNoticeById($id);
        if (!$notice) {
            $this->get('session')->getFlashBag()->add('show', 'La notificación solicitada <strong>no ha sido encontrado!</strong>');
        }

        if($notice && !$notice->getIsRead()){
            $em = $this->getDoctrine()->getManager();
            $notice->setIsRead(1);
            $em->persist($notice);
            $em->flush();
        }


        // Render view
        return $this->render('AppBundle:backend/notice:notice_show.html.twig', [
            'appConfig' => $this->get('app.query')->getConfig(),
            'menu' => 'notice',
            'nNotice' => $this->get('app.query')->countUnreadNotice(),
            'newNotices' => $this->get('app.query')->findAllNoticesUnRead(),
            'notice' => $notice
        ]);
    }

    /**
     * @Route("/notice/delete/{id}", name="_admin_notice_delete")
     */
    public function deleteNoticeAction($id)
    {
        $notice = $this->get('app.query')->findNoticeById($id);
        if (!$notice) {
            // Show view
            $this->get('session')->getFlashBag()->add('deleteError', 'La notificación que ha intentado eliminar <strong>no existe!</strong>');
            return new RedirectResponse($this->generateUrl('_admin_notice'));
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($notice);
        $em->flush();

        // Show view
        $this->get('session')->getFlashBag()->add('delete', 'Notificación <strong>eliminada correctamente!</strong>');
        return new RedirectResponse($this->generateUrl('_admin_notice'));
    }
}
