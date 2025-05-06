<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%translators}}`.
 */
class m250506_163358_create_translators_table extends Migration
{

    /**
     * Наименование таблицы, которая создается
     */
    const TABLE_NAME = 'translators';

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
            'name' => $this->string(255)->notNull()->comment('ФИО переводчика'),
            'type' => $this->string(20)->notNull()->comment('Тип: full_time/part_time'),
            'available_days' => $this->string(20)->notNull()->comment('Дни доступности: weekdays/weekends'),
            'created_at' => $this->integer()->comment('Дата создания'),
            'updated_at' => $this->integer()->comment('Дата обновления'),
        ], $tableOptions);

        // Генерация тестовых данных
        $faker = \Faker\Factory::create('ru_RU');
        $translatorTypes = ['full_time', 'part_time'];

        for ($i = 0; $i < 30; $i++) {
            $type = $translatorTypes[array_rand($translatorTypes)];
            $days = ($type === 'full_time') ? 'weekdays' : 'weekends';

            $this->insert(
                self::TABLE_NAME,
                [
                    'name' => $faker->name,
                    'type' => $type,
                    'available_days' => $days,
                    'created_at' => time(),
                    'updated_at' => time(),
                ]
            );
        }

         $this->createIndex('idx-translators-type', self::TABLE_NAME, 'type');
         $this->createIndex('idx-translators-available_days', self::TABLE_NAME, 'available_days');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE_NAME);
    }
}
