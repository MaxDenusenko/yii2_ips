<?php


namespace core\services\cabinet;


use core\entities\Core\Tariff;
use core\entities\User\User;
use core\forms\user\ProfileEditForm;
use core\repositories\UserRepository;

class ProfileService
{
    private $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    public function edit($id, ProfileEditForm $form): void
    {
        $user = $this->users->get($id);
        $user->editProfile(
            $form->username,
            $form->email,
            $form->full_name,
            $form->telegram,
            $form->gabber,
            $form->tariff_reminder
        );
        $this->users->save($user);
    }

    public function addTariff(User $user, Tariff $tariff, bool $trial, $order = null, $additional_id = 0)
    {
        $user->assignTariff($tariff->id, $trial, $order, (int) $additional_id);
        $this->users->save($user);
    }
}
