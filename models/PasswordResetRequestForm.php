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
            ['email', 'exist',
                'targetClass' => '\app\models\User',
                'message' => 'Nincs ilyen e-mail címmel regisztrált felhasználó a rendszerben.'
            ],
        ];
    }

    public function sendEmail()
    {
        $user = User::findOne(['email' => $this->email]);

        if (!$user) {
            return false;
        }

        $user->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();

        if (!$user->save(false)) {
            return false;
        }

        $resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);

        // 2. EXTRA: Profi HTML E-mail sablon gombbal
        $htmlBody = "
        <div style='font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, sans-serif; max-width: 600px; margin: 0 auto; border: 1px solid #e5e5e7; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 20px rgba(0,0,0,0.05);'>
            <div style='background-color: #0071e3; padding: 30px; text-align: center; color: white;'>
                <h2 style='margin: 0; font-size: 24px;'>Jelszó Visszaállítása</h2>
            </div>
            <div style='padding: 40px; background-color: #ffffff; color: #1d1d1f;'>
                <p style='font-size: 16px;'><strong>Szia!</strong></p>
                <p style='font-size: 16px; line-height: 1.6; color: #515154;'>Kaptunk egy kérést a fiókod jelszavának visszaállítására. Ez a link biztonsági okokból <strong>1 óráig</strong> érvényes.</p>
                <div style='text-align: center; margin: 40px 0;'>
                    <a href='{$resetLink}' style='background-color: #0071e3; color: #ffffff; padding: 16px 32px; text-decoration: none; border-radius: 12px; font-weight: bold; font-size: 16px; display: inline-block;'>Új Jelszó Beállítása</a>
                </div>
                <hr style='border: none; border-top: 1px solid #e5e5e7; margin: 30px 0;'>
                <p style='font-size: 12px; color: #86868b; text-align: center;'>Ha nem te kérted a visszaállítást, nyugodtan hagyd figyelmen kívül ezt az e-mailt. A fiókod biztonságban van.</p>
            </div>
        </div>";

        return Yii::$app->mailer->compose(['html' => 'passwordResetToken-html'], ['resetLink' => $resetLink])
    ->setFrom(['noreply@vizsgamunka.hu' => 'Vizsgamunka Rendszer'])
    ->setTo($this->email)
    ->setSubject('Jelszó visszaállítása - Vizsgamunka')
    ->send();

        return Yii::$app->mailer->compose()
            ->setFrom(['noreply@vizsgamunka.hu' => 'Vizsgamunka Rendszer'])
            ->setTo($this->email)
            ->setSubject('Jelszó visszaállítása - Vizsgamunka')
            ->setHtmlBody($htmlBody)
            ->send();
    }
}