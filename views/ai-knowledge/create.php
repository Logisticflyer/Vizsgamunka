<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\AiKnowledge $model */

$this->title = Yii::t('app', 'Create Ai Knowledge');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Ai Knowledges'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ai-knowledge-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
