<?php

namespace common\models;

use Yii;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "tasks".
 *
 * @property int $id
 * @property string $task_date Дата создания
 * @property string $descr Описание
 * @property int $user_id Поручено пользователю
 * @property Translator $translator Связанный переводчик
 */
class Tasks extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tasks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['task_date', 'descr', 'user_id'], 'required'],
            [['task_date'], 'safe'],
            [['user_id'], 'integer'],
            [['descr'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'task_date' => 'Дата создания',
            'descr' => 'Описание',
            'user_id' => 'Ответственный',
        ];
    }

    /**
     * Gets query for [[Translator]]
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTranslator()
    {
        return $this->hasOne(Translator::class, ['id' => 'user_id']);
    }

    /** Расчет кол-ва дней в зависимости от графика работы ответственного
     * от текущей даты
     * @param $id_task
     * @param $id_user
     * @return false|int|string
     * @throws \Exception
     */
    public static function leadTime($id_task, $id_user)
    {
        $result = self::findOne(['id' => $id_task]);
        $date_completion = $result['date_completion'];
        $user = Users::find()->where(['id' => $id_user])->one();
        $user_busyness = $user['busyness'];

        $now_date = date('Y-m-d', time());
        //разница дней арифметически $timeDiff/86400
        $timeDiff = abs(strtotime($date_completion) - strtotime($now_date));

        //разница дней date_diff->days
        $interval = date_diff(date_create($date_completion), date_create($now_date));

        $dates = self::dateRange($now_date, $date_completion);
        $day_sundays = 0;
//        $weekends = array_filter($dates, function ($date) {
//            $day = $date->format("N");
//
//            return $day === '6' || $day === '7';
//        });

        /* weekdays output */
//        foreach ($weekends as $date) {
//            echo $date->format("D Y-m-d") . "</br>";
//        }

        /* define sundays */
        $sundays = array_filter($dates, function ($date) {
            return $date->format("N") === '7';
        });

        /* sundays output */
        foreach ($sundays as $date) {
            $day_sundays ++;
//            echo $date->format("D Y-m-d") . "</br>";
        }
//
//        /* define mondays */
//        $mondays = array_filter($dates, function ($date) {
//            return $date->format("N") === '1';
//        });
//
//        /* mondays output */
//        foreach ($mondays as $date) {
//            echo $date->format("D Y-m-d") . "</br>";
//        }
        switch ($user_busyness) {
            case 1: return $interval->days;
            case 2: return $interval->days-$day_sundays;
            default: return 'не указано';
        }
    }

    /**
     * @param $begin
     * @param $end
     * @param $interval
     * @return array
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

    public function fields()
    {
        $fields = parent::fields();
        $fields['translator'] = function($model) {
            return $model->translator ? [
                'id' => $model->translator->id,
                'name' => $model->translator->name,
            ] : null;
        };
        return $fields;
    }

    /**
     * Получить статистику по заказам
     * @return array
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
     * Получить последние заказы
     * @param int $limit
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getRecent($limit = 5)
    {
        // Старый вариант с ActiveQuery
        /*
        return self::find()
            ->with('translator')
            ->orderBy(['task_date' => SORT_DESC])
            ->limit($limit)
            ->all();
        */

        // Новый вариант с SQL-запросом
        $sql = "
            SELECT 
                t.id,
                t.task_date,
                t.descr,
                t.user_id,
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
            
            if (!empty($row['translator_name'])) {
                $task->translator = new Translator();
                $task->translator->name = $row['translator_name'];
            }
            $tasks[] = $task;
        }

        return $tasks;
    }
}
