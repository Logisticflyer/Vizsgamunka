<?php

namespace app\models;

use yii\base\Model;

/**
 * Class SignupForm
 * 
 * Felelősség: Kizárólag a regisztrációs űrlap adatainak tárolása és a beviteli mezők validációja.
 * Az SRP (Egyfelelősségi elv) értelmében nem végez adatbázis-mentést és üzleti logikát;
 * a mentést a UserService és UserRepository kezeli.
 */
class SignupForm extends Model
{
    /**
     * @var string A regisztráló felhasználó neve
     */
    public $username;

    /**
     * @var string A regisztráló e-mail címe
     */
    public $email;

    /**
     * @var string A regisztráló jelszava
     */
    public $password;

    /**
     * Ellenőrzési (validációs) szabályok definiálása
     *
     * @return array
     */
    public function rules()
    {
        return [
            [['username', 'email', 'password'], 'required', 'message' => 'Ezt a mezőt kötelező kitölteni.'],
            ['username', 'trim'],
            ['username', 'string', 'min' => 2, 'max' => 32],
            ['username', 'unique', 'targetClass' => '\app\models\User', 'message' => 'Ez a felhasználónév már foglalt.'],

            ['email', 'trim'],
            ['email', 'email', 'message' => 'Kérlek, egy érvényes e-mail formátumot adj meg!'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\app\models\User', 'message' => 'Ezzel az e-mail címmel már regisztráltak.'],

            ['password', 'string', 'min' => 6, 'message' => 'A jelszónak legalább 6 karakterből kell állnia.'],
        ];
    }

    /**
     * Mezők emberi feliratai
     *
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Felhasználónév',
            'email' => 'E-mail cím',
            'password' => 'Jelszó',
        ];
    }
}