<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class MailRepository extends EntityRepository
{

    public function findMailById($id)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT m FROM AppBundle:Mail m WHERE m.id=:id')
            ->setParameter('id',$id)
            ->getResult();
    }
} 