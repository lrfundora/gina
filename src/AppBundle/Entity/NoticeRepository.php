<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class NoticeRepository extends EntityRepository
{

    public function findAllNotice()
    {
        return $this->getEntityManager()
            ->createQuery('SELECT n FROM AppBundle:Notice n')
            ->getResult();
    }

    public function findAllNoticesUnRead($max)
    {
        if ($max == 0) {
            $query = $this->getEntityManager()
                ->createQuery('SELECT n FROM AppBundle:Notice n WHERE n.isRead=0 ORDER BY n.date DESC')
                ->getResult();
        } else {
            $query = $this->getEntityManager()
                ->createQuery('SELECT n FROM AppBundle:Notice n WHERE n.isRead=0 ORDER BY n.date DESC')
                ->setMaxResults($max)
                ->getResult();
        }
        return $query;
    }

    public function countUnreadNotice()
    {
        return $this->getEntityManager()
            ->createQuery('SELECT COUNT(n) FROM AppBundle:Notice n WHERE n.isRead=0')
            ->getSingleScalarResult();
    }

    public function findNoticeById($id)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT n FROM AppBundle:Notice n WHERE n.id=:id')
            ->setParameter('id', $id)
            ->getOneOrNullResult();
    }

    public function isCheckOffers()
    {
        $today = new \DateTime('now');
        return $this->getEntityManager()
            ->createQuery('SELECT n FROM AppBundle:Notice n JOIN n.type t WHERE DATE_DIFF(n.date,:today)=0')
            ->setParameter('today', $today)
            ->getResult();
    }
} 