<?php
/**
 * Created by PhpStorm.
 * User: sascha
 * Date: 4/24/2016
 * Time: 10:41 AM
 */

namespace api\modules\v1\models;

use Yii;
use yii\web\User;
use yii\web\IdentityInterface;

class ApiUser extends User implements IdentityInterface
{
    private static $instance = null;


    public function getId()
    {
        Yii::info("ApiUser::getId()\n", "api");
        return 100; //$this->id;
    }

	public function init()
	{
		Yii::info("ApiUser::init()\n", "api");
        // parent::init(); // <- fails if we call the parent
	}

    // Somehow not called
//    public function login(IdentityInterface $identity, $duration = 0)
//    {
//        Yii::info("ApiUser::login()\n", "api");
//    }

	public static function findOne($id)
    {
        Yii::info("ApiUser::findOne(". var_export($id,true) .")\n", "api");
        if (self::$instance == null) {
            self::$instance = new ApiUser();
        }
        return self::$instance;
    }

//	public static function findByRoot()
//	{
//		Yii::info("ApiUser::findByRoot()\n", "api");
//		return (self::$users[100]);
//	}

    public static function findIdentityByAccessToken($token, $type = null)
    {
        Yii::info("ApiUser::findIdentityByAccessToken($token,$type)\n", "api");
        return static::findOne(['access_token' => $token]);
    }

	public static function findIdentity($id)
	{
		Yii::info("ApiUser::findIdentity($id)\n", "api");
        return static::findOne($id);
	}


	public function getAuthKey()
	{
		Yii::info("ApiUser::getAuthKey()\n", "api");
		return $this->authKey;
	}

	public function validateAuthKey($authKey)
	{
		Yii::info("ApiUser::validateAuthKey()\n", "api");
		return $this->authKey === $authKey;
	}
}