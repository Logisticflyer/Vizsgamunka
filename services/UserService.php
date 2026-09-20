<?php

namespace app\services;

use app\models\User;
use app\models\SignupForm;
use app\repositories\UserRepositoryInterface;

/**
 * Class UserService
 *
 * Felelősség: A felhasználókkal kapcsolatos üzleti logika (pl. regisztráció folyamata)
 * koordinálása az SRP elvnek megfelelően.
 */
class UserService
{
    /**
     * @var UserRepositoryInterface
     */
    private UserRepositoryInterface $userRepository;

    /**
     * Dependency Injection segítségével átvesszük a UserRepository-t.
     *
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Új felhasználó regisztrációjának feldolgozása.
     *
     * @param SignupForm $form Validált regisztrációs űrlap
     * @return User|null A létrehozott User objektum vagy null hiba esetén
     */
    public function register(SignupForm $form): ?User
    {
        // 1. Validáció ellenőrzése
        if (!$form->validate()) {
            return null;
        }

        // 2. User entitás felépítése az üzleti logika szerint
        $user = new User();
        $user->username = $form->username;
        $user->email = $form->email;
        $user->setPassword($form->password);
        $user->generateAuthKey();
        $user->status = User::STATUS_ACTIVE;

        $currentTime = time();
        $user->created_at = $currentTime;
        $user->updated_at = $currentTime;
        $user->auth_key_expire = $currentTime + (30 * 24 * 60 * 60);

        // 3. Mentés a Repository-n keresztül
        if ($this->userRepository->save($user)) {
            return $user;
        }

        return null;
    }
}