<?php

namespace backend\models;

use Yii;


/**
 * This is the model class for table "user_options".
 *
 * @property integer $id
 * @property integer $id_user
 * @property integer $id_option
 * @property string $value
 *
 * @property Options $idOption
 * @property User $idUser
 */
class UserOptions extends \common\models\CActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_options';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_user', 'id_option', 'value'], 'required'],
            [['id_user', 'id_option'], 'integer'],
            [['value'], 'string', 'max' => 255]
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
            'id_option' => Yii::t('app', 'Id Option'),
            'value' => Yii::t('app', 'Value'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdOption()
    {
        return $this->hasOne(Options::className(), ['id' => 'id_option']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdUser()
    {
        return $this->hasOne(User::className(), ['id' => 'id_user']);
    }

    /**
     * @inheritdoc
     * @return UserOptionsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserOptionsQuery(get_called_class());
    }


}
