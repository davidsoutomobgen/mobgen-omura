<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "project".
 *
 * @property integer $id
 * @property string $name
 * @property string $alias
 * @property string $logo
 * @property string $description
 * @property integer $active
 * @property integer $internal
 * @property string $additional_information
 * @property string $onboarding_document
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $deleted
 *
 * @property Client[] $Client
 * @property Client[] $clients
 * @property NewFieldProject[] $newFieldProjects
 * @property NewField[] $newFields
 * @property Permissions $permissionView
 * @property Permissions $permissionUpdate
 * @property ProjectType[] $projectTypes
 */
class Project extends \yii\db\ActiveRecord
{

    public $image_logo;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'project';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'alias', 'description'], 'required'],
            [['description'], 'string'],
            [['active', 'internal', 'created_at', 'updated_at', 'deleted'], 'integer'],
            [['name', 'alias', 'logo', 'additional_information', 'onboarding_document'], 'string', 'max' => 255],
            [['image_logo'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
            //[['permission_view'], 'exist', 'skipOnError' => true, 'targetClass' => Permissions::className(), 'targetAttribute' => ['permission_view' => 'id']],
            //[['permission_update'], 'exist', 'skipOnError' => true, 'targetClass' => Permissions::className(), 'targetAttribute' => ['permission_update' => 'id']],
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
            'alias' => Yii::t('app', 'Alias'),
            'logo' => Yii::t('app', 'Logo'),
            'description' => Yii::t('app', 'Description'),
            'active' => Yii::t('app', 'Active'),
            'internal' => Yii::t('app', 'Internal'),
            'additional_information' => Yii::t('app', 'Additional Information'),
            'onboarding_document' => Yii::t('app', 'Onboarding Document'),
            'permission_view' => Yii::t('app', 'Permission View'),
            'permission_update' => Yii::t('app', 'Permission Update'),
            'created_at' => Yii::t('app', 'Created at'),
            'updated_at' => Yii::t('app', 'Updated at'),
            'deleted' => Yii::t('app', 'Deleted'),
        ];
    }

    /**
     * @inheritdoc
     * @return ProjectQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProjectQuery(get_called_class());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasMany(Client::className(), ['id_project' => 'id']);
    }

    public function upload()
    {
        if ($this->validate() && (!empty($this->image_logo))) {
            //print_r($this->image_logo);
            //echo '<br>';
            //print_r($this->image_logo->extension);
            //die;
            $image_path = 'files/projects/' . rand(0, 99999999) . '_' . $this->image_logo->baseName . '.' . $this->image_logo->extension;
            //$this->image_logo->saveAs($image_path);
            try {
                //$this->image_logo->saveAs($image_path);
                copy($this->image_logo->tempName, $image_path);

            } catch (\Exception $e) {
                print_r($e);
            }
            return $image_path;
        } else {
            return false;
        }
    }


    public function getNewFieldProjects()
    {
        return $this->hasMany(NewFieldProject::className(), ['project_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNewFields()
    {
        return $this->hasMany(NewField::className(), ['id' => 'new_field_id'])->viaTable('new_field_project', ['project_id' => 'id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPermissionView()
    {
        return $this->hasOne(Permissions::className(), ['id' => 'permission_view']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPermissionUpdate()
    {
        return $this->hasOne(Permissions::className(), ['id' => 'permission_update']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjectTypes()
    {
        return $this->hasMany(ProjectType::className(), ['project_id' => 'id']);
    }

}
