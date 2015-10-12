<?php
/**
 * Created by PhpStorm.
 * User: ivanis
 * Date: 26/08/15
 * Time: 20:29
 */

namespace AppBundle\Service;


use Doctrine\ORM\EntityRepository;

class Place
{
    /**
     * Should be the ticket repository but can be any repository you'd like
     * @var  EntityRepository $repository
     */
    protected $repository;

    protected $maximumPlayerNumber;

    /**
     * Ticketter constructor.
     * @param EntityRepository $repository
     * @param int $maximumPlayerNumber
     */
    public function __construct(EntityRepository $repository, $maximumPlayerNumber = 300)
    {
        $this->repository = $repository;
        $this->maximumPlayerNumber = $maximumPlayerNumber;
    }

    public function canRegister() {
        return count($this->repository->findAll()) < $this->maximumPlayerNumber;
    }

    public function canPay() {
        return count($this->repository->findAll()) < $this->maximumPlayerNumber;
    }

    public function isTooLate() {
        return time() < mktime(0,0,0,11,30,2015);
    }

    function getEAN13(){
        mt_srand(microtime(true));
        $barcode = mt_rand(0, pow(10,12) - 1);
        $barcode = str_pad($barcode, 12, "0", STR_PAD_LEFT);
        $sum = 0;
        for($i=(strlen($barcode)-1);$i>=0;$i--){
            $sum += (($i % 2) * 2 + 1 ) * intval($barcode[$i]);
        }
        $checksum = 10 - ($sum % 10);
        return $barcode . $checksum;
    }
}