<?php
/**
 * Created by PhpStorm.
 * User: ivanis
 * Date: 19/08/15
 * Time: 22:59
 */

namespace AppBundle\EventListner;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\Session\Session;

class FormListener implements EventSubscriberInterface
{
    /** @var  Session the session */
    private $session;

    public function __construct(Session $session) {
        $this->session = $session;
    }
    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2'))
     *
     * @return array The event names to listen to
     *
     * @api
     */
    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::POST_SUBMIT => 'onPostSubmit',
        );
    }

    public function onPostSubmit(FormEvent $event) {
        $form = $event->getForm();
        if (!$form->isValid()) {
            $this->session->getFlashBag()->add('error', 'Le formulaire est incorrect');
        }
    }

}