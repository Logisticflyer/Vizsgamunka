<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\User;

class UpdateProfileForm extends Model
{
    public $username;
    public $email;
    public $password;

    private $_user;

    public function __construct($user, $config = [])
    {
        $this->_user = $user;
        $this->username = $user->username;
        $this->email = $user->email;
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['username', 'email'], 'required', 'message' => 'Ezt a mezőt kötelező kitölteni.'],
            ['email', 'email', 'message' => 'Érvénytelen e-mail formátum.'],
            
            // Egyediség ellenőrzése, kivéve a saját jelenlegi adatokat
            ['username', 'unique', 'targetClass' => '\app\models\User', 'filter' => ['!=', 'id', $this->_user->id], 'message' => 'Ez a felhasználónév már foglalt.'],
            ['email', 'unique', 'targetClass' => '\app\models\User', 'filter' => ['!=', 'id', $this->_user->id], 'message' => 'Ez az e-mail cím már használatban van.'],
            
            ['password', 'string', 'min' => 6, 'message' => 'A jelszónak legalább 6 karakterből kell állnia.'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Felhasználónév',
            'email' => 'E-mail cím',
            'password' => 'Új jelszó (hagyd üresen, ha nem akarod megváltoztatni)',
        ];
    }

    public function updateProfile()
    {
        if ($this->validate()) {
            $user = $this->_user;
            $user->username = $this->username;
            $user->email = $this->email;
            
            if (!empty($this->password)) {
                $user->setPassword($this->password);
                $user->generateAuthKey(); // Új jelszó esetén érdemes az auth key-t is frissíteni
            }
            
            return $user->save(false);
        }
        return false;
    }
}