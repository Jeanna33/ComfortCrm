<?php

use yii\db\Migration;

class m250428_213547_crm_tables_orders extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('Clients', [
            'id' => $this->primaryKey(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'is_active' => $this->boolean()->defaultValue(true),
            // Основная информация о компании
            'company_name' => $this->string(255)->notNull(),
            'trading_name' => $this->string(),
            'company_type' => $this->string(),
            'industry' => $this->string(),

            // Юридические идентификаторы
            'registration_number' => $this->string(),
            'tax_id' => $this->string(),
            'vat_number' => $this->string(),
            'incorporation_date' => $this->date(),

            // Контактная информация
            'legal_address' => $this->text(),
            'country_code' => $this->string(2)->notNull(),
            'city' => $this->string(),
            'postal_code' => $this->string(),
            'website' => $this->string(),
            'phone' => $this->string(),
            'email' => $this->string(),

            // Данные директора/владельца
            'director_name' => $this->string(),
            'director_tax_id' => $this->string(),

            // Финансовая информация
            'annual_revenue' => $this->decimal(15, 2),
            'currency' => $this->string(3),
            'employee_count' => $this->integer(),
            'credit_rating' => $this->string(10),

            // Статус и проверки
            'last_verification_date' => $this->timestamp(),
            'verification_status' => $this->string(20),
            'risk_level' => $this->string(10),

            'delete_status' => $this->boolean()->defaultValue(false),
        ]);

        $this->createTable('Client_order', [
            'id' => $this->primaryKey(),
            'client_id' => $this->Integer(),
            'order_id' => $this->Integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('Clients');
        $this->dropTable('Client_order');
    }
}
