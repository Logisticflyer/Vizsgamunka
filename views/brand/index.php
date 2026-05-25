<?php
/** @var yii\web\View $this */
/** @var yii\data\ArrayDataProvider $dataProvider */

use yii\grid\GridView;

echo "<h1>Márkák listája</h1>";
echo GridView::widget([
    'dataProvider' => $dataProvider,
]);