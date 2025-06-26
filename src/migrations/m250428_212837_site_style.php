<?php

use yii\db\Migration;

class m250428_212837_site_style extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('styles_crm', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'path_file_style' => $this->string(),
            'active' => $this->smallInteger()->defaultValue(0),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'author_name' => $this->string(),
            'author_contact' => $this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('styles_crm');
    }
}
