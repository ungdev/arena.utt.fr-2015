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
    /** @var Swift_Mailer $transport */
    protected $transport;

    /** @var  string the expeditor email */
    protected $expeditorEmail;

    /** @var  string the expeditor name */
    protected $expeditorName;

    public function __construct(Swift_Mailer $transport, $expeditorEmail, $expeditorName) {
        $this->transport = $transport;
        $this->expeditorEmail = $expeditorEmail;
        $this->expeditorName = $expeditorName;
    }

    public function send(
        $to,
        $subject,
        $body,
        $attachements = array()
    ) {
        /** @var Swift_Message $message */
        $message = Swift_Message::newInstance($subject)
            ->setFrom($this->expeditorEmail, $this->expeditorName)
            ->setTo($to);

        foreach($attachements as $fileName => $data) {
            $message->attach(new \Swift_Attachment($data, $fileName));
        }    

        $message->setBody($body, 'text/html', 'utf-8');

        $this->transport->send($message);
    }
}