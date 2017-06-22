<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

use backend\models\Mobgenners;


/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $role_id
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;
    const ROLE_USER = 10;

    const ADMIN_ROLE = 1;
    const DEVELOPER_ROLE = 10;
    const QA_ROLE = 11;
    const LEAD = 12;
    const CLIENT_ROLE = 99;

    //ADMIN = 10
    //QA = 11

    /**
     * @var array EAuth attributes
     */
    public $profile;
    public $job_title;
    public $oldpass;
    public $password;
    public $newpass;
    public $repeatnewpass;
    public $image;
    public $gender;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            [['role_id', 'username', 'first_name', 'last_name'], 'safe'],

            //['role_id', 'default', 'value' => self::ROLE_USER],
            //['role_id', 'in', 'range' => [self::ROLE_USER]],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            //'id' => Yii::t('app', 'ID'),
            'send_email' => Yii::t('app', 'Send email'),
        ];
    }

    /**
     * @inheritdoc
     */

    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /*
    public static function findIdentity($id) {
        if (Yii::$app->getSession()->has('user-'.$id)) {
            return new self(Yii::$app->getSession()->get('user-'.$id));
        }
        else {
            return isset(self::$users[$id]) ? new self(self::$users[$id]) : null;
        }
    }
    */


    /**
     * @inheritdoc
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
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        //$parts = explode('_', $token);
        //$timestamp = (int) end($parts);
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }


    public static function getUser()
    {
        $id_user = Yii::$app->user->getId();
        return static::findOne(['id' => $id_user, 'status' => self::STATUS_ACTIVE]);
    }

    public static function getUserIdRole()
    {
        $id_user = Yii::$app->user->getId();
        $user = static::findOne(['id' => $id_user, 'status' => self::STATUS_ACTIVE]);
        return $user->role_id;
    }

    public static function getUserInfo($id_user = '')
    {
        if (empty($id_user))
            $id_user = Yii::$app->user->getId();

        $user = static::findOne(['id' => $id_user, 'status' => self::STATUS_ACTIVE]);
        //echo '<pre>'; print_r($user->attributes); echo '</pre>';//die;
        if ($user->role_id != 99) {
            //$mobgenner = Mobgenners::findOne(['user' => $user->id, 'active' => '1']);
            $mobgenner = Mobgenners::findOne(['user' => $user->id]);
            if (empty($mobgenner->image)) {
                if ($mobgenner->gender == 'M') $user->image  = '/files/user2-128x128.png';
                else $user->image  = '/files/user3-128x128.png';
            }
            else $user->image = '/files/mobgenners/' . $mobgenner->image;

            $user->job_title = $mobgenner->job_title;
        }
        return $user;
    }

    public static function getImageUser($id_user = '')
    {
        if (empty($id_user))
            $id_user = Yii::$app->user->getId();

        $user = static::findOne(['id' => $id_user, 'status' => self::STATUS_ACTIVE]);
        //echo '<pre>'; print_r($user->attributes); echo '</pre>';//die;
        if ($user->role_id != 99) {
            //$mobgenner = Mobgenners::findOne(['user' => $user->id, 'active' => '1']);
            $mobgenner = Mobgenners::findOne(['user' => $user->id]);
            if (empty($mobgenner->image)) {
                if ($mobgenner->gender == 'M') $user->image  = '/files/user2-128x128.png';
                else $user->image  = '/files/user3-128x128.png';
            }
            else $user->image = '/files/mobgenners/' . $mobgenner->image;
        }
        return $user->image;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
}
