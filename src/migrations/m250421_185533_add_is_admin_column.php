<?php

use yii\db\Migration;

class m250421_185533_add_is_admin_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'is_admin', $this->boolean()->notNull()->defaultValue(false));
        $this->createIndex('idx-user-is_admin', 'user', 'is_admin');
    }

    public function safeDown()
    {
        $this->dropColumn('user', 'is_admin');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250421_185533_add_is_admin_column cannot be reverted.\n";

        return false;
    }
    */
}
