<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * SpotlightGame
 *
 * @ORM\Entity
 * @ORM\Table(name="spotlight_games", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="UNIQ_spotlight_games_name", columns={"name"}),
 *     @ORM\UniqueConstraint(name="UNIQ_spotlight_games_slug", columns={"slug"}),
 * })
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"single" = "SpotlightGame", "team" = "TeamSpotlightGame"})
 */
class SpotlightGame
{
    /**
     * @var integer
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=50, unique=true, nullable=false)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(length=128, unique=true, nullable=false)
     */
    protected $slug;


    /**
     * @var integer
     * @ORM\Column(name="maximum", type="integer")
     */
    protected $maximum;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Player", mappedBy="spotlightGame")
     */
    protected $players;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->spotlightGamePlayers = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return SpotlightGame
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return SpotlightGame
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set maximum
     *
     * @param integer $maximum
     * @return SpotlightGame
     */
    public function setMaximum($maximum)
    {
        $this->maximum = $maximum;

        return $this;
    }

    /**
     * Get maximum
     *
     * @return integer 
     */
    public function getMaximum()
    {
        return $this->maximum;
    }

    /**
     * Add players
     *
     * @param \AppBundle\Entity\Player $players
     * @return SpotlightGame
     */
    public function addPlayer(Player $players)
    {
        $this->players[] = $players;

        return $this;
    }

    /**
     * Remove players
     *
     * @param \AppBundle\Entity\Player $players
     */
    public function removePlayer(Player $players)
    {
        $this->players->removeElement($players);
    }

    /**
     * Get players
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPlayers()
    {
        return $this->players;
    }

    public function isSelectable() {
        return count($this->getSelectors()) < $this->getMaximum();
    }

    public function __toString() {
        return $this->getName();
    }

    public function getSelectors (){
        return $this->getPlayers()->getValues();
        /*return count(array_filter($this->getPlayers(), function (Player $player) {
        return !is_null($player->getTicket());
    })) */
    }
}
