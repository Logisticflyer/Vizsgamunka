<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Producttype $model */

$this->title = Yii::t('app', 'Create Producttype');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Producttypes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="producttype-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
