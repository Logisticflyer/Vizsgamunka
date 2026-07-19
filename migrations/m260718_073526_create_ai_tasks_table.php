<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%ai_tasks}}`.
 */
class m260718_073526_create_ai_tasks_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%ai_tasks}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'prompt' => $this->text()->notNull(),
            'response' => $this->text(),
            'status' => $this->string()->defaultValue('pending'),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        // Hozzunk létre indexet a user_id-ra, hogy gyorsabb legyen a lekérdezés
        $this->createIndex('idx-ai_tasks-user_id', '{{%ai_tasks}}', 'user_id');

        // Hozzunk létre kulcsot (FK) a user táblához
        $this->addForeignKey(
            'fk-ai_tasks-user_id',
            '{{%ai_tasks}}',
            'user_id',
            '{{%user}}', // Feltételezve, hogy a felhasználói táblád neve 'user'
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%ai_tasks}}');
    }
}
