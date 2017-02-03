<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "templates".
 *
 * @property integer $id
 * @property string $te_name
 * @property string $description
 *
 * @property Structures[] $Structures
 */
class Templates extends \common\models\CActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'templates';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['te_name', 'description'], 'required'],
            [['description'], 'string'],
            [['te_name'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'te_name' => 'Name of template',
            'description' => 'Description',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStructures()
    {
        return $this->hasMany(Structures::className(), ['template_id' => 'id']);
    }


    public static function getTemplatesTemporaly() {

        return [
            0 => 'default',
            1 => 'ABN AMRO',
            2 => 'Shell Innovation',
            3 => 'Redevco',
            4 => 'WhoIsWho (Ron)',
            5 => 'National Express',
            6 => 'James BETA-User',
            7 => 'Classic Template',
        ];
    }


}
