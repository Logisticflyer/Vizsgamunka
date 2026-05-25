<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Producttype $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="producttype-form">

    <?php $form = ActiveForm::begin(); ?>

     <div class="col-md-6">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title"></h3>
            </div>    
            
            <div class="card-body">
                <div class ="form-group">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                </div>

                <div class ="form-group">
                    <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-succes']) ?>
                </div>
            </div>
        </div>
    </div>
    
    <?php ActiveForm::end(); ?>

</div>
