<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class BusinessRepository extends EntityRepository
{

    public function findAllBusiness(){
        return $this->getEntityManager()
            ->createQuery('SELECT b, img, sch FROM AppBundle:Business b JOIN b.imgThumb img JOIN b.schedules sch ORDER BY b.name ASC')
            ->getResult();
    }

    public function findBusinessById($id)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT b FROM AppBundle:Business b WHERE b.id=:id')
            ->setParameter('id', $id)
            ->getOneOrNullResult();
    }

    public function getAllBusinessName(){
        return $this->getEntityManager()
            ->createQuery('SELECT b FROM AppBundle:Business b')
            ->getResult();
    }

    public function findInfoBasicBySlug($slug)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT b, bthumb FROM AppBundle:Business b JOIN b.imgThumb bthumb WHERE b.slug=:slug')
            ->setParameter('slug', $slug)
            ->getOneOrNullResult();
    }

    public function findById($id)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT b, sch, imgT, imgP FROM AppBundle:Business b JOIN b.schedules sch JOIN b.imgThumb imgT JOIN b.imgPublicity imgP WHERE b.id=:id')
            ->setParameter('id', $id)
            ->getResult();
    }

    public function findBySlug($slug)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT b, bthumb, bsch FROM AppBundle:Business b JOIN b.imgThumb bthumb JOIN b.schedules bsch WHERE b.slug=:slug')
            ->setParameter('slug', $slug)
            ->getResult();
    }

    public function findAllCategories($id)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT b, c FROM AppBundle:Business b JOIN b.categories c WHERE b.id=:id')
            ->setParameter('id', $id)
            ->getResult();
    }

    public function searchBusiness($string)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT b, bthumb FROM AppBundle:Business b JOIN b.imgThumb bthumb WITH b.slug LIKE :string')
            ->setParameter('string', '%'.$string.'%')
            ->getResult();
    }
} 