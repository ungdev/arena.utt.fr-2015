<?php
/**
 * Created by PhpStorm.
 * User: ivanis
 * Date: 23/08/15
 * Time: 17:40
 */

namespace AppBundle\Service;


use AppBundle\Entity\Player;
use AppBundle\Model\Price;

class Pricer
{
    protected $basePrice;

    protected $reducedPrice;

    protected $reducedEmailDomains;

    /**
     * Pricer constructor.
     *
     * @param $basePrice
     * @param $reducedPrice
     * @param $reducedEmailDomains
     */
    public function __construct($basePrice, $reducedPrice, array $reducedEmailDomains)
    {
        $this->basePrice = new Price($basePrice);
        $this->reducedPrice =  new Price($reducedPrice);
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