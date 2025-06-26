<?php

use yii\db\Migration;

class m250626_180757_create_table_file extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('file', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'path_file' => $this->string(),
        ]);

        $this->createTable('file_order', [
            'id' => $this->primaryKey(),
            'order_id' => $this->Integer(),
            'file_id' => $this->Integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('file');
        $this->dropTable('file_order');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250626_180757_create_table_file cannot be reverted.\n";

        return false;
    }
    */
}
