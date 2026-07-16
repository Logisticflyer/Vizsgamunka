<?php
use yii\helpers\Html;

/** @var string $resetLink */
?>
<div style="background-color: #ffffff; padding: 40px; border-radius: 16px;">
    <h3>Szia!</h3>
    <p>Kaptunk egy kérést a fiókod jelszavának visszaállítására.</p>
    <p>Kattints az alábbi gombra az új jelszó beállításához:</p>
    
    <div style="text-align: center; margin: 30px 0;">
        <?= Html::a('Új Jelszó Beállítása', $resetLink, [
            'style' => 'background-color: #0071e3; color: #ffffff; padding: 15px 25px; text-decoration: none; border-radius: 10px; font-weight: bold;'
        ]) ?>
    </div>
    <p style="font-size: 12px; color: #888;">Ha nem te kérted, hagyd figyelmen kívül ezt a levelet.</p>
</div>