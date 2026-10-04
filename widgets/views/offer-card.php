<?php

use yii\helpers\Html;

/** @var object $offer */
/** @var string $theme */

$cardBg = $theme === 'dark' ? 'bg-dark text-white border-secondary' : 'bg-light text-dark';
?>
<div class="card mb-3 shadow-sm <?= $cardBg ?>">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">
            <i class="fas fa-file-alt me-2 text-primary"></i>
            <?= Html::encode($offer->targy ?? $offer->subject ?? 'Ajánlatkérés') ?>
        </h5>
        <?php if (!empty($offer->tipus)): ?>
            <span class="badge bg-primary rounded-pill"><?= Html::encode(strtoupper($offer->tipus)) ?></span>
        <?php endif; ?>
    </div>
    <div class="card-body">
        <p class="card-text">
            <?= nl2br(Html::encode($offer->leiras ?? $offer->body ?? '')) ?>
        </p>
    </div>
    <div class="card-footer text-muted d-flex justify-content-between small">
        <span><i class="fas fa-user me-1"></i> <?= Html::encode($offer->nev ?? $offer->name ?? 'Névtelen') ?></span>
        <span><i class="fas fa-envelope me-1"></i> <?= Html::encode($offer->email ?? '') ?></span>
    </div>
</div>