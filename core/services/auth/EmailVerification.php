<?php


namespace core\services\auth;


use core\repositories\UserRepository;
use core\forms\auth\ResendVerificationEmailForm;
use core\entities\User\User;
use yii\base\InvalidArgumentException;
use yii\mail\MailerInterface;

class EmailVerification
{
    private $supportEmail;
    private $appName;
    private $mailer;
    private $users;

    /**
     * PasswordResetService constructor.
     * @param $supportEmail
     * @param $appName
     * @param MailerInterface $mailer
     * @param UserRepository $users
     */
    public function __construct($supportEmail, $appName, MailerInterface $mailer, UserRepository $users)
    {
        $this->supportEmail = $supportEmail;
        $this->appName = $appName;
        $this->mailer = $mailer;
        $this->users = $users;
    }

    /**
     * Sends confirmation email to user
     *
     * @param ResendVerificationEmailForm $form
     * @return void whether the email was sent
     */
    public function sendEmail(ResendVerificationEmailForm $form):void
    {
        $user = $this->users->getBy([
            'email' => $form->email,
            'status' => User::STATUS_INACTIVE
        ]);

        $send = $this->mailer
            ->compose(
                ['html' => 'auth/signup/confirm-html', 'text' => 'auth/signup/confirm-text'],
                ['user' => $user]
            )
            ->setFrom($this->supportEmail)
            ->setTo($form->email)
            ->setSubject(\Yii::t('frontend', 'Account registration').' ' . $this->appName)
            ->send();

        if (!$send) {
            throw new \DomainException(\Yii::t('frontend', 'Sorry, we cannot forward the verification email to the indicated email address ..'));
        }
    }

    /**
     * Creates a form model with given token.
     *
     * @param string $token
     * @throws InvalidArgumentException if token is empty or not valid
     */
    public function validateToken(string $token): void
    {
        if (empty($token) || !is_string($token)) {
            throw new InvalidArgumentException(\Yii::t('frontend', 'Email token cannot be empty.'));
        }

        if (!User::findByVerificationToken($token)) {
            throw new InvalidArgumentException(\Yii::t('frontend', 'Invalid email token.'));
        }
    }

    /**
     * Verify email
     *
     * @param string $token
     * @return void the saved model or null if saving fails
     */
    public function verifyEmail(string $token) : void
    {
        if (empty($token)) {
            throw new \DomainException([\Yii::t('frontend', 'Confirmation blank token')]);
        }

        $user = $this->users->getByEmailConfirmToken($token);
        $user->verifyEmail();
        $user->activate();
        $this->users->save($user);
    }
}
