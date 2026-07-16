<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\User;

class PasswordResetRequestForm extends Model
{
    public $email;

    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required', 'message' => 'Az e-mail cím megadása kötelező.'],
            ['email', 'email', 'message' => 'Érvénytelen e-mail cím formátum.'],
            // BIZTONSÁG: Direkt kivettük az 'exist' validátort, hogy ne szivárogtassunk ki információt arról, létezik-e a user!
        ];
    }

    public function sendEmail()
    {
        $user = User::findOne(['email' => $this->email]);

        // BIZTONSÁG: Ha nem létezik a user, akkor is "sikert" (true) színlelünk a támadók megtévesztésére, de nem küldünk semmit.
        if (!$user) {
            return true; 
        }

        // BIZTONSÁG: Tranzakció indítása (Minden vagy semmi elv)
        $transaction = Yii::$app->db->beginTransaction();
        
        try {
            // Új token generálása
            $user->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();

            // Ha a mentés sikertelen, visszavonjuk a tranzakciót
            if (!$user->save(false)) {
                $transaction->rollBack();
                return false;
            }

            // Link generálása
            $resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);

            // E-mail küldés megkísérlése
            $mailSent = Yii::$app->mailer->compose(['html' => 'passwordResetToken-html'], ['resetLink' => $resetLink])
                ->setFrom(['noreply@vizsgamunka.hu' => 'Vizsgamunka Rendszer'])
                ->setTo($this->email)
                ->setSubject('Jelszó visszaállítása - Bizsgamunka')
                ->send();

            // Ha az e-mail elment, véglegesítjük az adatbázis módosítást
            if ($mailSent) {
                $transaction->commit();
                return true;
            } else {
                // Ha hiba volt a küldésnél, töröljük a generált tokent (visszavonás)
                $transaction->rollBack();
                return false;
            }
            
        } catch (\Exception $e) {
            // Váratlan szerverhiba esetén is védve vagyunk
            $transaction->rollBack();
            return false;
        }
    }
}