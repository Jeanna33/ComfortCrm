<?php

use yii\db\Migration;

class m250705_172847_table_finance extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('finance', [
            'id' => $this->primaryKey(),
            'date' => $this->timestamp(),
            'sum' => $this->double(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('finance');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250705_172847_table_finance cannot be reverted.\n";

        return false;
    }
    */
}
