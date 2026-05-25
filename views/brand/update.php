<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Brand $model */

$this->title = Yii::t('app', 'Update Brand: {name}'. [
    'name' => $model->name,
]);

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Brands'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name,  'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="brand-update">
    
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>