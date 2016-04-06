<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "ota_projects".
 *
 * @property integer $id
 * @property string $name
 * @property string $safename
 * @property string $proCreated
 * @property string $proModified
 * @property integer $id_project
 * @property integer $id_ota_template
 * @property string $proHash
 * @property string $proAPIKey
 * @property string $proAPIBuildKey
 * @property string $proBuildTypes
 * @property string $default_notify_email
 * @property string $proDevUrl1
 * @property string $proDevUrl2
 * @property string $proDevUrl3
 * @property string $proDevUrl4
 * @property string $proAltUrl1
 * @property string $proAltUrl2
 * @property string $proAltUrl3
 * @property string $proAltUrl4
 * @property string $proProdUrl1
 * @property string $proProdUrl2
 * @property string $proProdUrl3
 * @property string $proProdUrl4
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property OtaProjectsBuildtypes[] $otaProjectsBuildtypes
 */
class OtaProjects extends \common\models\CActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ota_projects';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'id_ota_template', 'default_notify_email'], 'required'],
            //[['proCreated', 'proModified'], 'safe'],
            [['id_project', 'id_ota_template', 'created_at', 'updated_at'], 'integer'],
            [['name', 'safename', 'proDevUrl1', 'proDevUrl2', 'proDevUrl3', 'proDevUrl4', 'proAltUrl1', 'proAltUrl2', 'proAltUrl3', 'proAltUrl4', 'proProdUrl1', 'proProdUrl2', 'proProdUrl3', 'proProdUrl4'], 'string', 'max' => 64],
            [['proHash', 'proAPIKey'], 'string', 'max' => 16],
            [['proAPIBuildKey', 'proBuildTypes', 'default_notify_email'], 'string', 'max' => 255],
            [['proHash'], 'unique'],
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
            'safename' => Yii::t('app', 'Safename'),
            'proCreated' => Yii::t('app', 'Pro Created'),
            'proModified' => Yii::t('app', 'Pro Modified'),
            'id_project' => Yii::t('app', 'Id Project'),
            'id_ota_template' => Yii::t('app', 'Id Ota Template'),
            'proHash' => Yii::t('app', 'Pro Hash'),
            'proAPIKey' => Yii::t('app', 'API'),
            'proAPIBuildKey' => Yii::t('app', 'Build API'),
            'proBuildTypes' => Yii::t('app', 'PrBuild Types'),
            'default_notify_email' => Yii::t('app', 'Default Notify Email'),
            'proDevUrl1' => Yii::t('app', 'Pro Dev Url1'),
            'proDevUrl2' => Yii::t('app', 'Pro Dev Url2'),
            'proDevUrl3' => Yii::t('app', 'Pro Dev Url3'),
            'proDevUrl4' => Yii::t('app', 'Pro Dev Url4'),
            'proAltUrl1' => Yii::t('app', 'Pro Alt Url1'),
            'proAltUrl2' => Yii::t('app', 'Pro Alt Url2'),
            'proAltUrl3' => Yii::t('app', 'Pro Alt Url3'),
            'proAltUrl4' => Yii::t('app', 'Pro Alt Url4'),
            'proProdUrl1' => Yii::t('app', 'Pro Prod Url1'),
            'proProdUrl2' => Yii::t('app', 'Pro Prod Url2'),
            'proProdUrl3' => Yii::t('app', 'Pro Prod Url3'),
            'proProdUrl4' => Yii::t('app', 'Pro Prod Url4'),
            'created_at' => Yii::t('app', 'Created date'),
            'updated_at' => Yii::t('app', 'Modified date'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOtaProjectsBuildtypes()
    {
        return $this->hasMany(OtaProjectsBuildtypes::className(), ['id_ota_project' => 'id']);
    }

    /**
     * @inheritdoc
     * @return OtaProjectsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new OtaProjectsQuery(get_called_class());
    }
}
