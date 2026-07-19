<?php

/** @var yii\web\View $this */
/** @var app\models\LoginForm $model */
/** @var app\models\SignupForm $signupModel */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Saját Vizsgamunka - Belépés';

// Megnézzük, hogy a regisztrációs modellben van-e validációs hiba
$hasSignupError = $signupModel->hasErrors();
?>

<div class="hero-container d-flex align-items-center">
    <div class="container">
        <div class="row align-items-center g-5">
            
            <div class="col-lg-6 text-center text-lg-start pe-lg-5">
                <h1 class="hero-title">Digitális Precizitás <br><span class="text-primary">& Automatizáció</span></h1>
                <p class="hero-subtitle mt-4">
                    Üdvözlünk a platformon. Optimalizáld a munkafolyamataidat fejlett AI asszisztensekkel vagy egyedi 3D tervezéssel egyetlen, letisztult felületen.
                </p>
                
                <div class="hero-graphic mt-5">
                    <svg viewBox="0 0 300 120" class="w-100" style="max-width: 320px;">
                        <defs>
                            <linearGradient id="lineGrad" x1="0%" y1="0%" x2="100%" y2="0%">
                                <stop offset="0%" style="stop-color:#0071e3;stop-opacity:1" />
                                <stop offset="100%" style="stop-color:#5e5ce6;stop-opacity:1" />
                            </linearGradient>
                        </defs>
                        <path d="M0,60 Q75,10 150,60 T300,60" stroke="url(#lineGrad)" stroke-width="8" fill="none" stroke-linecap="round"/>
                        <circle cx="0" cy="60" r="8" fill="#0071e3"/>
                        <circle cx="150" cy="60" r="8" fill="#5e5ce6"/>
                        <circle cx="300" cy="60" r="8" fill="#0071e3"/>
                    </svg>
                    <p class="text-muted mt-3 fw-light small" style="letter-spacing: 0.5px;">ADATVEZÉRELT AUTOMATIZÁCIÓS FOLYAMAT</p>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="auth-card shadow-sm">
                    
                    <?php if (Yii::$app->session->hasFlash('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert" style="border-radius: 12px; font-size: 0.9rem; background-color: #fff0f0; color: #ff3b30; border: 1px solid #ffcaca; padding: 16px; margin-bottom: 20px;">
                            <div class="flex-grow-1">
                                <strong>Hoppá!</strong> <?= Yii::$app->session->getFlash('error') ?>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="font-size: 0.8rem;"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (Yii::$app->session->hasFlash('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert" style="border-radius: 12px; font-size: 0.9rem; background-color: #f0fff4; color: #28a745; border: 1px solid #c3e6cb; padding: 16px; margin-bottom: 20px;">
                            <div class="flex-grow-1">
                                <strong>Szuper!</strong> <?= Yii::$app->session->getFlash('success') ?>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="font-size: 0.8rem;"></button>
                        </div>
                    <?php endif; ?>

                    <ul class="nav nav-pills nav-fill mb-4 custom-tabs" id="authTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link <?= $hasSignupError ? '' : 'active' ?> fw-bold" id="login-tab" data-bs-toggle="tab" data-bs-target="#login-pane" type="button" role="tab">Bejelentkezés</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link <?= $hasSignupError ? 'active' : '' ?> fw-bold" id="signup-tab" data-bs-toggle="tab" data-bs-target="#signup-pane" type="button" role="tab">Regisztráció</button>
                        </li>
                    </ul>

                    <div class="tab-content" id="authTabContent">
                        <div class="tab-pane fade <?= $hasSignupError ? '' : 'show active' ?>" id="login-pane" role="tabpanel">
                            <?php $form = ActiveForm::begin(['id' => 'login-form', 'class' => 'modern-form']); ?>
                                <?= $form->field($model, 'username')->textInput(['autofocus' => true, 'class' => 'form-control custom-input', 'placeholder' => 'Felhasználónév'])->label('Felhasználónév') ?>
                                <?= $form->field($model, 'password')->passwordInput(['class' => 'form-control custom-input', 'placeholder' => '••••••••'])->label('Jelszó') ?>
                                <?= $form->field($model, 'rememberMe')->checkbox(['class' => 'form-check-input'])->label('Emlékezz rám') ?>
                                <div class="text-end mb-3 mt-2">
                                    <a href="<?= \yii\helpers\Url::to(['site/request-password-reset']) ?>" class="text-decoration-none fw-semibold" style="color: #0071e3; font-size: 0.9rem;">
                                        Elfelejtetted a jelszavad?
                                    </a>
                                </div>
                                <div class="d-grid mt-4">
                                    <?= Html::submitButton('Belépés <i class="fas fa-arrow-right ms-2"></i>', ['class' => 'btn btn-primary btn-lg custom-btn', 'name' => 'login-button', 'value' => 'login-button']) ?>
                                </div>
                            <?php ActiveForm::end(); ?>
                        </div>

                        <div class="tab-pane fade <?= $hasSignupError ? 'show active' : '' ?>" id="signup-pane" role="tabpanel">
                            <?php $form = ActiveForm::begin([
                                'id' => 'signup-form', 
                                'class' => 'modern-form',
                                'fieldConfig' => [
                                    'template' => "{label}\n{input}\n{error}",
                                ]
                            ]); ?>
                                <?= $form->field($signupModel, 'username')->textInput(['class' => 'form-control custom-input', 'placeholder' => 'Válassz egy nevet'])->label('Felhasználónév') ?>
                                <?= $form->field($signupModel, 'email')->input('email', ['class' => 'form-control custom-input', 'placeholder' => 'pelda@email.com'])->label('Email cím') ?>
                                <?= $form->field($signupModel, 'password')->passwordInput(['class' => 'form-control custom-input', 'placeholder' => 'Legalább 8 karakter'])->label('Jelszó') ?>
                                <?= $form->field($signupModel, 'password_repeat')->passwordInput(['class' => 'form-control custom-input', 'placeholder' => 'Ismételd meg a jelszót'])->label('Jelszó megerősítése') ?>
                                <div class="d-grid mt-5">
                                    <?= Html::submitButton('Fiók Létrehozása', ['class' => 'btn btn-dark btn-lg custom-btn', 'name' => 'signup-button', 'value' => 'signup-button']) ?>
                                </div>
                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.hero-container { min-height: calc(100vh - 140px); background-color: #f5f5f7; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; padding: 40px 0; }
.hero-title { font-size: 3.5rem; font-weight: 800; letter-spacing: -1.5px; color: #1d1d1f; line-height: 1.1; }
.text-primary { color: #0071e3 !important; }
.hero-subtitle { font-size: 1.25rem; color: #86868b; line-height: 1.6; max-width: 500px; }
.hero-graphic { display: flex; flex-direction: column; align-items: flex-start; margin-top: 2rem; }
.auth-card { background: #ffffff; border-radius: 24px; padding: 40px; border: 1px solid #e5e5e7; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.04) !important; }
.custom-tabs { background-color: #f5f5f7; border-radius: 12px; padding: 6px; }
.custom-tabs .nav-link { border-radius: 8px; color: #86868b; border: none; transition: 0.3s; }
.custom-tabs .nav-link.active { background-color: #ffffff; color: #1d1d1f; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
.custom-input { border-radius: 12px; border: 1px solid #d2d2d7; padding: 14px 16px; margin-bottom: 0.5rem; }
.custom-btn { border-radius: 12px; padding: 14px; font-weight: 600; background-color: #0071e3; border: none; }
.invalid-feedback { display: block !important; color: #ff3b30 !important; font-size: 0.8rem !important; margin-bottom: 1rem !important; margin-top: -0.5rem !important; }
@media (max-width: 991px) { .hero-title { font-size: 2.5rem; } .hero-graphic { align-items: center; margin-bottom: 30px; } }
</style>

<?php
$script = <<< JS
document.addEventListener("DOMContentLoaded", function() {
    let hash = window.location.hash; // Megnézzük a link végét (pl. #register)
    
    if (hash) {
        // Megkeressük a Bootstrap tab gombot, ami a hash-re mutat
        let tabBtn = document.querySelector('button[data-bs-target="' + hash + '"]');
        
        // Ha az azonosítók nem data-bs-target alapján vannak, hanem ID alapján (pl. id="register-tab")
        if (!tabBtn) {
            tabBtn = document.querySelector(hash + '-tab');
        }

        // Ha megtaláltuk a megfelelő gombot, kattintás nélkül "aktiváljuk"
        if (tabBtn) {
            let tab = new bootstrap.Tab(tabBtn);
            tab.show();
        }
    }
});
JS;

$this->registerJs($script);
?>