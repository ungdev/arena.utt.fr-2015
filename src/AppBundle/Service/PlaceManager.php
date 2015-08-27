<?php
/**
 * Created by PhpStorm.
 * User: ivanis
 * Date: 26/08/15
 * Time: 20:29
 */

namespace AppBundle\Service;


use Doctrine\ORM\EntityRepository;

class PlaceManager
{
    /** @var  EntityRepository */
    protected $repository;

    protected $maximumPlayerNumber;

    /**
     * Ticketter constructor.
     * @param EntityRepository $repository
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
}