<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Feladat Szerkesztése: #' . $model->id;
?>

<div class="ai-update p-4">
    
    <div class="mb-4">
        <h2><?= Html::encode($this->title) ?></h2>
        <p class="text-muted">Itt módosíthatod a korábban kiadott utasításodat.</p>
    </div>

    <div class="card shadow-sm border-0 border-left-primary">
        <div class="card-body">
            
            <?php $form = ActiveForm::begin(); ?>

            <!-- A prompt szövegdoboza, amibe a Yii automatikusan betölti a régi szöveget -->
            <?= $form->field($model, 'prompt')->textarea([
                'rows' => 6, 
                'class' => 'form-control form-control-lg'
            ])->label('A te utasításod (Prompt)') ?>

            <!-- Gombok -->
            <div class="form-group text-right mt-4 mb-0">
                <?= Html::a('Mégsem', ['index'], ['class' => 'btn btn-light mr-2']) ?>
                <?= Html::submitButton('Módosítások mentése <i class="fas fa-save ml-1"></i>', ['class' => 'btn btn-success px-4']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>

<style>
    .border-left-primary { border-left: 5px solid #007bff !important; }
</style>