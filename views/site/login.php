<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Login';
?>

<div class="site-auth-wrapper min-vh-100 d-flex align-items-center justify-content-center py-5">
    <div class="container" style="max-width: 1100px;">
        <div class="row g-0 rounded-4 overflow-hidden shadow-lg" style="background: #141824; border: 1px solid rgba(255, 255, 255, 0.08) !important;">
            
            <!-- BAL OLDAL: CÍM ÉS BEMUTATÓ PANEL -->
            <div class="col-lg-6 p-5 d-flex flex-column justify-content-between position-relative text-white" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); border-right: 1px solid rgba(255, 255, 255, 0.08);">
                <div>
                    <div class="d-inline-flex align-items-center justify-content-center rounded-3 mb-4 p-3" style="background: rgba(56, 189, 248, 0.12); color: #38bdf8;">
                        <i class="fas fa-robot fa-2x"></i>
                    </div>
                    <h2 class="display-5 fw-extrabold text-white mb-3" style="letter-spacing: -1px; font-weight: 800;">
                        Digitális Precizitás<br>
                        <span style="color: #38bdf8;">& Automatizáció</span>
                    </h2>
                    <p class="lead fs-6" style="line-height: 1.6; color: #94a3b8 !important;">
                        Üdvözlünk a platformon. Optimalizáld a munkafolyamataidat fejlett AI asszisztensekkel vagy egyedi 3D tervezéssel egyetlen, letisztult felületen.
                    </p>
                </div>

                <div class="mt-5 pt-4 border-top border-secondary border-opacity-25">
                    <div class="d-flex align-items-center gap-2 fw-bold small mb-2" style="color: #38bdf8;">
                        <i class="fas fa-wave-square"></i>
                        <span>ADATVEZÉREL T AUTOMATIZÁCIÓS FOLYAMAT</span>
                    </div>
                    <div class="progress" style="height: 6px; background-color: #1e293b;">
                        <div class="progress-bar" role="progressbar" style="width: 65%; background-color: #0071e3; border-radius: 3px;"></div>
                    </div>
                </div>
            </div>

            <!-- JOBB OLDAL: KÁRTYA (BEJELENTKEZÉS & REGISZTRÁCIÓ FÜLEK) -->
            <div class="col-lg-6 p-5 d-flex flex-column justify-content-center" style="background: #141824;">
                
                <!-- FÜL VÁLTÓ GOMBOK -->
                <div class="d-flex p-1 rounded-3 mb-4" style="background: #1e293b; border: 1px solid #334155;">
                    <button type="button" id="tab-login-btn" class="btn rounded-3 w-50 fw-bold py-2 text-white shadow-sm" style="background-color: #0071e3; border: none;" onclick="switchAuthTab('login')">
                        Bejelentkezés
                    </button>
                    <button type="button" id="tab-register-btn" class="btn btn-link rounded-3 w-50 fw-bold text-decoration-none py-2" style="color: #94a3b8 !important;" onclick="switchAuthTab('register')">
                        Regisztráció
                    </button>
                </div>

                <!-- 1. BEJELENTKEZÉSI ŰRLAP -->
                <div id="form-login-container">
                    <h1 class="visually-hidden">Login</h1>
                    <?php $form = ActiveForm::begin([
                        'id' => 'login-form',
                        'fieldConfig' => [
                            'template' => "{label}\n{input}\n{error}",
                            'labelOptions' => ['class' => 'form-label fw-semibold text-white small'],
                            'inputOptions' => ['class' => 'form-control custom-dark-input'],
                            'errorOptions' => ['class' => 'invalid-feedback small fw-bold mt-1'],
                        ],
                    ]); ?>

                        <div class="mb-3">
                            <?= $form->field($model, 'username')->textInput(['placeholder' => 'Felhasználónév', 'autofocus' => true])->label('<i class="fas fa-user text-info me-1"></i> Felhasználónév') ?>
                        </div>

                        <div class="mb-3">
                            <?= $form->field($model, 'password')->passwordInput(['placeholder' => '••••••••'])->label('<i class="fas fa-lock text-warning me-1"></i> Jelszó') ?>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <?= $form->field($model, 'rememberMe')->checkbox([
                                'template' => "<div class=\"form-check custom-dark-check\">{input} {label}</div>",
                                'labelOptions' => ['class' => 'form-check-label small', 'style' => 'color: #cbd5e1;'],
                                'inputOptions' => ['class' => 'form-check-input'],
                            ])->label('Emlékezz rám') ?>

                            <a href="<?= Url::to(['site/request-password-reset']) ?>" class="small text-decoration-none fw-semibold mb-3" style="color: #38bdf8;">Elfelejtetted a jelszavad?</a>
                        </div>

                        <div class="form-group">
                            <?= Html::submitButton('Belépés <i class="fas fa-arrow-right ms-2"></i>', [
                                'class' => 'btn btn-primary w-100 rounded-3 py-2.5 fw-bold shadow-sm',
                                'style' => 'background-color: #0071e3; border: none;',
                                'name' => 'login-button'
                            ]) ?>
                        </div>

                    <?php ActiveForm::end(); ?>
                </div>

                <!-- 2. REGISZTRÁCIÓS ŰRLAP (ALAPÉRTELMEZETTEN REJTVE) -->
                <div id="form-register-container" class="d-none">
                    <?php $form = ActiveForm::begin([
                        'id' => 'signup-form',
                        'action' => Url::to(['site/signup']),
                    ]); ?>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-white small" for="signup-username"><i class="fas fa-user text-info me-1"></i> Felhasználónév</label>
                            <input type="text" id="signup-username" name="SignupForm[username]" class="form-control custom-dark-input" placeholder="Válassz felhasználónevet" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-white small" for="signup-email"><i class="fas fa-envelope text-primary me-1"></i> E-mail cím</label>
                            <input type="email" id="signup-email" name="SignupForm[email]" class="form-control custom-dark-input" placeholder="nev@pelda.hu" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold text-white small" for="signup-password"><i class="fas fa-key text-warning me-1"></i> Jelszó</label>
                            <input type="password" id="signup-password" name="SignupForm[password]" class="form-control custom-dark-input" placeholder="••••••••" required>
                        </div>

                        <div class="form-group">
                            <?= Html::submitButton('Regisztráció véglegesítése <i class="fas fa-user-plus ms-2"></i>', [
                                'class' => 'btn btn-success w-100 rounded-3 py-2.5 fw-bold shadow-sm',
                                'style' => 'background-color: #10b981; border: none;'
                            ]) ?>
                        </div>

                    <?php ActiveForm::end(); ?>
                </div>

            </div>

        </div>
    </div>
</div>

<script>
function switchAuthTab(tab) {
    const loginBtn = document.getElementById('tab-login-btn');
    const registerBtn = document.getElementById('tab-register-btn');
    const loginForm = document.getElementById('form-login-container');
    const registerForm = document.getElementById('form-register-container');

    if (tab === 'register') {
        loginForm.classList.add('d-none');
        registerForm.classList.remove('d-none');
        
        registerBtn.className = 'btn rounded-3 w-50 fw-bold shadow-sm text-white py-2';
        registerBtn.style.backgroundColor = '#0071e3';
        registerBtn.style.border = 'none';

        loginBtn.className = 'btn btn-link rounded-3 w-50 fw-bold text-decoration-none py-2';
        loginBtn.style.backgroundColor = 'transparent';
        loginBtn.style.color = '#94a3b8';
    } else {
        registerForm.classList.add('d-none');
        loginForm.classList.remove('d-none');
        
        loginBtn.className = 'btn rounded-3 w-50 fw-bold shadow-sm text-white py-2';
        loginBtn.style.backgroundColor = '#0071e3';
        loginBtn.style.border = 'none';

        registerBtn.className = 'btn btn-link rounded-3 w-50 fw-bold text-decoration-none py-2';
        registerBtn.style.backgroundColor = 'transparent';
        registerBtn.style.color = '#94a3b8';
    }
}
</script>

<style>
html, body {
    background-color: #0b0f19 !important;
    color: #ffffff !important;
    min-height: 100vh !important;
}

.custom-dark-input {
    background-color: #1e293b !important;
    border: 1px solid #334155 !important;
    color: #ffffff !important;
    border-radius: 12px !important;
    padding: 12px 16px !important;
    font-size: 0.95rem !important;
    transition: all 0.2s ease-in-out;
}

.custom-dark-input:focus {
    background-color: #1e293b !important;
    color: #ffffff !important;
    border-color: #38bdf8 !important;
    box-shadow: 0 0 0 0.25rem rgba(56, 189, 248, 0.2) !important;
}

.custom-dark-input::placeholder {
    color: #64748b !important;
}

.custom-dark-check .form-check-input {
    background-color: #1e293b !important;
    border-color: #334155 !important;
    cursor: pointer;
}

.custom-dark-check .form-check-input:checked {
    background-color: #0071e3 !important;
    border-color: #0071e3 !important;
}
</style>