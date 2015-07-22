<?php

namespace AppBundle\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

use AppBundle\Entity\Player;

class Registration
{
    /**
     * @var Player
     *
     * @Assert\Type(type="AppBundle\Entity\Player")
     * @Assert\Valid()
     */
    protected $player;

    /**
     * @var boolean
     *
     * @Assert\IsTrue()
     */
    protected $rules;
    /**
     * @var boolean
     *
     * @Assert\IsTrue()
     */
    protected $cgu;

    /**
     * @return Player
     */
    public function getPlayer()
    {
        return $this->player;
    }

    /**
     * @param Player $player
     */
    public function setPlayer(Player $player)
    {
        $this->player = $player;
    }

    /**
     * @return mixed
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * @param mixed $rules
     */
    public function setRules($rules)
    {
        $this->rules = $rules;
    }

    /**
     * @return boolean
     */
    public function isCgu()
    {
        return $this->cgu;
    }

    /**
     * @param boolean $cgu
     */
    public function setCgu($cgu)
    {
        $this->cgu = $cgu;
    }

}