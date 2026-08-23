<?php

use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\models\AiKnowledge $model */

// Dinamikus mezőkezelés (angol és magyar oszlopnevek támogatása)
$catVal = $model->kategoria ?? $model->category ?? 'Általános';
$titleVal = $model->cim ?? $model->title ?? 'Részletek';
$contentVal = $model->tartalom ?? $model->content ?? '';

// Unix időbélyeg átalakítása olvasható dátummá
$createdAt = isset($model->created_at) ? (is_numeric($model->created_at) ? date('Y.m.d. H:i', $model->created_at) : $model->created_at) : '-';
$updatedAt = isset($model->updated_at) ? (is_numeric($model->updated_at) ? date('Y.m.d. H:i', $model->updated_at) : $model->updated_at) : '-';

$this->title = $titleVal;
?>

<div class="ai-knowledge-view container py-4" style="max-width: 900px;">
    <!-- Fejléc Sáv -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 pb-3 border-bottom border-secondary border-opacity-25 gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-2.5 py-1.5 rounded-2 fs-7">
                    <i class="fas fa-tag me-1"></i> <?= Html::encode($catVal) ?>
                </span>
                <span class="badge bg-dark text-muted border border-secondary border-opacity-25 px-2 py-1.5 rounded-2 fs-7">
                    ID: #<?= $model->id ?>
                </span>
            </div>
            <h1 class="h2 fw-bold text-white mb-0">
                <?= Html::encode($titleVal) ?>
            </h1>
        </div>
        <div class="d-flex align-items-center gap-2">
            <?= Html::a('<i class="fas fa-arrow-left me-1"></i> Vissza', ['index'], [
                'class' => 'btn btn-outline-light btn-sm rounded-3 px-3 py-2',
                'style' => 'border-color: #475569; color: #cbd5e1;'
            ]) ?>
            <?= Html::a('<i class="fas fa-edit me-1"></i> Szerkesztés', ['update', 'id' => $model->id], [
                'class' => 'btn btn-warning btn-sm rounded-3 px-3 py-2 fw-semibold text-dark',
            ]) ?>
            <?= Html::a('<i class="fas fa-trash-alt me-1"></i> Törlés', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger btn-sm rounded-3 px-3 py-2 fw-semibold',
                'data' => [
                    'confirm' => 'Biztosan törölni szeretnéd ezt a bejegyzést?',
                    'method' => 'post',
                ],
            ]) ?>
        </div>
    </div>

    <!-- Fő Tartalom Kártya -->
    <div class="card rounded-4 border-0 shadow-sm p-4 mb-4" style="background: #141824; border: 1px solid rgba(255, 255, 255, 0.08) !important;">
        <h6 class="text-uppercase fw-bold mb-3" style="font-size: 0.75rem; letter-spacing: 1px; color: #38bdf8 !important;">
            <i class="fas fa-align-left me-1"></i> Részletes Tartalom / Utasítás
        </h6>
        <div class="p-4 rounded-3 text-white mb-4" style="background: #1e293b; border: 1px solid #334155; font-size: 1.05rem; line-height: 1.7; white-space: pre-wrap;">
            <?= Html::encode($contentVal) ?>
        </div>

        <!-- Meta adatok (időbélyegek) -->
        <div class="row g-3 pt-3 border-top border-secondary border-opacity-25 text-muted small" style="color: #94a3b8 !important;">
            <div class="col-sm-6 d-flex align-items-center gap-2">
                <i class="fas fa-calendar-plus text-success"></i>
                <span>Létrehozva: <strong class="text-white"><?= $createdAt ?></strong></span>
            </div>
            <div class="col-sm-6 d-flex align-items-center gap-2 justify-content-sm-end">
                <i class="fas fa-history text-info"></i>
                <span>Utoljára frissítve: <strong class="text-white"><?= $updatedAt ?></strong></span>
            </div>
        </div>
    </div>
</div>