<?php


namespace core\services\auth;


use core\entities\User\User;
use core\repositories\UserRepository;

class NetworkService
{
    private $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    /**
     * @param $id
     * @param $network
     * @param $identity
     */
    public function attach($id, $network, $identity): void
    {
        if ($this->users->findByNetworkIdentity($network, $identity)) {
            throw new \DomainException('Network is already signed up.');
        }
        $user = $this->users->get($id);
        $user->attachNetwork($network, $identity);
        $this->users->save($user);
    }

    /**
     * @param $network
     * @param $identity
     * @return User
     */
    public function auth($network, $identity):User
    {
        if ($user = $this->users->findByNetworkIdentity($network, $identity)) {
            return  $user;
        }
        $user = User::signupByNetwork($network, $identity);
        $this->users->save($user);
        return $user;
    }
}
