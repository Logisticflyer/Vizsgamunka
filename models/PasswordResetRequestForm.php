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
        ];
    }

    public function sendEmail()
    {
        // 1. BIZTONSÁG: Rate Limiting (Spam és Bot védelem IP cím alapján)
        $ip = Yii::$app->request->userIP;
        $cacheKey = 'password_reset_limit_' . $ip;
        $attempts = Yii::$app->cache->get($cacheKey);

        if ($attempts !== false && $attempts >= 3) {
            // Ha már 3-szor próbálkozott, visszadobjuk a formhoz egy hibaüzenettel
            $this->addError('email', 'Túl sok próbálkozás! Biztonsági okokból kérlek, várj 5 percet.');
            return false;
        }

        // Növeljük a próbálkozások számát a gyorsítótárban (300 másodperc = 5 perc lejárattal)
        Yii::$app->cache->set($cacheKey, ($attempts !== false ? $attempts + 1 : 1), 300);


        // 2. BIZTONSÁG: E-mail Enumeration védelem (Felsorolásos támadás ellen)
        $user = User::findOne(['email' => $this->email]);

        if (!$user) {
            // Nem áruljuk el a támadónak, hogy nincs ilyen e-mail, "sikert" színlelünk
            return true; 
        }


        // 3. BIZTONSÁG: Adatbázis Tranzakció (Atomicitás - Minden vagy semmi elv)
        $transaction = Yii::$app->db->beginTransaction();
        
        try {
            $user->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();

            if (!$user->save(false)) {
                $transaction->rollBack();
                return false;
            }

            $resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);

            $mailSent = Yii::$app->mailer->compose(['html' => 'passwordResetToken-html'], ['resetLink' => $resetLink])
                ->setFrom(['noreply@vizsgamunka.hu' => 'Vizsgamunka Rendszer'])
                ->setTo($this->email)
                ->setSubject('Jelszó visszaállítása - Vizsgamunka')
                ->send();

            if ($mailSent) {
                // Csak akkor mentjük el véglegesen a tokent, ha az e-mail sikeresen el is ment
                $transaction->commit();
                return true;
            } else {
                $transaction->rollBack();
                return false;
            }
            
        } catch (\Exception $e) {
            // Váratlan szerverhiba esetén mindent visszavonunk
            $transaction->rollBack();
            return false;
        }
    }
}