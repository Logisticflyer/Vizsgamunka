<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\AjanlatkeresSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="ajanlatkeres-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'nev') ?>

    <?= $form->field($model, 'email') ?>

    <?= $form->field($model, 'targy') ?>

    <?= $form->field($model, 'leiras') ?>

    <?php // echo $form->field($model, 'tipus') ?>

    <?php // echo $form->field($model, 'letrehozva_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
