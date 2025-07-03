<?php

use yii\db\Migration;

class m250630_170440_diary_tabke extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('diary_lists', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'text_list' => $this->string(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'mood' => $this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('diary_lists');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250630_170440_diary_tabke cannot be reverted.\n";

        return false;
    }
    */
}
