<?php
namespace api\modules\v1\models;
use \yii\db\ActiveRecord;

/**
 * Builds Model
 *
 * @author David Souto <david.souto@mobgen.com>
 */
class Otaprojects extends ActiveRecord
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
    public static function primaryKey()
    {
        return ['id'];
    }

    /**
     * Define rules for validation
     */
    public function rules()
    {
        return [
            [['id', 'name', 'safename'], 'required']
        ];
    }
}
