<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\AiTasks $model */

$this->title = Yii::t('app', 'Create Ai Tasks');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Ai Tasks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ai-tasks-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
