<?php

class Mailer
{

    /**
     * @var \Snipworks\Smtp\Email
     */
    private $mail;


    /**
     *
     */
    public function __construct()
    {
        $this->mail = new Snipworks\Smtp\Email('shopware', 1025);
        $this->mail->setLogin('', '');
    }

    /**
     * @param $recipient
     * @param $subject
     * @param $text
     */
    public function sendMail($recipient, $subject, $text)
    {
        $this->mail->addTo($recipient, $recipient);

        $this->mail->setFrom('reviews-dev@dockware.com', 'Dockware Reviews Sample Service');

        $this->mail->setSubject($subject);
        $this->mail->setHtmlMessage($text);

        $this->mail->send();
    }

}