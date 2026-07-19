<?php

use yii\helpers\Html;
use yii\helpers\Url;

?>
<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">

    <!-- Bal oldali menü -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="<?= Url::to(['site/rolunk']) ?>" class="nav-link">Rólunk</a>
        </li>
        <!-- A Contact link törölve -->
    </ul>

    <!-- Jobb oldali menü -->
    <ul class="navbar-nav ml-auto">
        <?php if (!Yii::$app->user->isGuest): ?>
            <li class="nav-item">
                <a href="<?= Url::to(['/site/choose']) ?>" class="nav-link" title="Vezérlőpult">
                    <i class="fas fa-th-large"></i> <span class="d-none d-md-inline ml-1">Vezérlőpult</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= Url::to(['/site/profile']) ?>" class="nav-link" title="Profilom">
                    <i class="fas fa-user-circle"></i> <span class="d-none d-md-inline ml-1">Profilom</span>
                </a>
            </li>
            <li class="nav-item">
                <?= Html::a('<i class="fas fa-sign-out-alt"></i>', ['/site/logout'], [
                    'data-method' => 'post', 
                    'class' => 'nav-link',
                    'title' => 'Kijelentkezés'
                ]) ?>
            </li>
        <?php else: ?>
            <li class="nav-item">
                <a href="<?= Url::to(['/site/login']) ?>" class="nav-link">Belépés</a>
            </li>
        <?php endif; ?>
    </ul>
</nav>
<!-- /.navbar -->