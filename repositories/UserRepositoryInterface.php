<?php

namespace app\repositories;

use app\models\User;

/**
 * Interface UserRepositoryInterface
 *
 * Felelősség: Az User modellt érintő adatbázis-műveletek absztrakciója.
 */
interface UserRepositoryInterface
{
    /**
     * Felhasználó keresése azonosító (ID) alapján.
     *
     * @param int $id
     * @return User|null
     */
    public function findById(int $id): ?User;

    /**
     * Felhasználó keresése e-mail cím alapján.
     *
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User;

    /**
     * User entitás elmentése az adatbázisba.
     *
     * @param User $user
     * @return bool
     */
    public function save(User $user): bool;
}