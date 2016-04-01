<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "ota_build_types".
 *
 * @property integer $id
 * @property string $name
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property OtaProjectsBuildtypes[] $otaProjectsBuildtypes
 */
class OtaBuildTypes extends \common\models\CActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ota_build_types';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 255],
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
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOtaProjectsBuildtypes()
    {
        return $this->hasMany(OtaProjectsBuildtypes::className(), ['id_ota_buildtypes' => 'id']);
    }
}
