<?php

use yii\db\Migration;

class m250702_170115_create_table_manager_clients extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('Managers_clients', [
            'id' => $this->primaryKey(),
            'client_id' => $this->integer(),
            'manager_id' => $this->integer(),
        ]);

        $this->addColumn('Managers', 'phone',$this->string());
        $this->addColumn('Managers', 'email',$this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('Managers_clients');

        $this->dropColumn('Managers', 'phone');
        $this->dropColumn('Managers', 'email');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250702_170115_create_table_manager_clients cannot be reverted.\n";

        return false;
    }
    */
}
