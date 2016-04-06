<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "devices".
 *
 * @property integer $id
 * @property string $label_1
 * @property string $label_2
 * @property string $brand
 * @property string $type
 * @property string $technical_details
 * @property string $remarks
 * @property string $office
 * @property string $used_by
 * @property string $information_by
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $delete
 */
class Devices extends \common\models\CActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'devices';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['label_1', 'brand', 'type',  'office', 'used_by', 'information_by'], 'required'],
            [['created_at', 'updated_at', 'delete'], 'integer'],
            [['label_1', 'label_2', 'brand', 'type', 'technical_details', 'remarks', 'office', 'used_by', 'information_by'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'label_1' => Yii::t('app', 'Label 1'),
            'label_2' => Yii::t('app', 'Label 2'),
            'brand' => Yii::t('app', 'Brand'),
            'type' => Yii::t('app', 'Type'),
            'technical_details' => Yii::t('app', 'Technical Details'),
            'remarks' => Yii::t('app', 'Remarks'),
            'office' => Yii::t('app', 'Office'),
            'used_by' => Yii::t('app', 'Used By'),
            'information_by' => Yii::t('app', 'Information By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'delete' => Yii::t('app', 'Delete'),
        ];
    }
}
