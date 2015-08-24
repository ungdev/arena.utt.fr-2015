<?php
/**
 * Created by PhpStorm.
 * User: ivanis
 * Date: 20/08/15
 * Time: 00:27
 */

namespace AppBundle\Form;


use AppBundle\EventListner\FormListener;
use Symfony\Component\Form\AbstractType;

abstract class BaseType extends AbstractType
{
    protected $listner;

    public function __construct(FormListener $listener) {
        $this->listner = $listener;
    }
}