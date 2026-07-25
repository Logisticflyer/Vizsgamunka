<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Új AI Feladat Indítása';
?>

<div class="ai-create p-4">
    
    <!-- Címsor és a Tudásbázis gomb közös konténere -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><?= Html::encode($this->title) ?></h2>
            <p class="text-muted mb-0">Fogalmazd meg pontosan a kérésed, és az AI a háttérben feldolgozza.</p>
        </div>
        
        <!-- A gomb, ami átvisz a Tudásbázisba -->
        <?= Html::a('<i class="fas fa-database"></i> Tudásbázis kezelése', ['/ai-knowledge/index'], [
            'class' => 'btn btn-outline-secondary',
            'title' => 'Saját tudásanyag szerkesztése'
        ]) ?>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            
            <!-- Az ActiveForm elindítása -->
            <?php $form = ActiveForm::begin(); ?>

            <!-- Prompt szövegdoboz -->
            <?= $form->field($model, 'prompt')->textarea([
                'rows' => 6, 
                'placeholder' => 'Pl.: Írj nekem egy Python szkriptet, ami optimalizálja az alábbi folyamatot...',
                'class' => 'form-control form-control-lg' 
            ])->label('A te utasításod (Prompt)') ?>

            <!-- Gombok -->
            <div class="form-group text-right mt-4 mb-0">
                <?= Html::a('Mégsem', ['index'], ['class' => 'btn btn-light mr-2']) ?>
                <?= Html::submitButton('Feladat elküldése <i class="fas fa-paper-plane ml-1"></i>', ['class' => 'btn btn-primary px-4']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>