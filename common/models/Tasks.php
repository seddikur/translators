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
}
