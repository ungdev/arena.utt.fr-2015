<?php
/**
 * Created by PhpStorm.
 * User: ivanis
 * Date: 10/08/15
 * Time: 01:03
 */

namespace AppBundle\Service;

use \Swift_Mailer;
use \Swift_Message;

class Mailer
{
    private $transport;

    private $from;

    public function __construct(Swift_Mailer $transport, $from) {
        $this->transport = $transport;
        $this->from = $from;
    }

    public function send($to, $subject, $body, $mime = 'text/html', $from = false) {
        /** @var Swift_Message $message */
        $message = Swift_Message::newInstance($subject)
            ->setFrom($from ?: $this->from)
            ->setTo($to)
            ->setReplyTo($from ?: $this->from)
            ->setBody($body, $mime, 'utf-8');

        $this->transport->send($message);
    }
}