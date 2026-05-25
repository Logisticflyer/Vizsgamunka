<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "Ajanlatkeres".
 *
 * @property int $id
 * @property string $nev
 * @property string $email
 * @property string $targy
 * @property string $leiras
 * @property string|null $tipus
 * @property string|null $letrehozva_at
 */
class Ajanlatkeres extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    // Csak létrehozáskor akarjuk beállítani, frissítéskor ne bántsa
                    ActiveRecord::EVENT_BEFORE_INSERT => ['letrehozva_at'],
                ],
                // Előírjuk, hogy ne Unix timestamp-et (pl. 171612 Hungarian time), hanem rendes MySQL DATETIME formátumot mentsen
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ajanlatkeres';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tipus'], 'default', 'value' => null],
            [['nev', 'email', 'targy', 'leiras'], 'required'],
            [['leiras'], 'string'],
            [['letrehozva_at'], 'safe'],
            [['nev', 'email', 'targy'], 'string', 'max' => 255],
            [['tipus'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
       return [
        'id' => 'ID',
        'nev' => 'Ügyfél Neve',
        'email' => 'E-mail Cím',
        'targy' => 'Projekt Tárgya',
        'leiras' => 'Részletes Leírás',
        'tipus' => 'Szolgáltatás Típusa',
        'letrehozva_at' => 'Beküldés Ideje',
       ];
    }

    /**
     * {@inheritdoc}
     * @return AjanlatkeresQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AjanlatkeresQuery(get_called_class());
    }

}
