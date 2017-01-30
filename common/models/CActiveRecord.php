<?php
namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class CActiveRecord extends ActiveRecord
{

    /**
     * By default, TimestampBehavior will fill the created_at and updated_at attributes with the current timestamp
     * when the associated AR object is being inserted; it will fill the updated_at attribute with the timestamp when
     * the AR object is being updated. The timestamp value is obtained by time().
     *
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
}
