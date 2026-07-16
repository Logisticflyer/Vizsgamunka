<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\base\InvalidArgumentException;
use app\models\User;

class ResetPasswordForm extends Model
{
    public $password;
    public $password_repeat;

    private $_user;

    public function __construct($token, $config = [])
    {
        if (empty($token) || !is_string($token)) {
            throw new InvalidArgumentException('A token hiányzik vagy érvénytelen.');
        }

        // 1. EXTRA: Token lejárat ellenőrzése (1 óra = 3600 másodperc)
        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = 3600;
        if ($timestamp + $expire < time()) {
            throw new InvalidArgumentException('Ez a jelszó-visszaállító link lejárt! Kérlek, igényelj biztonsági okokból egy újat.');
        }
        
        // Megkeressük a usert a linkben lévő token alapján
        $this->_user = User::findOne(['password_reset_token' => $token]);

        if (!$this->_user) {
            throw new InvalidArgumentException('Ez a jelszó-visszaállító link érvénytelen, vagy már felhasználták.');
        }
        
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['password', 'password_repeat'], 'required', 'message' => 'A mező kitöltése kötelező.'],
            ['password', 'string', 'min' => 8, 'tooShort' => 'A jelszónak legalább 8 karakternek kell lennie.'],
            ['password_repeat', 'compare', 'compareAttribute' => 'password', 'message' => 'A két jelszó nem egyezik meg!'],
        ];
    }

    public function resetPassword()
    {
        $user = $this->_user;
        
        // 1. Új jelszó hash generálása
        $user->password_hash = Yii::$app->security->generatePasswordHash($this->password);
        
        // 2. Token törlése (hogy ne lehessen újra felhasználni ugyanazt a linket)
        $user->password_reset_token = null;
        
        // 3. Biztonsági frissítés
        $user->auth_key = Yii::$app->security->generateRandomString();

        return $user->save(false);
    }
}