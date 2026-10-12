<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\LoginForm $model */
/** @var app\models\SignupForm $signupModel */
/** @var int|null $totalOffers */
/** @var int|null $totalKnowledge */
/** @var array|null $recentOffers */

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\OfferCardWidget;

// Biztonsági inicializálás vendég módhoz
if (!isset($model)) {
    $model = new \app\models\LoginForm();
}
if (!isset($signupModel)) {
    $signupModel = new \app\models\SignupForm();
}

$this->title = !Yii::$app->user->isGuest ? 'Adminisztrációs Vezérlőpult' : 'Bejelentkezés & Regisztráció';
?>

<?php if (!Yii::$app->user->isGuest): ?>

    <!-- 1. BEJELENTKEZETT ADMINISZTRÁTORI MŰSZERFAL -->
    <div class="container py-5 text-white">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1" style="color: #38bdf8;">Adminisztrációs Vezérlőpult</h2>
                <p class="small mb-0" style="color: #cbd5e1 !important;">Üdvözlünk újra, <?= Html::encode(Yii::$app->user->identity->username) ?>! Íme a rendszer aktuális áttekintése.</p>
            </div>
            <a href="<?= Url::to(['site/choose']) ?>" class="btn btn-outline-info rounded-3 fw-bold">
                <i class="fas fa-th-large me-2"></i>Szolgáltatások váltása
            </a>
        </div>

        <!-- KPI KÁRTYÁK -->
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="p-4 rounded-4 shadow-sm" style="background: #141824; border: 1px solid rgba(255, 255, 255, 0.08);">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="small fw-bold" style="color: #cbd5e1 !important;">ÖSSZES AJÁNLATKÉRÉS</span>
                        <i class="fas fa-file-invoice text-info fs-5"></i>
                    </div>
                    <div class="fs-2 fw-bold text-white"><?= $totalOffers ?? 0 ?> db</div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="p-4 rounded-4 shadow-sm" style="background: #141824; border: 1px solid rgba(255, 255, 255, 0.08);">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="small fw-bold" style="color: #cbd5e1 !important;">TUDÁSBÁZIS ELEMEK</span>
                        <i class="fas fa-database text-warning fs-5"></i>
                    </div>
                    <div class="fs-2 fw-bold text-white"><?= $totalKnowledge ?? 0 ?> db</div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="p-4 rounded-4 shadow-sm" style="background: #141824; border: 1px solid rgba(255, 255, 255, 0.08);">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="small fw-bold" style="color: #cbd5e1 !important;">RENDSZER ÁLLAPOT</span>
                        <i class="fas fa-server text-success fs-5"></i>
                    </div>
                    <div class="fs-2 fw-bold text-success">Aktív & Stabil</div>
                </div>
            </div>
        </div>

        <!-- LEGUTÓBBI AJÁNLATKÉRÉSEK TÁBLÁZAT -->
        <div class="p-4 rounded-4 shadow-sm" style="background: #141824; border: 1px solid rgba(255, 255, 255, 0.08);">
            <h4 class="fw-bold text-white mb-3"><i class="fas fa-history me-2 text-info"></i>Legutóbbi Ajánlatkérések</h4>
            <?php if (!empty($recentOffers)): ?>
                <div class="table-responsive">
                    <table class="table table-dark table-hover mb-0 align-middle" style="background: transparent;">
                        <thead>
                            <tr style="border-bottom: 1px solid rgba(255,255,255,0.1); color: #f8fafc;">
                                <th style="color: #cbd5e1;">#ID</th>
                                <th style="color: #cbd5e1;">Név</th>
                                <th style="color: #cbd5e1;">E-mail</th>
                                <th style="color: #cbd5e1;">Tárgy</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentOffers as $offer): ?>
                                <tr class="text-white">
                                    <td>#<?= Html::encode($offer->id ?? '') ?></td>
                                    <td><?= Html::encode($offer->nev ?? $offer->name ?? '-') ?></td>
                                    <td><?= Html::encode($offer->email ?? '-') ?></td>
                                    <td><?= Html::encode($offer->targy ?? $offer->subject ?? '-') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="mb-0" style="color: #cbd5e1 !important;">Még nem érkezett ajánlatkérés a rendszerbe.</p>
            <?php endif; ?>
        </div>
    </div>

<?php else: ?>

    <!-- 2. VENDÉG NÉZET: BEJELENTKEZÉS ÉS REGISZTRÁCIÓ -->
    <div class="site-auth-wrapper min-vh-100 d-flex align-items-center justify-content-center py-5" style="background-color: #0b0f19;">
        <div class="container" style="max-width: 1100px;">
            <div class="row g-0 rounded-4 overflow-hidden shadow-lg" style="background: #141824; border: 1px solid rgba(255, 255, 255, 0.08) !important;">
                
                <!-- BAL OLDAL: CÍM ÉS BEMUTATÓ PANEL -->
                <div class="col-lg-6 p-5 d-flex flex-column justify-content-between position-relative text-white" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); border-right: 1px solid rgba(255, 255, 255, 0.08);">
                    <div>
                        <h1 class="display-5 fw-extrabold text-white mb-3" style="letter-spacing: -1px; font-weight: 800;">
                            Digitális Precizitás<br>
                            <span style="color: #38bdf8;">& Automatizáció</span>
                        </h1>
                        <p class="lead fs-6" style="line-height: 1.6; color: #cbd5e1 !important;">
                            Üdvözlünk a platformon. Optimalizáld a munkafolyamataidat AI alapú automatizációval vagy egyedi 3D tervezéssel egyetlen, letisztult felületen.
                        </p>
                    </div>

                    <div class="mt-5 pt-4 border-top border-secondary border-opacity-25">
                        <div class="d-flex align-items-center gap-2 fw-bold small mb-2" style="color: #38bdf8 !important;">
                            <i class="fas fa-wave-square me-1"></i>
                            <span>ADATVEZÉREL T AUTOMATIZÁCIÓS FOLYAMAT</span>
                        </div>
                        <div class="progress" style="height: 6px; background-color: #0f172a;">
                            <div class="progress-bar" role="progressbar" style="width: 65%; background-color: #38bdf8; border-radius: 3px;"></div>
                        </div>
                    </div>
                </div>

                <!-- JOBB OLDAL: KÁRTYA (BEJELENTKEZÉS & REGISZTRÁCIÓ FÜLEK) -->
                <div class="col-lg-6 p-5 d-flex flex-column justify-content-center" style="background: #141824;">
                    
                    <!-- FÜL VÁLTÓ GOMBOK -->
                    <div class="d-flex p-1 rounded-3 mb-4" style="background: #1e293b; border: 1px solid #334155;">
                        <button type="button" id="tab-login-btn" class="btn rounded-3 w-50 fw-bold py-2 shadow-sm" style="background-color: #38bdf8; color: #0f172a !important; border: none;" onclick="switchAuthTab('login')">
                            Bejelentkezés
                        </button>
                        <button type="button" id="tab-register-btn" class="btn btn-link rounded-3 w-50 fw-bold text-decoration-none py-2" style="color: #94a3b8 !important;" onclick="switchAuthTab('register')">
                            Regisztráció
                        </button>
                    </div>

                    <!-- 1. BEJELENTKEZÉSI ŰRLAP -->
                    <div id="form-login-container">
                        <?php $form = ActiveForm::begin([
                            'id' => 'login-form',
                            'action' => Url::to(['site/login']), // MÓDOSÍTVA: Mivel korábban site/index volt, most a site/login akcióra mutat!
                            'fieldConfig' => [
                                'template' => "{label}\n{input}\n{error}",
                                'labelOptions' => ['class' => 'form-label fw-semibold text-white small mb-1', 'style' => 'color: #f8fafc !important;'],
                                'inputOptions' => ['class' => 'form-control custom-dark-input'],
                                'errorOptions' => ['class' => 'invalid-feedback small fw-bold mt-1 text-danger'],
                            ],
                        ]); ?>

                            <div class="mb-3">
                                <?= $form->field($model, 'username')->textInput(['placeholder' => 'Felhasználónév', 'autofocus' => true])->label('Felhasználónév') ?>
                            </div>

                            <div class="mb-3">
                                <?= $form->field($model, 'password')->passwordInput(['placeholder' => '••••••••'])->label('Jelszó') ?>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <?= $form->field($model, 'rememberMe')->checkbox([
                                    'template' => "<div class=\"form-check custom-dark-check\">{input} {label}</div>",
                                    'labelOptions' => ['class' => 'form-check-label small', 'style' => 'color: #e2e8f0 !important; cursor: pointer;'],
                                    'inputOptions' => ['class' => 'form-check-input'],
                                ])->label('Emlékezz rám') ?>

                                <a href="<?= Url::to(['site/request-password-reset']) ?>" class="small text-decoration-none fw-semibold mb-3" style="color: #38bdf8 !important;">Elfelejtetted a jelszavad?</a>
                            </div>

                            <div class="form-group">
                                <?= Html::submitButton('Belépés <i class="fas fa-arrow-right ms-2"></i>', [
                                    'class' => 'btn btn-primary w-100 rounded-3 py-2.5 fw-bold shadow-sm',
                                    'style' => 'background-color: #38bdf8; border: none; color: #0f172a; font-weight: 700;',
                                    'name' => 'login-button'
                                ]) ?>
                            </div>

                        <?php ActiveForm::end(); ?>
                    </div>

                    <!-- 2. REGISZTRÁCIÓS ŰRLAP -->
                    <div id="form-register-container" class="d-none">
                        <?php $signupForm = ActiveForm::begin([
                            'id' => 'signup-form',
                            'action' => Url::to(['site/signup']), // MÓDOSÍTVA: Mivel korábban site/index volt, most a site/signup akcióra mutat!
                            'fieldConfig' => [
                                'template' => "{label}\n{input}\n{error}",
                                'labelOptions' => ['class' => 'form-label fw-semibold text-white small mb-1', 'style' => 'color: #f8fafc !important;'],
                                'inputOptions' => ['class' => 'form-control custom-dark-input'],
                                'errorOptions' => ['class' => 'invalid-feedback small fw-bold mt-1 text-danger'],
                            ],
                        ]); ?>

                            <div class="mb-3">
                                <?= $signupForm->field($signupModel, 'username')->textInput(['placeholder' => 'Válassz felhasználónevet'])->label('Felhasználónév') ?>
                            </div>

                            <div class="mb-3">
                                <?= $signupForm->field($signupModel, 'email')->input('email', ['placeholder' => 'nev@pelda.hu'])->label('E-mail cím') ?>
                            </div>

                            <div class="mb-4">
                                <?= $signupForm->field($signupModel, 'password')->passwordInput(['placeholder' => '••••••••'])->label('Jelszó') ?>
                            </div>

                            <div class="form-group">
                                <?= Html::submitButton('Regisztráció véglegesítése <i class="fas fa-user-plus ms-2"></i>', [
                                    'class' => 'btn btn-success w-100 rounded-3 py-2.5 fw-bold shadow-sm',
                                    'style' => 'background-color: #10b981; border: none; font-weight: 700;',
                                    'name' => 'signup-button'
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
            
            registerBtn.className = 'btn rounded-3 w-50 fw-bold shadow-sm py-2';
            registerBtn.style.backgroundColor = '#38bdf8';
            registerBtn.style.color = '#0f172a';
            registerBtn.style.border = 'none';

            loginBtn.className = 'btn btn-link rounded-3 w-50 fw-bold text-decoration-none py-2';
            loginBtn.style.backgroundColor = 'transparent';
            loginBtn.style.color = '#94a3b8';
        } else {
            registerForm.classList.add('d-none');
            loginForm.classList.remove('d-none');
            
            loginBtn.className = 'btn rounded-3 w-50 fw-bold shadow-sm py-2';
            loginBtn.style.backgroundColor = '#38bdf8';
            loginBtn.style.color = '#0f172a';
            loginBtn.style.border = 'none';

            registerBtn.className = 'btn btn-link rounded-3 w-50 fw-bold text-decoration-none py-2';
            registerBtn.style.backgroundColor = 'transparent';
            registerBtn.style.color = '#94a3b8';
        }
    }
    </script>

<?php endif; ?>

<style>
.form-label, label {
    color: #f8fafc !important;
}

.custom-dark-input {
    background-color: #1e293b !important;
    border: 1px solid #475569 !important;
    color: #ffffff !important;
    border-radius: 10px !important;
    padding: 12px 16px !important;
    font-size: 0.95rem !important;
}

.custom-dark-input:focus {
    background-color: #1e293b !important;
    color: #ffffff !important;
    border-color: #38bdf8 !important;
    box-shadow: 0 0 0 0.25rem rgba(56, 189, 248, 0.25) !important;
}

.custom-dark-input::placeholder {
    color: #94a3b8 !important;
    opacity: 1 !important;
}

.custom-dark-check .form-check-input {
    background-color: #1e293b !important;
    border-color: #475569 !important;
}

.custom-dark-check .form-check-input:checked {
    background-color: #38bdf8 !important;
    border-color: #38bdf8 !important;
}
</style>