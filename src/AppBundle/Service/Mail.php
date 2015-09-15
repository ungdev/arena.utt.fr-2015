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

class Mail
{
    private $transport;

    private $from;

    public function __construct(Swift_Mailer $transport, $from) {
        $this->transport = $transport;
        $this->from = $from;
    }

    public function send(
        $to,
        $subject,
        $body,
        $attachements = array(),
        $from = false,
        $mime = 'text/html'
    ) {
        /** @var Swift_Message $message */
        $message = Swift_Message::newInstance($subject)
            ->setFrom($from ?: $this->from)
            ->setTo($to);

        foreach($attachements as $fileName => $data) {
            $message->attach(new \Swift_Attachment($data, $fileName));
        }    

        $message->setReplyTo($from ?: $this->from)
                ->setBody($body, $mime, 'utf-8');

        $this->transport->send($message);
    }
}