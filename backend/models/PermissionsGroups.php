<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "permissions_groups".
 *
 * @property integer $id
 * @property integer $id_permission
 * @property integer $id_group
 * @property integer $state
 *
 * @property Permissions $idPermission
 * @property Groups $idGroup
 */
class PermissionsGroups extends \common\models\CActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'permissions_groups';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_permission', 'id_group', 'state'], 'required'],
            [['id_permission', 'id_group', 'state', 'created_at', 'updated_at'], 'integer'],
            [['id_permission'], 'exist', 'skipOnError' => true, 'targetClass' => Permissions::className(), 'targetAttribute' => ['id_permission' => 'id']],
            [['id_group'], 'exist', 'skipOnError' => true, 'targetClass' => Groups::className(), 'targetAttribute' => ['id_group' => 'id']],
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
            'id_group' => Yii::t('app', 'Id Group'),
            'state' => Yii::t('app', 'State'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdPermission()
    {
        return $this->hasOne(Permissions::className(), ['id' => 'id_permission']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdGroup()
    {
        return $this->hasOne(Groups::className(), ['id' => 'id_group']);
    }

    /**
     * @inheritdoc
     * @return PermissionsGroupsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PermissionsGroupsQuery(get_called_class());
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
    /*
    public function getPermissionLabel() {
        return $this->idPermission->label;
    }
    */
}
