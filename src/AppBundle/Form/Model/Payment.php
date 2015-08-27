<?php
/**
 * Created by PhpStorm.
 * User: ivanis
 * Date: 26/07/15
 * Time: 02:46
 */

namespace AppBundle\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

class Payment
{
    /**
     * @var boolean
     *
     * @Assert\Expression(
     *     "(this.hasTshirt() and this.getTshirtSize()) or !this.hasTshirt()",
     *      message="Veuillez choisir une taille"
     * )
     */
    protected $tshirt;


    /**
     * @var int
     */
    protected $tshirtSize;
    /**
     * @return boolean
     */
    public function hasTshirt()
    {
        return $this->tshirt;
    }

    /**
     * @param boolean $tshirt
     */
    public function setTshirt($tshirt)
    {
        $this->tshirt = $tshirt;
    }

    /**
     * @return int
     */
    public function getTshirtSize()
    {
        return $this->tshirtSize;
    }

    /**
     * @param int $tshirtSize
     */
    public function setTshirtSize($tshirtSize)
    {
        $this->tshirtSize = $tshirtSize;
    }

}