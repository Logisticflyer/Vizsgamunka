<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Ajanlatkeres $model */

$this->title = Yii::t('app', 'Create Ajanlatkeres');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Ajanlatkeres'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ajanlatkeres-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
