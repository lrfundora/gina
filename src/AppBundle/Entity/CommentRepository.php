<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class CommentRepository extends EntityRepository
{
    // Comment
    public function findCommentsByProductId($id)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT c FROM AppBundle:Comment c WHERE c.product=:id')
            ->setParameter('id', $id)
            ->getResult();
    }

    public function countCommentsByProductId($id)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT COUNT(c) FROM AppBundle:Comment c WHERE c.product=:id')
            ->setParameter('id', $id)
            ->getSingleScalarResult();
    }

//    public function findTopComments($max)
//    {
//        return $this->getEntityManager()
//            ->createQuery('SELECT c FROM AppBundle:Comment c JOIN c.product p ORDER BY p.name DESC')
//            ->setMaxResults($max)
//            ->getResult();
//    }
} 