<?php

use yii\db\Migration;

/**
 * Class m261010_092944_insert_mate_user
 * 
 * Migráció a 'Mate' nevű vendég felhasználó biztonságos beszúrására érvényes BCrypt jelszó-hash-sel.
 */
class m261010_092944_insert_mate_user extends Migration
{
    /**
     * A migráció futtatásakor lefutó művelet (Beszúrás).
     */
    public function safeUp()
    {
        // Korábbi 'Mate' rekord törlése a duplikáció és a hibás hash elkerülésére
        $this->delete('user', ['username' => 'Mate']);

        // Érvényes BCrypt hash és AuthKey generálása a Yii2 biztonsági moduljával
        $passwordHash = Yii::$app->security->generatePasswordHash('mate2006');
        $authKey = Yii::$app->security->generateRandomString();

        $this->insert('user', [
            'username' => 'Mate',
            'auth_key' => $authKey,
            'password_hash' => $passwordHash, // Dinamikusan generált érvényes hash 'mate2006'-hoz
            'email' => 'mate@example.com',
            'status' => 10, // Aktív státusz
            'auth_key_expire' => time() + (3600 * 24 * 30), // 30 napos lejárati idő
            'created_at' => time(),
            'updated_at' => time(),
        ]);
    }

    /**
     * A migráció visszavonásakor lefutó művelet (Törlés).
     */
    public function safeDown()
    {
        $this->delete('user', ['username' => 'Mate']);
    }
}