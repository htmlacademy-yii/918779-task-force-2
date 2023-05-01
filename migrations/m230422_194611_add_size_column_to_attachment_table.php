<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%attachment}}`.
 */
class m230422_194611_add_size_column_to_attachment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%attachment}}', 'size', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%attachment}}', 'size');
    }
}
