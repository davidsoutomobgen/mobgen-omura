<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "options".
 *
 * @property integer $id
 * @property string $variable
 * @property string $type
 *
 * @property UserOptions[] $userOptions
 */
class Options extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'options';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['variable', 'type'], 'required'],
            [['variable', 'type'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'variable' => Yii::t('app', 'Variable'),
            'type' => Yii::t('app', 'Type'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserOptions()
    {
        return $this->hasMany(UserOptions::className(), ['id_option' => 'id']);
    }

    /**
     * @inheritdoc
     * @return OptionsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new OptionsQuery(get_called_class());
    }
}
