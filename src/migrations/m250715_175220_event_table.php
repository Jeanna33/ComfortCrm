<?php

use yii\db\Migration;

class m250715_175220_event_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('Event', [
            'id' => $this->primaryKey(),
            'title_event' => $this->string(255)->notNull(),
            'description' => $this->text(),
            'start_event' => $this->dateTime()->notNull(),
            'end_event' => $this->dateTime(),
            'all_day' => $this->boolean()->defaultValue(false),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('Event');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250715_175220_event_table cannot be reverted.\n";

        return false;
    }
    */
}
