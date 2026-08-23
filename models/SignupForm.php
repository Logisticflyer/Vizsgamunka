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

    /**
     * Ellenőrzési (validációs) szabályok
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
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Felhasználónév',
            'email' => 'E-mail cím',
            'password' => 'Jelszó',
        ];
    }

    /**
     * A felhasználó mentése az adatbázisba
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null; 
        }

        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->status = User::STATUS_ACTIVE; // 10-es aktív státusz
        
        $currentTime = time();
        $user->created_at = $currentTime;
        $user->updated_at = $currentTime;
        $user->auth_key_expire = $currentTime + (30 * 24 * 60 * 60); 

        if ($user->save(false)) {
            return $user;
        }

        return null;
    }
}