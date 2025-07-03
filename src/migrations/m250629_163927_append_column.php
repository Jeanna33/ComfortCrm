<?php

use yii\db\Migration;

class m250629_163927_append_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('Orders', 'sum',$this->Integer());
        $this->addColumn('Orders', 'currency',$this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('Orders', 'sum');
        $this->dropColumn('Orders', 'currency');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250629_163927_append_column cannot be reverted.\n";

        return false;
    }
    */
}
