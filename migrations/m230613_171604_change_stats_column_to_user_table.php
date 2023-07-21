<?php

use yii\db\Migration;

/**
 * Class m230613_171604_change_stats_column_to_user_table
 */
class m230613_171604_change_stats_column_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('user', 'stats', 'decimal(3,2) NULL');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230613_171604_change_stats_column_to_user_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230613_171604_change_stats_column_to_user_table cannot be reverted.\n";

        return false;
    }
    */
}
