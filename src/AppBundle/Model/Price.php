<?php
/**
 * Created by PhpStorm.
 * User: ivanis
 * Date: 23/08/15
 * Time: 20:05
 */

namespace AppBundle\Model;


class Price
{
    protected $amount;

    public function __construct ($amount) {
        $this->amount = $amount;
    }

    public function getAmount() {
        return $this->amount;
    }

    public function getFormatted() {
        return number_format($this->amount / 100, 2, ',', '.') . ' €';
    }
}