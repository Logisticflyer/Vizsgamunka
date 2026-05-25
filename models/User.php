<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface; 

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string|null $password_reset_token
 * @property string $email
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $auth_key_expire
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface // 
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['password_reset_token'], 'default', 'value' => null],
            [['status'], 'default', 'value' => 10],
            [['username', 'auth_key', 'password_hash', 'email', 'created_at', 'updated_at', 'auth_key_expire'], 'required'],
            [['status', 'created_at', 'updated_at', 'auth_key_expire'], 'integer'],
            [['username', 'auth_key'], 'string', 'max' => 32],
            [['password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['password_reset_token'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'Username'),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'password_hash' => Yii::t('app', 'Password Hash'),
            'password_reset_token' => Yii::t('app', 'Password Reset Token'),
            'email' => Yii::t('app', 'Email'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'auth_key_expire' => Yii::t('app', 'Auth Key Expire'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return UserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserQuery(get_called_class());
    }

    // =========================================================================
    // IDENTITY INTERFACE METÓDUSOK (A biztonságos bejelentkezéshez)
    // =========================================================================

    /**
     * Felhasználó keresése ID alapján (pl. sessionből való visszaállításhoz)
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * Token alapú keresés (REST API-hoz kellene, nálad maradhat null)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    /**
     * Visszaadja a bejelentkezett felhasználó egyedi azonosítóját
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Visszaadja az automatikus beléptetéshez (Remember Me cookie) használt kulcsot
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Ellenőrzi, hogy a cookie-ban lévő auth_key egyezik-e az adatbázisban lévővel
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Felhasználó keresése felhasználónév (vagy email) alapján
     * Ha a login formodon email-t is elfogadsz, az orWhere-es sort is használhatod.
     */
    public static function findByUsername($username)
    {
        return static::find()
        ->where(['username' => $username])
        ->orWhere(['email' => $username])
        ->one();
    }

    /**
     * Jelszó ellenőrzése a biztonságos bcrypt hash alapján
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }
}