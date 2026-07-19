<?php
use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'AI Automatizáció';
?>

<div class="ai-index p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>AI Automatizáció</h1>
        <?= Html::a('Új feladat indítása', ['create'], ['class' => 'btn btn-primary']) ?>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'prompt:ntext',
            'status', 
            'created_at:datetime',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>