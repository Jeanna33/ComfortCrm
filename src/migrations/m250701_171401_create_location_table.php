<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%location}}`.
 */
class m250701_171401_create_location_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('location', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'lat' => $this->decimal(10, 8)->notNull(),
            'lng' => $this->decimal(11, 8)->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('location');
    }
}
