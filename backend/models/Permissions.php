<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "permissions".
 *
 * @property integer $id
 * @property string $name
 * @property string $label
 * @property string $description
 * @property integer $permission_type
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property PermissionsGroups[] $permissionsGroups
 * @property PermissionsUsers[] $permissionsUsers
 */
class Permissions extends \common\models\CActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'permissions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'label', 'permission_type'], 'required'],
            [['permission_type', 'created_at', 'updated_at'], 'integer'],
            [['name', 'label', 'description'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'label' => Yii::t('app', 'Label'),
            'description' => Yii::t('app', 'Description'),
            'permission_type' => Yii::t('app', 'Permission Type'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPermissionsGroups()
    {
        return $this->hasMany(PermissionsGroups::className(), ['id_permission' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPermissionsUsers()
    {
        return $this->hasMany(PermissionsUsers::className(), ['id_permission' => 'id']);
    }

    /**
     * @inheritdoc
     * @return PermissionsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PermissionsQuery(get_called_class());
    }

}
