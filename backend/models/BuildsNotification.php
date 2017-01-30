<?php

namespace backend\models;

use Yii;
use common\models\User;

/**
 * This is the model class for table "builds_notification".
 *
 * @property integer $id
 * @property integer $buiId
 * @property string $email
 * @property integer $created_by
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Builds $bui
 */
class BuildsNotification extends \common\models\CActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'builds_notification';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['buiId', 'email'], 'required'],
            [['buiId', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['email'], 'string', 'max' => 255],
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
            'email' => Yii::t('app', 'Email to send notification'),
            'created_by' => Yii::t('app', 'Created By'),
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

}
