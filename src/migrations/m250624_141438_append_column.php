<?php

use yii\db\Migration;

class m250624_141438_append_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('Orders', 'name',$this->string());
        $this->addColumn('Orders', 'grade',$this->Integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('Orders', 'name');
        $this->dropColumn('Orders', 'grade');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250624_141438_append_column cannot be reverted.\n";

        return false;
    }
    */
}
