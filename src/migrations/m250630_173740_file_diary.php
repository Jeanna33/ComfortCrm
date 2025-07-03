<?php

use yii\db\Migration;

class m250630_173740_file_diary extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('file_diary', [
            'id' => $this->primaryKey(),
            'diary_list_id' => $this->Integer(),
            'file_id' => $this->Integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('file_diary');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250630_173740_file_diary cannot be reverted.\n";

        return false;
    }
    */
}
