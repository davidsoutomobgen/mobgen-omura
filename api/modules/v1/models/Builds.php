<?php
namespace api\modules\v1\models;
use \yii\db\ActiveRecord;

/**
 * Builds Model
 *
 * @author David Souto <david.souto@mobgen.com>
 */
class Builds extends ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'builds';
	}

    /**
     * @inheritdoc
     */
    public static function primaryKey()
    {
        return ['buiId'];
    }

    /**
     * Define rules for validation
     */
    public function rules()
    {
        return [
            [['buiId', 'buiName', 'buiSafename'], 'required']
        ];
    }
}
