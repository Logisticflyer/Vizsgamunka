<?php
use yii\helpers\Url;
use yii\helpers\Html;

/* @var $card array */
?>
<div class="col-12 col-md-6 col-lg-3 d-flex justify-content-center">
    <a href="<?= Url::to($card['url']) ?>" class="service-card-link w-100" data-shortcut="<?= $card['shortcut'] ?>">
        <div class="service-card <?= $card['cardClass'] ?? '' ?>">
            
            <!-- STÁTUSZ ÉS GYORSBILLENTYŰ BADGE-EK -->
            <div class="card-badges-wrapper">
                <?php if (isset($card['shortcutKey'])): ?>
                    <span class="shortcut-badge" title="Gyorsbillentyű"><?= $card['shortcutKey'] ?></span>
                <?php endif; ?>
                <span class="card-badge <?= $card['badgeClass'] ?? '' ?>"><?= $card['badgeText'] ?></span>
            </div>

            <div class="service-icon-wrap <?= $card['iconWrapClass'] ?? '' ?>">
                <i class="<?= $card['icon'] ?>"></i>
            </div>

            <h3><?= Html::encode($card['title']) ?></h3>
            <p><?= Html::encode($card['description']) ?></p>

            <span class="card-arrow">
                <?= Html::encode($card['actionText']) ?> <i class="fas fa-arrow-right"></i>
            </span>
        </div>
    </a>
</div>