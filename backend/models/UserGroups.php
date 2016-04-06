<?php

namespace backend\models;

use Yii;
use common\models\User;

/**
 * This is the model class for table "user_groups".
 *
 * @property integer $id
 * @property integer $id_user
 * @property integer $id_group
 * @property integer $state
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $idUser
 * @property Groups $idGroup
 */
class UserGroups extends \common\models\CActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_groups';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_user', 'id_group', 'state'], 'required'],
            [['id_user', 'id_group', 'state', 'created_at', 'updated_at'], 'integer'],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_user' => 'id']],
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
            'id_user' => Yii::t('app', 'Id User'),
            'id_group' => Yii::t('app', 'Id Group'),
            'state' => Yii::t('app', 'State'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdUser()
    {
        return $this->hasOne(User::className(), ['id' => 'id_user']);
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
     * @return UserGroupsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserGroupsQuery(get_called_class());
    }
}
