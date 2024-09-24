<?php

namespace common\models;

use Yii;

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
            'user_id' => 'Поручено пользователю',
        ];
    }
}
