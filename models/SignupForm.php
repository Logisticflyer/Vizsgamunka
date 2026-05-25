<?php

namespace app\models;

use Yii;
use yii\base\Model;

class SignupForm extends Model
{
    // Megadjuk azokat a mezőket, amiket a regisztrációs űrlapon kérünk be
    public $username;
    public $email;
    public $password;
    public $password_repeat;

    /**
     * Ellenőrzési (validációs) szabályok
     */
    public function rules()
    {
        return [
            // Minden mező kitöltése kötelező
            [['username', 'email', 'password', 'password_repeat'], 'required', 'message' => '{attribute} kitöltése kötelező.'],
            
            // A felhasználónévnek egyedinek kell lennie a 'user' táblában
            ['username', 'unique', 'targetClass' => '\app\models\User', 'message' => 'Ez a felhasználónév már foglalt.'],
            ['username', 'string', 'min' => 2, 'max' => 50],
            
            // Az e-mail formátumának ellenőrzése és egyedisége
            ['email', 'email', 'message' => 'Nem érvényes e-mail cím.'],
            ['email', 'unique', 'targetClass' => '\app\models\User', 'message' => 'Ezzel az e-mail címmel már regisztráltak.'],
            
            // Jelszó minimum hossza
            ['password', 'string', 'min' => 4],
            
            // Megnézzük, hogy a 'Jelszó megerősítése' pontosan megegyezik-e a jelszóval
            ['password_repeat', 'compare', 'compareAttribute' => 'password', 'message' => 'A két jelszó nem egyezik meg.'],
        ];
    }

    /**
     * Mezők emberi feliratai (ezek jelennek meg a beviteli mezők felett)
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Felhasználónév',
            'email' => 'E-mail cím',
            'password' => 'Jelszó',
            'password_repeat' => 'Jelszó megerősítése',
        ];
    }

    /**
     * A felhasználó tényleges mentése az adatbázisba
     */
    public function signup()
    {
        if (!$this->validate()) {
            Yii::error($this->getErrors(), 'signup_debug');
            return null;
        }

        $db = Yii::$app->db;

        try {
            $success = $db->createCommand()->insert('user', [
                'username'        => $this->username,
                'email'           => $this->email,
                'password_hash'   => Yii::$app->security->generatePasswordHash($this->password),
                'auth_key'        => Yii::$app->security->generateRandomString(),
                'auth_key_expire' => time() + 3600 * 24 * 30,
                'status'          => 10,
                'created_at'      => time(),
                'updated_at'      => time(),
            ])->execute();

            Yii::error(['insert_success' => $success], 'signup_debug'); // ← ÚJ
        } catch (\Exception $e) {
            Yii::error(['insert_exception' => $e->getMessage()], 'signup_debug'); // ← ÚJ
            return null;
        }

        if ($success) {
            $user = User::findByUsername($this->username);
            Yii::error(['found_user' => $user ? $user->id : 'NULL'], 'signup_debug'); // ← ÚJ
            return $user;
        }

        return null;
    }
}