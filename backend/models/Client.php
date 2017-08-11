<?php

namespace backend\models;

use Yii;
use common\models\User;

/**
 * This is the model class for table "client".
 *
 * @property integer $id
 * @property integer $id_project
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $phone
 * @property string $company
 * @property string $job_title
 * @property string $image
 * @property integer $active
 * @property integer $user
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $deleted
 */
class Client extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'client';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_project', 'first_name', 'last_name', 'email', 'company'], 'required'],
            [['id_project', 'active', 'user', 'created_at', 'updated_at', 'deleted'], 'integer'],
            [['first_name', 'last_name', 'email', 'phone', 'company', 'job_title', 'image'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'id_project' => Yii::t('app', 'Id Project'),
            'first_name' => Yii::t('app', 'First Name'),
            'last_name' => Yii::t('app', 'Last Name'),
            'email' => Yii::t('app', 'Email'),
            'phone' => Yii::t('app', 'Phone'),
            'company' => Yii::t('app', 'Company'),
            'job_title' => Yii::t('app', 'Job Title'),
            'image' => Yii::t('app', 'Image'),
            'active' => Yii::t('app', 'Active'),
            'user' => Yii::t('app', 'User'),
            'created_at' => Yii::t('app', 'Date Created'),
            'updated_at' => Yii::t('app', 'Date Updated'),
            'deleted' => Yii::t('app', 'Deleted'),
            'project' => Yii::t('app', 'Project'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser0()
    {
        return $this->hasOne(User::className(), ['id' => 'user']);
    }

    /**
     * @inheritdoc
     * @return ClientQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ClientQuery(get_called_class());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProject()
    {
        return $this->hasOne(Project::className(), ['id' => 'id_project']);
    }
}
