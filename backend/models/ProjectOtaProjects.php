<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "ota_projects_buildtypes".
 *
 * @property integer $id
 * @property integer $id_ota_project
 * @property integer $id_project
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property OtaProjects $idOtaProject
 * @property Projects $idProject
 */
class ProjectOtaProjects extends \common\models\CActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'project_otaprojects';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_ota_project', 'id_project'], 'required'],
            [['id_project', 'id_ota_project', 'created_at', 'updated_at'], 'integer'],
            [['id_ota_project'], 'exist', 'skipOnError' => true, 'targetClass' => OtaProjects::className(), 'targetAttribute' => ['id_ota_project' => 'id']],
            [['id_project'], 'exist', 'skipOnError' => true, 'targetClass' => Project::className(), 'targetAttribute' => ['id_project' => 'id']],
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
            'id_project' => Yii::t('app', 'Id Project'),
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
    public function getIdProject()
    {
        return $this->hasOne(Project::className(), ['id' => 'id_project']);
    }
}
