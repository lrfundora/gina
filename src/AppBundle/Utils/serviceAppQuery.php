<?php

namespace AppBundle\Utils;


class serviceAppQuery
{

    private $em;

    public function __construct($manager)
    {
        $this->em = $manager;
    }

    // User Queries
    public function findAllUser()
    {
        return $this->em->getRepository('AppBundle:User')->findAllUsers();
    }

    public function findUserById($id)
    {
        return $this->em->getRepository('AppBundle:User')->findUserById($id);
    }

    public function findUser($username)
    {
        return $this->em->getRepository('AppBundle:User')->findUser($username);
    }

    // App Queries
    public function findSystem()
    {
        return $this->em->getRepository('AppBundle:appConfig')->findSystem();
    }

    public function getConfig($code = 'system')
    {
        return $this->em->getRepository('AppBundle:appConfig')->getConfig($code);
    }

    public function getAppInfo()
    {
        return $this->em->getRepository('AppBundle:appConfig')->getAppInfo();
    }

    // Business Queries
    public function findAllBusiness()
    {
        return $this->em->getRepository('AppBundle:Business')->findAllBusiness();
    }

    public function findBusinessId($id)
    {
        return $this->em->getRepository('AppBundle:Business')->findBusinessById($id);
    }

    public function getAllBusinessName()
    {
        return $this->em->getRepository('AppBundle:Business')->getAllBusinessName();
    }

    public function findInfoBasicBusinessBySlug($slug)
    {
        return $this->em->getRepository('AppBundle:Business')->findInfoBasicBySlug($slug);
    }

    public function findBusinessById($id)
    {
        return $this->em->getRepository('AppBundle:Business')->findById($id);
    }

    public function findBusinessBySlug($slug)
    {
        return $this->em->getRepository('AppBundle:Business')->findBySlug($slug);
    }

    public function searchBusiness($string)
    {
        return $this->em->getRepository('AppBundle:Business')->searchBusiness($string);
    }

    // Category Queries
    public function existCategory($idBusiness = null, $name)
    {
        return $this->em->getRepository('AppBundle:Category')->existCategoryByName($idBusiness, $name);
    }

    public function findCategoryById($id)
    {
        return $this->em->getRepository('AppBundle:Category')->findCategoryById($id);
    }

    public function findBusinessByCategoryId($id)
    {
        return $this->em->getRepository('AppBundle:Category')->findBusinessByCategoryId($id);
    }

    public function findAllCategory()
    {
        return $this->em->getRepository('AppBundle:Category')->findAllCategories();
    }

    public function findCategoriesAndProductsByBusinessSlug($business)
    {
        return $this->em->getRepository('AppBundle:Category')->findCategoriesAndProductsByBusinessSlug($business);
    }

    public function findCategoryAndProductsByBusinessSlug($business, $category)
    {
        return $this->em->getRepository('AppBundle:Category')->findCategoryAndProductsByBusinessSlug($business, $category);
    }

    public function countCategoriesByBusinessSlug($slug)
    {
        return $this->em->getRepository('AppBundle:Category')->countByBusinessSlug($slug);
    }

    public function searchCategory($string)
    {
        return $this->em->getRepository('AppBundle:Category')->searchCategory($string);
    }

    // Product Queries
    public function findAllProduct()
    {
        return $this->em->getRepository('AppBundle:Product')->findAll();
    }

    public function findProductById($id)
    {
        return $this->em->getRepository('AppBundle:Product')->findProductById($id);
    }

    public function countProductsByBusinessSlug($slug)
    {
        return $this->em->getRepository('AppBundle:Product')->countByBusinessSlug($slug);
    }

    public function findAllProductsByBusinessSlug($slug, $field = 'name', $order = 'ASC')
    {
        return $this->em->getRepository('AppBundle:Product')->findAllProductsByBusinessSlug($slug, $field, $order);
    }

    public function searchProduct($string)
    {
        return $this->em->getRepository('AppBundle:Product')->searchProduct($string);
    }

    // Product Comment Queries
    public function countComments($id)
    {
        return $this->em->getRepository('AppBundle:Comment')->countCommentsByProductId($id);
    }

    public function findComments($id)
    {
        return $this->em->getRepository('AppBundle:Comment')->findCommentsByProductId($id);
    }

    // Offer Queries
    public function countOffers()
    {
        return $this->em->getRepository('AppBundle:Product')->countAllOffers();
    }

    public function findTopOffers($max = 3)
    {
        return $this->em->getRepository('AppBundle:Product')->findTopOffers($max);
    }

    public function findAllOffers($field = 'discount', $order = 'DESC')
    {
        return $this->em->getRepository('AppBundle:Product')->findAllOffers($field, $order);
    }

    public function findAllOffersByBusinessSlug($slug, $field = 'discount', $order = 'DESC')
    {
        return $this->em->getRepository('AppBundle:Product')->findAllOffersByBusinessSlug($slug, $field, $order);
    }

    public function findDetailsOfferByProductId($id)
    {
        return $this->em->getRepository('AppBundle:Product')->findDetailsOffersByProductId($id);
    }

    public function findRelatedProductByCategoryId($id, $field = 'discount', $order = 'DESC')
    {
        return $this->em->getRepository('AppBundle:Product')->findRelatedProductByCategoryId($id, $field, $order);
    }

    // Comments
    public function findTopComments($max = 4)
    {
        return $this->em->getRepository('AppBundle:Product')->findTopComments($max);
    }

    // Notice Queries
    public function findNoticeByType($name)
    {
        return $this->em->getRepository('AppBundle:NoticeType')->findOneBy(['type' => $name]);
    }

    public function findAllNotices()
    {
        return $this->em->getRepository('AppBundle:Notice')->findAllNotice();
    }

    public function findAllNoticesUnRead($max = 0)
    {
        return $this->em->getRepository('AppBundle:Notice')->findAllNoticesUnRead($max);
    }

    public function countUnreadNotice()
    {
        return $this->em->getRepository('AppBundle:Notice')->countUnreadNotice();
    }

    public function findNoticeById($id)
    {
        return $this->em->getRepository('AppBundle:Notice')->findNoticeById($id);
    }

    public function findMailById($id)
    {
        return $this->em->getRepository('AppBundle:Mail')->findMailById($id);
    }

}