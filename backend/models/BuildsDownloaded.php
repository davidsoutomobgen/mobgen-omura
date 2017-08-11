<?php

namespace backend\models;

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
class BuildsDownloaded extends \common\models\CActiveRecord

{
    public $cnt;
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
            [['buiId'], 'required'],
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

    /**
     * @inheritdoc
     * @return BuildsDownloadedQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BuildsDownloadedQuery(get_called_class());
    }

    public static function getDownloads($id){
        $count = BuildsDownloaded::find()
            ->select(['COUNT(*) AS cnt'])
            ->where('buiId = '.$id)
            //->groupBy(['promoter_location_id', 'lead_type_id'])
            ->one();

        return $count->cnt;
    }

}
