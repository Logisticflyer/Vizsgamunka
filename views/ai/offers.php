<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Beérkezett Ajánlatkérések | Admin Panel';

// Biztonságos modell-kinyerés
$offersList = $offers ?? ($dataProvider ? $dataProvider->getModels() : []);

// Statisztikák számítása
$totalCount = count($offersList);
$pendingCount = 0;
$aiCount = 0;
$count3d = 0;

foreach ($offersList as $o) {
    $status = strtolower($o->status ?? 'pending');
    if ($status === 'pending' || $status === 'new' || $status === 'új') {
        $pendingCount++;
    }
    
    $type = $o->tipus ?? $o->type ?? '';
    if ($type === '3D Tervezés' || $type === '3D' || $type === '3d_design') {
        $count3d++;
    } else {
        $aiCount++;
    }
}
?>

<style>
.admin-card-dark {
    background: #141824 !important;
    border: 1px solid rgba(255, 255, 255, 0.1) !important;
}

.text-slate-muted {
    color: #94a3b8 !important;
}

.text-slate-light {
    color: #cbd5e1 !important;
}

.badge-admin-panel {
    background-color: rgba(56, 189, 248, 0.15) !important;
    color: #38bdf8 !important;
    border: 1px solid rgba(56, 189, 248, 0.3) !important;
}

.offers-table-card {
    background-color: #141824 !important;
    border: 1px solid rgba(255, 255, 255, 0.12) !important;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5) !important;
}

.offers-search-bar {
    background-color: #0f172a !important;
    border-bottom: 1px solid #1e293b !important;
}

.dark-input {
    background-color: #1e293b !important;
    border: 1px solid #334155 !important;
    color: #f8fafc !important;
}

.dark-input:focus {
    border-color: #38bdf8 !important;
    box-shadow: 0 0 0 0.25rem rgba(56, 189, 248, 0.25) !important;
}

.dark-input::placeholder {
    color: #64748b !important;
}

.table-dark-custom {
    --bs-table-bg: #141824 !important;
    --bs-table-color: #f8fafc !important;
    --bs-table-hover-bg: #1e293b !important;
    --bs-table-hover-color: #ffffff !important;
    --bs-table-border-color: #1e293b !important;
    background-color: #141824 !important;
    color: #f8fafc !important;
}

.table-dark-custom > :not(caption) > * > * {
    background-color: inherit !important;
    color: inherit !important;
    border-bottom-color: #1e293b !important;
}

.table-dark-custom thead tr {
    background-color: #0f172a !important;
    color: #94a3b8 !important;
}

/* Interaktív sötét státusz lenyíló menü */
.status-select-dark {
    background-color: #0f172a !important;
    border: 1px solid #334155 !important;
    color: #f8fafc !important;
    font-size: 0.825rem;
    padding: 0.35rem 0.6rem;
    border-radius: 50rem;
    font-weight: 600;
    transition: all 0.2s ease;
}

.status-select-dark:focus {
    border-color: #38bdf8 !important;
    box-shadow: 0 0 0 0.2rem rgba(56, 189, 248, 0.2) !important;
}

.status-pending { color: #f59e0b !important; border-color: #f59e0b !important; }
.status-processed { color: #10b981 !important; border-color: #10b981 !important; }
.status-rejected { color: #ef4444 !important; border-color: #ef4444 !important; }
</style>

<div class="container-fluid py-4" style="background-color: #0b0d12; min-height: 100vh; font-family: 'Inter', system-ui, sans-serif;">
    <div class="container">

        <!-- FEJLÉC -->
        <div class="card border-0 shadow-lg mb-4 rounded-4 admin-card-dark">
            <div class="card-body p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <span class="badge badge-admin-panel px-3 py-2 rounded-pill mb-2 fw-semibold fs-6">
                        <i class="fas fa-shield-alt me-1"></i> Adminisztrációs Panel
                    </span>
                    <h2 class="fw-bold text-white mb-1"><i class="fas fa-inbox text-primary me-2"></i>Beérkezett Ajánlatkérések</h2>
                    <p class="text-slate-muted mb-0 small">Az AI asszisztens és a 3D CAD modul által előkészített leadek és megkeresések.</p>
                </div>
                <div>
                    <a href="<?= Url::to(['/ai/index']) ?>" class="btn btn-outline-light rounded-pill px-4 py-2 fw-medium">
                        <i class="fas fa-arrow-left me-2"></i>Vissza az Automatizációhoz
                    </a>
                </div>
            </div>
        </div>

        <!-- STATISZTIKAI KÁRTYÁK -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 p-3 h-100 admin-card-dark">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle p-3 bg-primary bg-opacity-20 text-primary">
                            <i class="fas fa-folder-open fa-2x"></i>
                        </div>
                        <div>
                            <span class="text-slate-muted small fw-semibold d-block">Összes Megkeresés</span>
                            <h3 class="fw-bold text-white mb-0"><?= $totalCount ?> db</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 p-3 h-100 admin-card-dark">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle p-3 bg-warning bg-opacity-20 text-warning">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                        <div>
                            <span class="text-slate-muted small fw-semibold d-block">Feldolgozásra Vár</span>
                            <h3 class="fw-bold text-white mb-0"><?= $pendingCount ?> db</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 p-3 h-100 admin-card-dark">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle p-3 bg-info bg-opacity-20 text-info">
                            <i class="fas fa-chart-pie fa-2x"></i>
                        </div>
                        <div>
                            <span class="text-slate-muted small fw-semibold d-block">Megoszlás (AI / 3D)</span>
                            <h3 class="fw-bold text-white mb-0"><?= $aiCount ?> AI / <?= $count3d ?> 3D</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- KERESŐ & TÁBLÁZAT KÁRTYA -->
        <div class="card border-0 shadow-lg rounded-4 overflow-hidden offers-table-card">
            
            <!-- KERESŐSÁV -->
            <div class="p-3 offers-search-bar d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="position-relative flex-grow-1" style="max-width: 400px;">
                    <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-3 text-slate-muted"></i>
                    <input type="text" id="offerSearchInput" class="form-control rounded-pill ps-5 dark-input" placeholder="Keresés név, e-mail vagy projekt alapján...">
                </div>
                <small class="text-slate-muted"><i class="fas fa-info-circle me-1"></i>A státusz a lenyíló menüben azonnal módosítható</small>
            </div>

            <!-- TÁBLÁZAT -->
            <div class="table-responsive">
                <table class="table table-dark-custom align-middle mb-0" id="offersTable">
                    <thead class="text-uppercase small" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                        <tr>
                            <th class="ps-4" style="width: 60px;">#ID</th>
                            <th style="width: 120px;">Típus</th>
                            <th style="width: 180px;">Ügyfél / Cégnév</th>
                            <th style="width: 200px;">E-mail cím</th>
                            <th>Projekt leírása</th>
                            <th style="width: 160px;">Dátum és Idő</th>
                            <th style="width: 160px;">Státusz</th>
                            <th class="text-end pe-4" style="width: 140px;">Műveletek</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($offersList)): ?>
                            <tr>
                                <td colspan="8" class="text-center py-5 text-slate-muted">
                                    <i class="fas fa-inbox fa-3x mb-3 d-block opacity-50"></i>
                                    Még nem érkezett egyetlen ajánlatkérés sem.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($offersList as $offer): ?>
                                <?php 
                                    $rawType = $offer->tipus ?? $offer->type ?? '';
                                    $is3D = ($rawType === '3D Tervezés' || $rawType === '3D' || $rawType === '3d_design');

                                    $clientName = $offer->nev ?? $offer->user_name ?? $offer->name ?? $offer->company_name ?? 'Névtelen';
                                    $clientEmail = $offer->email ?? $offer->user_email ?? '-';

                                    $title = $offer->targy ?? $offer->project_title ?? $offer->title ?? '';
                                    $desc = $offer->leiras ?? $offer->description ?? '';
                                    $displayText = !empty($title) ? $title : (!empty($desc) ? $desc : '3D CAD / AI Megkeresés');

                                    $rawDate = $offer->created_at ?? $offer->created_date ?? null;
                                    if ($rawDate) {
                                        if (is_numeric($rawDate)) {
                                            $formattedDate = date('Y-m-d H:i', (int)$rawDate);
                                        } else {
                                            $time = strtotime($rawDate);
                                            $formattedDate = ($time !== false) ? date('Y-m-d H:i', $time) : $rawDate;
                                        }
                                    } else {
                                        $formattedDate = '-';
                                    }

                                    $status = strtolower($offer->status ?? 'pending');
                                    
                                    // Státusz osztály meghatározása
                                    $statusClass = 'status-pending';
                                    if ($status === 'processed' || $status === 'feldolgozva' || $status === 'completed') {
                                        $statusClass = 'status-processed';
                                    } elseif ($status === 'rejected' || $status === 'elutasitva') {
                                        $statusClass = 'status-rejected';
                                    }
                                ?>
                                <tr>
                                    <td class="ps-4 fw-bold text-slate-muted">#<?= $offer->id ?></td>
                                    
                                    <td>
                                        <?php if ($is3D): ?>
                                            <span class="badge text-white px-2 py-1 rounded-pill" style="background-color: #6f42c1;">
                                                <i class="fas fa-cube me-1"></i>3D CAD
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-primary px-2 py-1 rounded-pill">
                                                <i class="fas fa-robot me-1"></i>AI Lead
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <td class="fw-bold text-white"><?= Html::encode($clientName) ?></td>
                                    
                                    <td>
                                        <a href="mailto:<?= Html::encode($clientEmail) ?>" class="text-decoration-none text-info fw-medium">
                                            <i class="far fa-envelope me-1"></i><?= Html::encode($clientEmail) ?>
                                        </a>
                                    </td>

                                    <td class="text-truncate text-slate-light" style="max-width: 260px;" title="<?= Html::encode($displayText) ?>">
                                        <?= Html::encode($displayText) ?>
                                    </td>

                                    <td class="small text-slate-muted">
                                        <i class="far fa-clock me-1"></i><?= Html::encode($formattedDate) ?>
                                    </td>

                                    <!-- INTERAKTÍV STÁTUSZ VÁLASZTÓ -->
                                    <td>
                                        <select class="form-select form-select-sm status-select-dark status-changer <?= $statusClass ?>" data-id="<?= $offer->id ?>">
                                            <option value="pending" <?= ($status === 'pending' || $status === 'new' || $status === 'új') ? 'selected' : '' ?>>Új Megkeresés</option>
                                            <option value="processed" <?= ($status === 'processed' || $status === 'feldolgozva' || $status === 'completed') ? 'selected' : '' ?>>Feldolgozva / Kész</option>
                                            <option value="rejected" <?= ($status === 'rejected' || $status === 'elutasitva') ? 'selected' : '' ?>>Elutasítva</option>
                                        </select>
                                    </td>

                                    <td class="text-end pe-4">
                                        <a href="<?= Url::to(['/ai/offer-details', 'id' => $offer->id]) ?>" class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm fw-medium">
                                            Részletek <i class="fas fa-arrow-right ms-1"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // 1. Kereső funkció
    const searchInput = document.getElementById("offerSearchInput");
    const tableRows = document.querySelectorAll("#offersTable tbody tr");

    if (searchInput) {
        searchInput.addEventListener("keyup", function() {
            const query = this.value.toLowerCase().trim();
            tableRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(query) ? "" : "none";
            });
        });
    }

    // 2. AJAX Státusz Módosítás
    const statusChangers = document.querySelectorAll(".status-changer");
    statusChangers.forEach(select => {
        select.addEventListener("change", function() {
            const offerId = this.getAttribute("data-id");
            const newStatus = this.value;
            const selectElement = this;

            // Színosztály frissítése azonnal
            selectElement.classList.remove('status-pending', 'status-processed', 'status-rejected');
            if (newStatus === 'processed') {
                selectElement.classList.add('status-processed');
            } else if (newStatus === 'rejected') {
                selectElement.classList.add('status-rejected');
            } else {
                selectElement.classList.add('status-pending');
            }

            // AJAX kérés mentéshez
            fetch('<?= Url::to(['/ai/update-status']) ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-CSRF-Token': '<?= Yii::$app->request->getCsrfToken() ?>'
                },
                body: `id=${encodeURIComponent(offerId)}&status=${encodeURIComponent(newStatus)}`
            })
            .then(res => res.json())
            .then(data => {
                if (!data.success) {
                    alert("Hiba történt a státusz frissítésekor: " + (data.error || "Ismeretlen hiba"));
                }
            })
            .catch(err => {
                console.error("AJAX hiba:", err);
                alert("Hálózati hiba történt a mentés során.");
            });
        });
    });
});
</script>