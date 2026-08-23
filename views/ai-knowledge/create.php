<?php

use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\models\AiKnowledge $model */

$this->title = 'Új AI Tudásbázis Bejegyzés';
?>

<div class="ai-knowledge-create container py-4" style="max-width: 850px;">
    <!-- Fejléc Sáv -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 pb-3 border-bottom border-secondary border-opacity-25 gap-3">
        <div>
            <h1 class="h2 fw-bold text-white mb-1">
                <i class="fas fa-plus-circle text-primary me-2"></i> Új Bejegyzés Létrehozása
            </h1>
            <span style="color: #94a3b8 !important;" class="small">Bővítsd az AI tudásbázisát új szabállyal vagy kontextussal.</span>
        </div>
        <div>
            <?= Html::a('<i class="fas fa-arrow-left me-1"></i> Vissza a listához', ['index'], [
                'class' => 'btn btn-outline-light btn-sm rounded-3 px-3 py-2',
                'style' => 'border-color: #475569; color: #cbd5e1;'
            ]) ?>
        </div>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>

<style>
/* Biztosítja, hogy az oldalon lévő összes h1/h2 címsor ragyogó fehér legyen */
.ai-knowledge-create h1,
.ai-knowledge-create h2 {
    color: #ffffff !important;
}
</style>