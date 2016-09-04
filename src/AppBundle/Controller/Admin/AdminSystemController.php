<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\File;
use AppBundle\Form\Type\FileType;
use AppBundle\Form\Type\SystemOfferType;
use AppBundle\Form\Type\SystemInfoType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AdminSystemController
 * @package AppBundle\Controller\Admin
 * @Route("/admin")
 */
class AdminSystemController extends Controller
{

    /**
     * @Route("/system", name="_admin_system")
     */
    public function editSystemAction()
    {
        $tab = $this->get('request')->query->get('tab');
        if ($tab == null) {
            $tab = '1';
        }
        $entity = $this->get('app.query')->getConfig();
        $formSystem = $this->createForm(new SystemInfoType(), $entity, [
            'action' => $this->generateUrl('_admin_system_info')
        ]);
        $formLogoTop = $this->createForm(new FileType(), new File(), [
            'action' => $this->generateUrl('_admin_system_upload_logotop')
        ]);
        $formInfoImages = $this->createForm(new FileType(), new File(), [
            'action' => $this->generateUrl('_admin_system_upload_infoimages')
        ]);
        $formParallax = $this->createForm(new FileType(), new File(), [
            'action' => $this->generateUrl('_admin_system_upload_parallax')
        ]);
        $formLogoDown = $this->createForm(new FileType(), new File(), [
            'action' => $this->generateUrl('_admin_system_upload_logodown')
        ]);
        $formOffer = $this->createForm(new SystemOfferType(), $entity, [
            'action' => $this->generateUrl('_admin_system_offer')
        ]);

        // Render view
        return $this->render('AppBundle:backend/system:system.html.twig', [
            'appConfig' => $this->get('app.query')->getConfig(),
            'menu' => 'system',
            'nNotice' => $this->get('app.query')->countUnreadNotice(),
            'newNotices' => $this->get('app.query')->findAllNoticesUnRead(),
            'entity' => $entity,
            'formSystem' => $formSystem->createView(),
            'formLogoTop' => $formLogoTop->createView(),
            'formInfoImages' => $formInfoImages->createView(),
            'formParallax' => $formParallax->createView(),
            'formLogoDown' => $formLogoDown->createView(),
            'formOffer' => $formOffer->createView(),
            'tab' => $tab
        ]);
    }

    /**
     * @Route("/system/info", name="_admin_system_info")
     */
    public function updateInfoAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $this->get('app.query')->getConfig();
        $formSystem = $this->createForm(new SystemInfoType(), $entity);
        $formSystem->handleRequest($request);
        if ($formSystem->isValid()) {
            $em->flush();
            $this->get('session')->getFlashBag()->add('sysInfo', 'Información <strong>actualizada con éxito!</strong>');
        }
        return new RedirectResponse($this->generateUrl('_admin_system', ['tab' => '1']));
    }

    /**
     * @Route("/system/upload/logotop", name="_admin_system_upload_logotop")
     */
    public function updateLogoTopAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $this->get('app.query')->getConfig();

        $img = new File();
        $formLogoTop = $this->createForm(new FileType(), $img, [
            'action' => $this->generateUrl('_admin_system_upload_logotop')
        ]);
        $formLogoTop->handleRequest($request);
        if ($formLogoTop->isValid()) {
            if ($formLogoTop['file']->getData() != null) {
                $em->persist($img);
                $entity->setLogoTop($img);
                $this->get('session')->getFlashBag()->add('sysImages', 'Logo superior <strong>actualizado con éxito!</strong>');
                $em->flush();
            } else {
                $this->get('session')->getFlashBag()->add('sysImagesError', '<strong>Error</strong> al actualizar el logo superior!');
            }
        }
        return new RedirectResponse($this->generateUrl('_admin_system', ['tab' => '2']));
    }

    /**
     * @Route("/system/upload/infoimages", name="_admin_system_upload_infoimages")
     */
    public function addInfoImagesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $this->get('app.query')->getConfig();

        $img = new File();
        $formInfoImages = $this->createForm(new FileType(), $img, [
            'action' => $this->generateUrl('_admin_system_upload_infoimages')
        ]);
        $formInfoImages->handleRequest($request);
        if ($formInfoImages->isValid()) {
            if ($formInfoImages['file']->getData() != null) {
                $em->persist($img);
                $entity->addInfoImage($img);
                $this->get('session')->getFlashBag()->add('sysImages', 'Imagen de información <strong>agregada con éxito!</strong>');
                $em->flush();
            } else {
                $this->get('session')->getFlashBag()->add('sysImagesError', '<strong>Error</strong> al agregar la imagen de información!');
            }
        }
        return new RedirectResponse($this->generateUrl('_admin_system', ['tab' => '2']));
    }

    /**
     * @Route("/system/delete/infoimages/{id}", name="_admin_system_delete_infoimages")
     */
    public function deleteInfoImagesAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $this->get('app.query')->getConfig();
        $img = $this->getDoctrine()->getRepository('AppBundle:File')->findOneBy(['id' => $id]);
        if (!$img) {
            $this->get('session')->getFlashBag()->add('sysImagesError', 'La imagen de información que intenta eliminar <strong>no existe!</strong>');
            return new RedirectResponse($this->generateUrl('_admin_system', ['tab' => '2']));
        }

        $entity->removeInfoImage($img);
        $this->get('session')->getFlashBag()->add('sysImages', 'Imagen de información <strong>eliminada con éxito!</strong>');
        $em->flush();

        return new RedirectResponse($this->generateUrl('_admin_system', ['tab' => '2']));
    }

    /**
     * @Route("/system/upload/parallax", name="_admin_system_upload_parallax")
     */
    public function updateParallaxAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $this->get('app.query')->getConfig();

        $img = new File();
        $formParallax = $this->createForm(new FileType(), $img, [
            'action' => $this->generateUrl('_admin_system_upload_parallax')
        ]);
        $formParallax->handleRequest($request);
        if ($formParallax->isValid()) {
            if ($formParallax['file']->getData() != null) {
                $em->persist($img);
                $entity->setImgParallax($img);
                $this->get('session')->getFlashBag()->add('sysImages', 'Imagen movible <strong>actualizada con éxito!</strong>');
                $em->flush();
            } else {
                $this->get('session')->getFlashBag()->add('sysImagesError', '<strong>Error</strong> al actualizar la imagen movible!');
            }
        }
        return new RedirectResponse($this->generateUrl('_admin_system', ['tab' => '2']));
    }

    /**
     * @Route("/system/upload/logodown", name="_admin_system_upload_logodown")
     */
    public function updateLogoDownAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $this->get('app.query')->getConfig();

        $img = new File();
        $formLogoDown = $this->createForm(new FileType(), $img, [
            'action' => $this->generateUrl('_admin_system_upload_logodown')
        ]);
        $formLogoDown->handleRequest($request);
        if ($formLogoDown->isValid()) {
            if ($formLogoDown['file']->getData() != null) {
                $em->persist($img);
                $entity->setLogoDown($img);
                $this->get('session')->getFlashBag()->add('sysImages', 'Logo Inferior <strong>actualizado con éxito!</strong>');
                $em->flush();
            } else {
                $this->get('session')->getFlashBag()->add('sysImagesError', '<strong>Error</strong> al actualizar el logo inferior!');
            }
        }
        return new RedirectResponse($this->generateUrl('_admin_system', ['tab' => '2']));
    }

    /**
     * @Route("/system/offer", name="_admin_system_offer")
     */
    public function updateOfferAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $this->get('app.query')->getConfig();
        $formOffer = $this->createForm(new SystemOfferType(), $entity);
        $formOffer->handleRequest($request);
        if ($formOffer->isValid()) {
            if ($formOffer['imgOffer']->getData() != null) {
                $em->persist($entity);
                $this->get('session')->getFlashBag()->add('sysOffer', 'Datos de oferta <strong>actualizados con éxito!</strong>');
            }
            $em->flush();
        }
        return new RedirectResponse($this->generateUrl('_admin_system', ['tab' => '3']));
    }

}
