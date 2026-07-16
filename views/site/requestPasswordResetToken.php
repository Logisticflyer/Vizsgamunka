<?php
/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\PasswordResetRequestForm $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Jelszó visszaállítása';
?>

<div class="container mt-5 pt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="auth-card shadow-sm p-5 text-center" style="background: #ffffff; border-radius: 24px; border: 1px solid #e5e5e7;">
                
                <h2 class="fw-bold mb-3">Elfelejtett jelszó</h2>
                <p class="text-muted mb-4">Kérlek, add meg az e-mail címedet. Küldeni fogunk egy linket, amin keresztül új jelszót állíthatsz be.</p>

                <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>

                    <?= $form->field($model, 'email', [
                        'template' => "{input}\n{error}"
                    ])->textInput([
                        'autofocus' => true, 
                        'class' => 'form-control custom-input text-center', 
                        'placeholder' => 'pelda@email.com'
                    ]) ?>

                    <div class="d-grid mt-4">
                        <?= Html::submitButton('Küldés <i class="fas fa-paper-plane ms-2"></i>', ['class' => 'btn btn-primary btn-lg custom-btn']) ?>
                    </div>

                <?php ActiveForm::end(); ?>
                
                <div class="mt-4">
                    <a href="<?= \yii\helpers\Url::to(['site/index']) ?>" class="text-decoration-none text-muted" style="font-size: 0.9rem;">
                        <i class="fas fa-arrow-left me-1"></i> Vissza a bejelentkezéshez
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
.custom-input { border-radius: 12px; border: 1px solid #d2d2d7; padding: 14px 16px; margin-bottom: 0.5rem; }
.custom-btn { border-radius: 12px; padding: 14px; font-weight: 600; background-color: #0071e3; border: none; transition: 0.3s; }
.custom-btn:hover { background-color: #005bb5; }
.invalid-feedback { display: block !important; color: #ff3b30 !important; font-size: 0.8rem !important; margin-bottom: 1rem !important; margin-top: 5px !important; text-align: left; }
</style>