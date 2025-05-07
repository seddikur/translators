<?php

namespace common\models;

use Yii;
use yii\helpers\VarDumper;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "tasks".
 *
 * @property int $id
 * @property string $task_date Дата создания
 * @property string $descr Описание
 * @property string|null $date_completion Дата выполнения
 * @property int|null $time_completion Время выполнения
 * @property int $user_id Поручено пользователю
 * @property Translator $translator Связанный переводчик
 */
class Tasks extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     * Устанавливает поведение модели, включая автоматическое заполнение даты создания
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'task_date',
                'updatedAtAttribute' => false,
                'value' => new Expression('CURRENT_DATE'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     * Возвращает имя таблицы в базе данных
     */
    public static function tableName()
    {
        return 'tasks';
    }

    /**
     * {@inheritdoc}
     * Правила валидации для атрибутов модели
     */
    public function rules()
    {
        return [
            [['descr', 'user_id'], 'required'],
            [['task_date', 'date_completion'], 'safe'],
            [['user_id', 'time_completion'], 'integer'],
            [['descr'], 'string', 'max' => 255],
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
            'task_date' => 'Дата создания',
            'date_completion' => 'Дата выполнения',
            'time_completion' => 'Время выполнения',
            'descr' => 'Описание',
            'user_id' => 'Ответственный',
        ];
    }

    /**
     * Получает запрос для связи с моделью Translator
     * @return \yii\db\ActiveQuery
     */
    public function getTranslator()
    {
        return $this->hasOne(Translator::class, ['id' => 'user_id']);
    }

    /**
     * Расчет количества дней в зависимости от графика работы ответственного
     * от текущей даты
     * @param int $id_task ID задачи
     * @param int $id_user ID пользователя
     * @return false|int|string Количество дней или сообщение об ошибке
     * @throws \Exception
     */
    public static function leadTime($id_task, $id_user)
    {
        $result = self::findOne(['id' => $id_task]);
        if (!$result) {
            return 'Задача не найдена';
        }
        
        $date_completion = $result['date_completion'];
        $user = Translator::find()->where(['id' => $id_user])->one();
        if (!$user) {
            return 'Переводчик не найден';
        }
        
        $user_busyness = $user['type'] ?? null;
        if ($user_busyness === null) {
            return 'Не указан тип занятости переводчика';
        }

        $now_date = date('Y-m-d', time());
        //разница дней арифметически $timeDiff/86400
        $timeDiff = abs(strtotime($date_completion) - strtotime($now_date));

        //разница дней date_diff->days
        $interval = date_diff(date_create($date_completion), date_create($now_date));

        $dates = self::dateRange($now_date, $date_completion);
        $day_sundays = 0;

        /* define sundays */
        $sundays = array_filter($dates, function ($date) {
            return $date->format("N") === '7';
        });

        /* sundays output */
        foreach ($sundays as $date) {
            $day_sundays ++;
        }

        switch ($user_busyness) {
            case Translator::TYPE_FULL_TIME: return $interval->days;
            case Translator::TYPE_PART_TIME: return $interval->days-$day_sundays;
            default: return 'не указано';
        }
    }

    /**
     * Создает массив дат в указанном диапазоне
     * @param string $begin Начальная дата
     * @param string $end Конечная дата
     * @param string|null $interval Интервал (по умолчанию P1D - один день)
     * @return array Массив дат
     * @throws \Exception
     */
    public static function dateRange($begin, $end, $interval = null)
    {
        $begin = new \DateTime($begin);
        $end = new \DateTime($end);

        $end = $end->modify('+1 day');
        $interval = new \DateInterval($interval ? $interval : 'P1D');

        return iterator_to_array(new \DatePeriod($begin, $interval, $end));
    }

    /**
     * Определяет поля, которые будут возвращаться при сериализации модели
     * @return array Массив полей
     */
    public function fields()
    {
        $fields = parent::fields();
        $fields['translator'] = function($model) {
            return $model->translator ? [
                'id' => $model->translator->id,
                'name' => $model->translator->name,
            ] : null;
        };
        $fields['leadTime'] = function($model) {
            return self::leadTime($model->id, $model->user_id);
        };
        return $fields;
    }

    /**
     * Получает статистику по заказам
     * @return array Массив со статистикой
     */
    public static function getStats()
    {
        // Старый вариант с ActiveQuery
        /*
        return [
            'total' => self::find()->count(),
        ];
        */

        // Новый вариант с SQL-запросом
        $sql = "SELECT COUNT(*) as total FROM {{%tasks}}";
        $result = Yii::$app->db->createCommand($sql)->queryOne();

        return [
            'total' => (int)$result['total'],
        ];
    }

    /**
     * Получает последние заказы
     * @param int $limit Количество последних заказов
     * @return array Массив последних заказов
     */
    public static function getRecent($limit = 5)
    {
        // вариант с SQL-запросом
        $sql = "
            SELECT 
                t.*,
                tr.id as translator_id,
                tr.name as translator_name
            FROM {{%tasks}} t
            LEFT JOIN {{%translators}} tr ON t.user_id = tr.id
            ORDER BY t.task_date DESC
            LIMIT :limit
        ";

        $result = Yii::$app->db->createCommand($sql, [':limit' => $limit])->queryAll();

        // Преобразуем результат в модели
        $tasks = [];
        foreach ($result as $row) {
            $task = new static();
            // Явно устанавливаем атрибуты
            $task->id = (int)$row['id'];
            $task->task_date = $row['task_date'];
            $task->descr = $row['descr'];
            $task->user_id = (int)$row['user_id'];
            $task->date_completion = $row['date_completion'];
            $task->time_completion = $row['time_completion'];
            $tasks[] = $task;
        }

        return $tasks;
    }
}
