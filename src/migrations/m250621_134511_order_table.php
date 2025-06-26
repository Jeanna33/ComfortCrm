<?php

use yii\db\Migration;

class m250621_134511_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('Orders', [
            'id' => $this->primaryKey(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'is_active' => $this->boolean()->defaultValue(true),
            'date_begin' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'date_end' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),

            // Контактная информация
            'info_about_order' => $this->text(),

            'delete_status' => $this->boolean()->defaultValue(false),
        ]);

        $this->createTable('Managers', [
            'id' => $this->primaryKey(),

            // Данные контактного лица
            'full_name' => $this->string(),
            'info' => $this->string(),
        ]);

        $this->createTable('Managers_orders', [
            'id' => $this->primaryKey(),
            'order_id' => $this->Integer(),
            'manager_id' => $this->Integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('Orders');
        $this->dropTable('Managers');
        $this->dropTable('Managers_orders');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250621_134511_order_table cannot be reverted.\n";

        return false;
    }
    */
}
