<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class ProductRepository extends EntityRepository
{
    // Products
    public function findAll()
    {
        return $this->getEntityManager()
            ->createQuery('SELECT p FROM AppBundle:Product p')
            ->getResult();
    }

    public function findProductById($id)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT p FROM AppBundle:Product p WHERE p.id=:id')
            ->setParameter('id', $id)
            ->getOneOrNullResult();
    }

    public function countByBusinessSlug($slug)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT COUNT(p) FROM AppBundle:Product p JOIN p.category c JOIN c.business b WHERE b.slug=:slug')
            ->setParameter('slug', $slug)
            ->getSingleScalarResult();
    }

    public function findAllProductsByBusinessSlug($slug, $field, $order)
    {
        $dql = 'SELECT p, photo, c, b FROM AppBundle:Product p JOIN p.photos photo JOIN p.category c JOIN c.business b WHERE b.slug=:slug ORDER BY p.' . $field . ' ' . $order;

        return $this->getEntityManager()
            ->createQuery($dql)
            ->setParameter('slug', $slug)
            ->getResult();
    }

    public function searchProduct($string)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT p, pimg, c, b, bthumb FROM AppBundle:Product p JOIN p.photos pimg JOIN p.category c JOIN c.business b JOIN b.imgThumb bthumb WITH p.slug LIKE :string')
            ->setParameter('string', '%'.$string.'%')
            ->getResult();
    }

    // Offers
    public function countAllOffers()
    {
        return $this->getEntityManager()
            ->createQuery('SELECT COUNT(p) FROM AppBundle:Product p WHERE p.isOffer=1')
            ->getSingleScalarResult();
    }

    public function findTopOffers($max)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT b, c, p, photo FROM AppBundle:Product p JOIN p.photos photo JOIN p.category c JOIN c.business b WHERE p.isOffer=1 ORDER BY p.discount DESC')
            ->setMaxResults($max)
            ->getResult();
    }

    public function findAllOffers($field, $order)
    {
        $dql = 'SELECT p, photo, c, b FROM AppBundle:Product p JOIN p.photos photo JOIN p.category c JOIN c.business b WHERE p.isOffer=1 ORDER BY p.' . $field . ' ' . $order;

        return $this->getEntityManager()
            ->createQuery($dql)
            ->getResult();
    }
    public function findAllOffersByBusinessSlug($slug, $field, $order)
    {
        $dql = 'SELECT p, photo, c, b FROM AppBundle:Product p JOIN p.photos photo JOIN p.category c JOIN c.business b WHERE p.isOffer=1 AND b.slug=:slug ORDER BY p.' . $field . ' ' . $order;

        return $this->getEntityManager()
            ->createQuery($dql)
            ->setParameter('slug', $slug)
            ->getResult();
    }

    public function findDetailsOffersByProductId($id)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT b, c, p, photo FROM AppBundle:Product p JOIN p.photos photo JOIN p.category c JOIN c.business b WHERE p.id=:id')
            ->setParameter('id', $id)
            ->getOneOrNullResult();
    }

    public function findRelatedProductByCategoryId($id, $field, $order)
    {
        $dql = 'SELECT p, photo, c, b FROM AppBundle:Product p JOIN p.photos photo JOIN p.category c JOIN c.business b WHERE c.id=:id ORDER BY p.' . $field . ' ' . $order;
        return $this->getEntityManager()
            ->createQuery($dql)
            ->setParameter('id', $id)
            ->getResult();
    }

    public function findTopComments($max)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT p, SIZE(p.comments) as comments FROM AppBundle:Product p JOIN p.comments c GROUP BY p.id ORDER BY comments DESC')
            ->setMaxResults($max)
            ->getResult();
    }
} 