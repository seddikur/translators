<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;

/**
 * Модель переводчика
 *
 * @property int $id
 * @property string $name ФИО переводчика
 * @property string $type Тип: full_time/part_time
 * @property string $available_days Дни доступности: weekdays/weekends
 * @property int $created_at Дата создания
 * @property int $updated_at Дата обновления
 */
class Translator extends ActiveRecord
{
    /**
     * Типы переводчиков
     */
    const TYPE_FULL_TIME = 'full_time';
    const TYPE_PART_TIME = 'part_time';

    /**
     * Дни доступности
     */
    const DAYS_WEEKDAYS = 'weekdays';
    const DAYS_WEEKENDS = 'weekends';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'translators';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'type', 'available_days'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['type'], 'string', 'max' => 20],
            [['type'], 'in', 'range' => [self::TYPE_FULL_TIME, self::TYPE_PART_TIME]],
            [['available_days'], 'string', 'max' => 20],
            [['available_days'], 'in', 'range' => [self::DAYS_WEEKDAYS, self::DAYS_WEEKENDS]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'ФИО переводчика',
            'type' => 'Тип',
            'available_days' => 'Дни доступности',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * Получить список типов переводчиков
     * @return array
     */
    public static function getTypeList()
    {
        return [
            self::TYPE_FULL_TIME => 'Полный день',
            self::TYPE_PART_TIME => 'Частичная занятость',
        ];
    }

    /**
     * Получить список дней доступности
     * @return array
     */
    public static function getAvailableDaysList()
    {
        return [
            self::DAYS_WEEKDAYS => 'Будни',
            self::DAYS_WEEKENDS => 'Выходные',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->created_at = time();
            }
            $this->updated_at = time();
            return true;
        }
        return false;
    }

    /**
     * Поиск переводчиков
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = self::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['id' => $this->id])
            ->andFilterWhere(['type' => $this->type])
            ->andFilterWhere(['available_days' => $this->available_days])
            ->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
} 