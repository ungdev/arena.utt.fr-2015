<?php
/**
 * Created by PhpStorm.
 * User: ivanis
 * Date: 26/07/15
 * Time: 02:20
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\TShirtRepository")
 * @ORM\Table(name="tshirts", uniqueConstraints={
 *  @ORM\UniqueConstraint(name="UNIQ_tshirts", columns={"size", "gender"})
 * })
 *
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
     * @ORM\Column(type="string")
     */
    protected $size;

    /**
     * Gender of tshirt
     *
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $gender;

    /**
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param string $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    /**
     * @return string
     */
    public function getGenderLabel()
    {
        switch ($this->gender) {
            case 'M':
                return 'Homme';
            case 'F':
                return 'Femme';
        }
        return 'Unisex';
    }


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
