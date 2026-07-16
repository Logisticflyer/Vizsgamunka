<?php
/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\ResetPasswordForm $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Új jelszó megadása';
?>

<div class="container mt-5 pt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="auth-card shadow-sm p-5 text-center" style="background: #ffffff; border-radius: 24px; border: 1px solid #e5e5e7;">
                
                <h2 class="fw-bold mb-3">Új jelszó</h2>
                <p class="text-muted mb-4">Kérlek, válassz egy új, erős jelszót a fiókodhoz.</p>

                <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>

                    <div class="input-group mb-2 position-relative">
                        <?= Html::activePasswordInput($model, 'password', [
                            'class' => 'form-control custom-input mb-0', 
                            'placeholder' => 'Új jelszó (min. 8 karakter)',
                            'id' => 'new-password'
                        ]) ?>
                        <button class="btn btn-outline-secondary custom-eye-btn" type="button" id="togglePassword">
                            <i class="fas fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                    <?= Html::error($model, 'password', ['class' => 'invalid-feedback d-block mb-3 text-start']) ?>

                    <div class="progress mb-4" style="height: 6px; border-radius: 3px;">
                        <div id="strengthBar" class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div id="strengthText" class="text-start mb-3" style="font-size: 0.75rem; font-weight: 600; margin-top: -15px;"></div>

                    <?= $form->field($model, 'password_repeat', [
                        'template' => "{input}\n{error}"
                    ])->passwordInput([
                        'class' => 'form-control custom-input', 
                        'placeholder' => 'Jelszó megerősítése'
                    ]) ?>

                    <div class="d-grid mt-4">
                        <?= Html::submitButton('Jelszó mentése <i class="fas fa-check ms-2"></i>', ['class' => 'btn btn-dark btn-lg custom-btn']) ?>
                    </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>

<style>
.custom-input { border-radius: 12px; border: 1px solid #d2d2d7; padding: 14px 16px; margin-bottom: 0.5rem; border-right: none; }
.custom-input:focus { box-shadow: none; border-color: #0071e3; }
.custom-eye-btn { border-radius: 0 12px 12px 0; border: 1px solid #d2d2d7; border-left: none; background: transparent; color: #86868b; padding: 0 16px; }
.custom-eye-btn:hover, .custom-eye-btn:focus, .custom-eye-btn:active { background: transparent; color: #0071e3; border-color: #d2d2d7; box-shadow: none; }
.input-group:focus-within .custom-input, .input-group:focus-within .custom-eye-btn { border-color: #0071e3; }
.custom-btn { border-radius: 12px; padding: 14px; font-weight: 600; border: none; transition: 0.3s; background-color: #1d1d1f; }
.custom-btn:hover { background-color: #000000; }
.invalid-feedback { color: #ff3b30 !important; }
</style>

<script>
// Szem ikon logikája
document.getElementById('togglePassword').addEventListener('click', function (e) {
    const passwordInput = document.getElementById('new-password');
    const eyeIcon = document.getElementById('eyeIcon');
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.classList.remove('fa-eye');
        eyeIcon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        eyeIcon.classList.remove('fa-eye-slash');
        eyeIcon.classList.add('fa-eye');
    }
});

// Jelszóerősség mérő logikája
document.getElementById('new-password').addEventListener('input', function() {
    const val = this.value;
    const bar = document.getElementById('strengthBar');
    const text = document.getElementById('strengthText');
    let strength = 0;

    if(val.length >= 8) strength += 25;
    if(val.match(/[A-Z]/)) strength += 25;
    if(val.match(/[0-9]/)) strength += 25;
    if(val.match(/[^a-zA-Z0-9]/)) strength += 25; // Speciális karakter

    bar.style.width = strength + '%';

    if (val.length === 0) {
        bar.style.width = '0%';
        bar.className = 'progress-bar';
        text.innerText = '';
    } else if (strength <= 25) {
        bar.className = 'progress-bar bg-danger';
        text.innerText = 'Nagyon gyenge';
        text.style.color = '#dc3545';
    } else if (strength === 50) {
        bar.className = 'progress-bar bg-warning';
        text.innerText = 'Közepes';
        text.style.color = '#ffc107';
    } else if (strength === 75) {
        bar.className = 'progress-bar bg-info';
        text.innerText = 'Jó';
        text.style.color = '#17a2b8';
    } else {
        bar.className = 'progress-bar bg-success';
        text.innerText = 'Kiváló';
        text.style.color = '#28a745';
    }
});
</script>