<?php

use app\models\Ajanlatkeres;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

$this->title = 'Beérkezett Ajánlatkérések';
?>
<div class="admin-dashboard">

    <div class="dashboard-header mb-4">
        <h1><?= Html::encode($this->title) ?></h1>
        <p class="subtitle">Beérkezett ügyfél megkeresések és válaszadási határidők (SLA)</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon icon-total"><i class="fas fa-envelope"></i></div>
            <div class="stat-info">
                <span class="stat-label">Összes Ajánlatkérés</span>
                <span class="stat-number"><?= Html::encode($totalCount) ?></span>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon icon-ai"><i class="fas fa-robot"></i></div>
            <div class="stat-info">
                <span class="stat-label">AI Automatizáció Projekt</span>
                <span class="stat-number"><?= Html::encode($aiCount) ?></span>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon icon-3d"><i class="fas fa-cubes"></i></div>
            <div class="stat-info">
                <span class="stat-label">3D Tervezés Projekt</span>
                <span class="stat-number"><?= Html::encode($count3d) ?></span>
            </div>
        </div>
    </div>

    <div class="custom-table-wrapper shadow-sm">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'tableOptions' => ['class' => 'custom-table m-0'],
            'layout' => "{summary}\n{items}\n<div class='p-3 border-top d-flex justify-content-center'>{pager}</div>",
            'rowOptions' => function ($model) {
                if ($model->is_read == 0) {
                    return ['class' => 'unread-row'];
                }
                return [];
            },
            'summary' => '<div class="p-3 text-muted text-sm border-bottom">Összesen <b>{totalCount}</b> ajánlatkérés található. Megjelenítve: {begin} - {end}</div>',
            'columns' => [
                [
                    'attribute' => 'nev',
                    'contentOptions' => ['class' => 'font-medium text-dark', 'style' => 'vertical-align: middle;'],
                ],
                [
                    'attribute' => 'email',
                    'format' => 'email',
                    'contentOptions' => ['class' => 'email-cell', 'style' => 'vertical-align: middle;'],
                ],
                [
                    'attribute' => 'targy',
                    'contentOptions' => ['style' => 'vertical-align: middle;'],
                ],
                [
                    'attribute' => 'tipus',
                    'filter' => ['ai' => 'AI Automatizáció', '3d' => '3D Tervezés'],
                    'format' => 'raw',
                    'value' => function ($model) {
                        if ($model->tipus === 'ai') {
                            return '<span class="type-tag tag-ai"><i class="fas fa-robot"></i> AI Projekt</span>';
                        } elseif ($model->tipus === '3d') {
                            return '<span class="type-tag tag-3d"><i class="fas fa-cubes"></i> 3D Tervezés</span>';
                        }
                        return '<span class="type-tag tag-general">Általános</span>';
                    },
                    'contentOptions' => ['style' => 'width: 160px; vertical-align: middle;'],
                ],
                [
                    'attribute' => 'letrehozva_at',
                    'value' => function ($model) {
                        return date('Y.m.d. H:i', strtotime($model->letrehozva_at));
                    },
                    'contentOptions' => ['style' => 'width: 150px; vertical-align: middle;', 'class' => 'text-muted'],
                ],
                [
                    'header' => 'Válaszadási Határidő',
                    'format' => 'raw',
                    'value' => function ($model) {
                        if ($model->is_read == 1) {
                            return '<span class="sla-status text-success"><i class="fas fa-check-circle"></i> Megválaszolva</span>';
                        }

                        $hatarido = strtotime($model->letrehozva_at) + (24 * 60 * 60);
                        $most = time();
                        $hatralevoMasodperc = $hatarido - $most;

                        if ($hatralevoMasodperc <= 0) {
                            return '<span class="sla-badge badge-danger"><i class="fas fa-exclamation-triangle"></i> LEJÁRT!</span>';
                        }

                        $hatralevoOra = ceil($hatralevoMasodperc / 3600);

                        if ($hatralevoOra <= 4) {
                            return '<span class="sla-badge badge-warning"><i class="fas fa-hourglass-half"></i> Sürgős: ' . $hatralevoOra . ' óra</span>';
                        }

                        return '<span class="sla-badge badge-normal"><i class="fas fa-clock"></i> ' . $hatralevoOra . ' óra maradt</span>';
                    },
                    'contentOptions' => ['style' => 'width: 180px; vertical-align: middle;'],
                ],
                [
                    'class' => ActionColumn::className(),
                    'header' => 'Műveletek',
                    'headerOptions' => ['style' => 'width: 180px; text-align: center; font-weight:600; color:#1d1d1f;'],
                    'contentOptions' => ['style' => 'text-align: center; vertical-align: middle; white-space: nowrap;'],
                    'template' => '{view} {fastReply} {update} {delete}',
                    'urlCreator' => function ($action, Ajanlatkeres $model, $key, $index, $column) {
                        return Url::toRoute([$action, 'id' => $model->id]);
                    },
                    'buttons' => [
                        'view' => function ($url, $model) {
                            return Html::a('<i class="fas fa-eye"></i>', $url, [
                                'class' => 'btn-action btn-view',
                                'title' => 'Megtekintés',
                                'data-toggle' => 'tooltip'
                            ]);
                        },
                        'fastReply' => function ($url, $model) {
                            $projektNev = ($model->tipus === 'ai') ? 'AI Automatizáció' : '3D Tervezés';
                            $subject = rawurlencode("Kapcsolatfelvétel: " . $projektNev . " ajánlatkérés");
                            $body = rawurlencode("Tisztelt " . $model->nev . "!\n\nKöszönjük megkeresését. Átolvastuk az ajánlatkérését, és...");
                            
                            $outlookUrl = "mailto:" . $model->email . "?subject=" . $subject . "&body=" . $body;
                            $gmailUrl = "https://mail.google.com/mail/?view=cm&fs=1&to=" . $model->email . "&su=" . $subject . "&body=" . $body;

                            return '<div class="btn-group" style="display: inline-block; vertical-align: middle;">
                                <a href="#" class="btn-action btn-reply dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Gyors kapcsolatfelvétel">
                                    <i class="fas fa-paper-plane"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right shadow border-0 py-1" style="min-width: 160px; border-radius: 10px; font-size:13px;">
                                    <a class="dropdown-item py-2" href="' . $gmailUrl . '" target="_blank">
                                        <i class="fab fa-google text-danger mr-2"></i> Gmail (Web)
                                    </a>
                                    <a class="dropdown-item py-2" href="' . $outlookUrl . '">
                                        <i class="fab fa-windows text-primary mr-2"></i> Kliens / Outlook
                                    </a>
                                </div>
                            </div>';
                        },
                        'update' => function ($url, $model) {
                            return Html::a('<i class="fas fa-edit"></i>', $url, [
                                'class' => 'btn-action btn-update',
                                'title' => 'Szerkesztés',
                                'data-toggle' => 'tooltip'
                            ]);
                        },
                        'delete' => function ($url, $model) {
                            // Csak akkor rajzolja ki, ha az RBAC szerint admin a bejelentkezett felhasználó
                            if (!Yii::$app->user->can('admin')) {
                                return '';
                            }
                            return Html::a('<i class="fas fa-trash-alt"></i>', $url, [
                                'class' => 'btn-action btn-delete',
                                'title' => 'Törlés',
                                'data-toggle' => 'tooltip',
                                'data' => [
                                    'confirm' => 'Biztosan törölni szeretnéd ezt az ajánlatkérést?',
                                    'method' => 'post',
                                ],
                            ]);
                        },
                    ],
                ],
            ],
        ]); ?>
    </div>
</div>

<style>
    .admin-dashboard {
        animation: fadeIn 0.4s ease-out;
    }
    .dashboard-header h1 {
        font-size: 26px;
        font-weight: 600;
        color: #1d1d1f;
        margin: 0 0 4px 0;
    }
    .dashboard-header .subtitle {
        color: #86868b;
        margin: 0;
        font-size: 14px;
    }
    
    /* Statisztikai kártyák */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    .stat-card {
        background: #f5f5f7;
        border-radius: 16px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        border: 1px solid #e5e5e7;
    }
    .stat-icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }
    .icon-total { background: rgba(0,0,0,0.04); color: #1d1d1f; }
    .icon-ai { background: rgba(0, 122, 255, 0.08); color: #007aff; }
    .icon-3d { background: rgba(175, 82, 222, 0.08); color: #af52de; }
    .stat-label { display: block; color: #86868b; font-size: 11px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px; }
    .stat-number { display: block; color: #1d1d1f; font-size: 24px; font-weight: 600; margin-top: 2px; }

    /* Custom GridView stílusok */
    .custom-table-wrapper {
        background: #fff;
        border-radius: 14px;
        overflow: hidden;
        border: 1px solid #e5e5e7;
    }
    .custom-table {
        width: 100%;
        border-collapse: collapse;
    }
    .custom-table th {
        background: #f5f5f7 !important;
        color: #1d1d1f !important;
        padding: 14px 16px !important;
        font-weight: 600 !important;
        border-bottom: 1px solid #e5e5e7 !important;
        font-size: 13px;
    }
    /* Yii2 szűrő sor (Filters) finomítása */
    .custom-table .filters input, .custom-table .filters select {
        border: 1px solid #d2d2d7;
        border-radius: 6px;
        padding: 6px 10px;
        font-size: 13px;
        background: #fff;
        outline: none;
        box-shadow: none;
        transition: 0.2s;
    }
    .custom-table .filters input:focus, .custom-table .filters select:focus {
        border-color: #007aff;
    }
    .custom-table td {
        padding: 14px 16px !important;
        border-bottom: 1px solid #f5f5f7 !important;
        font-size: 14px;
        color: #333336;
    }
    .custom-table tbody tr:hover {
        background: #fafafa;
    }
    .unread-row {
        background: rgba(0, 122, 255, 0.02) !important;
    }
    .unread-row td {
        font-weight: 500;
        color: #000;
    }

    /* Tag-ek és Badge-ek */
    .type-tag {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .tag-ai { background: rgba(0, 122, 255, 0.06); color: #007aff; }
    .tag-3d { background: rgba(175, 82, 222, 0.06); color: #af52de; }
    .tag-general { background: #f5f5f7; color: #86868b; }

    /* SLA elemek */
    .sla-status { font-size: 13px; font-weight: 500; display: inline-flex; align-items: center; gap: 6px; }
    .sla-badge { padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 500; display: inline-flex; align-items: center; gap: 6px; }
    .sla-badge.badge-danger { background: rgba(255, 59, 48, 0.1); color: #ff3b30; }
    .sla-badge.badge-warning { background: rgba(255, 149, 0, 0.1); color: #ff9500; }
    .sla-badge.badge-normal { background: #1d1d1f; color: #fff; }

    .email-cell a { color: #007aff; text-decoration: none; }
    .email-cell a:hover { text-decoration: underline; }
    .font-medium { font-weight: 500; }

    /* Modern Akció gombok */
    .btn-action {
        width: 30px;
        height: 30px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none !important;
        transition: 0.2s;
        border: none;
        background: #f5f5f7;
        color: #1d1d1f;
        cursor: pointer;
    }
    .btn-action:hover { background: #e5e5e7; color: #000; }
    .btn-reply { color: #34c759; }
    .btn-reply:hover { background: rgba(52, 199, 89, 0.1); }
    .btn-update { color: #ff9500; }
    .btn-update:hover { background: rgba(255, 149, 0, 0.1); }
    .btn-delete { color: #ff3b30; }
    .btn-delete:hover { background: #ff3b30; color: #fff; }

    /* Yii2 Alapértelmezett Lapozó (Pager) finomítása Apple-stílusra */
    .pagination { display: flex; gap: 4px; padding: 0; margin: 0; list-style: none; }
    .pagination li a, .pagination li span { 
        padding: 6px 12px; border-radius: 6px; color: #1d1d1f; text-decoration: none; font-size: 13px; font-weight: 500; background: #f5f5f7;
    }
    .pagination li.active span { background: #007aff; color: #fff; }
    .pagination li.disabled span { color: #aeaeaif; background: #f5f5f7; cursor: not-allowed; }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(8px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>