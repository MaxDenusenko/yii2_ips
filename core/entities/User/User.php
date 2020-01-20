<?php
namespace core\entities\User;

use core\entities\Core\Tariff;
use core\entities\Core\TariffAssignment;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use core\entities\Network;
use Yii;
use yii\base\Exception;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $verification_token
 * @property string $email
 * @property string $full_name
 * @property string $telegram
 * @property string $gabber
 * @property string $auth_key
 * @property integer $status
 * @property integer $ip
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_BANNED = 1;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;
    /**
     * @param string $username
     * @param string $email
     * @param string $password
     * @param string $full_name
     * @param string $telegram
     * @param string $gabber
     * @return static
     * @throws Exception
     */
    public static function create(string $username, string $email, string $password, string $full_name, string $telegram, string $gabber): self
    {
        $user = new static();
        $user->username = $username;
        $user->email = $email;
        $user->full_name = $full_name;
        $user->telegram = $telegram;
        $user->gabber = $gabber;
        $user->setPassword(!empty($password) ? $password : Yii::$app->security->generateRandomString());
        $user->created_at = time();
        $user->status = self::STATUS_ACTIVE;
        $user->auth_key = Yii::$app->security->generateRandomString();
        return $user;
    }

    /**
     * @param string $username
     * @param string $email
     * @param string $full_name
     * @param string $telegram
     * @param string $gabber
     */
    public function edit(string $username, string $email, string $full_name, string $telegram, string $gabber):void
    {
        $this->username = $username;
        $this->email = $email;
        $this->full_name = $full_name;
        $this->telegram = $telegram;
        $this->gabber = $gabber;
        $this->updated_at = time();
    }

    /**
     * @param string $username
     * @param string $email
     * @param string $password
     * @param string $full_name
     * @param string $telegram
     * @param string $gabber
     * @return static
     * @throws Exception
     */
    public static function signup(string $username, string $email, string $password, string $full_name, string $telegram, string $gabber):self {
        $user = new static();
        $user->username = $username;
        $user->email = $email;
        $user->setPassword($password);
        $user->created_at = time();
        $user->full_name = $full_name;
        $user->telegram = $telegram;
        $user->gabber = $gabber;
        $user->status = self::STATUS_INACTIVE;
        $user->generateEmailVerificationToken();
        $user->generateAuthKey();

        return $user;
    }

    /**
     * @param $network
     * @param $identity
     */
    public function attachNetwork($network, $identity):void
    {
        $networks = $this->networks;
        /**
         * @var  $current Network
         */
        foreach ($networks as $current) {
            if ($current->isFor($networks, $identity)) {
                throw new \DomainException('Network is already attached');
            }
        }
        $networks[] = Network::create($network, $identity);
        $this->networks = $networks;
    }

    /**
     * @param $network
     * @param $identity
     * @return static
     * @throws Exception
     */
    public static function signupByNetwork($network, $identity):self {

        $user = new static();
        $user->created_at = time();
        $user->status = self::STATUS_ACTIVE;
        $user->generateAuthKey();
        $user->networks = [Network::create($network, $identity)];
        return $user;
    }

    /**
     * @return ActiveQuery
     */
    public function getNetworks(): ActiveQuery
    {
        return $this->hasMany(Network::className(), ['user_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            [
                'class' => SaveRelationsBehavior::className(),
                'relations' => ['networks', 'tariffAssignments']
            ]
        ];
    }

    /**
     * Check password reset token
     * @throws Exception
     */
    public function checkPasswordResetToken(): void {
        if (!empty($this->password_reset_token) && self::isPasswordResetTokenValid($this->password_reset_token)) {
            throw new \DomainException('Password resetting is already request');
        }
        $this->generatePasswordResetToken();
    }

    /**
     * Reset password
     * @param $password string
     * @throws Exception
     */
    public function resetPassword(string $password) : void {

        if (empty($this->password_reset_token)) {
            throw new \DomainException('Password resetting is not requested');
        }
        $this->setPassword($password);
        $this->removePasswordResetToken();
    }

    /**
     * Verify email
     */
    public function verifyEmail() : void {
        if (!$this->isWait()) {
            throw new \DomainException('User is already active');
        }

        $this->status = self::STATUS_ACTIVE;
        $this->removeEmailConfirmToken();
    }

    /**
     * @return bool
     */
    public function isWait(): bool
    {
        return $this->status == self::STATUS_INACTIVE;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->status == self::STATUS_ACTIVE;
    }

    /**
     * @return void
     */
    private function removeEmailConfirmToken():void
    {
        $this->verification_token = null;

    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%users}}';
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return static|null
     */
    public static function findByVerificationToken($token) {
        return static::findOne([
            'verification_token' => $token,
            'status' => self::STATUS_INACTIVE
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     * @throws Exception
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     * @throws Exception
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     * @throws Exception
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * @throws Exception
     */
    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function getTariffAssignments(): ActiveQuery
    {
        return $this->hasMany(TariffAssignment::class, ['user_id' => 'id']);
    }

    public function getTariffs(): ActiveQuery
    {
        return $this->hasMany(Tariff::class, ['id' => 'tariff_id'])->via('tariffAssignments');
    }

    public function assignTariff($id, bool $trial = false): void
    {
        $assignments = $this->tariffAssignments;
        foreach ($assignments as $assignment) {
            if ($assignment->isForTariff($id)) {
                return;
            }
        }
        $assignments[] = TariffAssignment::create($id, $trial);
        $this->tariffAssignments = $assignments;
    }

    public function deleteTariff($id)
    {
        $assignments = $this->tariffAssignments;
        foreach ($assignments as $k => $assignment) {
            if ($assignment->isForTariff($id)) {
                unset($assignments[$k]);
            }
        }
        $this->tariffAssignments = $assignments;
    }

    public function editProfile(string $username, string $email, string $full_name, string $telegram, string $gabber)
    {
        $this->username = $username;
        $this->email = $email;
        $this->full_name = $full_name;
        $this->telegram = $telegram;
        $this->gabber = $gabber;
    }

    public function issetTariff($tariff_id, $user_id): bool
    {
        $tariffs = $this->tariffAssignments;
        foreach ($tariffs as $tariff) {

            if ($tariff->tariff_id == $tariff_id && $tariff->user_id == $user_id)
                return true;
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Логин',
            'email' => 'Email',
            'full_name' => 'ФИО',
            'telegram' => 'Telegram',
            'gabber' => 'Gabber',
            'created_at' => 'Аккаунт создан',
            'updated_at' => 'Аккаунт изменен',
        ];
    }

    public function toBan()
    {
        $this->status = self::STATUS_BANNED;
    }

    public function unban()
    {
        $this->status = self::STATUS_ACTIVE;
    }
}
