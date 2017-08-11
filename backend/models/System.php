<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "system".
 *
 * @property integer $id
 * @property string $last_update
 * @property string $last_remove_builds
 * @property string $type_remove_builds
 * @property string $version
 */
class System extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'system';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['last_update', 'last_remove_builds', 'type_remove_builds'], 'safe'],
            [['version'], 'string', 'max' => 7],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'last_update' => Yii::t('app', 'Last Update'),
            'version' => Yii::t('app', 'Version'),
            'last_remove_builds' => Yii::t('app', 'Last Remove Builds'),
            'type_remove_builds' => Yii::t('app', 'Type Remove Builds'),

        ];
    }

    public static function getVersion()
    {
        $id = 1;
        $system = System::find()
                ->where(['id' => $id])
                ->one();
        return $system->version;
    }
}
