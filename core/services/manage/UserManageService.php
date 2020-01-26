<?php


namespace core\services\manage;


use core\entities\User\User;
use core\forms\manage\User\UserCreateForm;
use core\forms\manage\User\UserEditForm;
use core\repositories\Core\TariffRepository;
use core\repositories\UserRepository;
use core\services\TransactionManager;
use yii\base\Exception;
use yii\rbac\DbManager;

class UserManageService
{
    private $users;
    private $transaction;
    private $tariffs;

    public function __construct(
        UserRepository $users,
        TransactionManager $transaction,
        TariffRepository $tariffs
    )
    {
        $this->users = $users;
        $this->transaction = $transaction;
        $this->tariffs = $tariffs;
    }

    /**
     * @param UserCreateForm $form
     * @return User
     * @throws Exception
     */
    public function create(UserCreateForm $form): User
    {
        $user = User::create(
            $form->username,
            $form->email,
            $form->password,
            $form->full_name,
            $form->telegram,
            $form->gabber,
            $form->tariff_reminder
        );
        $this->users->save($user);

        $r = new DbManager();
        $role = $r->getRole('user');
        $r->assign($role,$user->id);

        return $user;
    }

    public function toBan($id)
    {
        $user = $this->users->get($id);
        $user->toBan();
        $this->users->save($user);
    }

    public function unban($id)
    {
        $user = $this->users->get($id);
        $user->unban();
        $this->users->save($user);
    }

    public function activate($id)
    {
        $user = $this->users->get($id);
        $user->activate();
        $this->users->save($user);
    }

    public function edit($id, UserEditForm $form): void
    {
        $user = $this->users->get($id);
        $user->edit(
            $form->username,
            $form->email,
            $form->full_name,
            $form->telegram,
            $form->gabber,
            $form->tariff_reminder
        );

        is_array($form->tariffs->list) ?: $form->tariffs->list = [];

        foreach ($form->tariffs->list as $otherId) {
            $tariff = $this->tariffs->get($otherId);
            $user->assignTariff($tariff->id);
        }

        foreach ($user->tariffAssignments as $assignment) {
            if (!in_array($assignment->tariff_id, $form->tariffs->list)) {
                $user->deleteTariff($assignment->tariff_id);
            }
        }

        $this->users->save($user);
    }
}
