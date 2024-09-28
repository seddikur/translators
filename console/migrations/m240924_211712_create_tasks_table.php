<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tasks}}`.
 */
class m240924_211712_create_tasks_table extends Migration
{
    /**
     * Наименование таблицы, которая создается
     */
    const TABLE_NAME = 'tasks';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey()->unsigned()->notNull(),
            'task_date' => $this->date()->notNull()->comment('Дата создания'),
            'descr' => $this->string(255)->notNull()->comment('Описание'),
            'date_completion' => $this->date()->comment('Дата выполнения'),
            'time_completion' => $this->integer(11)->comment('Время выполнения'),
            'user_id' => $this->integer(11)->comment('Испольнитель'),
        ], $tableOptions);

        $faker = \Faker\Factory::create();
        for ($i = 0; $i < 50; $i++) {
            $this->insert(
                self::TABLE_NAME,
                [
                    'task_date' => '2024-09-21',
                    'descr' => 'состав заказа #' . $i,
//                    'user_id' => (int)rand(1, 4),
                    'date_completion' => date_format($faker->dateTimeBetween($startDate = '+10 days', $endDate = '+30 days'), "Y-m-d"),
                    'time_completion' => (int)rand(10, 30),
                ]
            );
        }

//        $this->batchInsert(
//            self::TABLE_NAME,
//            [
//                'id', 'task_date', 'descr', 'user_id',
//            ],
//            [
//                [3, '2017-12-03', 'состав заказа #3',  4],
//                [9, '2017-12-03', 'состав заказа #9',  4],
//                [10, '2017-12-01', 'состав заказа #10',  2],
//                [11, '2017-12-02', 'состав заказа #11',  2],
//            ]
//        );

        // Индексы

        $this->createIndex(
            'dt_created',
            self::TABLE_NAME,
            'task_date'
        );
        $this->createIndex(
            'user_id',
            self::TABLE_NAME,
            'user_id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE_NAME);
    }
}
