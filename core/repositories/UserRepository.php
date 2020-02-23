<?php


namespace core\repositories;


use core\entities\User\User;
use core\repositories\NotFoundException;
use yii\db\ActiveRecord;

class UserRepository
{

    /**
     * @param $network
     * @param $identity
     * @return array|User|ActiveRecord|null
     */
    public function findByNetworkIdentity($network, $identity)
    {
        return User::find()->joinWith('networks n')->andWhere(['n.network' => $network, 'n.identity' => $identity])->one();
    }

    public function findByUsernameOrEmail($emailOrUsername): User
    {
        if (filter_var($emailOrUsername, FILTER_VALIDATE_EMAIL)) {
            return $this->getByEmail($emailOrUsername);
        }
        return $this->getByUsername($emailOrUsername);
    }

    /**
     * @param $token
     * @return User
     */
    public function getByEmailConfirmToken($token): User
    {
        return $this->getBy(['verification_token' => $token]);
    }

    /**
     * @param array $condition
     * @return User
     */
    public function getBy(array $condition): User
    {
        if (!$user = User::find()->andWhere($condition)->limit(1)->one()) {
            throw new NotFoundException(\Yii::t('frontend', 'User not found'));
        }
        return $user;
    }

    public function get($id): User
    {
        if (!$user = User::findOne($id)) {
            throw new NotFoundException(\Yii::t('frontend', 'User not found'));
        }
        return $user;
    }

    /**
     * @param string $email
     * @return User
     */
    public function getByEmail(string $email): User
    {
        if (!$user = User::findOne(['email' => $email, 'status' => User::STATUS_ACTIVE])) {
            throw new NotFoundException(\Yii::t('frontend', 'User not found'));
        }
        return $user;
    }

    /**
     * @param string $username
     * @return User
     */
    public function getByUsername(string $username): User
    {
        if (!$user = User::findOne(['username' => $username, 'status' => User::STATUS_ACTIVE])) {
            throw new \DomainException(\Yii::t('frontend', 'User not found'));
        }
        return $user;
    }

    /**
     * @param string $token
     * @return bool
     */
    public function existsByPasswordResetToken(string $token): bool
    {
        return (bool) User::findByPasswordResetToken($token);
    }

    /**
     * @param string $token
     * @return User
     */
    public function getByPasswordResetToken(string $token): User
    {
        if (!$user = User::findByPasswordResetToken($token)) {
            throw new \DomainException(\Yii::t('frontend', 'User not found'));
        }
        return $user;
    }

    /**
     * @param User $user
     */
    public function save(User $user): void
    {
        if (!$user->save()) {
            throw new \RuntimeException(\Yii::t('frontend', 'Saving error'));
        }
    }
}
