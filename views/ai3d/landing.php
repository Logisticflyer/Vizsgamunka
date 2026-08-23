<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = '3D Tervezés & Ipari Automatizáció Árajánlatkérő';

// Select2 CSS és JS regisztrálása a Yii2 függőségi láncában
$this->registerCssFile('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css');
$this->registerJsFile('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', [
    'depends' => [\yii\web\JqueryAsset::class]
]);

$isGuest = Yii::$app->user->isGuest;
$loggedUser = $isGuest ? null : Yii::$app->user->identity;
$defaultName = $loggedUser ? $loggedUser->username : '';
$defaultEmail = $loggedUser ? $loggedUser->email : '';
?>

<div class="ai3d-landing-page text-light" style="background-color: #0b0d12; font-family: 'Inter', system-ui, -apple-system, sans-serif; min-height: 100vh;">

    <!-- 1. HERO SZEKCIÓ -->
    <section class="hero-section pt-4 pb-3 border-bottom border-secondary" style="background: radial-gradient(circle at 50% 0%, #1a233a 0%, #0b0d12 80%);">
        <div class="container">
            <div class="row align-items-center g-4 mb-4">
                <div class="col-lg-7">
                    <span class="badge bg-primary px-3 py-1 fs-6 rounded-pill mb-2">
                        <i class="fas fa-search me-2"></i>Kereshető Sablonkönyvtár & 3D Kalkulátor
                    </span>
                    <h1 class="display-5 fw-extrabold text-white mb-2" style="letter-spacing: -1px;">
                        Válasszon a bekalibrált <span class="text-primary">3D sablonok közül!</span>
                    </h1>
                    <p class="lead text-muted mb-3" style="line-height: 1.5; font-size: 1.05rem;">
                        Használja az azonnali keresőt a kívánt alkatrész vagy gépváz megtalálásához. Rendszerünk automatikusan betölti a kalibrált méreteket és a 3D modellt.
                    </p>
                </div>

                <div class="col-lg-5">
                    <div class="p-3 rounded-4 shadow-lg glass-card text-center position-relative overflow-hidden" style="background: linear-gradient(145deg, #1e293b 0%, #0f172a 100%); border: 1px solid rgba(56, 189, 248, 0.3) !important;">
                        <div class="d-flex align-items-center justify-content-center gap-3">
                            <i class="fas fa-database fa-2x text-info" style="filter: drop-shadow(0 0 8px rgba(56, 189, 248, 0.5));"></i>
                            <div class="text-start">
                                <h6 class="fw-bold text-white mb-0">Kereshető Sablon Adatbázis</h6>
                                <small class="text-muted">Több tucat előre konfigurált gépészeti modul és alkatrész.</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MICRO-RIBBON INFO -->
            <div class="row g-2 pt-3 border-top border-dark">
                <div class="col-6 col-md-3">
                    <div class="d-flex align-items-center justify-content-center gap-2 py-1 text-light">
                        <i class="fas fa-search text-warning"></i>
                        <span class="small fw-medium">Azonnali Szűrés</span>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="d-flex align-items-center justify-content-center gap-2 py-1 text-light">
                        <i class="fas fa-sliders-h text-info"></i>
                        <span class="small fw-medium">Pre-kalibrált Méretek</span>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="d-flex align-items-center justify-content-center gap-2 py-1 text-light">
                        <i class="fas fa-cube text-primary"></i>
                        <span class="small fw-medium">3D Élő Modell</span>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="d-flex align-items-center justify-content-center gap-2 py-1 text-light">
                        <i class="fas fa-calculator text-success"></i>
                        <span class="small fw-medium">Pontos Árbecslés</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 2. KERESHETŐ ÁRAJÁNLATKÉRŐ BUILDER -->
    <section id="quote-builder-section" class="py-4">
        <div class="container">
            <div class="row g-4 justify-content-center">
                
                <!-- BAL OLDAL: SABLON KERESŐ ÉS PARAMÉTEREK -->
                <div class="col-lg-6">
                    <div class="card shadow-lg border-secondary h-100" style="background: #141824; border-radius: 15px; overflow: visible !important;">
                        <div class="card-header bg-dark border-secondary p-3 d-flex align-items-center justify-content-between">
                            <span class="fw-bold text-white d-flex align-items-center gap-2">
                                <i class="fas fa-search text-primary"></i> 1. Sablon Keresése & Paraméterezés
                            </span>
                        </div>

                        <div class="card-body p-4" style="overflow: visible !important;">
                            <form id="template-quote-form" novalidate style="overflow: visible !important;">
                                
                                <!-- KERESHETŐ SELECT DROPDOWN - SABLON -->
                                <div class="mb-3">
                                    <label class="form-label text-muted small fw-bold" for="select-template">Gépészeti Sablon Keresése (Írja be a nevet) *</label>
                                    <select id="select-template" class="form-select bg-dark text-white border-secondary w-100" aria-label="Gépészeti sablon kiválasztása">
                                    </select>
                                </div>

                                <!-- ALAPANYAG VÁLASZTÁS & DARABSZÁM (PONTOS VONALBA IGAZÍTÁSSAL) -->
                                <div class="row g-2 mb-3 align-items-end">
                                    <div class="col-md-8">
                                        <label class="form-label text-muted small fw-bold mb-1" for="select-material">Alapanyag Keresése *</label>
                                        <select id="select-material" class="form-select bg-dark text-white border-secondary w-100" aria-label="Alapanyag kiválasztása">
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label text-muted small fw-bold mb-1" for="input-quantity">Darabszám *</label>
                                        <div class="input-group">
                                            <input type="number" id="input-quantity" class="form-control bg-dark text-white border-secondary" value="1" min="1" max="1000" aria-label="Darabszám" style="height: 42px !important;">
                                            <span class="input-group-text bg-secondary text-light border-secondary" style="height: 42px !important;">db</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- MÉRETEK CAD KONTROLL -->
                                <div class="row g-2 mb-3">
                                    <div class="col-4">
                                        <label class="form-label text-muted small fw-bold mb-1" for="dim-length">Hosszúság (L) *</label>
                                        <div class="input-group input-group-sm">
                                            <input type="number" id="dim-length" class="form-control bg-dark text-white border-secondary" value="1200" min="10" step="10" aria-label="Hosszúság milliméterben">
                                            <span class="input-group-text bg-secondary text-light border-secondary">mm</span>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <label class="form-label text-muted small fw-bold mb-1" for="dim-width">Szélesség (W) *</label>
                                        <div class="input-group input-group-sm">
                                            <input type="number" id="dim-width" class="form-control bg-dark text-white border-secondary" value="1000" min="10" step="10" aria-label="Szélesség milliméterben">
                                            <span class="input-group-text bg-secondary text-light border-secondary">mm</span>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <label class="form-label text-muted small fw-bold mb-1" for="dim-height">Magasság (H) *</label>
                                        <div class="input-group input-group-sm">
                                            <input type="number" id="dim-height" class="form-control bg-dark text-white border-secondary" value="1000" min="10" step="10" aria-label="Magasság milliméterben">
                                            <span class="input-group-text bg-secondary text-light border-secondary">mm</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- MEGJEGYZÉS -->
                                <div class="mb-3">
                                    <label class="form-label text-muted small fw-bold" for="input-description">Egyedi feladat leírása / Felületi követelmények</label>
                                    <textarea id="input-description" class="form-control bg-dark text-white border-secondary" rows="3" placeholder="Pl.: Motor felfogatási pontok elhelyezése, felületi érdesség, egyedi tűrések..."></textarea>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>

                <!-- JOBB OLDAL: ÉLŐ 3D ELŐNÉZET ÉS BECSÜLT ÁR -->
                <div class="col-lg-6">
                    <div id="quote-preview-card" class="card shadow-lg border-secondary h-100" style="background: #141824; border-radius: 15px;">
                        
                        <div class="card-header bg-dark border-secondary p-3 d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-info"><i class="fas fa-cube me-2"></i>2. Interaktív 3D Koncepció & Ár</span>
                            <span class="badge bg-success">Azonnali Kalkuláció</span>
                        </div>

                        <div class="card-body p-4 d-flex flex-column justify-content-between">
                            
                            <div>
                                <h5 id="quote-title" class="text-white fw-bold mb-1">Sablon Betöltése...</h5>
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <span id="quote-category" class="badge bg-secondary">Kategória</span>
                                    <span id="mat-mismatch-warning" class="badge bg-warning text-dark d-none" title="A választott gépészeti sablonhoz a jelenlegi alapanyag technológiailag nem javasolt, egyedi árazást igényelhet.">
                                        <i class="fas fa-exclamation-triangle me-1"></i>Egyedi anyagválasztás
                                    </span>
                                </div>

                                <!-- 3D VIEWPORT -->
                                <div class="mb-3 position-relative preview-3d-wrapper" style="cursor: pointer;" tabindex="0" role="button" aria-label="Interaktív 3D modell megnyitása" data-bs-toggle="modal" data-bs-target="#modal3DViewer" onkeydown="if(event.key==='Enter'||event.key===' '){ event.preventDefault(); $('#modal3DViewer').modal('show'); }">
                                    <div id="landing-3d-canvas" class="rounded-3 border border-secondary" style="width: 100%; height: 220px; background: #0e1017;"></div>
                                    <div class="position-absolute bottom-0 end-0 m-2 px-2 py-1 bg-dark bg-opacity-75 text-info rounded small border border-secondary pointer-events-none" style="font-size: 0.78rem;">
                                        <i class="fas fa-search-plus me-1"></i> Interaktív 3D Nézet
                                    </div>
                                </div>

                                <!-- TÉTELBONTÁS -->
                                <div class="p-2 rounded-3 mb-3" style="background: #1c2130;">
                                    <h6 class="text-info fw-bold mb-2 small"><i class="fas fa-list-ul me-1"></i> Becsült Tételbontás</h6>
                                    <ul id="quote-breakdown-list" class="list-group list-group-flush bg-transparent small mb-0">
                                    </ul>
                                </div>
                            </div>

                            <div>
                                <div class="d-flex justify-content-between align-items-center p-3 rounded-3 bg-dark border border-secondary mb-3">
                                    <span class="text-muted small">Becsült Nettó Gyártási Ár:</span>
                                    <strong id="quote-total-price" class="fs-4 text-success">0 Ft</strong>
                                </div>

                                <button type="button" id="btn-proceed-to-contact" class="btn btn-success w-100 fw-bold py-2 rounded-3 mb-1" data-bs-toggle="modal" data-bs-target="#contactModal">
                                    <i class="fas fa-paper-plane me-2"></i>Árajánlat Véglegesítése & Beküldése
                                </button>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

</div>

<!-- 3D INTERAKTÍV TELJES NÉZET MODAL -->
<div class="modal fade" id="modal3DViewer" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content text-white border-secondary" style="background: #141824; border-radius: 15px;">
            <div class="modal-header border-secondary bg-dark d-flex justify-content-between align-items-center">
                <h5 class="modal-title fw-bold text-info"><i class="fas fa-cube me-2"></i>Interaktív CAD 3D Modell Nézegető</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Bezárás"></button>
            </div>
            <div class="modal-body p-3 text-center">
                <div id="modal-3d-canvas" style="width: 100%; height: 460px; background: #090d16; border-radius: 10px;" class="border border-secondary"></div>
                
                <div class="mt-3 d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-sm btn-outline-info rounded-pill px-3" onclick="window.set3DView('front')">
                        <i class="fas fa-vector-square me-1"></i> Elölnézet
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-info rounded-pill px-3" onclick="window.set3DView('top')">
                        <i class="fas fa-layer-group me-1"></i> Felülnézet
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-info rounded-pill px-3" onclick="window.set3DView('iso')">
                        <i class="fas fa-cube me-1"></i> Izometrikus Nézet
                    </button>
                </div>

                <div class="mt-2 text-white small d-flex justify-content-center gap-4" style="color: #ffffff !important;">
                    <span><i class="fas fa-mouse me-1 text-primary"></i> <strong>Forgatás:</strong> Bal egérgomb</span>
                    <span><i class="fas fa-search-plus me-1 text-info"></i> <strong>Nagyítás:</strong> Egérgörgő</span>
                    <span><i class="fas fa-arrows-alt me-1 text-warning"></i> <strong>Mozgatás:</strong> Jobb egérgomb</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- KAPCSOLATI ADATOK BEKÉRŐ MODAL -->
<div class="modal fade" id="contactModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-white border-secondary" style="background: #141824; border-radius: 15px;">
            <div class="modal-header border-secondary bg-dark">
                <h5 class="modal-title fw-bold text-success"><i class="fas fa-envelope me-2"></i>Kapcsolati Adatok Megadása</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Bezárás"></button>
            </div>
            <div class="modal-body p-4">
                <form id="final-submit-form">
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold" for="user-name">Teljes Név *</label>
                        <input type="text" id="user-name" class="form-control text-white bg-dark border-secondary" placeholder="Kovács János" value="<?= Html::encode($defaultName) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold" for="user-email">E-mail Cím *</label>
                        <input type="email" id="user-email" class="form-control text-white bg-dark border-secondary" placeholder="nev@ceg.hu" value="<?= Html::encode($defaultEmail) ?>" required>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label text-muted small fw-bold" for="user-phone">Telefonszám</label>
                            <input type="tel" id="user-phone" class="form-control text-white bg-dark border-secondary" placeholder="+36 30 123 4567">
                        </div>
                        <div class="col-6">
                            <label class="form-label text-muted small fw-bold" for="user-company">Cégnév (Opcionális)</label>
                            <input type="text" id="user-company" class="form-control text-white bg-dark border-secondary" placeholder="Minta Kft.">
                        </div>
                    </div>

                    <div class="alert alert-info py-2 fs-7 mb-3" style="background: rgba(13,110,253,0.15); border-color: #0d6efd; color: #fff;">
                        <i class="fas fa-info-circle me-1"></i> Beküldés után kollégáink áttekintik a megadott 3D specifikációkat. A státuszt a Profil oldaladon követheted élőben!
                    </div>

                    <button type="submit" class="btn btn-success w-100 fw-bold py-2 rounded-3">
                        Árajánlat Beküldése Mérnökeinknek
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- THREE.JS 3D KÖNYVTÁRAK -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/controls/OrbitControls.js"></script>

<style>
/* MODAL FELIRATOK ÉS CÍMKÉK KONTRASTJÁNAK JAVÍTÁSA */
.modal-content {
    background-color: #141824 !important;
    border: 1px solid rgba(255, 255, 255, 0.15) !important;
}

.modal-content label,
.modal-content .form-label {
    color: #cbd5e1 !important;
    font-weight: 600 !important;
    font-size: 0.875rem !important;
    margin-bottom: 0.4rem !important;
    display: block !important;
}

.modal-content .form-control {
    background-color: #1e293b !important;
    border: 1px solid #334155 !important;
    color: #ffffff !important;
}

.modal-content .form-control:focus {
    border-color: #38bdf8 !important;
    box-shadow: 0 0 0 0.2rem rgba(56, 189, 248, 0.25) !important;
}

.modal-content .form-control::placeholder {
    color: #64748b !important;
}

.custom-scrollbar::-webkit-scrollbar { width: 5px; }
.custom-scrollbar::-webkit-scrollbar-track { background: rgba(0, 0, 0, 0.2); }
.custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.2); border-radius: 4px; }

.ai3d-landing-page input::-webkit-outer-spin-button,
.ai3d-landing-page input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
.ai3d-landing-page input[type=number] {
    -moz-appearance: textfield;
}

.ai3d-landing-page .text-muted,
.ai3d-landing-page p.lead,
.ai3d-landing-page .small.text-muted,
.ai3d-landing-page label.small.text-muted {
    color: #cbd5e1 !important;
}

.glass-card, 
.ai3d-landing-page .card {
    border: 1px solid rgba(255, 255, 255, 0.15) !important;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5) !important;
}

.ai3d-landing-page .form-control,
.ai3d-landing-page .form-select {
    background-color: #1e293b !important;
    color: #f8fafc !important;
    border-color: #334155 !important;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
}

.ai3d-landing-page .form-control:focus,
.ai3d-landing-page .form-select:focus {
    border-color: #38bdf8 !important;
    box-shadow: 0 0 0 0.25rem rgba(56, 189, 248, 0.25) !important;
}

.ai3d-landing-page .form-control.is-invalid {
    border-color: #ef4444 !important;
    box-shadow: 0 0 0 0.25rem rgba(239, 68, 68, 0.25) !important;
}

.ai3d-landing-page .form-control::placeholder,
.ai3d-landing-page .form-control::-webkit-input-placeholder {
    color: #94a3b8 !important;
    opacity: 1 !important;
}

.ai3d-landing-page .input-group-text {
    user-select: none;
    -webkit-user-select: none;
    pointer-events: none;
}

.ai3d-landing-page .card-body {
    padding: 1.5rem !important;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.preview-3d-wrapper:focus-visible {
    outline: 2px solid #38bdf8 !important;
    outline-offset: 3px;
    border-radius: 0.5rem;
}

.preview-3d-wrapper:hover #landing-3d-canvas {
    border-color: #38bdf8 !important;
    box-shadow: 0 0 15px rgba(56, 189, 248, 0.3);
}

/* SELECT2 SÖTÉT TÉMA ÉS MEGFELELŐ SZÉLESSÉG / MAGASSÁG */
.select2-container {
    width: 100% !important;
    max-width: 100% !important;
}
.select2-container--default .select2-selection--single {
    background-color: #1e293b !important;
    border: 1px solid #334155 !important;
    height: 42px !important;
    border-radius: 0.375rem !important;
    display: flex !important;
    align-items: center !important;
    width: 100% !important;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #f8fafc !important;
    font-size: 0.95rem;
    padding-right: 25px !important;
    white-space: nowrap !important;
    overflow: hidden !important;
    text-overflow: ellipsis !important;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 40px !important;
}
.select2-dropdown {
    background-color: #141824 !important;
    border: 1px solid #334155 !important;
    color: #f8fafc !important;
    z-index: 99999 !important;
}
.select2-search__field {
    background-color: #1e293b !important;
    color: #ffffff !important;
    border: 1px solid #334155 !important;
    border-radius: 4px;
}
.select2-results__option {
    color: #ffffff !important;
    background-color: #141824 !important;
}
.select2-results__option--highlighted[aria-selected] {
    background-color: #0d6efd !important;
    color: #ffffff !important;
}
.select2-results__group {
    color: #38bdf8 !important;
    font-weight: bold;
    font-size: 0.85rem;
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function () {
    
    const templateSelect = $('#select-template');
    const materialSelect = $('#select-material');
    const quantityInput = document.getElementById('input-quantity');
    const lengthInput = document.getElementById('dim-length');
    const widthInput = document.getElementById('dim-width');
    const heightInput = document.getElementById('dim-height');
    
    const quoteTitle = document.getElementById('quote-title');
    const quoteCategory = document.getElementById('quote-category');
    const breakdownList = document.getElementById('quote-breakdown-list');
    const totalPriceEl = document.getElementById('quote-total-price');
    const matMismatchWarning = document.getElementById('mat-mismatch-warning');

    let currentSelectedTemplate = null;
    let currentSelectedMaterial = null;
    
    let scene, camera, renderer, previewGroup;
    let modalScene, modalCamera, modalRenderer, modalControls, modalAnimId;

    const materialLibrary = [
        { keywords: ['rozsdamentes', 'saválló', '1.4301', '1.4404'], props: { color: 0x94a3b8, metalness: 0.8, roughness: 0.2 } },
        { keywords: ['alumínium', 'alu', '6082', '7075'], props: { color: 0xcbd5e1, metalness: 0.6, roughness: 0.4 } },
        { keywords: ['fekete', 'blk', 'pom fekete', 'pa6 fekete'], props: { color: 0x334155, metalness: 0.1, roughness: 0.6 } },
        { keywords: ['műanyag', 'pom', 'pa6', 'ptfe', 'teflon'], props: { color: 0xf1f5f9, metalness: 0.1, roughness: 0.6 } },
        { keywords: ['réz', 'bronz', 'sárgaréz'], props: { color: 0xd97706, metalness: 0.8, roughness: 0.3 } },
        { keywords: ['szerkezeti', 'szénacél', 'hardox', 'acél', '42crmo4'], props: { color: 0x475569, metalness: 0.6, roughness: 0.4 } }
    ];

    function getMaterialProps(matName, matCode, matCat) {
        const searchStr = `${matName} ${matCode} ${matCat}`.toLowerCase();
        for (let lib of materialLibrary) {
            if (lib.keywords.some(kw => searchStr.includes(kw))) {
                return lib.props;
            }
        }
        return { color: 0x2563eb, metalness: 0.4, roughness: 0.3 };
    }

    function createMeshForShape(shapeType) {
        const group = new THREE.Group();

        const lRaw = parseFloat(lengthInput.value) || 1000;
        const wRaw = parseFloat(widthInput.value) || 800;
        const hRaw = parseFloat(heightInput.value) || 1000;

        const l = lRaw / 1000;
        const w = wRaw / 1000;
        const h = hRaw / 1000;

        let matCode = '', matName = '', matCat = '';
        if (currentSelectedMaterial) {
            matCode = currentSelectedMaterial.code || '';
            matName = currentSelectedMaterial.text || '';
            matCat = currentSelectedMaterial.category || '';
        }
        const matProps = getMaterialProps(matName, matCode, matCat);
        const standardMat = new THREE.MeshStandardMaterial(matProps);

        if (shapeType === 'frame' || (currentSelectedTemplate && currentSelectedTemplate.name.toLowerCase().includes('váz'))) {
            const profThick = Math.max(0.03, Math.min(h, w, l) * 0.05);

            const vertGeo = new THREE.BoxGeometry(profThick, h, profThick);
            const positionsVert = [
                [-l/2 + profThick/2, 0, -w/2 + profThick/2],
                [l/2 - profThick/2, 0, -w/2 + profThick/2],
                [-l/2 + profThick/2, 0, w/2 - profThick/2],
                [l/2 - profThick/2, 0, w/2 - profThick/2]
            ];
            positionsVert.forEach(pos => {
                const col = new THREE.Mesh(vertGeo, standardMat);
                col.position.set(...pos);
                group.add(col);
            });

            const horizGeoL = new THREE.BoxGeometry(l - profThick*2, profThick, profThick);
            const horizGeoW = new THREE.BoxGeometry(profThick, profThick, w - profThick*2);

            [h/2 - profThick/2, -h/2 + profThick/2].forEach(yPos => {
                const b1 = new THREE.Mesh(horizGeoL, standardMat);
                b1.position.set(0, yPos, -w/2 + profThick/2);
                group.add(b1);

                const b2 = new THREE.Mesh(horizGeoL, standardMat);
                b2.position.set(0, yPos, w/2 - profThick/2);
                group.add(b2);

                const b3 = new THREE.Mesh(horizGeoW, standardMat);
                b3.position.set(-l/2 + profThick/2, yPos, 0);
                group.add(b3);

                const b4 = new THREE.Mesh(horizGeoW, standardMat);
                b4.position.set(l/2 - profThick/2, yPos, 0);
                group.add(b4);
            });

        } else if (shapeType === 'shaft' || shapeType === 'cylinder_horiz') {
            const radius = Math.max(0.1, Math.min(h, w) / 2);
            const geom = new THREE.CylinderGeometry(radius, radius, l, 32);
            const mesh = new THREE.Mesh(geom, standardMat);
            mesh.rotation.z = Math.PI / 2;
            group.add(mesh);

        } else if (shapeType === 'disc' || shapeType === 'tank') {
            const radius = Math.max(0.1, Math.min(w, l) / 2);
            const geom = new THREE.CylinderGeometry(radius, radius, h, 32);
            const mesh = new THREE.Mesh(geom, standardMat);
            group.add(mesh);

        } else {
            const geom = new THREE.BoxGeometry(l, h, w);
            const mesh = new THREE.Mesh(geom, standardMat);
            group.add(mesh);

            const edges = new THREE.EdgesGeometry(geom, 15);
            const lineMat = new THREE.LineBasicMaterial({ color: 0x38bdf8, linewidth: 2 });
            const lineSegments = new THREE.LineSegments(edges, lineMat);
            group.add(lineSegments);
        }

        const box = new THREE.Box3().setFromObject(group);
        const center = box.getCenter(new THREE.Vector3());
        group.position.sub(center);

        return group;
    }

    function cleanUp3DScene() {
        if (previewGroup) {
            previewGroup.traverse((child) => {
                if (child.isMesh) {
                    if (child.geometry) child.geometry.dispose();
                    if (child.material) {
                        if (Array.isArray(child.material)) {
                            child.material.forEach(m => m.dispose());
                        } else {
                            child.material.dispose();
                        }
                    }
                }
            });
            if (scene) scene.remove(previewGroup);
        }
        if (renderer) renderer.dispose();
    }

    function update3DModel(shapeType) {
        const container = document.getElementById('landing-3d-canvas');
        if (!container) return;

        cleanUp3DScene();
        container.innerHTML = '';

        const width = container.clientWidth || 300;
        const height = container.clientHeight || 220;

        scene = new THREE.Scene();
        scene.background = new THREE.Color(0x0e1017);

        camera = new THREE.PerspectiveCamera(45, width / height, 0.1, 100);
        camera.position.set(2.8, 2.0, 3.2);
        camera.lookAt(0, 0, 0);

        renderer = new THREE.WebGLRenderer({ antialias: true });
        renderer.setSize(width, height);
        renderer.setPixelRatio(window.devicePixelRatio);
        container.appendChild(renderer.domElement);

        const keyLight = new THREE.DirectionalLight(0xffffff, 2.0);
        keyLight.position.set(5, 10, 7);
        const fillLight = new THREE.DirectionalLight(0x38bdf8, 1.0);
        fillLight.position.set(-5, -5, -5);

        scene.add(keyLight, fillLight, new THREE.AmbientLight(0xffffff, 0.8));

        previewGroup = createMeshForShape(shapeType);
        scene.add(previewGroup);

        function animate() {
            requestAnimationFrame(animate);
            renderer.render(scene, camera);
        }
        animate();
    }

    function renderInteractive3DModal() {
        if (!currentSelectedTemplate) return;

        const container = document.getElementById('modal-3d-canvas');
        if (!container) return;

        if (modalAnimId) cancelAnimationFrame(modalAnimId);
        container.innerHTML = '';

        const width = container.clientWidth || 750;
        const height = container.clientHeight || 460;

        modalScene = new THREE.Scene();
        modalScene.background = new THREE.Color(0x090d16);

        modalCamera = new THREE.PerspectiveCamera(45, width / height, 0.1, 100);
        modalCamera.position.set(2.8, 2.0, 3.2);
        modalCamera.lookAt(0, 0, 0);

        modalRenderer = new THREE.WebGLRenderer({ antialias: true });
        modalRenderer.setSize(width, height);
        modalRenderer.setPixelRatio(window.devicePixelRatio);
        container.appendChild(modalRenderer.domElement);

        modalControls = new THREE.OrbitControls(modalCamera, modalRenderer.domElement);
        modalControls.enableDamping = true;
        modalControls.dampingFactor = 0.05;

        const keyLight = new THREE.DirectionalLight(0xffffff, 2.0);
        keyLight.position.set(6, 12, 8);
        
        const fillLight = new THREE.DirectionalLight(0x38bdf8, 0.8);
        fillLight.position.set(-6, -4, -6);

        modalScene.add(keyLight, fillLight, new THREE.AmbientLight(0xffffff, 0.8));

        const gridHelper = new THREE.GridHelper(10, 20, 0x38bdf8, 0x1e293b);
        gridHelper.position.y = -1.1;
        modalScene.add(gridHelper);

        const modalGroup = createMeshForShape(currentSelectedTemplate.shape);
        modalScene.add(modalGroup);

        function animateModal() {
            modalAnimId = requestAnimationFrame(animateModal);
            if (modalControls) modalControls.update();
            if (modalRenderer && modalScene && modalCamera) {
                modalRenderer.render(modalScene, modalCamera);
            }
        }
        animateModal();
    }

    window.set3DView = function(viewType) {
        if (!modalCamera || !modalControls) return;

        if (viewType === 'front') {
            modalCamera.position.set(0, 0, 3.5);
        } else if (viewType === 'top') {
            modalCamera.position.set(0, 3.5, 0);
        } else if (viewType === 'iso') {
            modalCamera.position.set(2.8, 2.0, 3.2);
        }
        modalCamera.lookAt(0, 0, 0);
        modalControls.update();
    };

    $('#modal3DViewer').on('shown.bs.modal', function () {
        renderInteractive3DModal();
    });

    $('#modal3DViewer').on('hidden.bs.modal', function () {
        if (modalAnimId) cancelAnimationFrame(modalAnimId);
        if (modalRenderer) modalRenderer.dispose();
        const container = document.getElementById('modal-3d-canvas');
        if (container) container.innerHTML = '';
    });

    function validateInputs() {
        let isValid = true;
        const inputs = [
            { el: quantityInput, min: 1 },
            { el: lengthInput, min: 10 },
            { el: widthInput, min: 10 },
            { el: heightInput, min: 10 }
        ];

        inputs.forEach(item => {
            const val = parseFloat(item.el.value);
            if (isNaN(val) || val < item.min) {
                item.el.classList.add('is-invalid');
                isValid = false;
            } else {
                item.el.classList.remove('is-invalid');
            }
        });
        return isValid;
    }

    function calculateQuote() {
        if (!currentSelectedTemplate) return;

        if (!validateInputs()) {
            totalPriceEl.textContent = 'Érvénytelen méret!';
            totalPriceEl.classList.replace('text-success', 'text-danger');
            return;
        }
        totalPriceEl.classList.replace('text-danger', 'text-success');

        const matFactor = currentSelectedMaterial ? (parseFloat(currentSelectedMaterial.factor) || 1.0) : 1.0;
        const qty = parseInt(quantityInput.value) || 1;
        
        const lVal = parseFloat(lengthInput.value) || 1000;
        const wVal = parseFloat(widthInput.value) || 800;
        const hVal = parseFloat(heightInput.value) || 1000;

        const l = lVal / 1000;
        const w = wVal / 1000;
        const h = hVal / 1000;

        const volumeFactor = (h * w * l);
        const unitPrice = Math.round((currentSelectedTemplate.baseCost * volumeFactor * matFactor));
        const totalPrice = unitPrice * qty;

        const mainMaterialCost = Math.round(totalPrice * 0.55);
        const machiningCost = Math.round(totalPrice * 0.45);

        quoteTitle.textContent = `${currentSelectedTemplate.name} (${lVal}x${wVal}x${hVal}mm)`;
        quoteCategory.textContent = currentSelectedTemplate.categoryName || 'Kategória';
        totalPriceEl.textContent = new Intl.NumberFormat('hu-HU').format(totalPrice) + ' Ft';

        const templateNameLower = (currentSelectedTemplate.name || '').toLowerCase();
        const materialNameLower = (currentSelectedMaterial ? currentSelectedMaterial.text || '' : '').toLowerCase();

        if (templateNameLower.includes('alu') && !materialNameLower.includes('alu')) {
            matMismatchWarning.classList.remove('d-none');
        } else {
            matMismatchWarning.classList.add('d-none');
        }

        const rawMatName = currentSelectedMaterial ? (currentSelectedMaterial.text || 'Alapanyag') : 'Alapanyag';
        const cleanMatName = rawMatName.replace(/\s*\([^)]*\)/g, '').trim();

        breakdownList.innerHTML = `
            <li class="list-group-item bg-transparent text-light border-secondary d-flex justify-content-between align-items-center p-1 gap-2">
                <span class="text-truncate" title="Alapanyag & Előkészítés (${rawMatName})">
                    Alapanyag & Előkészítés (${cleanMatName})
                </span>
                <span class="fw-bold text-success text-nowrap flex-shrink-0">
                    ${new Intl.NumberFormat('hu-HU').format(mainMaterialCost)} Ft
                </span>
            </li>
            <li class="list-group-item bg-transparent text-light border-secondary d-flex justify-content-between align-items-center p-1 gap-2">
                <span class="text-truncate" title="CNC Megmunkálás & Összeszerelés">CNC Megmunkálás & Összeszerelés</span>
                <span class="fw-bold text-success text-nowrap flex-shrink-0">
                    ${new Intl.NumberFormat('hu-HU').format(machiningCost)} Ft
                </span>
            </li>
        `;

        update3DModel(currentSelectedTemplate.shape);
    }

    templateSelect.select2({
        width: '100%',
        placeholder: "Kezdje el gépelni a sablon nevét (pl. tartály, tengely)...",
        templateResult: function(data) {
            if (!data.id) return data.text;
            return $('<span title="' + data.text + '">' + data.text + '</span>');
        },
        templateSelection: function(data) {
            if (!data.id) return data.text;
            return $('<span title="' + data.text + '">' + data.text + '</span>');
        },
        ajax: {
            url: '<?= Url::to(['ai3d/get-templates']) ?>',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return { q: params.term };
            },
            processResults: function (data) {
                return { results: data.results };
            },
            cache: true
        }
    });

    templateSelect.on('select2:select', function (e) {
        const data = e.params.data; 
        const cleanedName = data.text.replace(/\s*\(\d+x\d+.*?\)/gi, '').trim();

        currentSelectedTemplate = {
            id: data.id,
            name: cleanedName,
            categoryName: data.categoryName,
            shape: data.shape,
            baseCost: data.baseCost,
            h: data.h,
            w: data.w,
            l: data.l
        };
        
        lengthInput.value = currentSelectedTemplate.l || 1000;
        widthInput.value = currentSelectedTemplate.w || 800;
        heightInput.value = currentSelectedTemplate.h || 1000;

        calculateQuote();
    });

    materialSelect.select2({
        width: '100%',
        placeholder: "Keresse meg az alapanyagot (pl. 1.4301, S235, POM, Titanium)...",
        templateResult: function(data) {
            if (!data.id) return data.text;
            return $('<span title="' + data.text + '">' + data.text + '</span>');
        },
        templateSelection: function(data) {
            if (!data.id) return data.text;
            return $('<span title="' + data.text + '">' + data.text + '</span>');
        },
        ajax: {
            url: '<?= Url::to(['ai3d/get-materials']) ?>',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return { q: params.term };
            },
            processResults: function (data) {
                return { results: data.results };
            },
            cache: true
        }
    });

    materialSelect.on('select2:select', function (e) {
        currentSelectedMaterial = e.params.data;
        calculateQuote();
    });

    $.ajax({
        type: 'GET',
        url: '<?= Url::to(['ai3d/get-templates']) ?>'
    }).then(function (data) {
        if (data.results && data.results.length > 0 && data.results[0].children.length > 0) {
            const firstItem = data.results[0].children[0];
            const option = new Option(firstItem.text, firstItem.id, true, true);
            templateSelect.append(option).trigger('change');
            
            templateSelect.trigger({
                type: 'select2:select',
                params: { data: firstItem }
            });
        }
    });

    $.ajax({
        type: 'GET',
        url: '<?= Url::to(['ai3d/get-materials']) ?>'
    }).then(function (data) {
        if (data.results && data.results.length > 0 && data.results[0].children.length > 0) {
            const firstMat = data.results[0].children[0];
            const option = new Option(firstMat.text, firstMat.id, true, true);
            materialSelect.append(option).trigger('change');

            currentSelectedMaterial = firstMat;
            calculateQuote();
        }
    });

    [quantityInput, lengthInput, widthInput, heightInput].forEach(el => {
        el.addEventListener('change', calculateQuote);
        el.addEventListener('input', calculateQuote);
    });

    document.getElementById('final-submit-form').addEventListener('submit', function (e) {
        e.preventDefault();

        if (!validateInputs()) {
            alert('Kérjük, adjon meg érvényes méreteket a beküldés előtt!');
            return;
        }

        const qty = parseInt(quantityInput.value) || 1;
        const matName = currentSelectedMaterial ? currentSelectedMaterial.text : 'Ismeretlen alapanyag';

        fetch('<?= Url::to(['ai3d/submit-quote']) ?>', {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-CSRF-Token': '<?= Yii::$app->request->getCsrfToken() ?>'
            },
            body: new URLSearchParams({
                '<?= Yii::$app->request->csrfParam ?>': '<?= Yii::$app->request->getCsrfToken() ?>',
                user_name: document.getElementById('user-name').value,
                user_email: document.getElementById('user-email').value,
                user_phone: document.getElementById('user-phone').value,
                company_name: document.getElementById('user-company').value,
                project_title: quoteTitle.textContent || '3D Sablon Igény',
                quote_data: JSON.stringify({
                    template_id: currentSelectedTemplate?.id,
                    material_id: currentSelectedMaterial?.id,
                    material: matName,
                    quantity: qty,
                    description: document.getElementById('input-description').value
                }),
                shape_parameters: JSON.stringify({
                    length_mm: lengthInput.value,
                    width_mm: widthInput.value,
                    height_mm: heightInput.value
                }),
                total_price_huf: totalPriceEl.textContent.replace(/[^0-9]/g, '')
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert('🎉 ' + data.message + '\n\nAz ajánlatkérésed sikeresen rögzítve lett. Most átirányítunk a Profilodra, ahol élőben követheted a feldolgozási státuszt!');
                window.location.href = '<?= Url::to(['/site/profile']) ?>';
            } else {
                alert('Hiba: ' + data.message);
            }
        });
    });
});
</script>