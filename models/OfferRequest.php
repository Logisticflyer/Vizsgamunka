<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class OfferRequest extends ActiveRecord
{
    public static function tableName()
    {
        return 'ajanlatkeres';
    }

    public function rules()
    {
        return [
            [['nev', 'email'], 'required'],
            [['leiras'], 'string'],
            [['created_at', 'name', 'message', 'chat_context', 'targy', 'tipus', 'status'], 'safe'],
            [['nev', 'email', 'targy'], 'string', 'max' => 255],
            [['tipus'], 'string', 'max' => 50],
            [['status'], 'string', 'max' => 20],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nev' => 'Név / Cégnév',
            'email' => 'E-mail cím',
            'targy' => 'Tárgy / Projekt címe',
            'leiras' => 'Projekt leírása / Igény',
            'tipus' => 'Típus (AI / 3D)',
            'status' => 'Státusz',
            'created_at' => 'Beérkezés ideje',
        ];
    }

    /**
     * Dátum automatikus konvertálása MySQL DATETIME kompatibilis formátumra mentés előtt
     */
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if (is_numeric($this->created_at)) {
            $this->created_at = date('Y-m-d H:i:s', (int)$this->created_at);
        } elseif (empty($this->created_at)) {
            $this->created_at = date('Y-m-d H:i:s');
        }

        return true;
    }

    // --- ALIAS MEGOLDÁSOK A CONTROLLER KOMPATIBILITÁSHOZ ---

    public function getName()
    {
        return $this->nev;
    }

    public function setName($value)
    {
        $this->nev = $value;
    }

    public function getMessage()
    {
        return $this->leiras;
    }

    public function setMessage($value)
    {
        $this->leiras = $value;
    }

    public function getChat_context()
    {
        return $this->leiras;
    }

    public function setChat_context($value)
    {
        if (!empty($value)) {
            $this->leiras = trim($this->leiras . "\n\n--- Chat Előzmény ---\n" . $value);
        }
    }
}