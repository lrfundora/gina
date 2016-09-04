<?php

namespace AppBundle\Controller\Client;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CategoryController extends Controller
{
    /**
     * @Route("/{business}/categories", name="_business_categories")
     */
    public function categoryAction($business)
    {
        // Loading data business
        $Business = $this->get('app.query')->findInfoBasicBusinessBySlug($business);
        if(!$Business)
        {
            throw new NotFoundHttpException();
        }

        // Create breadcrumbs
        $breadcrumbs = $this->get("white_october_breadcrumbs");

        // Loading Categories
        $businessSlug = $this->get('request')->query->get('find');

        if ($businessSlug == null || $businessSlug == 'all') {
            $categories = $this->get('app.query')->findCategoriesAndProductsByBusinessSlug($business);
            $breadcrumbs->addItem("Inicio", $this->get("router")->generate("_home"))
                ->addItem($Business->getName(), $this->get('router')->generate("_business", ["find" => $Business->getSlug()]))
                ->addItem("Categorías");
        } else {
            $categories = $this->get('app.query')->findCategoryAndProductsByBusinessSlug($business, $businessSlug);
            $breadcrumbs->addItem("Inicio", $this->get("router")->generate("_home"))
                ->addItem($Business->getName(), $this->get('router')->generate("_business", ["find" => $Business->getSlug()]))
                ->addItem("Categorías", $this->get('router')->generate("_business_categories",["business" => $Business->getSlug(), "find"=>"all"]))
                ->addItem($categories[0]->getName());
        }

        // Render views
        return $this->render('AppBundle:publicity:categories.html.twig', [
            'data' => $this->get('app.query')->getAppInfo(),
            'numOffer' => $this->get('app.query')->countOffers(),
            'topOffers' => $this->get('app.query')->findTopOffers(),
            'topComments' => $this->get('app.query')->findTopComments(),
            'business' => $Business,
            'categories' => $categories
        ]);
    }

} 