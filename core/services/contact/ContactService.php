<?php


namespace core\services\contact;

use core\forms\ContactForm;
use yii\mail\MailerInterface;

class ContactService
{
    private $supportEmail;
    private $adminEmail;
    private $mailer;

    /**
     * ContactService constructor.
     * @param $supportEmail
     * @param $adminEmail
     * @param MailerInterface $mailer
     */
    public function __construct($supportEmail, $adminEmail, MailerInterface $mailer)
    {
        $this->supportEmail = $supportEmail;
        $this->adminEmail = $adminEmail;
        $this->mailer = $mailer;
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     *
     * @param ContactForm $form
     * @return void whether the email was sent
     */
    public function send(ContactForm $form):void
    {
        $sent = $this->mailer->compose()
            ->setFrom($this->supportEmail)
            ->setTo($this->adminEmail)
            ->setSubject($form->subject)
            ->setTextBody($form->body)
            ->send();

        if (!$sent) {
            throw new \RuntimeException('Sending error');
        }
    }
}