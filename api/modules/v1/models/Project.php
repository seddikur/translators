<?php

namespace api\modules\v1\models;

/**
 * Выборка проектов по переданным параметрам.
 *
 * @author Roman Karkachev <post@romankarkachev.ru>
 */
class Project extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'production_sites';
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
            [['id', 'name'], 'required'],
        ];
    }
}
