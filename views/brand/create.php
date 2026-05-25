<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Brand $model */

$this->title = Yii::t('app', 'Create Brand');
$this->params['breadcrumbs'] [] = ['label' => Yii::t('app', 'Brands'), 'url' => ['index']];
$this->params['breadcrumbs'] [] = $this->title;
?>
<div class="brand-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>




