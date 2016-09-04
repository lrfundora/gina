<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Business;
use AppBundle\Entity\File;
use AppBundle\Entity\Schedule;
use AppBundle\Form\Type\AnimationType;
use AppBundle\Form\Type\BusinessAddType;
use AppBundle\Form\Type\BusinessEditType;
use AppBundle\Form\Type\FileType;
use AppBundle\Form\Type\ScheduleType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class AdminBusinessController
 * @package AppBundle\Controller\Admin
 * @Route("/admin")
 */
class AdminBusinessController extends Controller
{
    /**
     * @Route("/business", name="_admin_business")
     */
    public function listBusinessAction()
    {
        // Render view
        return $this->render('AppBundle:backend/business:listBusiness.html.twig', [
            'appConfig' => $this->get('app.query')->getConfig(),
            'menu' => 'business',
            'nNotice' => $this->get('app.query')->countUnreadNotice(),
            'newNotices' => $this->get('app.query')->findAllNoticesUnRead(),
            'business' => $this->get('app.query')->findAllBusiness()
        ]);
    }

    /**
     * @Route("/business/add", name="_admin_business_add")
     */
    public function addBusinessAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $appConfig = $this->get('app.query')->getConfig();

        $entity = new Business();
        $entity->setConfig($appConfig);
        $entity->addSchedule(new Schedule());

        $form = $this->createForm(new BusinessAddType($em), $entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('businessSuccess', 'Negocio <strong>agregado con éxito!</strong>');
            return new RedirectResponse($this->generateUrl('_admin_business'));
        }

        // Render view
        return $this->render('AppBundle:backend/business:addBusiness.html.twig', [
            'appConfig' => $appConfig,
            'menu' => 'business',
            'nNotice' => $this->get('app.query')->countUnreadNotice(),
            'newNotices' => $this->get('app.query')->findAllNoticesUnRead(),
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/business/{id}/edit", name="_admin_business_edit")
     */
    public function editBusinessAction($id)
    {
        $tab = $this->get('request')->query->get('tab');
        if (null == $tab) {
            $tab = 1;
        }

        $em = $this->getDoctrine()->getManager();

        $business = $this->getDoctrine()->getRepository('AppBundle:Business')->find($id);
        if (!$business) {
            $this->get('session')->getFlashBag()->add('businessError', 'Error: El negocio <strong>no existe!</strong>');
            return new RedirectResponse($this->generateUrl('_admin_business'));
        }

        $formSch = $this->createForm(new ScheduleType(), new Schedule(), [
            'action' => $this->generateUrl('_admin_business_sch_add', ['id' => $id])
        ]);
        $formImgPublicity = $this->createForm(new FileType(), new File(), [
            'action' => $this->generateUrl('_admin_business_upload_img-public', ['id' => $id])
        ]);
        $formImgThumb = $this->createForm(new FileType(), new File(), [
            'action' => $this->generateUrl('_admin_business_upload_img-thumb', ['id' => $id])
        ]);

        $formAnimation = $this->createForm(new AnimationType($em), $business, [
            'action' => $this->generateUrl('_admin_business_edit_animation', ['id' => $id])
        ]);

        $form = $this->createForm(new BusinessEditType(), $business, [
            'action' => $this->generateUrl('_admin_business_edit_info', ['id' => $id])
        ]);

        // Render view
        return $this->render('AppBundle:backend/business:editBusiness.html.twig', [
            'appConfig' => $this->get('app.query')->getConfig(),
            'menu' => 'business',
            'nNotice' => $this->get('app.query')->countUnreadNotice(),
            'newNotices' => $this->get('app.query')->findAllNoticesUnRead(),
            'entity' => $business,
            'form' => $form->createView(),
            'formSch' => $formSch->createView(),
            'formImgPublicity' => $formImgPublicity->createView(),
            'formAnimation' => $formAnimation->createView(),
            'formImgThumb' => $formImgThumb->createView(),
            'tab' => $tab
        ]);
    }

    /**
     * @Route("/business/{id}/edit/info", name="_admin_business_edit_info")
     */
    public function editInfoBusinessAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $business = $this->getDoctrine()->getRepository('AppBundle:Business')->find($id);
        if (!$business) {
            $this->get('session')->getFlashBag()->add('infoError', 'Error: El negocio <strong>no existe!</strong>');
            return new RedirectResponse($this->generateUrl('_admin_business'));
        }
        $form = $this->createForm(new BusinessEditType(), $business);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em->flush();
            $this->get('session')->getFlashBag()->add('infoSuccess', 'Información del negocio <strong>actualizada con éxito!</strong>');
        }
        return new RedirectResponse($this->generateUrl('_admin_business_edit', ['id' => $id, 'tab' => 1]));

    }

    /**
     * @Route("/business/{id}/edit/animation", name="_admin_business_edit_animation")
     */
    public function editAnimationBusinessAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $business = $this->getDoctrine()->getRepository('AppBundle:Business')->find($id);
        if (!$business) {
            $this->get('session')->getFlashBag()->add('imageError', 'Error: El negocio <strong>no existe!</strong>');
            return new RedirectResponse($this->generateUrl('_admin_business'));
        }
        $form = $this->createForm(new AnimationType($em), $business);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em->flush();
            $this->get('session')->getFlashBag()->add('imageSuccess', 'Animación <strong>cambiada con éxito!</strong>');
        }
        return new RedirectResponse($this->generateUrl('_admin_business_edit', ['id' => $id, 'tab' => 2]) . '#img1');

    }

    /**
     * @Route("/business/{id}/upload/img-public", name="_admin_business_upload_img-public")
     */
    public function imagePublicBusinessAction(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();
        $business = $this->getDoctrine()->getRepository('AppBundle:Business')->find($id);
        if (!$business) {
            $this->get('session')->getFlashBag()->add('imageError', 'Error: El negocio <strong>no existe!</strong>');
            return new RedirectResponse($this->generateUrl('_admin_business'));
        }

        $imgPublicity = new File();
        $form = $this->createForm(new FileType(), $imgPublicity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            if ($form['file']->getData() != null) {
                $em->persist($imgPublicity);
                $business->setImgPublicity($imgPublicity);
                $em->flush();
                $this->get('session')->getFlashBag()->add('imageSuccess', 'Imagen de publicidad <strong>actualizada con éxito!</strong>');
            }
        }
        return new RedirectResponse($this->generateUrl('_admin_business_edit', ['id' => $id, 'tab' => 2]) . '#img1');

    }

    /**
     * @Route("/business/{id}/upload/img-thumb", name="_admin_business_upload_img-thumb")
     */
    public function imageThumbBusinessAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $business = $this->getDoctrine()->getRepository('AppBundle:Business')->find($id);
        if (!$business) {
            $this->get('session')->getFlashBag()->add('imageError', 'Error: El negocio <strong>no existe!</strong>');
            return new RedirectResponse($this->generateUrl('_admin_business'));
        }

        $imgThumb = new File();
        $form = $this->createForm(new FileType(), $imgThumb);
        $form->handleRequest($request);
        if ($form->isValid()) {
            if ($form['file']->getData() != null) {
                $em->persist($imgThumb);
                $business->setImgThumb($imgThumb);
                $em->flush();
                $this->get('session')->getFlashBag()->add('imageSuccess', 'Imagen de presentacion <strong>actualizada con éxito!</strong>');
            }
        }
        return new RedirectResponse($this->generateUrl('_admin_business_edit', ['id' => $id, 'tab' => 2]) . '#img2');
    }

    /**
     * @Route("/business/{id}/schedule/add", name="_admin_business_sch_add")
     */
    public function addScheduleAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $business = $this->getDoctrine()->getRepository('AppBundle:Business')->find($id);

        if (!$business) {
            $this->get('session')->getFlashBag()->add('businessError', 'Error: El negocio <strong>no existe!</strong>');
            return new RedirectResponse($this->generateUrl('_admin_business', ['tab' => 1]));
        }

        $sch = new Schedule();
        $form = $this->createForm(new ScheduleType(), $sch);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $business->addSchedule($sch);
            $em->persist($sch);
            $em->flush();
        }

        $this->get('session')->getFlashBag()->add('infoSuccess', 'Nuevo horario <strong>agregado con éxito!</strong>');
        return new RedirectResponse($this->generateUrl('_admin_business_edit', ['id' => $id, 'tab' => 1]));
    }

    /**
     * @Route("/business/{id}/schedule/{item}/delete", name="_admin_business_sch_delete")
     */
    public function deleteScheduleAction($id, $item)
    {
        $em = $this->getDoctrine()->getManager();

        $business = $this->getDoctrine()->getRepository('AppBundle:Business')->find($id);

        if (!$business) {
            $this->get('session')->getFlashBag()->add('businessError', 'Error: El negocio <strong>no existe!</strong>');
            return new RedirectResponse($this->generateUrl('_admin_business', ['tab' => 1]));
        }

        foreach ($business->getSchedules() as $i => $sch) {
            if ($i == $item) {
                $em->remove($sch);
                $em->flush();
                $this->get('session')->getFlashBag()->add('infoSuccess', 'Horario <strong>eliminado con éxito! </strong>');
                return new RedirectResponse($this->generateUrl('_admin_business_edit', ['id' => $id, 'tab' => 1]));
            }
        }

        $this->get('session')->getFlashBag()->add('infoError', 'Error: El horario <strong>no existe!</strong>');
        return new RedirectResponse($this->generateUrl('_admin_business_edit', ['id' => $id, 'tab' => 1]));
    }

    /**
     * @Route("/business/{id}/delete", name="_admin_business_delete")
     */
    public function deleteBusinessAction($id)
    {
        $business = $this->get('app.query')->findBusinessId($id);

        if (!$business) {
            $this->get('session')->getFlashBag()->add('businessError', 'El negocio que intenta eliminar <strong>no existe!</strong>');
            return new RedirectResponse($this->generateUrl('_admin_business'));
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($business);
        $em->flush();

        $this->get('session')->getFlashBag()->add('businessSuccess', 'Negocio <strong>eliminado con éxito!</strong>');
        return new RedirectResponse($this->generateUrl('_admin_business'));
    }
}
