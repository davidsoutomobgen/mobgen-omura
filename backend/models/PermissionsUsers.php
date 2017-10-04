<?php

namespace backend\models;

use Yii;
use common\models\User;

/**
 * This is the model class for table "permissions_users".
 *
 * @property integer $id
 * @property integer $id_permission
 * @property integer $id_user
 * @property integer $state
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Permissions $idPermission
 * @property User $idUser
 */
class PermissionsUsers extends \common\models\CActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'permissions_users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_permission', 'id_user', 'state'], 'required'],
            [['id_permission', 'id_user', 'state', 'created_at', 'updated_at'], 'integer'],
            [['id_permission', 'id_user'], 'unique', 'targetAttribute' => ['id_permission', 'id_user']],
            [['id_permission'], 'exist', 'skipOnError' => true, 'targetClass' => Permissions::className(), 'targetAttribute' => ['id_permission' => 'id']],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_user' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'id_permission' => Yii::t('app', 'Id Permission'),
            'id_user' => Yii::t('app', 'Id User'),
            'state' => Yii::t('app', 'State'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdPermissions()
    {
        return $this->hasOne(Permissions::className(), ['id' => 'id_permission']);
    }

    /**
     * Getter for module type name
     * @return string
     */
    public function getPermissionLabel() {
        return $this->idPermissions->label;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdUser()
    {
        return $this->hasOne(User::className(), ['id' => 'id_user']);
    }

}
