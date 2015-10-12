<?php
/**
 * Created by PhpStorm.
 * User: ivanis
 * Date: 12/10/15
 * Time: 00:24
 */

namespace AppBundle\Entity;


use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class TShirtRepository extends EntityRepository
{
    /**
     * Returns all possible size for the tshirts
     *
     * @return array
     */
    public function getSizes() {
        $sizes = $this->createQueryBuilder('t')
            ->select('DISTINCT(t.size)')
            ->getQuery()
            ->execute();

        array_walk($sizes, function (&$size) {
           $size = array_pop($size);
        });

        $sizes = array_combine(array_values($sizes), array_values($sizes));

        return $sizes;
    }

    /**
     * Returns all possible genders for the tshirts
     *
     * @return array
     */
    public function getGenders () {
        $genders =  $this->createQueryBuilder('t')
            ->select('DISTINCT(t.gender)')
            ->getQuery()
            ->execute();

        array_walk($genders, function (&$gender) {
            $gender = array_pop($gender);
        });

        $genders = array_combine(array_values($genders), array_values($genders));

        return $genders;
    }

    /**
     * Find a tshirt by its size and gender
     *
     * @param $size
     * @param $gender
     *
     * @return TShirt
     */
    public function findBySizeAndGender($size, $gender) {
        $tshirt = $this->findOneBy(array('size' => $size, 'gender' => $gender));

        return $tshirt;
    }
}