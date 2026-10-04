<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface; 

/**
 * Model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string|null $password_reset_token
 * @property string|null $access_token
 * @property string $email
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $auth_key_expire
 */
class User extends ActiveRecord implements IdentityInterface 
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    /**
     * Virtuális tulajdonság a profil űrlapon történő jelszóváltáshoz
     */
    public $password;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * Getter a password mezőhöz az ActiveForm hiba megelőzésére
     */
    public function getPassword()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'email'], 'required'],
            [['password_reset_token', 'access_token'], 'default', 'value' => null],
            [['status'], 'default', 'value' => self::STATUS_ACTIVE],
            [['status', 'created_at', 'updated_at', 'auth_key_expire'], 'integer'],
            [['username', 'auth_key'], 'string', 'max' => 32],
            [['password_hash', 'password_reset_token', 'access_token', 'email'], 'string', 'max' => 255],
            [['password'], 'string', 'min' => 6],
            [['email'], 'email'],
            [['username'], 'unique', 'when' => function($model) { return $model->isAttributeChanged('username'); }],
            [['email'], 'unique', 'when' => function($model) { return $model->isAttributeChanged('email'); }],
            [['password_reset_token'], 'unique'],
        ];
    }

    /**
     * Új jelszó hash-elése és automatikus időbélyeg frissítés mentés előtt
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                if (empty($this->auth_key)) {
                    $this->generateAuthKey();
                }
                if (empty($this->created_at)) {
                    $this->created_at = time();
                }
            }
            
            $this->updated_at = time();

            if (!empty($this->password)) {
                $this->setPassword($this->password);
            }
            return true;
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'Felhasználónév'),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'password_hash' => Yii::t('app', 'Password Hash'),
            'password_reset_token' => Yii::t('app', 'Password Reset Token'),
            'access_token' => Yii::t('app', 'Access Token'),
            'email' => Yii::t('app', 'E-mail cím'),
            'status' => Yii::t('app', 'Státusz'),
            'created_at' => Yii::t('app', 'Létrehozva'),
            'updated_at' => Yii::t('app', 'Módosítva'),
            'auth_key_expire' => Yii::t('app', 'Auth Key Lejárat'),
        ];
    }

    // =========================================================================
    // IDENTITY INTERFACE METÓDUSOK
    // =========================================================================

    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token, 'status' => self::STATUS_ACTIVE]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    // =========================================================================
    // BIZTONSÁGI ÉS KERESŐ METÓDUSOK
    // =========================================================================

    /**
     * Felhasználó keresése felhasználónév vagy email alapján (Precíz SQL zárójelezéssel)
     */
    public static function findByUsername($username)
    {
        return static::find()
            ->where(['or', ['username' => $username], ['email' => $username]])
            ->andWhere(['status' => self::STATUS_ACTIVE])
            ->one();
    }

    public static function findByPasswordResetToken($token)
    {
        if (empty($token) || !is_string($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
}