<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "type".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $logo
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $deleted
 */
class Type extends \common\models\CActiveRecord
{
    public $image_logo;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['created_at', 'updated_at', 'deleted'], 'integer'],
            [['name', 'description', 'logo'], 'string', 'max' => 255],
            [['image_logo'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
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
            'description' => Yii::t('app', 'Description'),
            'logo' => Yii::t('app', 'Logo'),
            'created_at' => Yii::t('app', 'Date Created'),
            'updated_at' => Yii::t('app', 'Date Updated'),
            'deleted' => Yii::t('app', 'Deleted'),
        ];
    }

    public function upload()
    {
        if ($this->validate() && (!empty($this->image_logo))) {
            //print_r($this->image_logo);die;
            $image_path = 'files/types/' . rand(0, 99999999) . '_' . $this->image_logo->baseName . '.' . $this->image_logo->extension;
            $this->image_logo->saveAs($image_path);
            return $image_path;
        } else
            return false;
    }
}
