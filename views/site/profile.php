<?php
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\User $user */
/** @var app\models\UpdateProfileForm $model */

$this->title = 'Profilom';
?>

<div class="profile-container" style="padding: 40px 20px; max-width: 1000px; margin: 0 auto;">
    
    <h1 style="font-weight: 700; font-size: 32px; letter-spacing: -0.5px; color: #1d1d1f; margin-bottom: 40px;">Profilom</h1>

    <div class="row g-5">
        <!-- Bal oldal: Profil kártya -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-4 text-center" style="border-radius: 20px; background: #fff;">
                <div style="font-size: 60px; color: #d2d2d7; margin-bottom: 15px;">
                    <i class="fas fa-user-circle"></i>
                </div>
                <h4 style="font-weight: 600; margin-bottom: 5px;"><?= Html::encode($user->username) ?></h4>
                <p style="color: #86868b; font-size: 14px;"><?= Html::encode($user->email) ?></p>
            </div>
        </div>
        
        <!-- Jobb oldal: Form -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm p-4" style="border-radius: 20px; background: #fff;">
                <h5 style="font-weight: 600; margin-bottom: 25px; color: #1d1d1f;">Adatok szerkesztése</h5>
                
                <?php $form = ActiveForm::begin(['id' => 'profile-form']); ?>

                    <?= $form->field($model, 'username', [
                        'inputTemplate' => '<div class="input-group"><span class="input-group-text bg-light border-0"><i class="fas fa-user text-muted"></i></span>{input}</div>'
                    ])->textInput(['class' => 'form-control border-0 bg-light py-3', 'placeholder' => 'Felhasználónév']) ?>

                    <?= $form->field($model, 'email', [
                        'inputTemplate' => '<div class="input-group"><span class="input-group-text bg-light border-0"><i class="fas fa-envelope text-muted"></i></span>{input}</div>'
                    ])->textInput(['class' => 'form-control border-0 bg-light py-3', 'placeholder' => 'E-mail cím']) ?>

                    <hr style="margin: 30px 0; border-top: 1px solid #f2f2f7;">
                    
                    <h6 style="font-weight: 600; margin-bottom: 20px;">Biztonság</h6>

                    <?= $form->field($model, 'password', [
                        'inputTemplate' => '<div class="input-group"><span class="input-group-text bg-light border-0"><i class="fas fa-lock text-muted"></i></span>{input}</div>'
                    ])->passwordInput(['class' => 'form-control border-0 bg-light py-3', 'placeholder' => 'Új jelszó (ha változtatni szeretnél)']) ?>

                    <div class="form-group mt-4">
                        <?= Html::submitButton('Változtatások mentése', [
                            'class' => 'btn btn-dark px-4 py-2', 
                            'style' => 'border-radius: 20px; font-weight: 500;'
                        ]) ?>
                    </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>