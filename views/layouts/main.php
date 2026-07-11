<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use app\assets\AppAsset;

\hail812\adminlte3\assets\FontAwesomeAsset::register($this);
\hail812\adminlte3\assets\AdminLteAsset::register($this);
$this->registerCssFile('@web/css/site.css', ['depends' => [\hail812\adminlte3\assets\AdminLteAsset::class]]);
$this->registerCssFile('https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback');
AppAsset::register($this);

$assetDir = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');

/*$publishedRes = Yii::$app->assetManager->publish('@vendor/hail812/yii2-adminlte3/src/web/js');
$this->registerJsFile($publishedRes[1].'/control_sidebar.js', ['depends' => '\hail812\adminlte3\assets\AdminLteAsset']);
*/

$currentController = Yii::$app->controller->id;
$currentAction = Yii::$app->controller->action->id;

$isAuthPage = (($currentController === 'site' && in_array($currentAction, ['index', 'signup', 'choose', 'rolunk', 'login', 'contact'])) || $currentController === 'ajanlatkeres');
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>

    <?php $this->head() ?>
</head>
<body class="hold-transition <?= $isAuthPage ? '' : 'sidebar-mini' ?>">
<?php $this->beginBody() ?>

<div class="wrapper">
    <?= $this->render('navbar', ['assetDir' => $assetDir]) ?>
    <?php if (!$isAuthPage): ?>
        <?= $this->render('sidebar', ['assetDir' => $assetDir]) ?>
    <?php endif; ?>

    <?= $this->render('content', ['content' => $content, 'assetDir' => $assetDir]) ?>
    <?php if (!$isAuthPage): ?>
        <?= $this->render('control-sidebar') ?>
    <?php endif; ?>

    <?= $this->render('footer') ?>
</div>

<?php if ($isAuthPage): ?>
<style>
    .content-wrapper, body .content-wrapper {
        margin-left: 0 !important;
        padding-left: 0 !important;
    }
    .main-sidebar, .sidebar, aside.main-sidebar {
        display: none !important;
    }
</style>
<?php endif; ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>