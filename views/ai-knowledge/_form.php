<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\AiKnowledge $model */
/** @var yii\bootstrap5\ActiveForm $form */

// Dinamikus oszlopnév támogatás (magyar/angol adatbázis sémához)
$catAttr = $model->hasAttribute('kategoria') ? 'kategoria' : 'category';
$titleAttr = $model->hasAttribute('cim') ? 'cim' : 'title';
$contentAttr = $model->hasAttribute('tartalom') ? 'tartalom' : 'content';
?>

<div class="card rounded-4 border-0 shadow-sm p-4" style="background: #141824; border: 1px solid rgba(255, 255, 255, 0.08) !important;">
    <?php $form = ActiveForm::begin([
        'id' => 'ai-knowledge-form',
        'fieldConfig' => [
            'labelOptions' => ['class' => 'form-label fw-semibold text-white mb-2', 'style' => 'font-size: 0.9rem;'],
            'inputOptions' => ['class' => 'form-control custom-dark-input'],
            'errorOptions' => ['class' => 'invalid-feedback d-block text-danger mt-1', 'style' => 'font-size: 0.8rem;'],
        ],
    ]); ?>

    <!-- Kategória -->
    <div class="mb-4">
        <?= $form->field($model, $catAttr)->textInput([
            'placeholder' => 'pl. AI Szabályok, Árazás, 3D Tervezés...',
            'autofocus' => true
        ])->label('<i class="fas fa-tag text-warning me-1"></i> Kategória') ?>
    </div>

    <!-- Cím -->
    <div class="mb-4">
        <?= $form->field($model, $titleAttr)->textInput([
            'placeholder' => 'Adj meg egy rövid, kifejező címet...'
        ])->label('<i class="fas fa-heading text-info me-1"></i> Cím / Téma') ?>
    </div>

    <!-- Tartalom -->
    <div class="mb-4">
        <?= $form->field($model, $contentAttr)->textarea([
            'rows' => 6,
            'placeholder' => 'Írd le részletesen az AI szabályt, kontextust vagy utasítást...'
        ])->label('<i class="fas fa-align-left text-primary me-1"></i> Részletes Tartalom') ?>
    </div>

    <!-- Művelet gombok -->
    <div class="d-flex justify-content-end align-items-center gap-3 pt-3 border-top border-secondary border-opacity-25 mt-4">
        <?= Html::a('Mégse', ['index'], ['class' => 'btn btn-outline-secondary px-4 py-2 rounded-3 text-white border-opacity-25']) ?>
        <?= Html::submitButton('<i class="fas fa-save me-2"></i> Mentés és Publikálás', [
            'class' => 'btn btn-primary px-4 py-2 fw-semibold rounded-3 shadow-sm',
            'style' => 'background-color: #0071e3; border: none;'
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<style>
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
</style>