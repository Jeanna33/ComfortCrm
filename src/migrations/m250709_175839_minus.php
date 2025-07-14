<?php

use yii\db\Migration;

class m250709_175839_minus extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('finance', 'minus',$this->double());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('finance', 'minus');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250709_175839_minus cannot be reverted.\n";

        return false;
    }
    */
}
