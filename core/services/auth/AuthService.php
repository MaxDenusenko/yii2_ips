<?php


namespace core\services\auth;


use DomainException;
use core\entities\User\User;
use core\forms\auth\LoginForm;
use core\repositories\UserRepository;

class AuthService
{
    private $users;

    /**
     * AuthService constructor.
     * @param UserRepository $users
     */
    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    /**
     * @param LoginForm $form
     * @return User
     */
    public function auth(LoginForm $form): User
    {
        $user = $this->users->findByUsernameOrEmail($form->username);
        if (!$user || !$user->isActive() || !$user->validatePassword($form->password)) {
            throw new DomainException('Неверный логин или пароль');
        }
        return $user;
    }

}
