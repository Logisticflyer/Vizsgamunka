<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm - A bejelentkezési űrlap modellje.
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user = false;

    /**
     * Validációs szabályok.
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Mezőcímkék fordítása.
     */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('app', 'Felhasználónév'),
            'password' => Yii::t('app', 'Jelszó'),
            'rememberMe' => Yii::t('app', 'Megjegyzés'),
        ];
    }

    /**
     * Jelszó ellenőrző egyedi validátor.
     * Biztonságos null-check vizsgálattal a PHP 8.x kompatibilitásért.
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, Yii::t('app', 'Helytelen felhasználónév vagy jelszó.'));
            }
        }
    }

    /**
     * Felhasználó bejelentkeztetése a munkamenetbe.
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }

        return false;
    }

    /**
     * Felhasználó lekérdezése az adatbázisból felhasználónév vagy email alapján.
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }
}