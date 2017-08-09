<?php

namespace backend\models;


use Yii;
use common\models\User;

/**
 * This is the model class for table "mobgenners".
 *
 * @property integer $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $phone
 * @property string $skype
 * @property string $job_title
 * @property string $image
 * @property integer $gender
 * @property integer $active
 * @property integer $user
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $deleted
 * @property User $user0
 */
class Mobgenners extends \common\models\CActiveRecord
{
    public $role_id;
    public $profile;
    public $oldpass;
    public $newpass;
    public $repeatnewpass;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mobgenners';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['first_name', 'last_name', 'gender', 'email'], 'required'],
            [['active','created_at', 'updated_at', 'deleted'], 'integer'],
            [['first_name', 'last_name', 'email', 'phone', 'skype', 'job_title', 'image'], 'string', 'max' => 255],
            ['gender', 'string', 'max' => 1],
            ['email', 'unique'],
            ['email', 'email'],
            [['user'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'first_name' => Yii::t('app', 'First Name'),
            'last_name' => Yii::t('app', 'Last Name'),
            'email' => Yii::t('app', 'Email'),
            'phone' => Yii::t('app', 'Phone'),
            'skype' => Yii::t('app', 'Skype'),
            'job_title' => Yii::t('app', 'Job Title'),
            'image' => Yii::t('app', 'Image'),
            'gender' => Yii::t('app', 'Gender'),
            'active' => Yii::t('app', 'MOBGEN Status'),
            'user' => Yii::t('app', 'User'),
            'created_at' => Yii::t('app', 'Created'),
            'updated_at' => Yii::t('app', 'Updated'),
            'deleted' => Yii::t('app', 'Deleted'),
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser0()
    {
        return $this->hasOne(User::className(), ['id' => 'user']);
    }

    public function getRoleName()
    {
        if (!empty($this->user0->role_id)) {
            $items[1] = 'ADMIN';
            $items[10] = 'DEVELOPER';
            $items[11] = 'QA';
            $items[12] = 'LEAD';
            $items[13] = 'PM';

            return $items[$this->user0->role_id];
        }
        return null;
    }

    public function setRoleName($roleName)
    {
        return ($this->roleName = $roleName);
    }

    /**
     * @inheritdoc
     * @return OptionsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MobgennersQuery(get_called_class());
    }
}
