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
     * {@inheritdoc}
     */
    public function findById(int $id): ?User
    {
        return User::findOne($id);
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function save(User $user): bool
    {
        return $user->save(false);
    }
}