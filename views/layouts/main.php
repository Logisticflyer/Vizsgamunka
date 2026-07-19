<?php

/** @var yii\web\View $this */
/** @var string $content */

use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100 bg-light">

<?php $this->beginBody() ?>

<header id="header">
    <?php
    NavBar::begin([
        'brandLabel' => 'Vizsgamunka', // A logód vagy a projekted neve
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar navbar-expand-md navbar-dark bg-dark shadow-sm',
        ],
    ]);
    
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav ms-auto'], 
        'items' => [
            ['label' => 'Főoldal', 'url' => ['/site/index']],
            
            // --- VENDÉG MENÜ ---
            Yii::$app->user->isGuest ? ['label' => 'Belépés', 'url' => ['/site/index', '#' => 'login']] : '',
            Yii::$app->user->isGuest ? ['label' => 'Regisztráció', 'url' => ['/site/index', '#' => 'register']] : '',

            // --- BEJELENTKEZETT MENÜ ---
            // Itt az új gomb:
            !Yii::$app->user->isGuest ? ['label' => 'Vezérlőpult', 'url' => ['/site/choose']] : '',
            
            !Yii::$app->user->isGuest ? ['label' => 'Profilom', 'url' => ['/site/profile']] : '',
            
            !Yii::$app->user->isGuest ? (
                '<li class="nav-item">'
                . Html::beginForm(['/site/logout'], 'post', ['class' => 'd-flex'])
                . Html::submitButton(
                    'Kijelentkezés (' . Html::encode(Yii::$app->user->identity->username) . ')',
                    ['class' => 'nav-link btn btn-link logout text-decoration-none']
                )
                . Html::endForm()
                . '</li>'
            ) : ''
        ],
    ]);
    
    NavBar::end();
    ?>
</header>

<main id="main" class="flex-shrink-0" role="main">
    <div class="container-fluid p-4"> <!-- Tettem bele egy kis paddingot (p-4), hogy ne tapadjon a széléhez -->
        <?= $content ?>
    </div>
</main>

<footer id="footer" class="mt-auto py-3 bg-light border-top">
    <div class="container text-center text-muted">
        &copy; Vizsgamunka <?= date('Y') ?>
    </div>
</footer>

<?php $this->endBody() ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $this->endPage() ?>