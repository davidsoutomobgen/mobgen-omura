<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "ota_projects_buildtypes".
 *
 * @property integer $id
 * @property integer $id_ota_project
 * @property integer $id_ota_buildtypes
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property OtaProjects $idOtaProject
 * @property OtaBuildTypes $idOtaBuildtypes
 */
class OtaProjectsBuildtypes extends \common\models\CActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ota_projects_buildtypes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_ota_project', 'id_ota_buildtypes'], 'required'],
            [['id_ota_project', 'id_ota_buildtypes', 'created_at', 'updated_at'], 'integer'],
            [['id_ota_project'], 'exist', 'skipOnError' => true, 'targetClass' => OtaProjects::className(), 'targetAttribute' => ['id_ota_project' => 'id']],
            [['id_ota_buildtypes'], 'exist', 'skipOnError' => true, 'targetClass' => OtaBuildTypes::className(), 'targetAttribute' => ['id_ota_buildtypes' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'id_ota_project' => Yii::t('app', 'Id Ota Project'),
            'id_ota_buildtypes' => Yii::t('app', 'Id Ota Buildtypes'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdOtaProject()
    {
        return $this->hasOne(OtaProjects::className(), ['id' => 'id_ota_project']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdOtaBuildtypes()
    {
        return $this->hasOne(OtaBuildTypes::className(), ['id' => 'id_ota_buildtypes']);
    }
}
