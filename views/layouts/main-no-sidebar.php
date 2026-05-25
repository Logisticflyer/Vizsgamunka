<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;

\hail812\adminlte3\assets\FontAwesomeAsset::register($this);
\hail812\adminlte3\assets\AdminLteAsset::register($this);
$this->registerCssFile('https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback');

$assetDir = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="hold-transition">
<?php $this->beginBody() ?>

<div class="wrapper">
    <?= $this->render('navbar', ['assetDir' => $assetDir]) ?>

    <?= $this->render('content', ['content' => $content, 'assetDir' => $assetDir]) ?>

    <?= $this->render('footer') ?>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

<style>
    .main-sidebar, 
    .sidebar, 
    aside.main-sidebar, 
    [class*="sidebar-open"], 
    .nav-link[data-widget="pushmenu"] {
        display: none !important;
        visibility: hidden !important;
        width: 0 !important;
    }

    .content-wrapper, 
    body.sidebar-mini .content-wrapper, 
    body.sidebar-mini.sidebar-collapse .content-wrapper,
    .main-footer,
    .main-header {
        margin-left: 0 !important;
        padding-left: 0 !important;
        transform: none !important;
    }

    .wrapper, .content-wrapper {
        width: 100% !important;
        max-width: 100% !important;
    }
</style>