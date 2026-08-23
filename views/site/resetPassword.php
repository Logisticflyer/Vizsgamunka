<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\ResetPasswordForm $model */

$this->title = 'Új jelszó megadása';
?>

<div class="reset-password-wrapper d-flex align-items-center justify-content-center min-vh-100 py-5">
    <div class="card rounded-4 border-0 shadow-lg p-4 p-md-5" style="background: #141824; border: 1px solid rgba(255, 255, 255, 0.08) !important; width: 100%; max-width: 460px;">
        
        <!-- Fejléc Ikon & Cím -->
        <div class="text-center mb-4">
            <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="background: rgba(56, 189, 248, 0.12); color: #38bdf8; width: 64px; height: 64px;">
                <i class="fas fa-key fa-2x"></i>
            </div>
            <h2 class="fw-bold text-white mb-2 fs-3">Új jelszó megadása</h2>
            <p class="small mb-0" style="color: #94a3b8 !important;">
                Kérlek, válassz egy új, erős jelszót a fiókodhoz.
            </p>
        </div>

        <?php $form = ActiveForm::begin([
            'id' => 'reset-password-form',
            'fieldConfig' => [
                'labelOptions' => ['class' => 'form-label fw-semibold text-white mb-2', 'style' => 'font-size: 0.88rem;'],
                'inputOptions' => ['class' => 'form-control custom-dark-input'],
                'errorOptions' => ['class' => 'invalid-feedback d-block text-danger mt-1', 'style' => 'font-size: 0.8rem;'],
            ],
        ]); ?>

            <!-- Új jelszó -->
            <div class="mb-3">
                <?= $form->field($model, 'password')->passwordInput([
                    'autofocus' => true,
                    'placeholder' => 'Új jelszó (min. 8 karakter)',
                ])->label('<i class="fas fa-lock text-info me-1"></i> Új jelszó') ?>
            </div>

            <!-- Jelszó megerősítése (ha a modellben szerepel) -->
            <?php if ($model->hasProperty('password_repeat')): ?>
                <div class="mb-4">
                    <?= $form->field($model, 'password_repeat')->passwordInput([
                        'placeholder' => 'Jelszó megerősítése',
                    ])->label('<i class="fas fa-shield-alt text-warning me-1"></i> Jelszó megerősítése') ?>
                </div>
            <?php endif; ?>

            <!-- Gomb -->
            <div class="d-grid mb-3 mt-4">
                <?= Html::submitButton('Jelszó mentése <i class="fas fa-check ms-2"></i>', [
                    'class' => 'btn btn-primary py-2.5 fw-semibold rounded-3 shadow-sm',
                    'style' => 'background-color: #0071e3; border: none; font-size: 1rem;'
                ]) ?>
            </div>

            <!-- Vissza a belépéshez -->
            <div class="text-center mt-3 pt-3 border-top border-secondary border-opacity-25">
                <?= Html::a('<i class="fas fa-arrow-left me-1"></i> Vissza a belépéshez', ['site/login'], [
                    'class' => 'small text-decoration-none fw-semibold',
                    'style' => 'color: #38bdf8;'
                ]) ?>
            </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

<!-- Teljes oldalt sötétítő és a fehér fejlécet eltüntető CSS szabályok -->
<style>
html, body {
    background-color: #0b0f19 !important;
    color: #ffffff !important;
    min-height: 100vh !important;
}

/* Eltünteti a régi fehér navigációs sávot eről az oldalról */
nav, .navbar, header, .header {
    display: none !important;
}

/* Sötét beviteli mezők stílusa */
.custom-dark-input {
    background-color: #1e293b !important;
    border: 1px solid #334155 !important;
    color: #ffffff !important;
    border-radius: 12px !important;
    padding: 12px 16px !important;
    font-size: 0.95rem !important;
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
</style>