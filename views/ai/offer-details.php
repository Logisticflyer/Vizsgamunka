<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Ajánlatkérés Részletei #' . $offer->id;

// Adatok biztonságos kinyerése
$clientName = $offer->name ?? $offer->nev ?? $offer->user_name ?? 'Névtelen';
$clientEmail = $offer->email ?? $offer->user_email ?? '-';
$clientPhone = $offer->phone ?? $offer->user_phone ?? '-';
$companyName = $offer->company_name ?? '-';

$title = $offer->project_title ?? $offer->targy ?? $offer->title ?? 'Ajánlatkérés';
$message = $offer->message ?? $offer->leiras ?? $offer->description ?? 'Nincs leírás megadva.';

$chatContext = $offer->chat_context ?? $offer->chat_history ?? null;

$rawType = $offer->tipus ?? $offer->type ?? '';
$is3D = ($rawType === '3D Tervezés' || $rawType === '3D' || $rawType === '3d_design');

// Dátum formázása
$rawDate = $offer->created_at ?? null;
if ($rawDate && is_numeric($rawDate)) {
    $formattedDate = date('Y-m-d H:i:s', (int)$rawDate);
} elseif ($rawDate) {
    $formattedDate = date('Y-m-d H:i:s', strtotime($rawDate));
} else {
    $formattedDate = '-';
}

$status = strtolower($offer->status ?? 'pending');

// Segédfüggvény a blokkos szövegek automatikus strukturálásához és kiemeléséhez
function renderFormattedBrief($rawText) {
    $encoded = Html::encode($rawText);
    
    $headers = [
        'ÜGYFÉL FŐ CÉLJA:',
        'IGÉNYELT AUTOMATIZÁCIÓK & MODULOK:',
        'IGÉNYELT AUTOMATIZÁCIÓK:',
        'JAVASOLT TECHNOLÓGIAI STACK:',
        'ADMINISZTRÁTOR TEENDŐJE:'
    ];

    foreach ($headers as $header) {
        $replacement = '<div class="text-info fw-bold fs-6 mt-3 mb-1 text-uppercase" style="letter-spacing: 0.5px;"><i class="fas fa-chevron-right me-1 small"></i> ' . $header . '</div>';
        $encoded = str_replace($header, $replacement, $encoded);
    }

    return nl2br($encoded);
}
?>

<style>
/* Magas kontrasztú, szellős sötét téma */
.detail-card {
    background-color: #141824 !important;
    border: 1px solid rgba(255, 255, 255, 0.12) !important;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.6) !important;
    border-radius: 1rem !important;
}

.detail-section-bg {
    background-color: #1e293b !important;
    border: 1px solid rgba(255, 255, 255, 0.08) !important;
    border-radius: 0.75rem !important;
}

.text-slate-light {
    color: #cbd5e1 !important;
}

/* JAVÍTOTT, KINÉZETRE ÉS OLVASHATÓSÁGRA OPTIMALIZÁLT SZÖVEG DOBOZ */
.box-brief-output {
    background-color: #0f172a !important;
    border: 1px solid #334155 !important;
    color: #f1f5f9 !important;
    font-family: 'Inter', system-ui, -apple-system, sans-serif !important;
    font-size: 0.98rem !important;
    line-height: 1.75 !important;
    letter-spacing: 0.2px;
    border-radius: 0.75rem;
    padding: 1.5rem;
}

.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: rgba(0, 0, 0, 0.3);
    border-radius: 4px;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #334155;
    border-radius: 4px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #38bdf8;
}
</style>

<div class="container-fluid py-4" style="background-color: #0b0d12; min-height: 100vh; font-family: 'Inter', system-ui, sans-serif;">
    <div class="container" style="max-width: 950px;">

        <!-- TOP NAV & BADGE -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <a href="<?= Url::to(['/ai/offers']) ?>" class="btn btn-outline-light rounded-pill px-4 py-2 fw-medium shadow-sm">
                <i class="fas fa-arrow-left me-2"></i>Vissza az Ajánlatokhoz
            </a>
            
            <div>
                <?php if ($is3D): ?>
                    <span class="badge text-white px-3 py-2 rounded-pill fs-6 shadow-sm" style="background-color: #6f42c1; border: 1px solid #a855f7;">
                        <i class="fas fa-cube me-2"></i>3D CAD Ajánlat
                    </span>
                <?php else: ?>
                    <span class="badge bg-primary text-white px-3 py-2 rounded-pill fs-6 shadow-sm" style="border: 1px solid #38bdf8;">
                        <i class="fas fa-robot me-2"></i>AI Lead
                    </span>
                <?php endif; ?>
            </div>
        </div>

        <!-- FŐ KÁRTYA -->
        <div class="card detail-card text-white overflow-hidden">
            
            <!-- FEJLÉC -->
            <div class="card-header p-4 border-bottom border-secondary bg-dark bg-opacity-40 d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h2 class="fw-bold mb-1 text-white d-flex align-items-center gap-2">
                        <i class="fas fa-file-invoice text-primary"></i> Ajánlatkérés #<?= $offer->id ?>
                    </h2>
                    <span class="text-slate-light small">
                        <i class="far fa-clock me-1 text-info"></i> Beérkezett: <strong class="text-white"><?= Html::encode($formattedDate) ?></strong>
                    </span>
                </div>

                <div>
                    <?php if ($status === 'pending' || $status === 'new' || $status === 'új'): ?>
                        <span class="badge bg-warning text-dark px-4 py-2 rounded-pill fw-bold fs-6 shadow-sm border border-warning">
                            ÚJ MEGKERESÉS
                        </span>
                    <?php elseif ($status === 'processed' || $status === 'feldolgozva'): ?>
                        <span class="badge bg-success text-white px-4 py-2 rounded-pill fw-bold fs-6 shadow-sm">
                            FELDOLGOZVA
                        </span>
                    <?php else: ?>
                        <span class="badge bg-secondary text-white px-4 py-2 rounded-pill fw-bold fs-6 shadow-sm">
                            <?= Html::encode(strtoupper($status)) ?>
                        </span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card-body p-4 p-md-5">
                
                <!-- 1. ÜGYFÉL INFORMÁCIÓK -->
                <div class="mb-5">
                    <h5 class="text-info fw-bold mb-3 d-flex align-items-center gap-2">
                        <i class="fas fa-user-circle fs-4"></i> Ügyfél Információk
                    </h5>
                    
                    <div class="detail-section-bg p-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <span class="text-slate-light d-block small fw-bold text-uppercase mb-1" style="letter-spacing: 0.5px;">Ügyfél Neve / Cégnév</span>
                                <strong class="fs-5 text-white d-block"><?= Html::encode($clientName) ?></strong>
                                <?php if ($companyName !== '-'): ?>
                                    <span class="badge bg-primary bg-opacity-20 text-info mt-1"><?= Html::encode($companyName) ?></span>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-6">
                                <span class="text-slate-light d-block small fw-bold text-uppercase mb-1" style="letter-spacing: 0.5px;">E-mail Cím</span>
                                <a href="mailto:<?= Html::encode($clientEmail) ?>" class="text-info text-decoration-none fs-5 fw-bold d-inline-block">
                                    <i class="far fa-envelope me-2"></i><?= Html::encode($clientEmail) ?>
                                </a>
                            </div>

                            <?php if ($clientPhone !== '-'): ?>
                                <div class="col-md-6">
                                    <span class="text-slate-light d-block small fw-bold text-uppercase mb-1" style="letter-spacing: 0.5px;">Telefonszám</span>
                                    <a href="tel:<?= Html::encode($clientPhone) ?>" class="text-white text-decoration-none fs-6 fw-medium">
                                        <i class="fas fa-phone me-2 text-success"></i><?= Html::encode($clientPhone) ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- 2. PROJEKT SPECIFIKÁCIÓ & LEÍRÁS -->
                <div class="mb-4">
                    <h5 class="text-info fw-bold mb-3 d-flex align-items-center gap-2">
                        <i class="fas fa-list-alt fs-4"></i> Projekt / Igény Részletei
                    </h5>

                    <div class="detail-section-bg p-4 mb-4">
                        <span class="text-slate-light d-block small fw-bold text-uppercase mb-1" style="letter-spacing: 0.5px;">Projekt Címe / Tárgya</span>
                        <h4 class="fw-bold text-white mb-3"><?= Html::encode($title) ?></h4>

                        <span class="text-slate-light d-block small fw-bold text-uppercase mb-2" style="letter-spacing: 0.5px;">Üzenet / Leírás</span>
                        <div class="box-brief-output">
                            <?= renderFormattedBrief($message) ?>
                        </div>
                    </div>

                    <!-- 3. CHAT ELŐZMÉNYEK / BRIEF -->
                    <?php if (!empty($chatContext)): ?>
                        <div class="detail-section-bg p-4">
                            <span class="text-info d-block small fw-bold text-uppercase mb-2" style="letter-spacing: 0.5px;">
                                <i class="fas fa-comments me-1"></i> AI Chat Előzmények & Részletes Brief
                            </span>
                            <div class="box-brief-output custom-scrollbar" style="max-height: 350px; overflow-y: auto;">
                                <?= renderFormattedBrief($chatContext) ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

            </div>

            <!-- LÁBLÉC GOMBOK -->
            <div class="card-footer p-4 border-top border-secondary bg-dark bg-opacity-40 d-flex justify-content-between align-items-center flex-wrap gap-3">
                <a href="<?= Url::to(['/ai/offers']) ?>" class="btn btn-outline-secondary text-white rounded-pill px-4">
                    <i class="fas fa-arrow-left me-2"></i>Vissza
                </a>

                <a href="mailto:<?= Html::encode($clientEmail) ?>?subject=Válasz: <?= urlencode($title) ?>" class="btn btn-success btn-lg rounded-pill px-5 fw-bold shadow">
                    <i class="fas fa-paper-plane me-2"></i>Válasz Küldése e-mailben
                </a>
            </div>

        </div>

    </div>
</div>