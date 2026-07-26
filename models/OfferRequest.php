<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class OfferRequest extends ActiveRecord
{
    public static function tableName()
    {
        return 'offer_requests';
    }

    public function rules()
    {
        return [
            [['name', 'email', 'message'], 'required'],
            [['message', 'chat_context'], 'string'],
            [['created_at'], 'safe'],
            [['name', 'email'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Név / Cégnév',
            'email' => 'E-mail cím',
            'message' => 'Projekt leírása / Igény',
            'chat_context' => 'Chat Előzmény',
            'created_at' => 'Beérkezés ideje',
        ];
    }
}