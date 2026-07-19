<?php

namespace app\models;

use yii\db\ActiveRecord;
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
class User extends \yii\db\ActiveRecord implements IdentityInterface 
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

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
            [['status'], 'default', 'value' => self::STATUS_ACTIVE],
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

    // =========================================================================
    // IDENTITY INTERFACE METÓDUSOK
    // =========================================================================

    /**
     * Felhasználó keresése ID alapján. 
     * Csak az aktív (status = 10) felhasználókat engedjük be!
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Token alapú keresés
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
     * Visszaadja az automatikus beléptetéshez (Remember Me) használt kulcsot
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Ellenőrzi az Auth Key-t
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    // =========================================================================
    // EGYÉB BIZTONSÁGI ÉS KERESŐ METÓDUSOK
    // =========================================================================

    /**
     * Felhasználó keresése felhasználónév vagy email alapján
     */
    public static function findByUsername($username)
    {
        return static::find()
            ->where(['username' => $username, 'status' => self::STATUS_ACTIVE])
            ->orWhere(['email' => $username, 'status' => self::STATUS_ACTIVE])
            ->one();
    }

    /**
     * Jelszó ellenőrzése
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Új jelszó hash generálása (Ez hiányzott a profil szerkesztéshez!)
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Auth Key generálása (Ez hiányzott a regisztrációhoz és a jelszócseréhez!)
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Jelszó visszaállító token generálása
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Jelszó visszaállító token törlése
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
}