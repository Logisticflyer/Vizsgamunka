<?php

namespace tests\unit;

use Codeception\Test\Unit;
use app\models\User;
use app\repositories\UserRepository;

/**
 * Class UserRepositoryTest
 * 
 * Felelősség: A UserRepository adatbázis-kezelési műveleteinek (mentés, keresés)
 * integrációs tesztelése a valós MySQL kapcsolaton keresztül.
 */
class UserRepositoryTest extends Unit
{
    private UserRepository $userRepository;

    protected function _before()
    {
        // A Yii2 alkalmazást a Codeception Yii2 modulja automatikusan és tisztán betölti a config/test.php alapján.
        $this->userRepository = new UserRepository();
    }

    protected function _after()
    {
        // Takarítás: töröljük a teszt során létrehozott felhasználót az adatbázisból, ha létezik
        $user = User::findOne(['username' => 'integracios_teszt_user']);
        if ($user !== null) {
            $user->delete();
        }
    }

    /**
     * Teszteli, hogy a repository sikeresen menti a felhasználót az adatbázisba,
     * és a lekérdezés (findByUsername) is helyesen működik.
     */
    public function testSaveAndFindByUsername(): void
    {
        // --- DIAGNOSZTIKAI SOROK A KAPCSOLAT ELLENŐRZÉSÉHEZ ---
        codecept_debug("AKTÍV ADATBÁZIS DSN: " . \Yii::$app->db->dsn);
        codecept_debug("LÁTHATÓ TÁBLÁK AZ ADATBÁZISBAN: " . print_r(\Yii::$app->db->schema->getTableNames(), true));
        // ----------------------------------------------------

        // 1. Új User entitás előkészítése
        $user = new User();
        $user->username = 'integracios_teszt_user';
        $user->email = 'repo.test@example.com';
        $user->setPassword('BiztonsagiJelszo123');
        $user->generateAuthKey();
        $user->status = User::STATUS_ACTIVE;
        $user->created_at = time();
        $user->updated_at = time();
        // Hozzáadva: A MySQL NOT NULL 'auth_key_expire' mezőjének feltöltése (30 napos lejárattal)
        $user->auth_key_expire = time() + (30 * 24 * 60 * 60);

        // 2. Mentés tesztelése a repository-n keresztül
        $isSaved = $this->userRepository->save($user);
        $this->assertTrue($isSaved, 'A repository-nak sikerülnie kell a mentésnek.');

        // 3. Lekérdezés tesztelése a repository-n keresztül
        $foundUser = $this->userRepository->findByUsername('integracios_teszt_user');

        // 4. Ellenőrzések (Assertions)
        $this->assertNotNull($foundUser, 'A felhasználónak léteznie kell az adatbázisban.');
        $this->assertEquals('repo.test@example.com', $foundUser->email);
        $this->assertEquals('integracios_teszt_user', $foundUser->username);
    }
}