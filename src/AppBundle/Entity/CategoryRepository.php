<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class CategoryRepository extends EntityRepository
{
    public function findAllCategories()
    {
        return $this->getEntityManager()
            ->createQuery('SELECT c, b FROM AppBundle:Category c JOIN c.business b')
            ->getResult();
    }

    public function findCategoryById($id)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT c FROM AppBundle:Category c WHERE c.id=:id')
            ->setParameter('id', $id)
            ->getOneOrNullResult();
    }

    public function findBusinessByCategoryId($id)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT c, b FROM AppBundle:Category c JOIN c.business b WHERE c.id=:id')
            ->setParameter('id', $id)
            ->getOneOrNullResult();
    }

    public function countByBusinessSlug($slug)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT COUNT(c) FROM AppBundle:Category c JOIN c.business b WHERE b.slug=:slug')
            ->setParameter('slug', $slug)
            ->getSingleScalarResult();
    }

    public function countProductsByBusinessSlug($slug)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT COUNT(p) FROM AppBundle:Category c JOIN c.products p JOIN c.business b WHERE b.slug=:slug')
            ->setParameter('slug', $slug)
            ->getSingleScalarResult();
    }

    public function findCategoriesAndProductsByBusinessSlug($business)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT c, p FROM AppBundle:Category c JOIN c.products p JOIN c.business b WHERE b.slug=:slug')
            ->setParameter('slug', $business)
            ->getResult();
    }

    public function findCategoryAndProductsByBusinessSlug($business, $category)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT c, p FROM AppBundle:Category c JOIN c.products p JOIN c.business b WHERE b.slug=:slug AND c.slug=:category')
            ->setParameters(['slug' => $business, 'category' => $category])
            ->getResult();
    }

    public function searchCategory($string)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT c, b FROM AppBundle:Category c JOIN c.business b WITH c.slug LIKE :string')
            ->setParameter('string', '%' . $string . '%')
            ->getResult();
    }

    public function existCategoryByName($idBusiness, $name)
    {
        if ($idBusiness == null) {
            return $this->getEntityManager()
                ->createQuery('SELECT c FROM AppBundle:Category c WHERE c.name=:name')
                ->setParameter('name', $name)
                ->getOneOrNullResult();
        }

        return $this->getEntityManager()
            ->createQuery('SELECT c FROM AppBundle:Category c JOIN c.business b WHERE b.id=:id AND c.name=:name')
            ->setParameters(['id' => $idBusiness, 'name' => $name])
            ->getOneOrNullResult();
    }

    public function findCategoriesByBusinessId($id)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT c FROM AppBundle:Category c JOIN c.business b WHERE b.id=:id')
            ->setParameter('id', $id)
            ->getResult();
    }
} 