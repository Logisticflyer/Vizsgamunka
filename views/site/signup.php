<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\SignupForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Fiók létrehozása';
?>

<div class="site-contact-container">
    <div class="row justify-content-center">
        <div class="col-xl-6 col-lg-8 col-md-10">
            
            <div class="custom-contact-card">
                
                <div class="contact-card-header text-center mb-4">
                    <h2 class="form-title"><?= Html::encode($this->title) ?></h2>
                    <p class="form-subtitle">Regisztrálj, hogy elmenthesd a projektjeidet és követhesd az ajánlatokat.</p>
                </div>

                <?php $form = ActiveForm::begin([
                    'id' => 'signup-form',
                    'options' => ['class' => 'mt-4']
                ]); ?>

                <?= $form->field($model, 'username')->textInput([
                    'autofocus' => true, 
                    'placeholder' => 'Kívánt felhasználónév',
                    'class' => 'form-control modern-input'
                ]) ?>

                <?= $form->field($model, 'email')->textInput([
                    'placeholder' => 'pelda@email.hu',
                    'class' => 'form-control modern-input'
                ]) ?>

                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'password')->passwordInput([
                            'placeholder' => '••••••••',
                            'class' => 'form-control modern-input'
                        ]) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'password_repeat')->passwordInput([
                            'placeholder' => '••••••••',
                            'class' => 'form-control modern-input'
                        ]) ?>
                    </div>
                </div>

                <div class="form-group text-center mt-4">
                    <?= Html::submitButton('Regisztráció <i class="fas fa-user-plus ms-2"></i>', [
                        'class' => 'btn btn-submit-modern w-100', 
                        'name' => 'signup-button'
                    ]) ?>
                </div>

                <div class="text-center mt-4">
                    <p class="form-subtitle" style="font-size: 13px;">
                        Már van fiókod? <a href="<?= \yii\helpers\Url::to(['site/login']) ?>" style="color: #0071e3; text-decoration: none; font-weight: 500;">Jelentkezz be</a>
                    </p>
                </div>

                <?php ActiveForm::end(); ?>

            </div>

        </div>
    </div>
</div>

<style>
/* Root reset */
.site-contact-container *,
.site-contact-container *::before,
.site-contact-container *::after {
    box-sizing: border-box;
}

.site-contact-container {
    font-family: -apple-system, BlinkMacSystemFont, 'Helvetica Neue', sans-serif;
    padding: 60px 15px;
}

/* Kártya override AdminLTE felett */
.custom-contact-card {
    background: #ffffff !important;
    border-radius: 16px !important;
    padding: 36px 40px !important;
    box-shadow: 0 2px 16px rgba(0,0,0,0.06) !important;
    border: 1px solid #e5e5e7 !important;
}

/* Header */
.form-title {
    font-size: 28px;
    font-weight: 600;
    color: #1d1d1f;
    letter-spacing: -0.4px;
    margin-bottom: 6px;
}
.form-subtitle {
    color: #86868b;
    font-size: 15px;
    margin-bottom: 0;
}

/* Yii2 ActiveForm field wrapper fix a SignupForm mezőire leképelve */
.custom-contact-card .field-signupform-username,
.custom-contact-card .field-signupform-email,
.custom-contact-card .field-signupform-password,
.custom-contact-card .field-signupform-password_repeat {
    display: flex;
    flex-direction: column;
    margin-bottom: 16px;
}

/* Label — felső pozíció */
.custom-contact-card .form-label,
.custom-contact-card label.form-label {
    display: block !important;
    font-size: 12px !important;
    font-weight: 600 !important;
    color: #86868b !important;
    text-transform: uppercase !important;
    letter-spacing: 0.04em !important;
    margin-bottom: 6px !important;
    margin-left: 0 !important;
    order: 0;
}

/* Inputok */
.modern-input {
    display: block !important;
    width: 100% !important;
    background-color: #f5f5f7 !important;
    border: 1px solid #d2d2d7 !important;
    border-radius: 10px !important;
    padding: 10px 14px !important;
    font-size: 14px !important;
    color: #1d1d1f !important;
    transition: border-color 0.15s ease, box-shadow 0.15s ease !important;
    height: auto !important;
    line-height: 1.5 !important;
    order: 1;
}
.modern-input:focus {
    outline: none !important;
    background-color: #ffffff !important;
    border-color: #0071e3 !important;
    box-shadow: 0 0 0 3px rgba(0,113,227,0.15) !important;
}
.modern-input.is-invalid {
    border-color: #e34000 !important;
    box-shadow: none !important;
}
.modern-input.is-invalid:focus {
    box-shadow: 0 0 0 3px rgba(227,64,0,0.15) !important;
}

/* Hibaüzenetek stílusa */
.custom-contact-card .invalid-feedback,
.custom-contact-card .help-block-error,
.custom-contact-card .field-signupform-username .invalid-feedback,
.custom-contact-card .field-signupform-email .invalid-feedback,
.custom-contact-card .field-signupform-password .invalid-feedback,
.custom-contact-card .field-signupform-password_repeat .invalid-feedback {
    display: block !important;
    font-size: 12px !important;
    color: #e34000 !important;
    margin-top: 5px !important;
    padding-left: 2px !important;
    order: 2;
}

/* Submit gomb (Teljes szélességű verzió) */
.btn-submit-modern {
    background: #0071e3 !important;
    color: #ffffff !important;
    border: none !important;
    border-radius: 10px !important;
    padding: 12px 36px !important;
    font-size: 15px !important;
    font-weight: 600 !important;
    cursor: pointer !important;
    transition: background 0.15s ease, transform 0.1s ease, box-shadow 0.15s ease !important;
}
.btn-submit-modern:hover {
    background: #0077ed !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 4px 14px rgba(0,113,227,0.25) !important;
}
.btn-submit-modern:active {
    transform: translateY(0) !important;
}
</style>