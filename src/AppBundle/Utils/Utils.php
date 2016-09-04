<?php

namespace AppBundle\Utils;

use AppBundle\Entity\Notice;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;

class Utils
{
    public function checkOffers(ObjectManager $manager)
    {
        if (!$manager->getRepository('AppBundle:Notice')->isCheckOffers()) {

            $offers = $manager->getRepository('AppBundle:Product')->findBy(['isOffer' => 1]);
            $noticeType = $manager->getRepository('AppBundle:NoticeType')->findOneBy(['type' => 'offer']);

            $today = new \DateTime('now');
            $day1 = $today->modify('+1 day')->format('d-m-Y');
            $day2 = $today->modify('+1 day')->format('d-m-Y');
            $day3 = $today->modify('+1 day')->format('d-m-Y');
            $today = date_format(new \DateTime('now'), 'd-m-Y');

            $msgOffersEndsToday = '';
            $msgOffersEndsOneDays = '';
            $msgOffersEndsTwoDays = '';
            $msgOffersEndsThreeDays = '';
            $msgOfferByEnds = '';

            foreach ($offers as $offer) {

                if ($offer->getIsOffer()) {

                    $expired = date_format($offer->getExpired(), 'd-m-Y');

                    // Offer Ends Today
                    if ($today == $expired) {
                        $msgOffersEndsToday = $msgOffersEndsToday . $offer->getName() . '<br/>';
                    }

                    // Offer Ends in 1 Day
                    if ($day1 == $expired) {
                        $msgOffersEndsOneDays = $msgOffersEndsOneDays . $offer->getName() . '<br/>';
                    }

                    // Offer Ends in 2 Days
                    if ($day2 == $expired) {
                        $msgOffersEndsTwoDays = $msgOffersEndsTwoDays . $offer->getName() . '<br/>';
                    }

                    // Offer Ends in 3 Days
                    if ($day3 == $expired) {
                        $msgOffersEndsThreeDays = $msgOffersEndsThreeDays . $offer->getName() . '<br/>';
                    }

                    // Delete Offer
                    if ($today > $expired) {
//                    $offer
//                        ->setIsOffer(0)
//                        ->setPublicated(null)
//                        ->setExpired(null)
//                        ->setConditions(null);
//                    $manager->flush();
                        $msgOfferByEnds = $msgOfferByEnds . $offer->getName() . '<br/>';
                    }
                }
            }

            $msg = '<div>
                    <h4 class="text-uppercase">Ofertas terminadas automáticamente</h4>
                    ' . $msgOfferByEnds . '
                    <h4 class="text-uppercase">Ofertas por terminar</h4>
                    <strong>Hoy</strong><br/>
                    ' . $msgOffersEndsToday . '
                    <strong>Mañana</strong><br/>
                    ' . $msgOffersEndsOneDays . '
                    <strong>En 2 días</strong><br/>
                    ' . $msgOffersEndsTwoDays . '
                    <strong>En 3 días</strong><br/>
                    ' . $msgOffersEndsThreeDays . '
                </div>';

            $notice = new Notice();
            $notice
                ->setType($noticeType)
                ->setSubject('Ofertas que teriman')
                ->setMessage($msg);

            $manager->persist($notice);
            $manager->flush();
        }
    }

    public function getEncriptMD5()
    {
        return md5(uniqid(rand(), true));
    }

    public function setUserPassword($entity)
    {
        $entity->setSalt($this->getEncriptMD5());
        $encode = new BCryptPasswordEncoder(15);
        $password = $encode->encodePassword($entity->getPassword(), $entity->getSalt());
        $entity->setPassword($password);
    }

    static public function getSlug($cadena, $separador = '-')
    {
        // Código copiado de http://cubiq.org/the-perfect-php-clean-url-generator
        $slug = iconv('UTF-8', 'ASCII//TRANSLIT', $cadena);
        $slug = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $slug);
        $slug = strtolower(trim($slug, $separador));
        $slug = preg_replace("/[\/_|+ -]+/", $separador, $slug);
        return $slug;
    }

    public function getOrderUri($request, $arrOptions)
    {
        $parameters = $request->query->all();
        for ($i = 0; $i < sizeof($arrOptions); $i++) {
            $parameters['field'] = $arrOptions[$i]['field'];
            $parameters['order'] = $arrOptions[$i]['order'];
            $tmp = '';
            foreach ($parameters as $key => $value) {
                if ($tmp == '') {
                    $tmp = $key . '=' . $value;
                } else {
                    $tmp = $tmp . '&' . $key . '=' . $value;
                }
            }
            $parameter = $request->normalizeQueryString($tmp);
            $uri = $request->getUri();
            $url = preg_split('[\?]', $uri);
            $orderUri[$i][0] = $arrOptions[$i]['name'];
            $orderUri[$i][1] = $url[0] . '?' . $parameter;
        }
        return $orderUri;
    }
} 