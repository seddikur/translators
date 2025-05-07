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
     * Возвращает имя таблицы в базе данных
     */
    public static function tableName()
    {
        return 'translators';
    }

    /**
     * {@inheritdoc}
     * Правила валидации для атрибутов модели
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
     * Метки атрибутов (названия полей)
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
     * Получает список типов переводчиков с их русскими названиями
     * @return array Массив типов переводчиков
     */
    public static function getTypeList()
    {
        return [
            self::TYPE_FULL_TIME => 'Полный день',
            self::TYPE_PART_TIME => 'Частичная занятость',
        ];
    }

    /**
     * Получает список дней доступности с их русскими названиями
     * @return array Массив дней доступности
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
     * Автоматически устанавливает даты создания и обновления перед сохранением
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
     * Поиск переводчиков с фильтрацией и пагинацией
     * @param array $params Параметры поиска
     * @return ActiveDataProvider Провайдер данных с результатами поиска
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

    /**
     * Получает статистику по переводчикам
     * @return array Массив со статистикой:
     * - total: общее количество переводчиков
     * - fullTime: количество переводчиков на полный день
     * - partTime: количество переводчиков на частичную занятость
     * - weekdays: количество переводчиков, работающих в будни
     * - weekends: количество переводчиков, работающих в выходные
     */
    public static function getStats()
    {
         //  вариант с ActiveQuery
        /*
        return [
            'total' => self::find()->count(),
            'fullTime' => self::find()->where(['type' => self::TYPE_FULL_TIME])->count(),
            'partTime' => self::find()->where(['type' => self::TYPE_PART_TIME])->count(),
            'weekdays' => self::find()->where(['available_days' => self::DAYS_WEEKDAYS])->count(),
            'weekends' => self::find()->where(['available_days' => self::DAYS_WEEKENDS])->count(),
        ];
        */
        
        // вариант с SQL-запросами
        $sql = "
            SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN type = :full_time THEN 1 ELSE 0 END) as full_time,
                SUM(CASE WHEN type = :part_time THEN 1 ELSE 0 END) as part_time,
                SUM(CASE WHEN available_days = :weekdays THEN 1 ELSE 0 END) as weekdays,
                SUM(CASE WHEN available_days = :weekends THEN 1 ELSE 0 END) as weekends
            FROM {{%translators}}
        ";

        $params = [
            ':full_time' => self::TYPE_FULL_TIME,
            ':part_time' => self::TYPE_PART_TIME,
            ':weekdays' => self::DAYS_WEEKDAYS,
            ':weekends' => self::DAYS_WEEKENDS,
        ];

        $result = Yii::$app->db->createCommand($sql, $params)->queryOne();

        return [
            'total' => (int)$result['total'],
            'fullTime' => (int)$result['full_time'],
            'partTime' => (int)$result['part_time'],
            'weekdays' => (int)$result['weekdays'],
            'weekends' => (int)$result['weekends'],
        ];
    }
} 