<?php
/**
 * Created by PhpStorm.
 * User: ivanis
 * Date: 23/08/15
 * Time: 17:40
 */

namespace AppBundle\Service;


use AppBundle\Entity\Player;
use AppBundle\Model\Price as Model;

class Price
{
    protected $basePrice;

    protected $reducedPrice;

    protected $reducedEmailDomains;

    /**
     * PriceManager constructor.
     *
     * @param $basePrice
     * @param $reducedPrice
     * @param $reducedEmailDomains
     */
    public function __construct($basePrice, $reducedPrice, array $reducedEmailDomains)
    {
        $this->basePrice = new Model($basePrice);
        $this->reducedPrice =  new Model($reducedPrice);
        $this->reducedEmailDomains =  $reducedEmailDomains;
    }


    /**
     * @param Player $player
     * @return Price
     */
    public function getPrice(Player $player){
        if ($this->hasReducedPrice($player)){
            return $this->reducedPrice;
        }
        return $this->basePrice;
    }

    /**
     * @param Player $player
     *
     * @return boolean
     */
    public function hasReducedPrice(Player $player){
        return in_array(ltrim(strpbrk($player->getEmail(), '@'), '@'), $this->reducedEmailDomains);
    }
}