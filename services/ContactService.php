<?php

namespace app\services;

use app\models\ContactForm;
use Yii;

/**
 * Class ContactService
 *
 * Felelősség: A kapcsolatfelvételi e-mailek küldésének és az ahhoz kapcsolódó
 * üzleti folyamatoknak a lebonyolítása az SRP elv alapján.
 */
class ContactService
{
    /**
     * Kapcsolatfelvételi e-mail küldésének üzleti folyamata.
     *
     * @param ContactForm $form A kitöltött és validált űrlapmodell
     * @param string $adminEmail A cél e-mail cím, ahová a levelet küldjük
     * @return bool Igaz, ha a levélküldés sikeres volt
     */
    public function sendContactEmail(ContactForm $form, string $adminEmail): bool
    {
        if (!$form->validate()) {
            return false;
        }

        $sent = Yii::$app->mailer->compose()
            ->setTo($adminEmail)
            ->setFrom([$form->email => $form->name])
            ->setReplyTo([$form->email => $form->name])
            ->setSubject($form->subject)
            ->setTextBody($form->body)
            ->send();

        if ($sent && Yii::$app->has('session')) {
            Yii::$app->session->setFlash('contactFormSubmitted');
        }

        return $sent;
    }
}