<?php
/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Beérkezett Ajánlatkérések (Leadek)';

$models = $dataProvider->getModels();
$totalCount = $dataProvider->getTotalCount();

// Segédfüggvény a státuszjelvényekhez
function renderStatusBadge($status) {
    switch ($status) {
        case 'in_progress':
            return '<span class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill fw-semibold status-badge" style="font-size: 11px;">Feldolgozás alatt</span>';
        case 'completed':
            return '<span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill fw-semibold status-badge" style="font-size: 11px;">Ajánlat Elküldve</span>';
        default:
            return '<span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill fw-semibold status-badge" style="font-size: 11px;">Új Megkeresés</span>';
    }
}
?>

<div class="container-fluid py-4" style="max-width: 1400px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
    
    <!-- FEJLÉC SZEKCIÓ -->
    <div class="p-4 mb-4 bg-white rounded-4 shadow-sm border border-light d-flex flex-wrap align-items-center justify-content-between">
        <div>
            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-bold mb-2" style="font-size: 11px;">
                <i class="fas fa-shield-alt me-1"></i> Adminisztrációs Panel
            </span>
            <h2 class="fw-bold text-dark mb-0"><i class="fas fa-inbox text-primary me-2"></i> Beérkezett Ajánlatkérések</h2>
            <p class="text-muted small mb-0 mt-1">Az AI asszisztens által előkészített leadek és strukturált projekttervek.</p>
        </div>
        <div>
            <a href="<?= Url::to(['ai/automation']) ?>" class="btn btn-outline-secondary rounded-pill px-4 shadow-sm fw-semibold">
                <i class="fas fa-arrow-left me-2"></i> Vissza az Automatizációhoz
            </a>
        </div>
    </div>

    <!-- STATISZTIKAI KÁRTYÁK (KPI) -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="p-3 bg-white rounded-4 shadow-sm border border-light d-flex align-items-center">
                <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3 me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                    <i class="fas fa-inbox fs-4"></i>
                </div>
                <div>
                    <div class="text-muted small">Összes Megkeresés</div>
                    <div class="fs-4 fw-bold text-dark"><?= $totalCount ?> db</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-3 bg-white rounded-4 shadow-sm border border-light d-flex align-items-center">
                <div class="bg-warning bg-opacity-10 text-warning rounded-circle p-3 me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                    <i class="fas fa-clock fs-4"></i>
                </div>
                <div>
                    <div class="text-muted small">Feldolgozásra Vár</div>
                    <div class="fs-4 fw-bold text-dark"><?= $totalCount ?> db</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-3 bg-white rounded-4 shadow-sm border border-light d-flex align-items-center">
                <div class="bg-success bg-opacity-10 text-success rounded-circle p-3 me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                    <i class="fas fa-robot fs-4"></i>
                </div>
                <div>
                    <div class="text-muted small">AI-Előkészített Leadek</div>
                    <div class="fs-4 fw-bold text-dark">100%</div>
                </div>
            </div>
        </div>
    </div>

    <!-- ADATTÁBLÁZAT KÁRTYA -->
    <div class="card shadow-sm border border-light rounded-4 bg-white overflow-hidden">
        
        <!-- Táblázat Eszköztár (Kereső) -->
        <div class="p-3 border-bottom border-light bg-light d-flex align-items-center justify-content-between">
            <div class="input-group" style="max-width: 380px;">
                <span class="input-group-text bg-white border-end-0 rounded-start-pill ps-3 text-muted"><i class="fas fa-search"></i></span>
                <input type="text" id="tableSearch" class="form-control border-start-0 rounded-end-pill ps-0" placeholder="Keresés név, e-mail vagy projekt alapján...">
            </div>
            <span class="text-muted small d-none d-md-inline"><i class="fas fa-info-circle me-1"></i> Kattints a sorra a részletes AI Munkalap megtekintéséhez</span>
        </div>

        <!-- Táblázat -->
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="offersTable">
                    <thead class="bg-light text-secondary text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">
                        <tr>
                            <th class="py-3 px-4" style="width: 70px;">#ID</th>
                            <th class="py-3">Ügyfél / Cégnév</th>
                            <th class="py-3">E-mail Cím</th>
                            <th class="py-3">Projekt Leírása</th>
                            <th class="py-3">Dátum</th>
                            <th class="py-3 text-center">Státusz</th>
                            <th class="py-3 text-end px-4">Műveletek</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($models)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fas fa-inbox fs-1 d-block mb-3 opacity-25"></i>
                                Még nincsenek beérkezett ajánlatkérések.
                            </td>
                        </tr>
                        <?php else: foreach ($models as $model): ?>
                            <?php 
                                $id = Html::encode($model->id);
                                $name = Html::encode($model->name);
                                $email = Html::encode($model->email);
                                $message = Html::encode($model->message);
                                $chatContext = Html::encode($model->chat_context ?? '');
                                $date = Html::encode($model->created_at);
                                $status = Html::encode($model->status ?? 'new');
                            ?>
                            <tr class="offer-row" id="row-<?= $id ?>" style="cursor: pointer;">
                                <td class="px-4 fw-bold text-secondary">#<?= $id ?></td>
                                <td>
                                    <div class="fw-bold text-dark"><?= $name ?></div>
                                </td>
                                <td>
                                    <a href="mailto:<?= $email ?>" class="text-decoration-none text-primary fw-medium" onclick="event.stopPropagation();">
                                        <i class="far fa-envelope me-1"></i><?= $email ?>
                                    </a>
                                </td>
                                <td>
                                    <div class="text-secondary text-truncate" style="max-width: 300px;">
                                        <?= $message ?>
                                    </div>
                                </td>
                                <td class="text-muted small">
                                    <i class="far fa-clock me-1"></i><?= $date ?>
                                </td>
                                <td class="text-center status-td">
                                    <?= renderStatusBadge($status) ?>
                                </td>
                                <td class="text-end px-4">
                                    <button type="button" 
                                            class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm view-brief-btn"
                                            data-id="<?= $id ?>"
                                            data-name="<?= $name ?>"
                                            data-email="<?= $email ?>"
                                            data-message="<?= $message ?>"
                                            data-context="<?= $chatContext ?>"
                                            data-date="<?= $date ?>"
                                            data-status="<?= $status ?>"
                                            onclick="event.stopPropagation(); openBriefModal(this)">
                                        <i class="fas fa-brain me-1"></i> Részletek & AI Brief
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- MODAL: Részletes Ügyfél Munkalap & AI Projekt Brief -->
<div class="modal fade" id="briefModal" tabindex="-1" aria-labelledby="briefModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content rounded-4 border-0 shadow-lg overflow-hidden">
            
            <!-- Modal Fejléc -->
            <div class="modal-header bg-dark text-white p-4">
                <div class="d-flex align-items-center">
                    <div class="bg-primary rounded-circle p-2 me-3 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                        <i class="fas fa-user-check text-white fs-5"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="modalCustomerName">Ügyfél Neve</h5>
                        <small class="text-muted" id="modalCustomerDate">Dátum</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Bezárás"></button>
            </div>

            <div class="modal-body p-0">
                <div class="row g-0">
                    
                    <!-- BAL OLDAL: Ügyfél Kártya & Státuszválasztó (4 oszlop) -->
                    <div class="col-lg-4 bg-light p-4 border-end border-light">
                        <h6 class="fw-bold text-dark mb-3"><i class="fas fa-address-card text-primary me-2"></i> Ügyfél Adatai</h6>
                        
                        <div class="mb-3 p-3 bg-white rounded-3 border border-light">
                            <small class="text-muted d-block mb-1">Név / Cégnév:</small>
                            <strong class="text-dark fs-6" id="modalCardName">-</strong>
                        </div>

                        <div class="mb-3 p-3 bg-white rounded-3 border border-light">
                            <small class="text-muted d-block mb-1">E-mail Cím:</small>
                            <a href="#" id="modalCardEmailLink" class="text-primary fw-bold text-decoration-none fs-6">-</a>
                        </div>

                        <!-- DINAMIKUS STÁTUSZ VÁLASZTÓ MEZŐ (Ez hiányzott a képeden!) -->
                        <div class="mb-3 p-3 bg-white rounded-3 border border-light">
                            <small class="text-muted d-block mb-2 fw-bold text-dark"><i class="fas fa-tasks text-primary me-1"></i> Folyamat Státusza:</small>
                            <select id="modalStatusSelect" class="form-select form-select-sm rounded-pill fw-bold border-primary shadow-sm" style="font-size: 13px;">
                                <option value="new">🟡 Új Megkeresés</option>
                                <option value="in_progress">🔵 Feldolgozás alatt</option>
                                <option value="completed">🟢 Ajánlat Elküldve</option>
                            </select>
                            <div id="statusSaveSpinner" class="text-success small mt-2 fw-bold d-none">
                                <i class="fas fa-check-circle me-1"></i> Státusz elmentve!
                            </div>
                        </div>

                        <div class="mb-4 p-3 bg-white rounded-3 border border-light">
                            <small class="text-muted d-block mb-1">Beérkezés Időpontja:</small>
                            <span class="text-dark fw-medium" id="modalCardDate">-</span>
                        </div>

                        <hr class="text-muted opacity-25">

                        <h6 class="fw-bold text-dark mb-3"><i class="fas fa-bolt text-warning me-2"></i> Gyors Művelet</h6>
                        <a href="#" id="replyEmailBtn" target="_blank" class="btn btn-primary w-100 rounded-pill py-2 shadow-sm fw-bold">
                            <i class="fas fa-paper-plane me-2"></i> Válasz E-mail Írása
                        </a>
                    </div>

                    <!-- JOBB OLDAL: AI Projekt Brief & Nyers Chat Tabok (8 oszlop) -->
                    <div class="col-lg-8 p-4">
                        
                        <!-- Navigációs Tabok -->
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active rounded-pill px-4 fw-bold" id="pills-brief-tab" data-bs-toggle="pill" data-bs-target="#pills-brief" type="button" role="tab">
                                    <i class="fas fa-sparkles me-2"></i> Strukturált AI Brief
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link rounded-pill px-4 fw-bold" id="pills-chat-tab" data-bs-toggle="pill" data-bs-target="#pills-chat" type="button" role="tab">
                                    <i class="fas fa-comments me-2"></i> Nyers Chat Előzmény
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content" id="pills-tabContent">
                            
                            <!-- TAB 1: Strukturált AI Projekt Brief -->
                            <div class="tab-pane fade show active" id="pills-brief" role="tabpanel">
                                <div class="p-4 rounded-4 bg-light border border-primary border-opacity-25" style="min-height: 320px;">
                                    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                                        <span class="badge bg-primary text-white px-3 py-1 rounded-pill fw-bold">AI Rendszermérnöki Összefoglaló</span>
                                        <small class="text-muted"><i class="fas fa-check-circle text-success me-1"></i> Generálva</small>
                                    </div>
                                    <div id="modalBriefContent" class="text-dark" style="white-space: pre-wrap; line-height: 1.7; font-size: 14px;">
                                        <!-- Ide kerül a tömbösített kimenet -->
                                    </div>
                                </div>
                            </div>

                            <!-- TAB 2: Nyers Chat Előzmény -->
                            <div class="tab-pane fade" id="pills-chat" role="tabpanel">
                                <div class="p-4 rounded-4 bg-dark text-light overflow-auto" style="max-height: 380px; font-family: monospace; font-size: 13px; line-height: 1.6;">
                                    <div id="modalChatContextContent" style="white-space: pre-wrap;">
                                        <!-- Ide kerül a nyers beszélgetés -->
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>
            </div>

            <div class="modal-footer bg-light p-3">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Bezárás</button>
            </div>

        </div>
    </div>
</div>

<!-- JavaScript: Keresés, Modal feltöltés és AJAX StátuszFrissítés -->
<script>
let currentActiveOfferId = null;

document.addEventListener("DOMContentLoaded", function() {
    
    // Keresés a táblázatban
    const tableSearch = document.getElementById('tableSearch');
    if (tableSearch) {
        tableSearch.addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('#offersTable tbody tr.offer-row');

            rows.forEach(row => {
                let text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    }

    // Kattintás a teljes táblázatsorra
    document.querySelectorAll('#offersTable tbody tr.offer-row').forEach(row => {
        row.addEventListener('click', function() {
            let btn = this.querySelector('.view-brief-btn');
            if (btn) openBriefModal(btn);
        });
    });

    // Státusz megváltoztatása a Modalban (AJAX Mentés)
    const modalStatusSelect = document.getElementById('modalStatusSelect');
    if (modalStatusSelect) {
        modalStatusSelect.addEventListener('change', function() {
            if (!currentActiveOfferId) return;

            const newStatus = this.value;
            const spinner = document.getElementById('statusSaveSpinner');

            fetch('<?= Url::to(['ai/update-status']) ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-CSRF-Token': '<?= Yii::$app->request->getCsrfToken() ?>'
                },
                body: 'id=' + encodeURIComponent(currentActiveOfferId) + '&status=' + encodeURIComponent(newStatus)
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Sikeres visszajelzés megjelenítése
                    spinner.classList.remove('d-none');
                    setTimeout(() => spinner.classList.add('d-none'), 2000);

                    // Frissítjük a gomb data-status értékét
                    const rowBtn = document.querySelector(`#row-${currentActiveOfferId} .view-brief-btn`);
                    if (rowBtn) rowBtn.setAttribute('data-status', newStatus);

                    // Frissítjük a táblázatban a státusz jelvényt azonnal!
                    const rowStatusTd = document.querySelector(`#row-${currentActiveOfferId} .status-td`);
                    if (rowStatusTd) {
                        if (newStatus === 'in_progress') {
                            rowStatusTd.innerHTML = '<span class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill fw-semibold" style="font-size: 11px;">Feldolgozás alatt</span>';
                        } else if (newStatus === 'completed') {
                            rowStatusTd.innerHTML = '<span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill fw-semibold" style="font-size: 11px;">Ajánlat Elküldve</span>';
                        } else {
                            rowStatusTd.innerHTML = '<span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill fw-semibold" style="font-size: 11px;">Új Megkeresés</span>';
                        }
                    }
                }
            })
            .catch(err => {
                console.error("Státuszfrissítési hiba:", err);
            });
        });
    }

});

// Modal megnyitása és adatokkal való feltöltése
function openBriefModal(btn) {
    currentActiveOfferId = btn.getAttribute('data-id');
    let name = btn.getAttribute('data-name');
    let email = btn.getAttribute('data-email');
    let message = btn.getAttribute('data-message');
    let context = btn.getAttribute('data-context');
    let date = btn.getAttribute('data-date');
    let status = btn.getAttribute('data-status') || 'new';

    document.getElementById('modalCustomerName').textContent = name + ' (#' + currentActiveOfferId + ')';
    document.getElementById('modalCustomerDate').textContent = date;
    document.getElementById('modalCardName').textContent = name;
    
    let emailLink = document.getElementById('modalCardEmailLink');
    emailLink.textContent = email;
    emailLink.href = 'mailto:' + email;
    
    // Beállítjuk a kiválasztott státuszt a Dropdownban
    document.getElementById('modalStatusSelect').value = status;
    
    document.getElementById('replyEmailBtn').href = 'mailto:' + email + '?subject=' + encodeURIComponent('Re: AI Automatizációs Ajánlatkérés (' + name + ')');
    document.getElementById('modalCardDate').textContent = date;
    
    document.getElementById('modalBriefContent').textContent = message || 'Nincs elérhető leírás.';
    document.getElementById('modalChatContextContent').textContent = context || 'Nincs rögzített chat előzmény.';

    let briefModal = new bootstrap.Modal(document.getElementById('briefModal'));
    briefModal.show();
}
</script>