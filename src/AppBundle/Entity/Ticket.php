<?php
/**
 * Created by PhpStorm.
 * User: ivanis
 * Date: 25/07/15
 * Time: 16:17
 */

namespace AppBundle\Entity;

use AppBundle\Model\Price;
use Gedmo\Timestampable\Timestampable;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(
 *  name="tickets",
 *  uniqueConstraints={
 *    @ORM\UniqueConstraint(
 *      name="UNIQ_tickets_code",
 *      columns={"code"}
 *    )
 * })
 *
 * @UniqueEntity("code")
 */
class Ticket  implements Timestampable
{
    /**
     * Hook timestampable behavior
     * updates createdAt, updatedAt fields
     */
    use TimestampableEntity;

    /**
     * Identifier
     *
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Identifier
     *
     * @var string
     *
     * @ORM\Column(type="string", unique=true)
     *
     * @Assert\Length(min=13, max=13, exactMessage="Le code doit faire 13 caratères")
     */
    private $code;

    /**
     * Identifier
     *
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * Identifier
     *
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $reduced = false;

    /**
     * Teeshirt
     *
     * @var TShirt
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\TShirt")
     */
    private $tshirt;

    /**
     * @return TShirt
     */
    public function getTshirt()
    {
        return $this->tshirt;
    }

    /**
     * @param TShirt $tshirt
     */
    public function setTshirt($tshirt)
    {
        $this->tshirt = $tshirt;
    }

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     *
     * @Assert\Choice(choices = {"paypal", "stripe"}, message = "La méthode de paiement est incorrecte")
     */
    private $method;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return int
     */
    public function getPrice()
    {
        return new Price($this->price);
    }

    /**
     * @param int $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return boolean
     */
    public function isReduced()
    {
        return $this->reduced;
    }

    /**
     * @param boolean $reduced
     */
    public function setReduced($reduced)
    {
        $this->reduced = $reduced;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param string $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * Get reduced
     *
     * @return boolean 
     */
    public function getReduced()
    {
        return $this->reduced;
    }
}
