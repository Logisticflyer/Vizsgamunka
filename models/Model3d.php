<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Ez a Model3d osztály az adatbázis 'model_3d' tábláját képviseli.
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $title
 * @property string|null $description
 * @property string $file_path
 * @property string $file_type
 * @property int|null $file_size
 * @property string $created_at
 * @property string|null $updated_at
 */
class Model3d extends ActiveRecord
{
    public static function tableName()
    {
        return 'model_3d';
    }

    public function rules()
    {
        return [
            [['title', 'file_path'], 'required', 'message' => 'A {attribute} kitöltése kötelező.'],
            [['user_id', 'file_size'], 'integer'],
            [['description'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['title', 'file_path'], 'string', 'max' => 255],
            [['file_type'], 'string', 'max' => 50],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Felhasználó',
            'title' => 'Modell Neve',
            'description' => 'Leírás',
            'file_path' => 'Fájl útvonala',
            'file_type' => 'Típus',
            'file_size' => 'Méret (byte)',
            'created_at' => 'Létrehozva',
            'updated_at' => 'Módosítva',
        ];
    }

    /**
     * Kapcsolat a User modellhez
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}