<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "builds_downloaded".
 *
 * @property integer $id
 * @property integer $buiId
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Builds $bui
 */
class BuildsDownloaded extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'builds_downloaded';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['buiId', 'created_at', 'updated_at'], 'required'],
            [['buiId', 'created_at', 'updated_at'], 'integer'],
            [['buiId'], 'exist', 'skipOnError' => true, 'targetClass' => Builds::className(), 'targetAttribute' => ['buiId' => 'buiId']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'buiId' => Yii::t('app', 'Bui ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBui()
    {
        return $this->hasOne(Builds::className(), ['buiId' => 'buiId']);
    }
}
