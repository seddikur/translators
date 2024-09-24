<?php

use yii\db\Migration;

/**
 * Class m240924_204338_add_column_for_users_table
 */
class m240924_204338_add_column_for_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('users', 'busyness', $this->string()->defaultValue(0)->comment('Занятость '));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('users', 'busyness');
    }
}
