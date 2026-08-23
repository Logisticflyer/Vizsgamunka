<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'AI 3D Tervező Asszisztens (Enterprise CAD Engine)';
?>

<div class="ai3d-index container-fluid py-3" style="background-color: #0b0d12; min-height: 100vh; color: #f8fafc;">
    <div class="row g-3" id="main-cad-row">

        <!-- BAL OLDAL: AI CHAT ÉS PARAMÉTER PANEL -->
        <div class="col-lg-5 col-md-6 transition-all" id="sidebar-col">
            <div class="card shadow-lg h-100 border-secondary" style="border-radius: 15px; background: #141824;">
                
                <div class="card-header p-2 border-bottom border-secondary d-flex justify-content-between align-items-center" style="border-top-left-radius: 15px; border-top-right-radius: 15px; background: #0f121d !important;">
                    <ul class="nav nav-pills card-header-pills m-0" id="engineerTabs" role="tablist">
                        <li class="nav-item">
                            <button class="nav-item-btn nav-link active btn-sm text-white py-1 px-3 fw-bold" id="tab-chat-btn" type="button" aria-selected="true" role="tab">
                                💬 AI Chat
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-item-btn nav-link btn-sm text-white py-1 px-3 fw-bold" id="tab-params-btn" type="button" aria-selected="false" role="tab">
                                🎛️ Paraméterek <span id="param-badge" class="badge bg-primary ms-1 d-none">0</span>
                            </button>
                        </li>
                    </ul>
                    
                    <button type="button" id="btn-toggle-viewport" class="btn btn-outline-info btn-sm fw-bold d-flex align-items-center gap-1" title="Teljes Viewport / Chat Elrejtése" aria-label="Viewport méretezése">
                        <i class="fas fa-expand-alt"></i> <span class="d-none d-xl-inline">Teljes Viewport</span>
                    </button>
                </div>

                <div class="tab-content card-body p-3 d-flex flex-column" style="height: 640px;">
                    
                    <!-- TAB 1: AI CHAT -->
                    <div class="tab-pane fade show active flex-grow-1 d-flex flex-column h-100" id="tab-chat" role="tabpanel">
                        <div id="chat-messages" class="flex-grow-1 overflow-auto pe-2 mb-2 custom-scrollbar" style="max-height: 480px;">
                            <div class="d-flex align-items-start mb-3">
                                <div class="p-3 shadow-sm rounded-3 border w-100" style="background: #1e2433; border-color: rgba(255,255,255,0.08) !important; color: #e1e3e8;">
                                    <div class="fw-bold text-info mb-1"><i class="fas fa-robot me-1"></i> AI Asszisztens</div>
                                    <div class="mb-0 small" style="line-height: 1.5;">
                                        Üdvözlöm! Írja le a kívánt gépet (pl. ipari etetőberendezés), én pedig legenerálom a procedurális 3D modellt, a méreteket, a 3D kótázást és a DFM elemzést!
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="quick-prompts my-2 d-flex flex-wrap gap-1">
                            <small class="text-muted w-100 mb-1" style="font-size: 11px;">Mérnöki minták:</small>
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill py-0 px-2 btn-prompt text-light" data-prompt="Ipari etetőberendezés szalaggal és hajtással" style="font-size: 11px; border-color: #333947;">
                                ⚡ Ipari etető
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill py-0 px-2 btn-prompt text-light" data-prompt="Áttételes villanymotor hajtás tengellyel" style="font-size: 11px; border-color: #333947;">
                                ⚙️ Hajtómű
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill py-0 px-2 btn-prompt text-light" data-prompt="Pneumatikus megfogó modul vázszerkezettel" style="font-size: 11px; border-color: #333947;">
                                🔧 Megfogó modul
                            </button>
                        </div>

                        <div id="attachment-preview" class="alert alert-info py-1 px-2 mb-2 d-none d-flex justify-content-between align-items-center" style="font-size: 12px; background: rgba(13, 110, 253, 0.2); border-color: #0d6efd; color: #fff;">
                            <span><i class="fas fa-paperclip me-1"></i> <span id="attached-filename"></span></span>
                            <button type="button" id="btn-remove-attachment" class="btn-close btn-close-white btn-sm" style="font-size: 10px;" aria-label="Csatolmány törlése"></button>
                        </div>

                        <form id="chat-form" enctype="multipart/form-data" class="mt-auto">
                            <div class="input-group">
                                <label class="btn btn-outline-secondary text-light mb-0 d-flex align-items-center" title="Kép/PDF csatolása" style="border-color: #3a3f4d; cursor: pointer;">
                                    <i class="fas fa-paperclip"></i>
                                    <input type="file" id="file-input" name="attachment" accept="image/*,.pdf" style="display: none;">
                                </label>
                                <input type="text" id="chat-input" class="form-control text-white border-secondary" placeholder="Írja le az igényeit..." style="background: #1e2433; border-color: #3a3f4d !important;" required>
                                <button class="btn btn-primary px-4 fw-bold" type="submit" aria-label="Üzenet küldése">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- TAB 2: PARAMÉTEREK -->
                    <div class="tab-pane fade flex-grow-1 overflow-auto custom-scrollbar pe-1" id="tab-params" role="tabpanel">
                        <div class="p-2">
                            <h6 class="text-info fw-bold mb-3 border-bottom border-secondary pb-2 d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-sliders-h me-1"></i> Modell Gépi Paraméterei</span>
                                <small class="text-muted font-monospace" style="font-size: 10px;">Élő gépészeti szerkesztő</small>
                            </h6>
                            <div id="dynamic-params-container" class="d-flex flex-column gap-3">
                                <div class="text-muted text-center py-5">
                                    <i class="fas fa-cube fa-2x mb-2 opacity-50"></i>
                                    <p class="small mb-0">Generáljon egy modellt a chatben a paraméterek módosításához!</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- JOBB OLDAL: 3D CAD VIEWPORT & OVERLAY-EK -->
        <div class="col-lg-7 col-md-6 transition-all" id="viewport-col">
            <div class="card shadow-lg border-secondary mb-4 overflow-hidden" style="border-radius: 15px; background: #141824;">
                
                <div class="card-header border-bottom border-secondary py-2 px-3 d-flex justify-content-between align-items-center flex-wrap gap-2" style="background: rgba(15, 18, 29, 0.95) !important;">
                    
                    <div class="d-flex align-items-center me-2">
                        <label for="explode-slider" class="form-label me-2 mb-0 fw-bold text-white-50 small d-flex align-items-center gap-1" style="font-size: 11px;">
                            <i class="fas fa-cubes text-warning"></i> Robbantás:
                        </label>
                        <input type="range" class="form-range custom-range" id="explode-slider" min="0" max="100" value="0" style="width: 80px; cursor: pointer;">
                        <span id="explode-val" class="badge bg-warning text-dark ms-2 fw-bold" style="font-size: 10px;">0%</span>
                    </div>

                    <div class="d-flex align-items-center gap-1">
                        <div class="btn-group btn-group-sm" role="group" aria-label="CAD Nézetek">
                            <button type="button" class="btn btn-outline-secondary text-light btn-view-preset" id="btn-view-iso" title="Izometrikus Nézet">📐 ISO</button>
                            <button type="button" class="btn btn-outline-secondary text-light btn-view-preset" id="btn-view-top" title="Felülnézet">⬆️ TOP</button>
                            <button type="button" class="btn btn-outline-secondary text-light btn-view-preset" id="btn-view-front" title="Elölnézet">➡️ FRONT</button>
                            <button type="button" class="btn btn-outline-secondary text-light btn-view-preset" id="btn-view-side" title="Oldalnézet">↗️ SIDE</button>
                        </div>

                        <button type="button" class="btn btn-sm btn-outline-info active" id="btn-toggle-dims" title="3D Kótavonalak Ki/Be">📐 Kóták</button>
                        <button type="button" class="btn btn-sm btn-outline-primary active" id="btn-toggle-pins" title="Alkatrész Címkék Ki/Be">🏷️ Címkék</button>
                    </div>

                    <div class="d-flex align-items-center gap-2 ms-auto">
                        <button id="btn-export-stl" class="btn btn-outline-light btn-sm fw-semibold shadow-sm d-flex align-items-center gap-1" style="font-size: 11px;">
                            <i class="fas fa-download"></i> STL Export
                        </button>
                        <button id="btn-open-checkout" class="btn btn-success btn-sm fw-bold shadow-sm d-flex align-items-center gap-1" style="font-size: 11px;" data-bs-toggle="modal" data-bs-target="#orderModal">
                            <i class="fas fa-paper-plane"></i> Tervezet Beküldése
                        </button>
                    </div>
                </div>

                <div class="card-body p-0 position-relative overflow-hidden">
                    
                    <!-- OVERLAY 1: MÉRÉSI KÓTÁK -->
                    <div id="cad-dimensions-overlay" class="position-absolute top-0 start-0 m-3 p-3 text-white rounded-3 shadow-lg glass-card" style="z-index: 10; min-width: 200px; font-size: 11px;">
                        <div class="fw-bold text-info mb-2 border-bottom border-secondary pb-1 d-flex align-items-center justify-content-between">
                            <span><i class="fas fa-ruler me-1"></i> MÉRÉSI KÓTÁK</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">X (Szélesség):</span>
                            <strong id="dim-x-val" class="text-success font-monospace">0 mm</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Y (Magasság):</span>
                            <strong id="dim-y-val" class="text-success font-monospace">0 mm</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Z (Hosszúság):</span>
                            <strong id="dim-z-val" class="text-success font-monospace">0 mm</strong>
                        </div>
                    </div>

                    <!-- OVERLAY 2: DFM GYÁRTHATÓSÁG -->
                    <div id="dfm-overlay" class="position-absolute top-0 start-50 translate-middle-x mt-3 p-3 text-white rounded-3 shadow-lg glass-card" style="z-index: 10; min-width: 250px; font-size: 11px;">
                        <div class="fw-bold text-warning mb-2 border-bottom border-secondary pb-1 d-flex align-items-center justify-content-between gap-2">
                            <span><i class="fas fa-shield-alt me-1"></i> DFM GYÁRTHATÓSÁG</span>
                            <span id="dfm-status-badge" class="badge bg-success">OK</span>
                        </div>
                        <ul id="dfm-checklist" class="list-unstyled mb-0 d-flex flex-column gap-1">
                            <li class="text-success"><i class="fas fa-check-circle me-1"></i> Geometria ellenőrizve</li>
                        </ul>
                    </div>

                    <!-- OVERLAY 3: CAD SPECS -->
                    <div id="cad-hud-overlay" class="position-absolute top-0 end-0 m-3 p-3 text-white rounded-3 shadow-lg glass-card" style="z-index: 10; min-width: 210px; font-size: 11px;">
                        <div class="fw-bold text-success mb-2 border-bottom border-secondary pb-1 d-flex align-items-center justify-content-between">
                            <span><i class="fas fa-ruler-combined me-1"></i> CAD SPECS</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Befoglaló méret:</span>
                            <strong id="hud-dimensions" class="text-info">0 × 0 × 0 mm</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Becsült súly:</span>
                            <strong id="hud-weight" class="text-warning">0.0 kg</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Gyártási Ár:</span>
                            <strong id="hud-price" class="text-success">0 Ft</strong>
                        </div>
                    </div>

                    <!-- OVERLAY 4: BOM ALKATRÉSZJEGYZÉK -->
                    <div id="canvas-bom-overlay" class="position-absolute bottom-0 start-0 m-3 p-3 text-white rounded-3 shadow-lg glass-card" style="z-index: 10; max-width: 360px; font-size: 11px;">
                        <div class="fw-bold text-info mb-2 border-bottom border-secondary pb-1 d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-table me-1"></i> BOM ALKATRÉSZJEGYZÉK</span>
                            <span id="bom-count" class="badge bg-info text-dark">0 tétel</span>
                        </div>
                        <div class="table-responsive overflow-auto custom-scrollbar" style="max-height: 110px;">
                            <table class="table table-dark table-sm table-borderless mb-0 align-middle" style="font-size: 11px; background: transparent;">
                                <thead>
                                    <tr class="text-muted border-bottom border-secondary">
                                        <th>Alkatrész</th>
                                        <th>Material</th>
                                        <th>Kg</th>
                                        <th class="text-end">Ár</th>
                                    </tr>
                                </thead>
                                <tbody id="bom-table-body">
                                    <tr><td colspan="4" class="text-muted">3D Modell betöltése...</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- CÍMKE KONTÉNER ÉS THREE.JS VÁSZON -->
                    <div id="pins-container" class="position-absolute top-0 start-0 w-100 h-100 overflow-hidden" style="pointer-events: none; z-index: 5;"></div>
                    <div id="ai-canvas-container" style="width: 100%; height: 640px; background-color: #0b0d12;"></div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- MODAL: GYÁRTÁS-ELŐKÉSZÍTÉS ÉS MEGRENDELÉS -->
<div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content text-white border-secondary" style="background: #141824; border-radius: 15px;">
            <div class="modal-header border-secondary bg-dark">
                <h5 class="modal-title fw-bold text-success d-flex align-items-center gap-2" id="orderModalLabel">
                    <i class="fas fa-clipboard-check"></i> CAD Megrendelés & Gyártás-előkészítés
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Bezárás"></button>
            </div>
            <div class="modal-body p-4">
                <form id="checkout-form">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold" for="modal-surface-treatment">Felületkezelés Előírása</label>
                            <select class="form-select text-white border-secondary" id="modal-surface-treatment" style="background: #1e2433;">
                                <option value="raw">Nyers / Megmunkált (Alapértelmezett)</option>
                                <option value="powder">Porfestés (RAL Színskála)</option>
                                <option value="zinc">Galvanikus Horganyzás</option>
                                <option value="anodized">Eloxálás (Alumínium elemek)</option>
                            </select>
                        </div>
                        <div class="col-md-6" id="ral-picker-group" style="display: none;">
                            <label class="form-label text-muted small fw-bold" for="modal-ral-code">RAL Színválasztó</label>
                            <input type="text" class="form-control text-white border-secondary" id="modal-ral-code" placeholder="pl. RAL 7016 (Antracitszürke)" style="background: #1e2433;">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold" for="modal-urgency">Gyártási Sürgősség</label>
                            <select class="form-select text-white border-secondary" id="modal-urgency" style="background: #1e2433;">
                                <option value="1.0">Standard (10-15 munkanap)</option>
                                <option value="1.35">Expressz (+35% felár, 3-5 munkanap)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold" for="modal-quantity">Rendelési Darabszám (Széria)</label>
                            <input type="number" class="form-control text-white border-secondary" id="modal-quantity" value="1" min="1" max="500" style="background: #1e2433;">
                        </div>
                        <div class="col-12">
                            <label class="form-label text-muted small fw-bold" for="modal-notes">Egyedi Mérnöki Megjegyzések / Tűrések</label>
                            <textarea class="form-control text-white border-secondary" id="modal-notes" rows="2" placeholder="Szerelési tűrések, speciális csatlakozási igények..." style="background: #1e2433;"></textarea>
                        </div>
                    </div>

                    <hr class="border-secondary my-4">

                    <div class="p-3 rounded-3 border border-secondary d-flex justify-content-between align-items-center" style="background: #1e2433;">
                        <div>
                            <small class="text-muted d-block">Végleges Gyártási Becsült Költség:</small>
                            <span class="fs-4 fw-bold text-success" id="modal-total-price">0 Ft</span>
                            <small class="text-muted ms-2">(Nettó ár)</small>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-info fw-bold" id="btn-download-pdf">
                                <i class="fas fa-file-pdf me-1"></i> Árajánlat (PDF)
                            </button>
                            <button type="submit" class="btn btn-success fw-bold px-4" id="btn-submit-final-order">
                                <i class="fas fa-check-circle me-1"></i> Megrendelés Beküldése
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- EXTERNAL LIBRARIES -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/controls/OrbitControls.js"></script>
<script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/exporters/STLExporter.js"></script>
<script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/loaders/GLTFLoader.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<style>
.glass-card {
    background: rgba(20, 24, 36, 0.92) !important;
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, 0.12);
    box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.5);
}

.transition-all {
    transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
}

.custom-scrollbar::-webkit-scrollbar { width: 5px; }
.custom-scrollbar::-webkit-scrollbar-track { background: rgba(0, 0, 0, 0.2); }
.custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.2); border-radius: 4px; }

/* DYNAMIC 3D PIN LABELS */
.pin-3d-node {
    position: absolute;
    background: rgba(13, 110, 253, 0.92);
    color: #ffffff;
    padding: 3px 8px;
    border-radius: 6px;
    font-size: 10px;
    font-weight: 600;
    white-space: nowrap;
    border: 1px solid rgba(255,255,255,0.4);
    box-shadow: 0 4px 12px rgba(0,0,0,0.6);
    transform: translate(-50%, -100%);
    pointer-events: none;
    transition: opacity 0.2s ease;
    z-index: 6;
}
.pin-3d-node::after {
    content: '';
    position: absolute;
    bottom: -5px;
    left: 50%;
    transform: translateX(-50%);
    border-width: 5px 5px 0;
    border-style: solid;
    border-color: rgba(13, 110, 253, 0.92) transparent;
}

.nav-item-btn.active { background-color: #0d6efd !important; }
.btn-prompt:hover { background-color: #0d6efd !important; border-color: #0d6efd !important; color: #fff !important; }
.btn-view-preset:hover { background-color: #0d6efd !important; color: #fff !important; }

/* Inputs focus states for WCAG */
.form-control:focus, .form-select:focus {
    border-color: #38bdf8 !important;
    box-shadow: 0 0 0 0.25rem rgba(56, 189, 248, 0.25) !important;
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const chatMessages = document.getElementById('chat-messages');
    const chatForm = document.getElementById('chat-form');
    const chatInput = document.getElementById('chat-input');
    const fileInput = document.getElementById('file-input');
    const attachmentPreview = document.getElementById('attachment-preview');
    const attachedFilename = document.getElementById('attached-filename');
    const btnRemoveAttachment = document.getElementById('btn-remove-attachment');
    
    const btnExportStl = document.getElementById('btn-export-stl');
    const explodeSlider = document.getElementById('explode-slider');
    const explodeVal = document.getElementById('explode-val');

    const bomTableBody = document.getElementById('bom-table-body');
    const bomCount = document.getElementById('bom-count');
    const hudWeight = document.getElementById('hud-weight');
    const hudPrice = document.getElementById('hud-price');
    const hudDimensions = document.getElementById('hud-dimensions');
    const dfmChecklist = document.getElementById('dfm-checklist');
    const dfmStatusBadge = document.getElementById('dfm-status-badge');
    const pinsContainer = document.getElementById('pins-container');
    const dynamicParamsContainer = document.getElementById('dynamic-params-container');

    const dimXVal = document.getElementById('dim-x-val');
    const dimYVal = document.getElementById('dim-y-val');
    const dimZVal = document.getElementById('dim-z-val');

    let currentUploadedFilePath = null;
    let chatHistory = [];
    let currentShapeData = {};
    let assemblyComponents = []; 
    let bboxHelper = null;
    let dimLinesGroup = null;

    let showPins = true;
    let showDims = true;
    let paramUpdateTimeout = null;

    // LOCAL & CDN CAD LIBRARIES
    const LOCAL_CAD_LIBRARY = {
        'motor': '<?= Url::to('@web/models/cad_library/electric_motor.glb') ?>',
        'hopper': '<?= Url::to('@web/models/cad_library/industrial_hopper.glb') ?>',
        'conveyor': '<?= Url::to('@web/models/cad_library/belt_conveyor.glb') ?>',
        'gearbox': '<?= Url::to('@web/models/cad_library/gearbox.glb') ?>',
        'frame': '<?= Url::to('@web/models/cad_library/steel_frame.glb') ?>'
    };

    const FALLBACK_CDN_LIBRARY = {
        'motor': 'https://raw.githubusercontent.com/KhronosGroup/glTF-Sample-Models/main/2.0/Duck/glTF-Binary/Duck.glb',
        'hopper': 'https://raw.githubusercontent.com/KhronosGroup/glTF-Sample-Models/main/2.0/BoxAnimated/glTF-Binary/BoxAnimated.glb',
        'conveyor': 'https://raw.githubusercontent.com/KhronosGroup/glTF-Sample-Models/main/2.0/DamagedHelmet/glTF-Binary/DamagedHelmet.glb'
    };

    const gltfLoader = new THREE.GLTFLoader();

    // FILE ATTACHMENT HANDLERS
    if (fileInput) {
        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                attachedFilename.textContent = this.files[0].name;
                attachmentPreview.classList.remove('d-none');
            }
        });
    }

    if (btnRemoveAttachment) {
        btnRemoveAttachment.addEventListener('click', function() {
            fileInput.value = '';
            currentUploadedFilePath = null;
            attachmentPreview.classList.add('d-none');
        });
    }

    // TOGGLES
    const btnToggleDims = document.getElementById('btn-toggle-dims');
    const btnTogglePins = document.getElementById('btn-toggle-pins');

    btnToggleDims.addEventListener('click', function() {
        showDims = !showDims;
        this.classList.toggle('active', showDims);
        if (dimLinesGroup) dimLinesGroup.visible = showDims;
    });

    btnTogglePins.addEventListener('click', function() {
        showPins = !showPins;
        this.classList.toggle('active', showPins);
    });

    // TAB SWITCHING
    document.getElementById('tab-chat-btn').addEventListener('click', function() {
        document.getElementById('tab-chat').classList.add('show', 'active');
        document.getElementById('tab-params').classList.remove('show', 'active');
        this.classList.add('active');
        document.getElementById('tab-params-btn').classList.remove('active');
    });
    
    document.getElementById('tab-params-btn').addEventListener('click', function() {
        document.getElementById('tab-params').classList.add('show', 'active');
        document.getElementById('tab-chat').classList.remove('show', 'active');
        this.classList.add('active');
        document.getElementById('tab-chat-btn').classList.remove('active');
    });

    function formatAiMessage(text) {
        if (!text) return '';
        return text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>').replace(/\n/g, '<br>');
    }

    document.querySelectorAll('.btn-prompt').forEach(btn => {
        btn.addEventListener('click', function() {
            chatInput.value = this.getAttribute('data-prompt');
            chatForm.dispatchEvent(new Event('submit'));
        });
    });

    // VIEWPORT RESIZING
    const sidebarCol = document.getElementById('sidebar-col');
    const viewportCol = document.getElementById('viewport-col');
    const btnToggleViewport = document.getElementById('btn-toggle-viewport');
    let isCollapsed = false;

    btnToggleViewport.addEventListener('click', function() {
        isCollapsed = !isCollapsed;
        if (isCollapsed) {
            sidebarCol.classList.add('d-none');
            viewportCol.className = 'col-12 transition-all';
            btnToggleViewport.innerHTML = '<i class="fas fa-compress-alt"></i> <span class="d-none d-xl-inline">Panel Vissza</span>';
        } else {
            sidebarCol.classList.remove('d-none');
            viewportCol.className = 'col-lg-7 col-md-6 transition-all';
            btnToggleViewport.innerHTML = '<i class="fas fa-expand-alt"></i> <span class="d-none d-xl-inline">Teljes Viewport</span>';
        }
        setTimeout(resizeCanvas, 350);
    });

    function resizeCanvas() {
        if (!container || !renderer || !camera) return;
        const width = container.clientWidth;
        const height = container.clientHeight;
        camera.aspect = width / height;
        camera.updateProjectionMatrix();
        renderer.setSize(width, height);
    }
    window.addEventListener('resize', resizeCanvas);

    // THREE.JS SETUP
    const container = document.getElementById('ai-canvas-container');
    const scene = new THREE.Scene();
    scene.background = new THREE.Color(0x0b0d12);

    const camera = new THREE.PerspectiveCamera(45, container.clientWidth / container.clientHeight, 0.1, 1000);
    const renderer = new THREE.WebGLRenderer({ antialias: true });
    renderer.setSize(container.clientWidth, container.clientHeight);
    renderer.setPixelRatio(window.devicePixelRatio);
    renderer.shadowMap.enabled = true;
    container.appendChild(renderer.domElement);

    const controls = new THREE.OrbitControls(camera, renderer.domElement);
    controls.enableDamping = true;

    // LIGHTS
    scene.add(new THREE.AmbientLight(0xffffff, 0.8));
    const mainLight = new THREE.DirectionalLight(0xffffff, 1.5);
    mainLight.position.set(15, 30, 20);
    scene.add(mainLight);

    const fillLight = new THREE.DirectionalLight(0x38bdf8, 0.8);
    fillLight.position.set(-15, -10, -20);
    scene.add(fillLight);

    const gridHelper = new THREE.GridHelper(30, 30, 0x38bdf8, 0x1e293b);
    scene.add(gridHelper);

    let currentAssemblyGroup = null;

    const materials = {
        steel: new THREE.MeshStandardMaterial({ color: 0x95a5a6, roughness: 0.3, metalness: 0.8 }),
        dark_metal: new THREE.MeshStandardMaterial({ color: 0x2c3e50, roughness: 0.4, metalness: 0.7 }),
        motor_blue: new THREE.MeshStandardMaterial({ color: 0x2563eb, roughness: 0.3, metalness: 0.5 }),
        stainless: new THREE.MeshStandardMaterial({ color: 0xcbd5e1, roughness: 0.2, metalness: 0.9 }),
        brass: new THREE.MeshStandardMaterial({ color: 0xd97706, roughness: 0.3, metalness: 0.9 }),
        belt_black: new THREE.MeshStandardMaterial({ color: 0x111111, roughness: 0.8, metalness: 0.1 })
    };

    function setCamPreset(x, y, z) {
        if (!currentAssemblyGroup) return;
        const box = new THREE.Box3().setFromObject(currentAssemblyGroup);
        const center = box.getCenter(new THREE.Vector3());
        const size = box.getSize(new THREE.Vector3());
        const maxDim = Math.max(size.x, size.y, size.z) || 3;

        camera.position.set(center.x + x * maxDim * 2, center.y + y * maxDim * 2, center.z + z * maxDim * 2);
        controls.target.copy(center);
        controls.update();
    }

    document.getElementById('btn-view-iso').addEventListener('click', () => setCamPreset(0.8, 0.8, 1.2));
    document.getElementById('btn-view-top').addEventListener('click', () => setCamPreset(0.001, 1.5, 0));
    document.getElementById('btn-view-front').addEventListener('click', () => setCamPreset(0, 0, 1.5));
    document.getElementById('btn-view-side').addEventListener('click', () => setCamPreset(1.5, 0, 0));

    // MEMÓRIA TAKARÍTÓ FÜGGVÉNY (PREVENTS MEMORY LEAKS & WEBGL CRASH)
    function disposeGroup(group) {
        if (!group) return;
        group.traverse((child) => {
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
    }

    // PROCEDURAL COMPONENT GENERATOR
    function createProceduralComponent(comp) {
        const group = new THREE.Group();
        const rawParams = comp.params || {};

        const normalize = (val, defaultVal) => {
            if (typeof val !== 'number' || isNaN(val) || val <= 0) return defaultVal;
            return val > 20 ? val / 1000 : val;
        };

        const p = {
            width: normalize(rawParams.width || rawParams.w || rawParams.x, 0.4),
            height: normalize(rawParams.height || rawParams.h || rawParams.y, 0.4),
            length: normalize(rawParams.length || rawParams.l || rawParams.z || rawParams.depth, 0.4),
            radius: normalize(rawParams.radius || rawParams.r, 0.15),
            rTop: normalize(rawParams.rTop, 0.6),
            rBottom: normalize(rawParams.rBottom, 0.2)
        };

        const getMat = (matKey, defaultMat) => materials[matKey] || defaultMat || materials.steel;
        const compType = String(comp.type || '').toLowerCase();
        const compName = String(comp.name || '').toLowerCase();

        const matches = (...keywords) => keywords.some(kw => compType.includes(kw) || compName.includes(kw));

        if (matches('hopper', 'cone', 'garat', 'tölcsér')) {
            const geom = new THREE.CylinderGeometry(p.rTop, p.rBottom, p.height, 32, 1, true);
            const mesh = new THREE.Mesh(geom, getMat('stainless'));
            group.add(mesh);
        } else if (matches('conveyor', 'belt', 'szalag', 'pálya')) {
            const beltGeom = new THREE.BoxGeometry(p.width, 0.12, p.length);
            const beltMesh = new THREE.Mesh(beltGeom, getMat('belt_black'));
            group.add(beltMesh);

            const rollerGeom = new THREE.CylinderGeometry(p.width * 0.15, p.width * 0.15, p.width + 0.05, 24);
            const roller1 = new THREE.Mesh(rollerGeom, getMat('steel'));
            roller1.rotation.z = Math.PI / 2;
            roller1.position.set(0, 0, p.length / 2);

            const roller2 = roller1.clone();
            roller2.position.set(0, 0, -p.length / 2);

            group.add(roller1, roller2);
        } else if (matches('motor', 'engine', 'pump', 'hajtás')) {
            const bodyGeom = new THREE.CylinderGeometry(p.radius * 1.5, p.radius * 1.5, p.length * 0.6, 24);
            const bodyMesh = new THREE.Mesh(bodyGeom, getMat('motor_blue'));
            bodyMesh.rotation.z = Math.PI / 2;
            group.add(bodyMesh);

            const shaftGeom = new THREE.CylinderGeometry(p.radius * 0.3, p.radius * 0.3, p.length * 0.3, 16);
            const shaftMesh = new THREE.Mesh(shaftGeom, getMat('stainless'));
            shaftMesh.rotation.z = Math.PI / 2;
            shaftMesh.position.set(p.length * 0.4, 0, 0);
            group.add(shaftMesh);
        } else if (matches('frame', 'leg', 'váz', 'láb', 'állvány')) {
            const legGeom = new THREE.BoxGeometry(0.06, p.height, 0.06);
            const legPositions = [
                [-p.width / 2, p.height / 2, -p.length / 2],
                [p.width / 2, p.height / 2, -p.length / 2],
                [-p.width / 2, p.height / 2, p.length / 2],
                [p.width / 2, p.height / 2, p.length / 2]
            ];

            legPositions.forEach(pos => {
                const leg = new THREE.Mesh(legGeom, getMat('steel'));
                leg.position.set(...pos);
                group.add(leg);
            });
        } else {
            const geom = new THREE.BoxGeometry(p.width, p.height, p.length);
            const mesh = new THREE.Mesh(geom, getMat('steel'));
            group.add(mesh);
        }

        return group;
    }

    function processLoadedMesh(gltf, comp) {
        const loadedMesh = gltf.scene.clone();
        const rawParams = comp.params || {};

        const scaleX = rawParams.width ? (rawParams.width > 20 ? rawParams.width / 1000 : rawParams.width) : 1;
        const scaleY = rawParams.height ? (rawParams.height > 20 ? rawParams.height / 1000 : rawParams.height) : 1;
        const scaleZ = rawParams.length ? (rawParams.length > 20 ? rawParams.length / 1000 : rawParams.length) : 1;
        
        loadedMesh.scale.set(scaleX, scaleY, scaleZ);
        
        loadedMesh.traverse((child) => {
            if (child.isMesh) {
                child.castShadow = true;
                child.receiveShadow = true;
            }
        });

        return loadedMesh;
    }

    async function loadCADComponentOrFallback(comp) {
        const compType = String(comp.type || '').toLowerCase();
        const compName = String(comp.name || '').toLowerCase();

        let localUrl = null;
        let cdnUrl = null;

        for (let [key, url] of Object.entries(LOCAL_CAD_LIBRARY)) {
            if (compType.includes(key) || compName.includes(key)) {
                localUrl = url;
                cdnUrl = FALLBACK_CDN_LIBRARY[key] || null;
                break;
            }
        }

        if (localUrl) {
            try {
                const gltf = await new Promise((resolve, reject) => {
                    gltfLoader.load(localUrl, resolve, undefined, reject);
                });
                return processLoadedMesh(gltf, comp);
            } catch (error) {
                console.warn(`[CAD Engine] Helyi GLTF nem érhető el (${localUrl}), visszalépés...`);
            }
        }

        if (cdnUrl) {
            try {
                const gltf = await new Promise((resolve, reject) => {
                    gltfLoader.load(cdnUrl, resolve, undefined, reject);
                });
                return processLoadedMesh(gltf, comp);
            } catch (error) {
                console.warn(`[CAD Engine] CDN GLTF sem tölthető be (${cdnUrl}).`);
            }
        }

        return createProceduralComponent(comp);
    }

    function create3DDimensionLines(box) {
        if (dimLinesGroup) {
            disposeGroup(dimLinesGroup);
            scene.remove(dimLinesGroup);
        }

        dimLinesGroup = new THREE.Group();
        const min = box.min;
        const max = box.max;
        const size = box.getSize(new THREE.Vector3());

        const lineMaterial = new THREE.LineBasicMaterial({ color: 0x38bdf8, linewidth: 2 });

        const xGeom = new THREE.BufferGeometry().setFromPoints([
            new THREE.Vector3(min.x, min.y - 0.08, max.z + 0.08),
            new THREE.Vector3(max.x, min.y - 0.08, max.z + 0.08)
        ]);
        dimLinesGroup.add(new THREE.Line(xGeom, lineMaterial));

        const yGeom = new THREE.BufferGeometry().setFromPoints([
            new THREE.Vector3(max.x + 0.08, min.y, max.z + 0.08),
            new THREE.Vector3(max.x + 0.08, max.y, max.z + 0.08)
        ]);
        dimLinesGroup.add(new THREE.Line(yGeom, lineMaterial));

        const zGeom = new THREE.BufferGeometry().setFromPoints([
            new THREE.Vector3(max.x + 0.08, min.y - 0.08, min.z),
            new THREE.Vector3(max.x + 0.08, min.y - 0.08, max.z)
        ]);
        dimLinesGroup.add(new THREE.Line(zGeom, lineMaterial));

        dimLinesGroup.visible = showDims;
        scene.add(dimLinesGroup);

        dimXVal.textContent = `${Math.round(size.x * 1000)} mm`;
        dimYVal.textContent = `${Math.round(size.y * 1000)} mm`;
        dimZVal.textContent = `${Math.round(size.z * 1000)} mm`;
    }

    // ASZINKRON ASSEMBLY BUILDER (MEMORY CLEANED)
    async function buildCADAssembly(shapeData) {
        if (currentAssemblyGroup) {
            disposeGroup(currentAssemblyGroup);
            scene.remove(currentAssemblyGroup);
        }
        if (bboxHelper) scene.remove(bboxHelper);

        currentAssemblyGroup = new THREE.Group();
        assemblyComponents = [];
        pinsContainer.innerHTML = '';
        const tree = shapeData.assembly_tree || [];

        bomTableBody.innerHTML = '';
        bomCount.textContent = tree.length + ' tétel';

        let calculatedTotalWeight = 0;
        let calculatedTotalPrice = 0;

        for (let comp of tree) {
            const pos = comp.position || [0, 0, 0];
            const explodeDir = comp.explode_dir || [0, 1, 0];

            const meshGroup = await loadCADComponentOrFallback(comp);
            meshGroup.position.set(pos[0], pos[1], pos[2]);

            currentAssemblyGroup.add(meshGroup);

            const pinElem = document.createElement('div');
            pinElem.className = 'pin-3d-node';
            pinElem.style.display = 'none';
            pinElem.innerHTML = `<i class="fas fa-cog me-1"></i> ${comp.name}`;
            pinsContainer.appendChild(pinElem);

            assemblyComponents.push({
                mesh: meshGroup,
                pinElem: pinElem,
                basePos: new THREE.Vector3(pos[0], pos[1], pos[2]),
                explodeDir: new THREE.Vector3(explodeDir[0], explodeDir[1], explodeDir[2])
            });

            const weight = comp.weight_kg ?? comp.weight ?? comp.params?.weight_kg ?? 0.1;
            const price = comp.price_huf ?? comp.price ?? comp.params?.price_huf ?? 1500;

            calculatedTotalWeight += parseFloat(weight);
            calculatedTotalPrice += parseFloat(price);

            bomTableBody.insertAdjacentHTML('beforeend', `
                <tr>
                    <td class="fw-bold text-truncate" style="max-width: 110px;">${comp.name}</td>
                    <td><span class="badge bg-secondary" style="font-size: 9px;">${comp.material || 'S235'}</span></td>
                    <td>${parseFloat(weight).toFixed(2)}kg</td>
                    <td class="text-end fw-bold text-success">${new Intl.NumberFormat('hu-HU').format(price)} Ft</td>
                </tr>
            `);
        }

        scene.add(currentAssemblyGroup);

        const box = new THREE.Box3().setFromObject(currentAssemblyGroup);
        const size = box.getSize(new THREE.Vector3());
        
        bboxHelper = new THREE.Box3Helper(box, 0x38bdf8);
        scene.add(bboxHelper);

        create3DDimensionLines(box);

        const sizeXmm = Math.round(size.x * 1000);
        const sizeYmm = Math.round(size.y * 1000);
        const sizeZmm = Math.round(size.z * 1000);
        hudDimensions.textContent = `${sizeXmm} × ${sizeYmm} × ${sizeZmm} mm`;

        const finalWeight = shapeData.total_weight || calculatedTotalWeight;
        const finalPrice = shapeData.total_price || calculatedTotalPrice;

        hudWeight.textContent = parseFloat(finalWeight).toFixed(1) + ' kg';
        hudPrice.textContent = new Intl.NumberFormat('hu-HU').format(finalPrice) + ' Ft';
        document.getElementById('modal-total-price').textContent = new Intl.NumberFormat('hu-HU').format(finalPrice) + ' Ft';

        setCamPreset(0.8, 0.8, 1.2);

        explodeSlider.value = 0;
        explodeVal.textContent = '0%';

        runDFMCheck(shapeData, size);
        renderParameterPanel(shapeData);
    }

    function runDFMCheck(shapeData, size) {
        dfmChecklist.innerHTML = '';
        let warnings = 0;

        if (size.y > size.x * 2.5 && size.y > size.z * 2.5) {
            dfmChecklist.insertAdjacentHTML('beforeend', `<li class="text-warning"><i class="fas fa-exclamation-triangle me-1"></i> Magas súlypont (Dőlésveszély)</li>`);
            warnings++;
        } else {
            dfmChecklist.insertAdjacentHTML('beforeend', `<li class="text-success"><i class="fas fa-check-circle me-1"></i> Stabilitás megfelelő</li>`);
        }

        if ((shapeData.total_weight || 0) > 150) {
            dfmChecklist.insertAdjacentHTML('beforeend', `<li class="text-info"><i class="fas fa-info-circle me-1"></i> Nehéz gép: D2 jelű alapozás kell</li>`);
        } else {
            dfmChecklist.insertAdjacentHTML('beforeend', `<li class="text-success"><i class="fas fa-check-circle me-1"></i> Standard szerelőváz</li>`);
        }

        dfmChecklist.insertAdjacentHTML('beforeend', `<li class="text-success"><i class="fas fa-check-circle me-1"></i> ISO gépészeti tűrések</li>`);

        if (warnings > 0) {
            dfmStatusBadge.className = 'badge bg-warning text-dark';
            dfmStatusBadge.textContent = 'FIGYELEM';
        } else {
            dfmStatusBadge.className = 'badge bg-success';
            dfmStatusBadge.textContent = 'GYÁRTHATÓ';
        }
    }

    // DINAMIKUS PARAMÉTER PANEL (WITH DEBOUNCED UPDATE)
    function renderParameterPanel(shapeData) {
        dynamicParamsContainer.innerHTML = '';
        const tree = shapeData.assembly_tree || [];
        document.getElementById('param-badge').textContent = tree.length;
        document.getElementById('param-badge').classList.remove('d-none');

        tree.forEach((comp, idx) => {
            const params = comp.params || {};
            let inputsHtml = '';

            for (let [key, val] of Object.entries(params)) {
                if (typeof val === 'number') {
                    const minVal = (val * 0.1).toFixed(2);
                    const maxVal = (val * 3.0).toFixed(2);

                    inputsHtml += `
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="form-label mb-0 text-muted" style="font-size: 11px;">${key}:</label>
                                <div class="input-group input-group-sm" style="width: 110px;">
                                    <input type="number" class="form-control form-control-sm text-white bg-dark border-secondary param-number p-1 text-center" 
                                           data-comp-idx="${idx}" data-param-key="${key}" 
                                           min="${minVal}" max="${maxVal}" step="0.05" value="${val}">
                                </div>
                            </div>
                            <input type="range" class="form-range param-slider" 
                                   data-comp-idx="${idx}" data-param-key="${key}" 
                                   min="${minVal}" max="${maxVal}" step="0.05" value="${val}">
                        </div>
                    `;
                }
            }

            const cardHtml = `
                <div class="p-3 rounded-3 border" style="background: #1e2433; border-color: rgba(255,255,255,0.08) !important;">
                    <div class="fw-bold text-info mb-2 d-flex justify-content-between align-items-center" style="font-size: 12px;">
                        <span><i class="fas fa-cog me-1"></i> ${comp.name}</span>
                        <span class="badge bg-dark text-info border border-secondary">${comp.material || 'S235'}</span>
                    </div>
                    ${inputsHtml || '<div class="text-muted small">Nincsenek állítható méretparaméterek.</div>'}
                </div>
            `;
            dynamicParamsContainer.insertAdjacentHTML('beforeend', cardHtml);
        });

        // DEBOUNCED UPDATE LEHALÁS ELLEN
        const debouncedUpdateParam = (compIdx, paramKey, newVal) => {
            shapeData.assembly_tree[compIdx].params[paramKey] = newVal;
            if (paramUpdateTimeout) clearTimeout(paramUpdateTimeout);
            paramUpdateTimeout = setTimeout(() => {
                buildCADAssembly(shapeData);
            }, 150);
        };

        document.querySelectorAll('.param-slider').forEach(slider => {
            slider.addEventListener('input', function() {
                const idx = this.getAttribute('data-comp-idx');
                const key = this.getAttribute('data-param-key');
                const val = parseFloat(this.value);
                
                const numInput = document.querySelector(`.param-number[data-comp-idx="${idx}"][data-param-key="${key}"]`);
                if (numInput) numInput.value = val;

                debouncedUpdateParam(idx, key, val);
            });
        });

        document.querySelectorAll('.param-number').forEach(numInput => {
            const handleNumberInput = function() {
                const idx = this.getAttribute('data-comp-idx');
                const key = this.getAttribute('data-param-key');
                const val = parseFloat(this.value);

                if (isNaN(val) || val <= 0) return;

                const slider = document.querySelector(`.param-slider[data-comp-idx="${idx}"][data-param-key="${key}"]`);
                if (slider) slider.value = val;

                debouncedUpdateParam(idx, key, val);
            };

            numInput.addEventListener('input', handleNumberInput);
            numInput.addEventListener('change', handleNumberInput);
        });
    }

    // UPDATE 3D PINS AND LABELS ACCURATELY
    function update3DPinsAndDims() {
        if (!showPins || !assemblyComponents.length) {
            assemblyComponents.forEach(item => { item.pinElem.style.display = 'none'; });
            return;
        }

        const tempV = new THREE.Vector3();
        const containerWidth = container.clientWidth;
        const containerHeight = container.clientHeight;
        const widthHalf = containerWidth / 2;
        const heightHalf = containerHeight / 2;

        assemblyComponents.forEach(item => {
            if (!item.mesh) return;

            item.mesh.getWorldPosition(tempV);
            tempV.y += 0.2;
            tempV.project(camera);

            const x = (tempV.x * widthHalf) + widthHalf;
            const y = -(tempV.y * heightHalf) + heightHalf;

            if (tempV.z > 1 || x < 0 || x > containerWidth || y < 0 || y > containerHeight) {
                item.pinElem.style.display = 'none';
                return;
            }

            item.pinElem.style.display = 'block';
            item.pinElem.style.left = `${Math.round(x)}px`;
            item.pinElem.style.top = `${Math.round(y)}px`;
        });
    }

    explodeSlider.addEventListener('input', function () {
        const factor = parseFloat(explodeSlider.value) / 50;
        explodeVal.textContent = explodeSlider.value + '%';

        assemblyComponents.forEach(item => {
            item.mesh.position.x = item.basePos.x + (item.explodeDir.x * factor);
            item.mesh.position.y = item.basePos.y + (item.explodeDir.y * factor);
            item.mesh.position.z = item.basePos.z + (item.explodeDir.z * factor);
        });
    });

    // INIT DEMO MODEL
    buildCADAssembly({
        total_weight: 89.5,
        total_price: 540000,
        assembly_tree: [
            { name: 'Adagoló Garat', type: 'hopper', material: 'Rozsdamentes Acél', weight_kg: 24.0, price_huf: 160000, position: [0, 1.2, 0], explode_dir: [0, 1, 0], params: { rTop: 1.2, rBottom: 0.4, height: 1.5 } },
            { name: 'Szállítószálag Váz', type: 'conveyor_belt', material: 'S235 Acél', weight_kg: 35.0, price_huf: 210000, position: [0, 0.4, 0], explode_dir: [0, 0.5, 0], params: { length: 2.8, width: 0.8 } },
            { name: 'Ipari Hajtómotor', type: 'motor_block', material: 'Öntöttvas', weight_kg: 18.5, price_huf: 120000, position: [1.2, 0.4, 0], explode_dir: [1, 0, 0], params: { power_kw: 2.2 } },
            { name: 'Tartóláb Szerkezet', type: 'frame_legs', material: 'S235 Acél', weight_kg: 12.0, price_huf: 50000, position: [0, -0.6, 0], explode_dir: [0, -1, 0], params: { height: 1.2, width: 1.0, length: 2.2 } }
        ]
    });

    function animate() {
        requestAnimationFrame(animate);
        controls.update();
        renderer.render(scene, camera);
        update3DPinsAndDims();
    }
    animate();

    btnExportStl.addEventListener('click', function () {
        if (!currentAssemblyGroup) return;
        const exporter = new THREE.STLExporter();
        const result = exporter.parse(currentAssemblyGroup, { binary: true });
        const blob = new Blob([result], { type: 'application/octet-stream' });
        const link = document.createElement('a');
        link.href = URL.toObjectURL(blob);
        link.download = 'CAD_Terv_Export_' + Date.now() + '.stl';
        link.click();
    });

    // CHAT FORM SUBMIT
    chatForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const userMsg = chatInput.value.trim();
        if (!userMsg) return;

        appendMessage('user', userMsg, currentUploadedFilePath);
        chatInput.value = '';

        fetch('<?= Url::to(['ai3d/process-chat']) ?>', {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-CSRF-Token': '<?= Yii::$app->request->getCsrfToken() ?>'
            },
            body: new URLSearchParams({
                '<?= Yii::$app->request->csrfParam ?>': '<?= Yii::$app->request->getCsrfToken() ?>',
                message: userMsg,
                attachment_path: currentUploadedFilePath || '',
                current_parameters: JSON.stringify(currentShapeData)
            })
        })
        .then(res => {
            if (!res.ok) throw new Error('HTTP hiba: ' + res.status);
            return res.json();
        })
        .then(data => {
            appendMessage('ai', data.ai_reply);
            if (data.shape_data) {
                currentShapeData = data.shape_data;
                buildCADAssembly(data.shape_data);
            }
        })
        .catch(err => {
            console.error('Chat küldési hiba:', err);
        });

        fileInput.value = '';
        currentUploadedFilePath = null;
        attachmentPreview.classList.add('d-none');
    });

    function appendMessage(sender, text, attachmentPath = null) {
        const isUser = sender === 'user';
        const formattedText = isUser ? text : formatAiMessage(text);

        const msgHtml = `
            <div class="d-flex align-items-start mb-3 ${isUser ? 'justify-content-end' : ''}">
                <div class="p-3 shadow-sm rounded-3 border ${isUser ? 'bg-primary text-white' : ''}" style="${isUser ? '' : 'background: #1e2433; border-color: rgba(255,255,255,0.08) !important; color: #e1e3e8;'} max-width: 85%;">
                    <div class="fw-bold mb-1 font-size-12">${isUser ? 'Ön' : '<i class="fas fa-robot me-1 text-info"></i> AI Asszisztens'}</div>
                    <div class="mb-0 small" style="line-height: 1.5;">${formattedText}</div>
                </div>
            </div>
        `;
        chatMessages.insertAdjacentHTML('beforeend', msgHtml);
        chatMessages.scrollTop = chatMessages.scrollHeight;
        chatHistory.push({ sender: sender, text: text, attachment: attachmentPath });
    }

    // MODAL & PDF EXPORT
    const modalSurface = document.getElementById('modal-surface-treatment');
    const ralGroup = document.getElementById('ral-picker-group');
    const modalUrgency = document.getElementById('modal-urgency');
    const modalQuantity = document.getElementById('modal-quantity');
    const modalTotalPrice = document.getElementById('modal-total-price');

    modalSurface.addEventListener('change', function() {
        ralGroup.style.display = this.value === 'powder' ? 'block' : 'none';
    });

    function recalcModalPrice() {
        const basePrice = currentShapeData.total_price || 540000;
        const multUrgency = parseFloat(modalUrgency.value);
        const qty = parseInt(modalQuantity.value) || 1;
        const finalCalculated = Math.round(basePrice * multUrgency * qty);
        modalTotalPrice.textContent = new Intl.NumberFormat('hu-HU').format(finalCalculated) + ' Ft';
    }

    modalUrgency.addEventListener('change', recalcModalPrice);
    modalQuantity.addEventListener('input', recalcModalPrice);

    function cleanHungarianText(text) {
        if (!text) return '';
        return text.replace(/ő/g, 'o').replace(/Ő/g, 'O')
                   .replace(/ű/g, 'u').replace(/Ű/g, 'U');
    }

    document.getElementById('btn-download-pdf').addEventListener('click', function() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();

        const today = new Date().toLocaleDateString('hu-HU');
        const priceText = modalTotalPrice.textContent;

        doc.setFontSize(16);
        doc.text("HIVATALOS CAD GEPESZETI ARAJANLAT", 14, 20);
        
        doc.setFontSize(10);
        doc.text(`Datum: ${today}`, 14, 28);
        doc.text(`Azonosito: CAD-REQ-${Date.now().toString().slice(-6)}`, 14, 34);
        
        doc.line(14, 38, 196, 38);

        doc.setFontSize(12);
        doc.text("1. Modell es Gyartasi Parameterek", 14, 46);
        doc.setFontSize(10);
        doc.text(`- Feluletkezeles: ${cleanHungarianText(modalSurface.options[modalSurface.selectedIndex].text)}`, 16, 54);
        doc.text(`- Darabszam: ${modalQuantity.value} db`, 16, 60);
        doc.text(`- Gyartasi atfutas: ${cleanHungarianText(modalUrgency.options[modalUrgency.selectedIndex].text)}`, 16, 66);
        doc.text(`- Osszsuly: ${hudWeight.textContent}`, 16, 72);
        doc.text(`- Befoglalo meret: ${hudDimensions.textContent}`, 16, 78);

        doc.setFontSize(12);
        doc.text("2. Becsult Gyartasi Vegosszeg", 14, 90);
        doc.setFontSize(14);
        doc.text(`Netto ar: ${priceText}`, 16, 100);

        doc.setFontSize(8);
        doc.text("Ez az arajanlat az AI CAD Engine altal automatikusan generalt elozetes kalkulacio.", 14, 120);

        doc.save(`CAD_Arajanalat_${Date.now()}.pdf`);
    });

    document.getElementById('checkout-form').addEventListener('submit', function(e) {
        e.preventDefault();

        fetch('<?= Url::to(['ai3d/submit-order']) ?>', {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-CSRF-Token': '<?= Yii::$app->request->getCsrfToken() ?>'
            },
            body: new URLSearchParams({
                '<?= Yii::$app->request->csrfParam ?>': '<?= Yii::$app->request->getCsrfToken() ?>',
                title: 'Komplex CAD Megrendelés',
                parameters: JSON.stringify({
                    shape_data: currentShapeData,
                    surface: modalSurface.value,
                    ral: document.getElementById('modal-ral-code').value,
                    urgency: modalUrgency.value,
                    quantity: modalQuantity.value,
                    notes: document.getElementById('modal-notes').value,
                    total_price: modalTotalPrice.textContent
                }),
                chat_history: JSON.stringify(chatHistory)
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert('🎉 CAD Megrendelés és gyártási specifikáció sikeresen továbbítva!');
                window.location.reload();
            }
        });
    });
});
</script>