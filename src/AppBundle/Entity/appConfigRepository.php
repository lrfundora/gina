<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class appConfigRepository extends EntityRepository
{
    public function getAppInfo()
    {
        return $this->getEntityManager()
            ->createQuery('SELECT a, alt, ald, ap, ainfo, acar, aoffer, b, bt, bp, bpa, c, p FROM AppBundle:appConfig a
                          JOIN a.logoTop alt JOIN a.logoDown ald JOIN a.imgParallax ap JOIN a.infoImages ainfo
                          JOIN a.carouselImages acar JOIN a.imgOffer aoffer JOIN a.business b JOIN b.imgThumb bt JOIN b.imgPublicity bp JOIN b.imgPublicityAnimation bpa
                          JOIN b.categories c JOIN c.products p'
            )
            ->getResult();
    }

    public function findSystem()
    {
        return $this->getEntityManager()
            ->createQuery('SELECT a, alt, ald, ap, ainfo, acar, aoffer FROM AppBundle:appConfig a
                          JOIN a.logoTop alt JOIN a.logoDown ald JOIN a.imgParallax ap JOIN a.infoImages ainfo
                          JOIN a.carouselImages acar JOIN a.imgOffer aoffer'
            )
            ->getResult();
    }

    public function getConfig($code)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT c FROM AppBundle:appConfig c WHERE c.system=:code')
            ->setParameter('code',$code)
            ->getOneOrNullResult();
    }

} 