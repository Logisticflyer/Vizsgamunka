<?php

use app\models\AiAutomation; // Ellenőrizd, hogy a modellneved ez-e (ha nem, írd át arra, ami neked van)!
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\AiSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'AI Automatizáció';
?>

<div class="ai-container" style="padding: 40px 20px; max-width: 1000px; margin: 0 auto;">
    
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h1 style="font-weight: 700; font-size: 32px; letter-spacing: -0.5px; color: #1d1d1f;"><?= Html::encode($this->title) ?></h1>
        <?= Html::a('<i class="fas fa-plus me-2"></i> Új feladat indítása', ['create'], [
            'class' => 'btn btn-dark', 
            'style' => 'border-radius: 20px; padding: 10px 24px; font-weight: 500; background: #000;'
        ]) ?>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 20px; overflow: hidden; background: #fff;">
        <div class="card-body p-0">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'tableOptions' => ['class' => 'table table-hover align-middle mb-0'],
                'summary' => '<div style="color: #86868b; font-size: 14px; padding: 15px;">Összesen {totalCount} feladat</div>',
                'columns' => [
                    [
                        'attribute' => 'prompt',
                        'value' => function ($model) {
                            return StringHelper::truncate($model->prompt, 60);
                        },
                        'contentOptions' => ['style' => 'font-weight: 500;'],
                    ],
                    [
                        'attribute' => 'status',
                        'format' => 'raw',
                        'value' => function ($model) {
                            $color = ($model->status === 'completed') ? '#d4edda' : '#f2f2f7';
                            $textColor = ($model->status === 'completed') ? '#155724' : '#1d1d1f';
                            return '<span class="badge" style="background: ' . $color . '; color: ' . $textColor . '; border-radius: 10px; font-weight: 500; padding: 6px 12px; text-transform: uppercase; font-size: 11px;">' . Html::encode($model->status) . '</span>';
                        },
                    ],
                    [
                        'attribute' => 'created_at',
                        'value' => function ($model) {
                            return Yii::$app->formatter->asDatetime($model->created_at, 'short');
                        },
                        'contentOptions' => ['style' => 'color: #86868b;'],
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => '',
                        'contentOptions' => ['style' => 'text-align: right; padding-right: 20px; white-space: nowrap;'],
                        'template' => '{view} {update} {delete}',
                        'buttons' => [
                            'view' => function($url, $model) {
                                return Html::a('<i class="fas fa-eye"></i>', $url, ['style' => 'color: #86868b; margin-right: 10px;']);
                            },
                            'update' => function($url, $model) {
                                return Html::a('<i class="fas fa-edit"></i>', $url, ['style' => 'color: #86868b; margin-right: 10px;']);
                            },
                            'delete' => function($url, $model) {
                                return Html::a('<i class="fas fa-trash"></i>', $url, [
                                    'style' => 'color: #ff3b30;',
                                    'data-method' => 'post',
                                    'data-confirm' => 'Biztosan törlöd?'
                                ]);
                            },
                        ],
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>

<style>
    .ai-container th a {
        color: #86868b !important;
        text-decoration: none !important;
        font-weight: 500;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .ai-container th a:hover { color: #1d1d1f !important; }
    .ai-container th { border-bottom: 1px solid #e5e5e7 !important; padding: 20px !important; }
    .ai-container td { padding: 20px !important; }

    .ai-container input.form-control {
        border: 1px solid #e5e5e7 !important;
        border-radius: 10px !important;
        padding: 8px 12px !important;
        transition: all 0.3s ease !important;
    }

    /* Amikor a mezőbe kattintasz, legyen lágyabb a fókusz */
    .ai-container input.form-control:focus {
        border-color: #0071e3 !important;
        box-shadow: 0 0 0 4px rgba(0, 113, 227, 0.1) !important;
    }
</style>