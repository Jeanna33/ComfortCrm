<?php

use yii\db\Migration;

class m250719_133431_new_delete_row extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('Event', 'delete',$this->integer());
        $this->addColumn('Orders', 'delete',$this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('Event', 'delete');
        $this->dropColumn('Orders', 'delete');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250719_133431_new_delete_row cannot be reverted.\n";

        return false;
    }
    */
}
