<?php

use yii\helpers\Html;

$this->title = 'Feladat Részletei #' . $model->id;
?>

<div class="ai-view p-4">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><?= Html::encode($this->title) ?></h2>
        <?= Html::a('<i class="fas fa-arrow-left"></i> Vissza a listához', ['index'], ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <!-- Felhasználó kérdése (Prompt) -->
    <div class="card shadow-sm mb-4 border-left-primary">
        <div class="card-header bg-light font-weight-bold">
            <i class="fas fa-user text-primary"></i> Te kérdezted:
            <span class="float-right text-muted small"><?= date('Y-m-d H:i:s', $model->created_at) ?></span>
        </div>
        <div class="card-body">
            <p class="mb-0" style="white-space: pre-wrap; font-size: 1.1rem;"><?= Html::encode($model->prompt) ?></p>
        </div>
    </div>

    <!-- AI Válasza -->
    <div class="card shadow border-left-success">
        <div class="card-header bg-success text-white font-weight-bold">
            <i class="fas fa-robot"></i> AI Válasza:
            <span class="float-right small text-white-50">Státusz: <?= Html::encode($model->status) ?></span>
        </div>
        <div class="card-body" style="background-color: #f8f9fa;">
            <?php if ($model->response): ?>
                <p class="mb-0" style="white-space: pre-wrap; font-size: 1.1rem; line-height: 1.6;"><?= Html::encode($model->response) ?></p>
            <?php else: ?>
                <p class="text-muted fst-italic mb-0">A válasz még feldolgozás alatt áll, vagy hiba történt.</p>
            <?php endif; ?>
        </div>
    </div>

</div>

<style>
    .border-left-primary { border-left: 5px solid #007bff !important; }
    .border-left-success { border-left: 5px solid #28a745 !important; }
</style>