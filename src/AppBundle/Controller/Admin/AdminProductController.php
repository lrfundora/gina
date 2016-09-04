<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\File;
use AppBundle\Entity\Product;
use AppBundle\Form\Type\FileType;
use AppBundle\Form\Type\ProductAddType;
use AppBundle\Form\Type\ProductEditType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AdminProductController
 * @package AppBundle\Controller\Admin
 * @Route("/admin")
 */
class AdminProductController extends Controller
{
    /**
     * @Route("/product", name="_admin_product")
     */
    public function listProductAction()
    {
        // Check Offers
        $this->get('app.utils')->checkOffers($this->getDoctrine()->getManager());

        // Render view
        return $this->render('AppBundle:backend/product:listProduct.html.twig', [
            'appConfig' => $this->get('app.query')->getConfig(),
            'menu' => 'product',
            'nNotice' => $this->get('app.query')->countUnreadNotice(),
            'newNotices' => $this->get('app.query')->findAllNoticesUnRead(),
            'products' => $this->get('app.query')->findAllProduct()

        ]);
    }

    /**
     * @Route("/product/add", name="_admin_product_add1")
     */
    public function add1ProductAction()
    {
        // Render view
        return $this->render('AppBundle:backend/product:addProduct1.html.twig', [
            'appConfig' => $this->get('app.query')->getConfig(),
            'menu' => 'product',
            'nNotice' => $this->get('app.query')->countUnreadNotice(),
            'newNotices' => $this->get('app.query')->findAllNoticesUnRead(),
            'business' => $this->getDoctrine()->getRepository('AppBundle:Business')->findAll()
        ]);
    }

    /**
     * @Route("/product/add/", name="_admin_product_add2")
     */
    public function add2ProductAction(Request $request)
    {
        $idB = $request->get('form_business');
        $id = $this->get('request')->query->get('id');

        if ($idB == null) {
            if ($id != null) {
                $idB = $id;
            }
        }

        $business = $this->getDoctrine()->getRepository('AppBundle:Business')->findOneBy(['id' => $idB]);
        if (!$business) {
            $this->get('session')->getFlashBag()->add('Error', 'Error: El negocio seleccionado <strong>no existe!</strong>');
            return new RedirectResponse($this->generateUrl('_admin_product'));
        }

        return $this->render('AppBundle:backend/product:addProduct2.html.twig', [
            'appConfig' => $this->get('app.query')->getConfig(),
            'menu' => 'product',
            'nNotice' => $this->get('app.query')->countUnreadNotice(),
            'newNotices' => $this->get('app.query')->findAllNoticesUnRead(),
            'business' => $business,
            'categories' => $this->getDoctrine()->getRepository('AppBundle:Category')->findCategoriesByBusinessId($idB)
        ]);
    }

    /**
     * @Route("/product/add/{idB}", name="_admin_product_add3")
     */
    public function add3ProductAction(Request $request, $idB)
    {
        $idC = $request->get('form_category');
        return new RedirectResponse($this->generateUrl('_admin_product_add4', ['idB' => $idB, 'idC' => $idC]));
    }

    /**
     * @Route("/product/add/{idB}/{idC}", name="_admin_product_add4")
     */
    public function add4ProductAction(Request $request, $idB, $idC)
    {
        $em = $this->getDoctrine()->getManager();

        $business = $this->getDoctrine()->getRepository('AppBundle:Business')->findOneBy(['id' => $idB]);
        if (!$business) {
            $this->get('session')->getFlashBag()->add('Error', 'Error: El negocio seleccionado <strong>no existe!</strong>');
            return new RedirectResponse($this->generateUrl('_admin_product'));
        }

        $category = $this->getDoctrine()->getRepository('AppBundle:Category')->findOneBy(['id' => $idC]);
        if (!$category) {
            $this->get('session')->getFlashBag()->add('Error', 'Error: La categoría seleccionada <strong>no existe!</strong>');
            return new RedirectResponse($this->generateUrl('_admin_product'));
        }

        $business->addCategory($category);
        $photo = new File();

        $product = new Product();
        $product
            ->setCategory($category)
            ->addPhoto($photo);

        $form = $this->createForm(new ProductAddType(), $product);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em->persist($product);
            $em->flush();
            $this->get('session')->getFlashBag()->add('Success', 'Producto <strong>agregado con éxito!</strong>');
            return new RedirectResponse($this->generateUrl('_admin_product'));
        }

        return $this->render('AppBundle:backend/product:addProduct3.html.twig', [
            'appConfig' => $this->get('app.query')->getConfig(),
            'menu' => 'product',
            'nNotice' => $this->get('app.query')->countUnreadNotice(),
            'newNotices' => $this->get('app.query')->findAllNoticesUnRead(),
            'business' => $business,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/product/edit/{id}", name="_admin_product_edit")
     */
    public function editProductAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $tab = $this->get('request')->query->get('tab');
        if ($tab == null) {
            $tab = 1;
        }

        $product = $this->getDoctrine()->getRepository('AppBundle:Product')->findOneBy(['id' => $id]);
        if (!$product) {
            $this->get('session')->getFlashBag()->add('Error', 'Error: El producto seleccionado <strong>no existe!</strong>');
            return new RedirectResponse($this->generateUrl('_admin_product'));
        }

        $form = $this->createForm(new ProductEditType(), $product);
        $formPhoto = $this->createForm(new FileType(), new File(), [
            'action' => $this->generateUrl('_admin_product_photo_upload', ['id' => $id])
        ]);

        $form->handleRequest($request);
        if ($form->isValid()) {
            if (!$product->getIsOffer()) {
                $product
                    ->setDiscount(null)
                    ->setPublicated(null)
                    ->setExpired(null)
                    ->setConditions(null);
            }
            $em->flush();
            $this->get('session')->getFlashBag()->add('Success', 'Producto actualizado <strong>con éxito!</strong>');
            return new RedirectResponse($this->generateUrl('_admin_product'));
        }

        // Render view
        return $this->render('AppBundle:backend/product:editProduct.html.twig', [
            'appConfig' => $this->get('app.query')->getConfig(),
            'menu' => 'product',
            'nNotice' => $this->get('app.query')->countUnreadNotice(),
            'newNotices' => $this->get('app.query')->findAllNoticesUnRead(),
            'entity' => $product,
            'form' => $form->createView(),
            'formPhoto' => $formPhoto->createView(),
            'tab' => $tab
        ]);
    }

    /**
     * @Route("/product/edit/{id}/photo/upload", name="_admin_product_photo_upload")
     */
    public function addPhotoAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $this->getDoctrine()->getRepository('AppBundle:Product')->findOneBy(['id' => $id]);
        if (!$entity) {
            $this->get('session')->getFlashBag()->add('Error', 'Error: El producto seleccionado <strong>no existe!</strong>');
            return new RedirectResponse($this->generateUrl('_admin_product'));
        }

        $img = new File();
        $form = $this->createForm(new FileType(), $img, [
            'action' => $this->generateUrl('_admin_product_photo_upload', ['id' => $id])
        ]);
        $form->handleRequest($request);
        if ($form->isValid()) {
            if ($form['file']->getData() != null) {
                $em->persist($img);
                $entity->addPhoto($img);
                $em->flush();
                $this->get('session')->getFlashBag()->add('productImagesSuccess', 'Imagen del producto <strong>agregada con éxito!</strong>');
            } else {
                $this->get('session')->getFlashBag()->add('productImagesError', '<strong>Error</strong> al agregar la imagen del producto!');
            }
        }
        return new RedirectResponse($this->generateUrl('_admin_product_edit', ['id' => $id, 'tab' => '2']));
    }

    /**
     * @Route("/product/edit/{id}/photo/{idF}/delete", name="_admin_product_photo_delete")
     */
    public function deletePhotoAction($id, $idF)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $this->getDoctrine()->getRepository('AppBundle:Product')->findOneBy(['id' => $id]);
        if (!$entity) {
            $this->get('session')->getFlashBag()->add('Error', 'Error: El producto seleccionado <strong>no existe!</strong>');
            return new RedirectResponse($this->generateUrl('_admin_product'));
        }

        $img = $this->getDoctrine()->getRepository('AppBundle:File')->findOneBy(['id' => $idF]);
        if (!$img) {
            $this->get('session')->getFlashBag()->add('productImagesError', 'Error: La imagen seleccionado <strong>no existe!</strong>');
            return new RedirectResponse($this->generateUrl('_admin_product_edit', ['id' => $id, 'tab' => 2]));
        }

        $entity->removePhoto($img);
        $em->flush();
        $this->get('session')->getFlashBag()->add('productImagesSuccess', 'Imagen del producto <strong>eliminada con éxito!</strong>');

        return new RedirectResponse($this->generateUrl('_admin_product_edit', ['id' => $id, 'tab' => '2']));
    }

    /**
     * @Route("/product/offer/{id}/create", name="_admin_product_offer")
     */
    public function offerProductAction(Request $request, $id)
    {
        $product = $this->get('app.query')->findProductById($id);

        if (!$product) {
            $this->get('session')->getFlashBag()->add('Error', 'El producto <strong>no existe!</strong>');
            return new RedirectResponse($this->generateUrl('_admin_product'));
        }

        $today = new \DateTime('now');
        $discount = $request->get('form_discount');
        $datePublicated = date_create_from_format('d/m/Y', $request->get('form_publicated'));
        $dateExpired = date_create_from_format('d/m/Y', $request->get('form_expired'));
        $conditions = $request->get('form_conditions');

        if ($today > $datePublicated) {
            $this->get('session')->getFlashBag()->add('Error', 'Error: La fecha de inicio <strong>tiene que ser igual o mayor a la fecha actual!</strong>');
            return new RedirectResponse($this->generateUrl('_admin_product'));
        }
        if ($datePublicated >= $dateExpired) {
            $this->get('session')->getFlashBag()->add('Error', 'Error: La fecha de terminación <strong>tiene que ser mayor a la fecha de inicio!</strong>');
            return new RedirectResponse($this->generateUrl('_admin_product'));
        }

        if ($conditions == '') {
            $conditions = 'Sin condiciones';
        }

        $product
            ->setIsOffer(true)
            ->setDiscount($discount)
            ->setPublicated($datePublicated)
            ->setExpired($dateExpired)
            ->setConditions($conditions);

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        $this->get('session')->getFlashBag()->add('Success', 'Oferta <strong>creada con éxito!</strong>');
        return new RedirectResponse($this->generateUrl('_admin_product'));

    }

    /**
     * @Route("/product/delete/{id}", name="_admin_product_delete")
     */
    public function deleteProductAction($id)
    {
        $product = $this->get('app.query')->findProductById($id);

        if (!$product) {
            $this->get('session')->getFlashBag()->add('Error', 'El producto que ha intentado eliminar <strong>no existe!</strong>');
            return new RedirectResponse($this->generateUrl('_admin_product'));
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($product);
        $em->flush();

        $this->get('session')->getFlashBag()->add('Success', 'Producto <strong>eliminado con éxito!</strong>');
        return new RedirectResponse($this->generateUrl('_admin_product'));

    }

    /**
     * @Route("/product/comment/{id}", name="_admin_product_comment")
     */
    public function commentProductAction($id)
    {
        $product = $this->getDoctrine()->getRepository('AppBundle:Product')->findOneBy(['id' => $id]);
        if (!$product) {
            $this->get('session')->getFlashBag()->add('Error', 'Error: El comentario <strong>no existe!</strong>');
            return new RedirectResponse($this->generateUrl('_admin_product'));
        }

        return $this->render('AppBundle:backend/product:commentProduct.html.twig', [
            'appConfig' => $this->get('app.query')->getConfig(),
            'menu' => 'product',
            'nNotice' => $this->get('app.query')->countUnreadNotice(),
            'newNotices' => $this->get('app.query')->findAllNoticesUnRead(),
            'product' => $product
        ]);
    }

    /**
     * @Route("/product/comment/delete/{id}", name="_admin_product_comment_delete")
     */
    public function deleteCommentProductAction($id)
    {
        $comment = $this->getDoctrine()->getRepository('AppBundle:Comment')->findOneBy(['id' => $id]);

        if (!$comment) {
            $this->get('session')->getFlashBag()->add('Error', 'El comentario que ha intentado eliminar <strong>no existe!</strong>');
            return new RedirectResponse($this->generateUrl('_admin_product'));
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($comment);
        $em->flush();

        $this->get('session')->getFlashBag()->add('Success', 'Comentario <strong>eliminado con éxito!</strong>');
        return new RedirectResponse($this->generateUrl('_admin_product_comment', ['id' => $comment->getProduct()->getId()]));

    }
}
