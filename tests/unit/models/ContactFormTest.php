<?php

namespace tests\unit\models;

use app\models\ContactForm;
use yii\mail\MessageInterface;

class ContactFormTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    public $tester;

    public function testEmailIsSentOnContact()
    {
        // 1. Mockoljuk a ContactForm-ot, hogy a validate() metódus Session/Captcha hiányában is true-val térjen vissza
        $model = $this->getMockBuilder(ContactForm::class)
            ->onlyMethods(['validate'])
            ->getMock();

        $model->expects($this->once())
            ->method('validate')
            ->willReturn(true);

        $model->attributes = [
            'name' => 'Tester',
            'email' => 'tester@example.com',
            'subject' => 'very important letter subject',
            'body' => 'body of current message',
            'verifyCode' => 'testme',
        ];

        verify($model->contact('admin@example.com'))->notEmpty();

        // 2. Levél elküldésének ellenőrzése a Codeception modul segítségével
        $this->tester->seeEmailIsSent();

        /** @var MessageInterface $emailMessage */
        $emailMessage = $this->tester->grabLastSentEmail();
        verify($emailMessage)->instanceOf('yii\mail\MessageInterface');

        // Címzett ellenőrzése (admin@example.com)
        verify($emailMessage->getTo())->arrayHasKey('admin@example.com');

        // Feladó ellenőrzése: A ContactForm.php-ban setFrom([$this->email => $this->name]) szerepel
        verify($emailMessage->getFrom())->arrayHasKey('tester@example.com');

        // Tárgy és üzenettörzs ellenőrzése
        verify($emailMessage->getSubject())->equals('very important letter subject');
        verify($emailMessage->toString())->stringContainsString('body of current message');
    }
}