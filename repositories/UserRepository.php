<?php

namespace app\repositories;

use app\models\User;

/**
 * Class UserRepository
 *
 * Felelősség: A Yii2 ActiveRecord használatával elvégzi a User mentését és lekérdezését.
 */
class UserRepository implements UserRepositoryInterface
{
    /**
     * Felhasználó keresése azonosító (ID) alapján.
     * 
     * @param int $id
     * @return User|null Visszaadja a megtalált User objektumot, vagy null-t, ha nem létezik.
     */
    public function findById(int $id): ?User
    {
        return User::findOne($id);
    }

    /**
     * Felhasználó keresése e-mail cím alapján.
     * 
     * @param string $email
     * @return User|null Visszaadja a megtalált User objektumot, vagy null-t, ha nem létezik.
     */
    public function findByEmail(string $email): ?User
    {
        return User::findOne(['email' => $email]);
    }

    /**
     * Felhasználó keresése felhasználónév alapján.
     * 
     * @param string $username
     * @return User|null Visszaadja a megtalált User objektumot, vagy null-t, ha nem létezik.
     */
    public function findByUsername(string $username): ?User
    {
        return User::findOne(['username' => $username]);
    }

    /**
     * Felhasználó elmentése az adatbázisba.
     * 
     * @param User $user
     * @return bool Igaz, ha a mentés sikeres volt.
     */
    public function save(User $user): bool
    {
        return $user->save(false);
    }
}