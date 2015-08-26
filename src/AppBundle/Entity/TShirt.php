<?php
/**
 * Created by PhpStorm.
 * User: ivanis
 * Date: 26/07/15
 * Time: 02:20
 */

namespace AppBundle\Entity;

use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(
 *  name="tshirts",
 *  uniqueConstraints={
 *    @ORM\UniqueConstraint(
 *      name="UNIQ_shirt_size",
 *      columns={"size"}
 *    )
 * })
 *
 * @UniqueEntity("size")
 */
class TShirt
{
    /**
     * Identifier
     *
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * size the tshirt
     *
     * @var string
     *
     * @ORM\Column(type="string", unique=true)
     */
    protected $size;


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param string $size
     */
    public function setSize($size)
    {
        $this->size = $size;
    }

}
