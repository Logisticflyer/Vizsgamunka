<?php

use yii\bootstrap5\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var app\models\AiKnowledgeSearch|mixed $searchModel */

$this->title = 'AI Tudásbázis';
?>

<div class="container-fluid py-4">
    <!-- Fejléc Sáv -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 pb-3 border-bottom border-secondary border-opacity-25 gap-3">
        <div>
            <h2 class="fw-bold text-white mb-1">
                <i class="fas fa-database text-warning me-2"></i> AI Tudásbázis
            </h2>
            <span class="text-muted small" style="color: #94a3b8 !important;">Kezeld és bővítsd az AI kontextusokat, szabályokat és prompt paramétereket.</span>
        </div>
        <div>
            <?= Html::a('<i class="fas fa-plus me-2"></i> Új bejegyzés', ['create'], [
                'class' => 'btn btn-primary rounded-3 px-4 py-2 fw-semibold shadow-sm',
                'style' => 'background-color: #0071e3; border: none;'
            ]) ?>
        </div>
    </div>

    <!-- Fő Kártya / GridView Konténer -->
    <div class="card rounded-4 border-0 shadow-sm overflow-hidden" style="background: #141824; border: 1px solid rgba(255, 255, 255, 0.08) !important;">
        <div class="card-body p-0">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => null, // Szűrősor eltávolítva
                'layout' => "{items}\n<div class='d-flex justify-content-between align-items-center px-4 py-3 border-top border-secondary border-opacity-25'>{summary}{pager}</div>",
                'summary' => '<div class="small" style="color: #94a3b8;"><i class="fas fa-layer-group me-1"></i> Összesen <span class="text-white fw-bold">{totalCount}</span> bejegyzés</div>',
                'tableOptions' => ['class' => 'table table-dark table-hover align-middle mb-0', 'style' => 'background: transparent;'],
                'headerRowOptions' => ['class' => 'border-bottom border-secondary border-opacity-25', 'style' => 'font-size: 0.8rem; letter-spacing: 0.5px;'],
                'emptyText' => '
                    <div class="text-center py-5" style="color: #64748b;">
                        <i class="fas fa-folder-open fa-3x mb-3 opacity-50 d-block"></i>
                        <p class="mb-0 fs-6">Nincs találat a tudásbázisban.</p>
                    </div>
                ',
                'columns' => [
                    [
                        'class' => 'yii\grid\SerialColumn',
                        'header' => '#',
                        'headerOptions' => ['style' => 'width: 50px; text-align: center; color: #94a3b8;'],
                        'contentOptions' => ['class' => 'text-center text-muted fw-bold', 'style' => 'font-size: 0.85rem;'],
                    ],

                    // Kategória
                    [
                        'attribute' => isset($searchModel->kategoria) ? 'kategoria' : 'category',
                        'label' => 'Kategória',
                        'format' => 'raw',
                        'headerOptions' => ['style' => 'width: 180px;'],
                        'value' => function ($model) {
                            $val = $model->kategoria ?? $model->category ?? 'Általános';
                            return '<span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-2.5 py-1.5 rounded-2 fs-7">' 
                                . Html::encode($val) . '</span>';
                        },
                    ],

                    // Cím
                    [
                        'attribute' => isset($searchModel->cim) ? 'cim' : 'title',
                        'label' => 'Cím',
                        'format' => 'raw',
                        'headerOptions' => ['style' => 'width: 250px;'],
                        'value' => function ($model) {
                            $val = $model->cim ?? $model->title ?? '-';
                            return '<div class="fw-semibold text-white fs-6">' . Html::encode($val) . '</div>';
                        },
                    ],

                    // Tartalom
                    [
                        'attribute' => isset($searchModel->tartalom) ? 'tartalom' : 'content',
                        'label' => 'Tartalom',
                        'format' => 'raw',
                        'value' => function ($model) {
                            $val = $model->tartalom ?? $model->content ?? '-';
                            return '<span style="color: #cbd5e1; font-size: 0.88rem;">' 
                                . Html::encode(mb_strimwidth(strip_tags($val), 0, 110, '...')) 
                                . '</span>';
                        },
                    ],

                    // Művelet gombok
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => 'Műveletek',
                        'headerOptions' => ['class' => 'text-end pe-4', 'style' => 'width: 140px; color: #94a3b8;'],
                        'contentOptions' => ['class' => 'text-end pe-4', 'style' => 'white-space: nowrap;'],
                        'template' => '<div class="d-inline-flex gap-1">{view} {update} {delete}</div>',
                        'buttons' => [
                            'view' => function ($url) {
                                return Html::a('<i class="fas fa-eye"></i>', $url, [
                                    'class' => 'btn btn-sm rounded-2 border-0',
                                    'style' => 'background: rgba(56, 189, 248, 0.12); color: #38bdf8; width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;',
                                    'title' => 'Megtekintés',
                                ]);
                            },
                            'update' => function ($url) {
                                return Html::a('<i class="fas fa-pen"></i>', $url, [
                                    'class' => 'btn btn-sm rounded-2 border-0',
                                    'style' => 'background: rgba(251, 191, 36, 0.12); color: #fbbf24; width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;',
                                    'title' => 'Szerkesztés',
                                ]);
                            },
                            'delete' => function ($url) {
                                return Html::a('<i class="fas fa-trash-alt"></i>', $url, [
                                    'class' => 'btn btn-sm rounded-2 border-0',
                                    'style' => 'background: rgba(248, 113, 113, 0.12); color: #f87171; width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;',
                                    'title' => 'Törlés',
                                    'data-confirm' => 'Biztosan törölni szeretnéd ezt a bejegyzést?',
                                    'data-method' => 'post',
                                ]);
                            },
                        ],
                    ],
                ],
                'pager' => [
                    'class' => 'yii\bootstrap5\LinkPager',
                    'options' => ['class' => 'pagination pagination-sm mb-0'],
                    'linkContainerOptions' => ['class' => 'page-item'],
                    'linkOptions' => ['class' => 'page-link bg-dark text-white border-secondary border-opacity-25'],
                ],
            ]); ?>
        </div>
    </div>
</div>

<style>
.grid-view th a {
    color: #38bdf8 !important;
    text-decoration: none !important;
    font-weight: 600 !important;
    transition: color 0.2s ease;
}

.grid-view th a:hover {
    color: #7dd3fc !important;
}

.table-hover tbody tr:hover {
    background-color: rgba(56, 189, 248, 0.05) !important;
}

.pagination .page-link {
    background-color: #1e293b !important;
    border-color: #334155 !important;
    color: #94a3b8 !important;
}

.pagination .page-item.active .page-link {
    background-color: #0071e3 !important;
    border-color: #0071e3 !important;
    color: #ffffff !important;
}
</style>