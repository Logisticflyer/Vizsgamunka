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
            [['username', 'email', 'password'], 'required'],
            ['email', 'email'],
            ['email', 'email', 'message' => 'Kérlek, egy érvényes e-mail formátumot adj meg (pl. pelda@gmail.com)!'],
            ['email', 'unique', 'targetClass' => '\app\models\User', 'message' => 'Ezzel az e-mail címmel már regisztráltak.'],
            ['password', 'string', 'min' => 6],
            ['password_repeat', 'compare', 'compareAttribute' => 'password', 'message' => 'A jelszavak nem egyeznek.'],
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
        // 1. Validáció: Ha a mezők nem érvényesek, a kód itt VÉGLEG megáll.
        if (!$this->validate()) {
            return false; 
        }

        // 2. HA idáig eljutottunk, a validáció biztosan sikeres volt.
        $db = Yii::$app->db;

        try {
            // Biztonságos adatbázis beszúrás
            $success = $db->createCommand()->insert('user', [
                'username'        => $this->username,
                'email'           => $this->email,
                'password_hash'   => Yii::$app->security->generatePasswordHash($this->password),
                'auth_key'        => Yii::$app->security->generateRandomString(),
                'status'          => 10,
                'created_at'      => time(),
                'updated_at'      => time(),
            ])->execute();

            return $success > 0;

        } catch (\Exception $e) {
            // Ha adatbázis hiba történik
            return false;
        }
    }
}