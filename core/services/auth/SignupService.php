<?php


namespace core\services\auth;

use core\repositories\UserRepository;
use core\entities\User\User;
use core\forms\auth\SignupForm;
use yii\base\Exception;
use yii\mail\MailerInterface;

class SignupService
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
     * @param SignupForm $form
     * @return User
     * @throws Exception
     */
    public function signup(SignupForm $form): User {

        $user = User::signup(
            $form->username,
            $form->email,
            $form->password,
            $form->full_name,
            $form->telegram,
            $form->gabber
        );
        $this->users->save($user);
        $this->sendEmail($user);

        return $user;
    }

    /**
     * Sends confirmation email to user
     * @param User $user user model to with email should be send
     * @return void whether the email was sent
     */
    private function sendEmail($user): void
    {
        $send = $this->mailer
            ->compose(
                ['html' => 'auth/signup/confirm-html', 'text' => 'auth/signup/confirm-text'],
                ['user' => $user]
            )
            ->setFrom($this->supportEmail)
            ->setTo($user->email)
            ->setSubject('Account registration at ' . $this->appName)
            ->send();

        if (!$send) {
            throw new \RuntimeException('Sorry, we are unable to send verify token for the provided email address.');
        }
    }
}
