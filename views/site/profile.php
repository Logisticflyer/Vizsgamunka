<?php
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\User $user */
/** @var app\models\UpdateProfileForm $model */

$this->title = 'Profilom';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-profile container mt-4">
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0" style="border-radius: 16px;">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="fas fa-user-circle text-secondary" style="font-size: 80px;"></i>
                    </div>
                    <h4 class="fw-bold"><?= Html::encode($user->username) ?></h4>
                    <p class="text-muted"><?= Html::encode($user->email) ?></p>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card shadow-sm border-0" style="border-radius: 16px;">
                <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                    <h5 class="fw-bold mb-0">Adatok szerkesztése</h5>
                </div>
                <div class="card-body p-4">
                    
                    <?php if (Yii::$app->session->hasFlash('success')): ?>
                        <div class="alert alert-success rounded-3">
                            <?= Yii::$app->session->getFlash('success') ?>
                        </div>
                    <?php endif; ?>

                    <?php $form = ActiveForm::begin(['id' => 'profile-form']); ?>

                        <?= $form->field($model, 'username')->textInput(['autofocus' => true, 'class' => 'form-control form-control-lg rounded-3 mb-3']) ?>

                        <?= $form->field($model, 'email')->textInput(['class' => 'form-control form-control-lg rounded-3 mb-3']) ?>

                        <hr class="my-4">
                        <h6 class="fw-bold mb-3">Biztonság</h6>

                        <?= $form->field($model, 'password')->passwordInput(['class' => 'form-control form-control-lg rounded-3 mb-4', 'placeholder' => 'Csak akkor töltsd ki, ha újat szeretnél...']) ?>

                        <div class="form-group">
                            <?= Html::submitButton('Változtatások mentése', ['class' => 'btn btn-primary btn-lg rounded-3 px-4 shadow-sm', 'name' => 'profile-button']) ?>
                        </div>

                    <?php ActiveForm::end(); ?>
                    
                </div>
            </div>
        </div>
    </div>
</div>