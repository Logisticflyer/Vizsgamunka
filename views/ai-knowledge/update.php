<?php

use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\models\AiKnowledge $model */

$titleVal = $model->cim ?? $model->title ?? 'Bejegyzés';

$this->title = 'Szerkesztés: ' . $titleVal;
?>

<div class="ai-knowledge-update container py-4" style="max-width: 850px;">
    <!-- Fejléc Sáv -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 pb-3 border-bottom border-secondary border-opacity-25 gap-3">
        <div>
            <h1 class="h2 fw-bold text-white mb-1">
                <i class="fas fa-edit text-warning me-2"></i> Bejegyzés Szerkesztése
            </h1>
            <span style="color: #94a3b8 !important;" class="small">
                Módosítsd a(z) <strong class="text-white"><?= Html::encode($titleVal) ?></strong> nevű tudásbázis elemet.
            </span>
        </div>
        <div>
            <?= Html::a('<i class="fas fa-arrow-left me-1"></i> Vissza', ['view', 'id' => $model->id], [
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
.ai-knowledge-update h1,
.ai-knowledge-update h2 {
    color: #ffffff !important;
}
</style>