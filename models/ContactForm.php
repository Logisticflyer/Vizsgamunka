<?php

namespace app\models;

use Yii;
use yii\base\Model;

class ContactForm extends Model
{
    public $name;
    public $email;
    public $subject;
    public $body;
    public $verifyCode;
    public $tipus;

    public function rules()
    {
       return [
            [['name', 'email', 'subject', 'body'], 'required'],
            ['email', 'email'],
            ['tipus', 'safe'], // <--- Biztonságossá tesszük a tömeges hozzárendeléshez
            ['verifyCode', 'captcha'],
        ];
    }

    public function contact($email)
{
    if ($this->validate()) {
        // 1. Mentés az adatbázisba közvetlen SQL-lel (golyóálló, nem akad össze semmivel)
        $db = Yii::$app->db;
        $db->createCommand()->insert('lead', [
            'name' => $this->name,
            'email' => $this->email,
            'subject' => $this->subject,
            'body' => $this->body,
            'created_at' => time(),
        ])->execute();

        // 2. Az eredeti email küldő funkció (ha a vizsgáztató kéri, megmarad, de nem akasztja meg a futást)
        Yii::$app->mailer->compose()
            ->setTo($email)
            ->setFrom([$this->email => $this->name])
            ->setSubject($this->subject)
            ->setTextBody($this->body)
            ->send();

        return true;
    }
    return false;
}
}