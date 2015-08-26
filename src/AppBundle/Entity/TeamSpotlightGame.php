<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * TeamSpotlightGame
 * @ORM\Entity()
 * @ORM\Table(name="team_spotlight_games")
 */
class TeamSpotlightGame extends SpotlightGame
{

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Team", mappedBy="game")
     **/
    protected $teams;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    protected $teammateNumber;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->teams = new ArrayCollection();
    }




    /**
     * Set teammateNumber
     *
     * @param integer $teammateNumber
     * @return TeamSpotlightGame
     */
    public function setTeammateNumber($teammateNumber)
    {
        $this->teammateNumber = $teammateNumber;

        return $this;
    }

    /**
     * Get teammateNumber
     *
     * @return integer 
     */
    public function getTeammateNumber()
    {
        return $this->teammateNumber;
    }


    /**
     * Add teams
     *
     * @param \AppBundle\Entity\Team $teams
     * @return TeamSpotlightGame
     */
    public function addTeam(Team $teams)
    {
        $this->teams[] = $teams;

        return $this;
    }

    /**
     * Remove teams
     *
     * @param \AppBundle\Entity\Team $teams
     */
    public function removeTeam(Team $teams)
    {
        $this->teams->removeElement($teams);
    }

    /**
     * Get teams
     *
     * @return ArrayCollection
     */
    public function getTeams()
    {
        return $this->teams;
    }

    public function isSelectable() {
        return count($this->getSelectors()) < $this->getMaximum();
    }

    public function getSelectors (){
        return array_filter($this->getTeams()->getValues() ,function (Team $team) {
            return $team->isComplete();
        });
    }
}
