<?php

/** @var yii\web\View $this */
/** @var string $name */
/** @var string $message */
/** @var Exception $exception */

use yii\bootstrap5\Html;

$statusCode = $exception->statusCode ?? 500;
$this->title = $statusCode . ' - Hozzáférés megtagadva';
?>

<div class="error-card">
    <div class="error-icon">
        <?php if ($statusCode === 403): ?>
            <i class="fas fa-lock"></i>
        <?php else: ?>
            <i class="fas fa-triangle-exclamation"></i>
        <?php endif; ?>
    </div>
    
    <h1 class="error-code"><?= Html::encode($statusCode) ?></h1>
    <h2 class="error-title">Nincs jogosultságod</h2>
    
    <p class="error-message">
        <?= nl2br(Html::encode($message)) ?>
    </p>
    
    <div class="error-actions">
        <?= Html::a('<i class="fas fa-arrow-left me-2"></i> Vissza a főoldalra', ['/site/index'], ['class' => 'btn-apple-back']) ?>
    </div>
</div>

<style>
.error-card {
    background: #ffffff;
    border-radius: 24px;
    padding: 60px 40px;
    width: 100%;
    max-width: 440px; /* Megakadályozza az összenyomódást */
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.04);
    border: 1px solid #e5e5e7;
    text-align: center;
    animation: fadeIn 0.4s ease-out;
}

.error-icon {
    font-size: 44px;
    color: #ff3b30; /* Prémium Apple vörös */
    margin-bottom: 20px;
}

.error-code {
    font-size: 84px;
    font-weight: 700;
    color: #1d1d1f;
    line-height: 1;
    margin: 0 0 12px 0;
    letter-spacing: -2px;
}

.error-title {
    font-size: 22px;
    font-weight: 600;
    color: #1d1d1f;
    margin-bottom: 14px;
    letter-spacing: -0.5px;
}

.error-message {
    color: #86868b;
    font-size: 14px;
    line-height: 1.5;
    margin-bottom: 35px;
    padding: 0 15px;
}

.btn-apple-back {
    display: inline-block;
    background: #0071e3;
    color: #ffffff !important;
    border: none;
    border-radius: 12px;
    padding: 14px 32px;
    font-size: 15px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s ease;
}

.btn-apple-back:hover {
    background: #0077ed;
    box-shadow: 0 4px 12px rgba(0, 113, 227, 0.3);
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>