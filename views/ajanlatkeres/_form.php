<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Ajanlatkeres $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="ajanlatkeres-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nev')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'targy')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'leiras')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'tipus')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'letrehozva_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
