<?php

namespace backend\models;

use Yii;
use common\models\User;

/**
 * This is the model class for table "builds_qa".
 *
 * @property integer $id
 * @property integer $buiId
 * @property integer $status
 * @property string $description
 * @property integer $created_by
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Builds $bui
 * @property User $createdBy
 */
class BuildsQa extends \common\models\CActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'builds_qa';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['buiId', 'status'], 'required'],
            [['buiId', 'status', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['description'], 'string', 'max' => 255],
            [['buiId'], 'exist', 'skipOnError' => true, 'targetClass' => Builds::className(), 'targetAttribute' => ['buiId' => 'buiId']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
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
            'status' => Yii::t('app', 'Status'),
            'description' => Yii::t('app', 'Description'),
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
