<?php

namespace app\models;

use yii\base\Model;
use yii\base\InvalidArgumentException;
use app\models\User;

class ResetPasswordForm extends Model
{
    public $password;

    /**
     * @var \app\models\User
     */
    private $_user;

    public function __construct($token, $config = [])
    {
        if (empty($token) || !is_string($token)) {
            throw new InvalidArgumentException('Hiányzó vagy érvénytelen token.');
        }
        $this->_user = User::findByPasswordResetToken($token);
        if (!$this->_user) {
            throw new InvalidArgumentException('A jelszó-visszaállító link érvénytelen vagy már lejárt.');
        }
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            ['password', 'required', 'message' => 'A jelszó megadása kötelező.'],
            ['password', 'string', 'min' => 8, 'tooShort' => 'A jelszónak legalább 8 karakterből kell állnia.'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'password' => 'Új jelszó',
        ];
    }

    public function resetPassword()
    {
        $user = $this->_user;
        
        // Jelszó hash generálása
        $user->setPassword($this->password);
        
        // Token törlése, hogy többször ne lehessen felhasználni
        $user->removePasswordResetToken();
        
        if ($user->hasAttribute('updated_at')) {
            $user->updated_at = time();
        }

        // save(false) használata meggátolja, hogy egyéb szabályok blokkolják a mentést
        return $user->save(false);
    }
}