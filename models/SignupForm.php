<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\User;

class SignupForm extends Model
{
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
            [['username', 'email', 'password', 'password_repeat'], 'required', 'message' => 'Ezt a mezőt kötelező kitölteni.'],
            ['email', 'email', 'message' => 'Kérlek, egy érvényes e-mail formátumot adj meg!'],
            
            // Egyediség ellenőrzése (hogy ne legyen adatbázis ütközés)
            ['email', 'unique', 'targetClass' => '\app\models\User', 'message' => 'Ezzel az e-mail címmel már regisztráltak.'],
            ['username', 'unique', 'targetClass' => '\app\models\User', 'message' => 'Ez a felhasználónév már foglalt.'],
            
            ['password', 'string', 'min' => 6, 'message' => 'A jelszónak legalább 6 karakterből kell állnia.'],
            ['password_repeat', 'compare', 'compareAttribute' => 'password', 'message' => 'A jelszavak nem egyeznek.'],
        ];
    }

    /**
     * Mezők emberi feliratai
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
     * A felhasználó tényleges mentése az adatbázisba az ActiveRecord használatával
     */
    public function signup()
    {
        // 1. Validáció
        if (!$this->validate()) {
            return null; 
        }

        // 2. Új felhasználó objektum létrehozása
        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        
        // 3. Biztonsági adatok generálása a User modell metódusaival
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->status = User::STATUS_ACTIVE; // Ez alapértelmezetten a 10-es érték
        
        // 4. Időbélyegek és kötelező adatok kitöltése
        $currentTime = time();
        $user->created_at = $currentTime;
        $user->updated_at = $currentTime;
        
        // FONTOS: Ez hiányzott eddig, emiatt omlott össze az adatbázisod! (30 napos lejárat)
        $user->auth_key_expire = $currentTime + (30 * 24 * 60 * 60); 

        // 5. Mentés az adatbázisba
        if ($user->save(false)) { // A false kikapcsolja a dupla validációt, mivel feljebb már megtettük
            return $user; // SIKER: Visszaadjuk az objektumot, hogy a rendszer automatikusan be tudjon léptetni
        }

        return null; // Ha valamiért mégsem sikerült menteni
    }
}