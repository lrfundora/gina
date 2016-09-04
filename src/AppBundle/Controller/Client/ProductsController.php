<?php

namespace AppBundle\Controller\Client;

use AppBundle\Entity\Comment;
use AppBundle\Entity\Notice;
use AppBundle\Form\Type\CommentType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ProductsController extends Controller
{
    /**
     * @Route("/{business}/products", name="_business_products")
     */
    public function productsAction(Request $request, $business)
    {
        // Check Offers
        $this->get('app.utils')->checkOffers($this->getDoctrine()->getManager());

        // Loading data business
        $arrBusiness = $this->get('app.query')->findInfoBasicBusinessBySlug($business);
        if (!$arrBusiness) {
            // Render views
            return $this->render('AppBundle:errors:error404.html.twig', [
                'data' => $this->get('app.query')->getAppInfo(),
                'numOffer' => $this->get('app.query')->countOffers(),
                'topOffers' => $this->get('app.query')->findTopOffers(),
                'topComments' => $this->get('app.query')->findTopComments()
            ]);
            //throw new NotFoundHttpException();
        }

        // Order By
        $field = strtolower($this->get('request')->query->get('field'));
        $order = strtoupper($this->get('request')->query->get('order'));
        if ($field != null && $order != null) {
            $arrProducts = $this->get('app.query')->findAllProductsByBusinessSlug($business, $field, $order);
        } else {
            $arrProducts = $this->get('app.query')->findAllProductsByBusinessSlug($business);
        }

        // Array parameters order
        $arrOrder = [
            ['name' => 'Nombre', 'field' => 'name', 'order' => 'asc'],
            ['name' => 'Mayor descuento', 'field' => 'discount', 'order' => 'desc'],
            ['name' => 'Menor descuento', 'field' => 'discount', 'order' => 'asc']
        ];

        // Create Paginator
        if (!$this->get('request')->query->get('page')) {
            $page = 1;
        } else {
            $page = $this->get('request')->query->get('page');
        }
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $arrProducts,
            $page, /*page number*/
            12/*limit per page*/
        );

        // Create breadcrumbs
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem("Inicio", $this->get("router")->generate("_home"))
            ->addItem($arrBusiness->getName(), $this->get('router')->generate("_business", ["find" => $arrBusiness->getSlug()]))
            ->addItem("Productos");


        // Render views
        return $this->render('AppBundle:publicity:products.html.twig', [
            'data' => $this->get('app.query')->getAppInfo(),
            'numOffer' => $this->get('app.query')->countOffers(),
            'topOffers' => $this->get('app.query')->findTopOffers(),
            'topComments' => $this->get('app.query')->findTopComments(),
            'arrProducts' => $pagination,
            'orderData' => $this->get('app.utils')->getOrderUri($this->get('request'), $arrOrder)
        ]);
    }

    /**
     * @Route("/{business}/products/details", name="_business_products_details")
     */
    public function detailsProductAction()
    {
        // Create breadcrumbs
        $breadcrumbs = $this->get("white_october_breadcrumbs");

        // Get Request
        $prodId = $this->get('request')->query->get('id');
        $prodDetail = $this->get('app.query')->findDetailsOfferByProductId($prodId);
        if (!$prodDetail) {

            $breadcrumbs->addItem("Inicio", $this->get("router")->generate("_home"))
                ->addItem("Página no encontrada");
            // Render view
            return $this->render('AppBundle:errors:error404.html.twig', [
                'data' => $this->get('app.query')->getAppInfo(),
                'numOffer' => $this->get('app.query')->countOffers(),
                'topOffers' => $this->get('app.query')->findTopOffers(),
                'topComments' => $this->get('app.query')->findTopComments(),
            ]);
            //throw new NotFoundHttpException();
        }

        // Comment
        $nComments = $this->get('app.query')->countComments($prodId);

        // Products Related
        $prodRelated = $this->get('app.query')->findRelatedProductByCategoryId($prodDetail->getCategory()->getId());

        $breadcrumbs->addItem("Inicio", $this->get("router")->generate("_home"))
            ->addItem($prodDetail->getCategory()->getBusiness()->getName(), $this->get("router")->generate("_business", ["find" => $prodDetail->getCategory()->getBusiness()->getSlug()]))
            ->addItem($prodDetail->getCategory()->getName(), $this->get("router")->generate("_business_categories", ["business" => $prodDetail->getCategory()->getBusiness()->getSlug(), "find" => $prodDetail->getCategory()->getSlug()]))
            ->addItem("Producto", $this->get('router')->generate("_business_products", ["business" => $prodDetail->getCategory()->getBusiness()->getSlug()]))
            ->addItem($prodDetail->getName());


        // Render view
        return $this->render('AppBundle:publicity:product_details.html.twig', [
            'data' => $this->get('app.query')->getAppInfo(),
            'numOffer' => $this->get('app.query')->countOffers(),
            'topOffers' => $this->get('app.query')->findTopOffers(),
            'topComments' => $this->get('app.query')->findTopComments(),
            'prodDetail' => $prodDetail,
            'nComments' => $nComments,
            'prodRelated' => $prodRelated
        ]);
    }

    /**
     * @Route("/{business}/products/comments", name="_business_products_comment")
     */
    public function commentProductAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        // Create breadcrumbs
        $breadcrumbs = $this->get("white_october_breadcrumbs");

        // Get var
        $prodId = $this->get('request')->query->get('id');
        $prodDetail = $this->get('app.query')->findDetailsOfferByProductId($prodId);
        if (!$prodDetail) {

            $breadcrumbs->addItem("Inicio", $this->get("router")->generate("_home"))
                ->addItem("Página no encontrada");

            // Render view
            return $this->render('AppBundle:errors:error404.html.twig', [
                'data' => $this->get('app.query')->getAppInfo(),
                'numOffer' => $this->get('app.query')->countOffers(),
                'topOffers' => $this->get('app.query')->findTopOffers(),
                'topComments' => $this->get('app.query')->findTopComments(),
            ]);
        }

        // Comment
        $comment = new Comment();
        $form = $this->createForm(new CommentType(), $comment);
        $form->handleRequest($request);
        if ($form->isValid()) {

            // Save comment
            $comment->setProduct($prodDetail);
            $em->persist($comment);

            // Add notice type
            $noticeType = $this->get('app.query')->findNoticeByType('comment');
            $notice = new Notice();
            if($comment->getEmail()){
                $msg = $comment->getName() . ' &lt;' . $comment->getEmail() . '&gt; a la hora ' . $comment->getDate()->format('g:ia \d\e\l d/m/Y')
                    . ' dijo: <br /><strong>' . $comment->getText().'</strong>';
            }else{
                $msg = $comment->getName() . ' a la hora ' . $comment->getDate()->format('g:ia \d\e\l d/m/Y')
                    . ' dijo: <br /><strong>' . $comment->getText().'</strong>';
            }

            $notice
                ->setSubject('Comentario de ' . $comment->getName())
                ->setMessage($msg)
                ->setType($noticeType);
            $em->persist($notice);

            // Add notice to all Users
            $users = $this->get('app.query')->findAllUser();
            foreach ($users as $user) {
                $user->addNotice($notice);
                $em->persist($user);
            }

            $em->flush();
            return new RedirectResponse($this->generateUrl('_business_products_comment', ['business' => $prodDetail->getCategory()->getBusiness()->getSlug(), 'id' => $prodId]));
        }

        $nComments = $this->get('app.query')->countComments($prodId);
        $comments = $this->get('app.query')->findComments($prodId);

        $breadcrumbs->addItem("Inicio", $this->get("router")->generate("_home"))
            ->addItem($prodDetail->getCategory()->getBusiness()->getName(), $this->get("router")->generate("_business", ["find" => $prodDetail->getCategory()->getBusiness()->getSlug()]))
            ->addItem($prodDetail->getCategory()->getName(), $this->get("router")->generate("_business_categories", ["business" => $prodDetail->getCategory()->getBusiness()->getSlug(), "find" => $prodDetail->getCategory()->getSlug()]))
            ->addItem("Producto", $this->get('router')->generate("_business_products", ["business" => $prodDetail->getCategory()->getBusiness()->getSlug()]))
            ->addItem($prodDetail->getName(), $this->get('router')->generate("_business_products_details", ["business" => $prodDetail->getCategory()->getBusiness()->getSlug(), "id" => $prodId]))
            ->addItem("Comentarios");

        // Render view
        return $this->render('AppBundle:publicity:product_comments.html.twig', [
            'data' => $this->get('app.query')->getAppInfo(),
            'numOffer' => $this->get('app.query')->countOffers(),
            'topOffers' => $this->get('app.query')->findTopOffers(),
            'topComments' => $this->get('app.query')->findTopComments(),
            'prodDetail' => $prodDetail,
            'nComments' => $nComments,
            'comments' => $comments,
            'form' => $form->createView()
        ]);
    }

} 