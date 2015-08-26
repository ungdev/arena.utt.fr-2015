<?php
namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Blameable;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Timestampable;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Team
 *
 * @ORM\Table("teams",uniqueConstraints={
 *  @ORM\UniqueConstraint(name="UNIQ_team_name", columns={"name"}),
 *  @ORM\UniqueConstraint(name="UNIQ_team_slug", columns={"slug"})
 * })
 * @ORM\Entity()
 * @UniqueEntity("name")
 * @UniqueEntity("slug")
 */
class Team implements Blameable, Timestampable
{

    /**
     * Hook timestampable behavior
     * updates createdAt, updatedAt fields
     */
    use TimestampableEntity;

    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50, unique=true)
     * @Assert\Length(max = "50")
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @var Player
     *
     * @Gedmo\Blameable(on="create")
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Player")
     * @ORM\JoinColumn(referencedColumnName="id", unique=true)
     */
    private $createdBy;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Membership", mappedBy="team", cascade={"persist","remove"})
     *
     * @Assert\Count(min="1", max="$this->game->getTeammateNumber()")
     */
    private $memberships;

    /**
     * @var TeamSpotlightGame
     *
     * @ORM\ManyToOne(targetEntity="TeamSpotlightGame", inversedBy="teams")
     * @ORM\JoinColumn(onDelete="CASCADE")
     **/
    private $game;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"name"},updatable=true, unique=true)
     * @ORM\Column(length=128, unique=true)
     */
    protected $slug;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->memberships = new ArrayCollection();
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
     * @return Team
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
     * @return Team
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
     * Set createdBy
     *
     * @param Player $createdBy
     * @return Team
     */
    public function setCreatedBy(Player $createdBy = null)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return \AppBundle\Entity\Player 
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Add memberships
     *
     * @param \AppBundle\Entity\Membership $memberships
     * @return Team
     */
    public function addMembership(\AppBundle\Entity\Membership $memberships)
    {
        $this->memberships[] = $memberships;

        return $this;
    }

    /**
     * Remove memberships
     *
     * @param \AppBundle\Entity\Membership $memberships
     */
    public function removeMembership(\AppBundle\Entity\Membership $memberships)
    {
        $this->memberships->removeElement($memberships);
    }

    /**
     * Get memberships
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMemberships()
    {
        return $this->memberships;
    }

    /**
     * Set game
     *
     * @param \AppBundle\Entity\TeamSpotlightGame $game
     * @return Team
     */
    public function setGame(\AppBundle\Entity\TeamSpotlightGame $game = null)
    {
        $this->game = $game;

        return $this;
    }

    /**
     * Get game
     *
     * @return \AppBundle\Entity\TeamSpotlightGame 
     */
    public function getGame()
    {
        return $this->game;
    }

    public function isComplete() {
        return count($this->getMemberships()) == $this->getGame()->getTeammateNumber();
        /*return count(array_filter($this->getMemberships(), function(Membership $membership) {
            return !is_null($membership->getPlayer()->getTicket());
        })) == $this->getGame()->getTeammateNumber();*/
    }

    public function __toString() {
        return $this->getName();
    }
}
