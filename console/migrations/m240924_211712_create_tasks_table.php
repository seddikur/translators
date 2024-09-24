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
            'user_id' => $this->integer(11)->notNull()->comment('Поручено пользователю'),
        ], $tableOptions);


        $this->batchInsert(
            self::TABLE_NAME,
            [
                'id', 'task_date', 'descr', 'user_id',
            ],
            [
                [3, '2017-12-03', 'состав заказа #3',  4],
                [9, '2017-12-03', 'состав заказа #9',  4],
                [10, '2017-12-01', 'состав заказа #10',  2],
                [11, '2017-12-02', 'состав заказа #11',  2],
                [12, '2017-12-03', 'состав заказа #12', 2],
                [14, '2017-12-02', 'состав заказа #14',  3],
                [15, '2017-12-03', 'состав заказа #15',  3],
                [16, '2017-12-03', 'состав заказа #16',  4],
                [17, '2017-12-03', 'состав заказа #17',  2],
            ]
        );

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
