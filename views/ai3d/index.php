<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'AI 3D Tervező Asszisztens (Pro CAD Viewport)';
?>

<div class="ai3d-index container-fluid">
    <div class="row">

        <!-- BAL OLDAL: AI Multimodális Chatbot -->
        <div class="col-lg-5 col-md-6 mb-4">
            <div class="card shadow-sm h-100 border-0" style="border-radius: 15px; background: #ffffff;">
                <div class="card-header bg-dark text-white d-flex align-items-center justify-content-between py-3" style="border-top-left-radius: 15px; border-top-right-radius: 15px;">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-robot text-primary fa-lg me-2"></i>
                        <span class="fw-bold">3D AI Mérnök Asszisztens</span>
                    </div>
                    <span class="badge bg-success">Pro CAD Engine</span>
                </div>

                <!-- Chat Üzenetek Doboza -->
                <div class="card-body p-3 d-flex flex-column" style="height: 580px;">
                    <div id="chat-messages" class="flex-grow-1 overflow-auto pe-2 mb-3" style="max-height: 500px;">
                        <div class="d-flex align-items-start mb-3">
                            <div class="bg-light p-3 shadow-sm rounded-3 border w-100" style="max-width: 90%;">
                                <div class="fw-bold text-primary mb-1"><i class="fas fa-robot me-1"></i> AI Asszisztens</div>
                                <p class="mb-1 small">Üdvözlöm! Írja le a kívánt gépet (pl. ipari etetőberendezés), én pedig legenerálom a 3D összeállítást **a modellre integrált 3D BOM címkékkel**!</p>
                            </div>
                        </div>
                    </div>

                    <div id="attachment-preview" class="alert alert-info py-1 px-2 mb-2 d-none d-flex justify-content-between align-items-center" style="font-size: 12px;">
                        <span><i class="fas fa-paperclip me-1"></i> <span id="attached-filename"></span></span>
                        <button type="button" id="btn-remove-attachment" class="btn-close btn-sm" style="font-size: 10px;"></button>
                    </div>

                    <form id="chat-form" enctype="multipart/form-data">
                        <div class="input-group">
                            <label class="btn btn-outline-secondary mb-0" title="Kép / Skicc csatolása">
                                <i class="fas fa-paperclip"></i>
                                <input type="file" id="file-input" name="attachment" accept="image/*,.pdf" style="display: none;">
                            </label>
                            <input type="text" id="chat-input" class="form-control" placeholder="Írja le az igényeit (pl. 3 méteres etető)..." required>
                            <button class="btn btn-primary px-4" type="submit">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- JOBB OLDAL: TELJES MAGASSÁGÚ 3D VÁSZON INTEGRÁLT 3D BOM OVERLAY-JEL -->
        <div class="col-lg-7 col-md-6">
            <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px;">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center flex-wrap">
                    
                    <!-- 💥 ROBBANTOTT NÉZET CSÚSZKA -->
                    <div class="d-flex align-items-center me-3 my-1" style="min-width: 250px;">
                        <label for="explode-slider" class="form-label me-2 mb-0 fw-bold text-dark small">
                            💥 Robbantás:
                        </label>
                        <input type="range" class="form-range" id="explode-slider" min="0" max="100" value="0" style="cursor: pointer;">
                        <span id="explode-val" class="badge bg-primary ms-2">0%</span>
                    </div>

                    <div class="btn-group btn-group-sm">
                        <button id="btn-export-stl" class="btn btn-outline-primary fw-bold">
                            📥 CAD Export (.STL)
                        </button>
                    </div>

                    <button id="btn-submit-order" class="btn btn-success font-weight-bold shadow-sm" disabled>
                        🚀 Tervezet Beküldése
                    </button>
                </div>

                <div class="card-body p-0 position-relative overflow-hidden" style="border-bottom-left-radius: 15px; border-bottom-right-radius: 15px;">
                    
                    <!-- 📊 BEÉPÍTETT CAD DRAWING TITLE BLOCK / BOM OVERLAY (3D VÁSZNON BELÜL) -->
                    <div id="canvas-bom-overlay" class="position-absolute bottom-0 start-0 m-3 p-3 text-white rounded shadow-lg" style="background: rgba(14, 14, 16, 0.85); backdrop-filter: blur(8px); z-index: 10; max-width: 380px; font-size: 11px; border: 1px solid rgba(255,255,255,0.15);">
                        <div class="fw-bold text-primary mb-2 border-bottom pb-1 d-flex justify-content-between align-items-center">
                            <span>📊 CAD RAJZLAP BOM ALKATRÉSZJEGYZÉK</span>
                            <span id="bom-count" class="badge bg-primary">4 tétel</span>
                        </div>
                        <div class="table-responsive overflow-auto" style="max-height: 140px;">
                            <table class="table table-dark table-sm table-borderless mb-0 align-middle" style="font-size: 11px;">
                                <thead>
                                    <tr class="text-secondary border-bottom">
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

                    <!-- 📐 ÉLŐ KÖLTSÉGBECSLÉS HUD (JOBB FELÜL) -->
                    <div id="cad-hud-overlay" class="position-absolute top-0 end-0 m-3 p-3 text-white rounded shadow-lg" style="background: rgba(14, 14, 16, 0.85); backdrop-filter: blur(8px); z-index: 10; min-width: 200px; font-size: 12px; border: 1px solid rgba(255,255,255,0.15);">
                        <div class="fw-bold text-primary mb-2 border-bottom pb-1">📐 CAD KÖLTSÉGBECSLÉS</div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-secondary">Összsúly:</span>
                            <strong id="hud-weight" class="text-warning">0.0 kg</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-secondary">Gyártási Ár:</span>
                            <strong id="hud-price" class="text-success">0 Ft</strong>
                        </div>
                    </div>

                    <!-- 🏷️ DINAMIKUS 3D CÍMKÉK KONTÉNER (3D PIN CALLOUTS) -->
                    <div id="pins-container" class="position-absolute top-0 start-0 w-100 h-100" style="pointer-events: none; z-index: 5;"></div>

                    <!-- 3D CANVÁS -->
                    <div id="ai-canvas-container" style="width: 100%; height: 580px; background-color: #0e0e10;"></div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Three.js, STLExporter és Control modulok -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/controls/OrbitControls.js"></script>
<script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/exporters/STLExporter.js"></script>

<style>
/* 🏷️ 3D Floating Pin Stílusok */
.3d-pin-label {
    position: absolute;
    background: rgba(13, 110, 253, 0.85);
    color: #ffffff;
    padding: 3px 8px;
    border-radius: 6px;
    font-size: 10px;
    font-weight: 600;
    white-space: nowrap;
    border: 1px solid rgba(255,255,255,0.4);
    box-shadow: 0 4px 10px rgba(0,0,0,0.5);
    transform: translate(-50%, -100%);
    transition: opacity 0.2s ease;
    backdrop-filter: blur(4px);
}
.3d-pin-label::after {
    content: '';
    position: absolute;
    bottom: -5px;
    left: 50%;
    transform: translateX(-50%);
    border-width: 5px 5px 0;
    border-style: solid;
    border-color: rgba(13, 110, 253, 0.85) transparent;
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
    const btnSubmitOrder = document.getElementById('btn-submit-order');
    const btnExportStl = document.getElementById('btn-export-stl');
    
    const explodeSlider = document.getElementById('explode-slider');
    const explodeVal = document.getElementById('explode-val');

    // UI Overlay Elemek
    const bomTableBody = document.getElementById('bom-table-body');
    const bomCount = document.getElementById('bom-count');
    const hudWeight = document.getElementById('hud-weight');
    const hudPrice = document.getElementById('hud-price');
    const pinsContainer = document.getElementById('pins-container');

    let currentUploadedFilePath = null;
    let chatHistory = [];
    let currentShapeData = {};
    let assemblyComponents = []; 

    // -------------------------------------------------------------
    // 1. THREE.JS ENGINE & 3D FLOATING CALLOUTS
    // -------------------------------------------------------------
    const container = document.getElementById('ai-canvas-container');
    const scene = new THREE.Scene();
    scene.background = new THREE.Color(0x0e0e10);

    const camera = new THREE.PerspectiveCamera(45, container.clientWidth / container.clientHeight, 0.1, 1000);
    const renderer = new THREE.WebGLRenderer({ antialias: true });
    renderer.setSize(container.clientWidth, container.clientHeight);
    renderer.setPixelRatio(window.devicePixelRatio);
    container.appendChild(renderer.domElement);

    const controls = new THREE.OrbitControls(camera, renderer.domElement);
    controls.enableDamping = true;

    // Megvilágítás
    scene.add(new THREE.AmbientLight(0xffffff, 1.0));
    const dirLight1 = new THREE.DirectionalLight(0xffffff, 1.8);
    dirLight1.position.set(15, 30, 20);
    scene.add(dirLight1);

    const gridHelper = new THREE.GridHelper(30, 30, 0x0d6efd, 0x333333);
    scene.add(gridHelper);

    let currentAssemblyGroup = null;

    const materials = {
        steel: new THREE.MeshStandardMaterial({ color: 0x95a5a6, roughness: 0.3, metalness: 0.8 }),
        dark_metal: new THREE.MeshStandardMaterial({ color: 0x2c3e50, roughness: 0.4, metalness: 0.7 }),
        motor_blue: new THREE.MeshStandardMaterial({ color: 0x2980b9, roughness: 0.3, metalness: 0.5 }),
        stainless: new THREE.MeshStandardMaterial({ color: 0xecf0f1, roughness: 0.2, metalness: 0.9 })
    };

    /**
     * CAD ÖSSZEÁLLÍTÁS, BEÉPÍTETT BOM TÁBLÁZAT ÉS 3D FLOATING PIN CÍMKÉK
     */
    function buildCADAssembly(shapeData) {
        if (currentAssemblyGroup) scene.remove(currentAssemblyGroup);

        currentAssemblyGroup = new THREE.Group();
        assemblyComponents = [];
        pinsContainer.innerHTML = ''; // 3D Címkék törlése
        const tree = shapeData.assembly_tree || [];

        bomTableBody.innerHTML = '';
        bomCount.textContent = tree.length + ' tétel';

        tree.forEach((comp, idx) => {
            let mesh;
            const pos = comp.position || [0, 0, 0];
            const explodeDir = comp.explode_dir || [0, 1, 0];
            const params = comp.params || {};

            if (comp.type === 'truncated_cone') {
                const geom = new THREE.CylinderGeometry(params.rTop || 1, params.rBottom || 0.3, params.height || 1.5, 32, 1, true);
                mesh = new THREE.Mesh(geom, materials.stainless);
            } else if (comp.type === 'cylinder_horizontal') {
                const geom = new THREE.CylinderGeometry(params.radius || 0.3, params.radius || 0.3, params.length || 2, 32);
                mesh = new THREE.Mesh(geom, materials.dark_metal);
                mesh.rotation.z = Math.PI / 2;
            } else if (comp.type === 'motor_block') {
                const groupMotor = new THREE.Group();
                const s = params.size || 0.5;
                const bodyMesh = new THREE.Mesh(new THREE.BoxGeometry(s, s, s * 1.4), materials.motor_blue);
                groupMotor.add(bodyMesh);
                mesh = groupMotor;
            } else if (comp.type === 'frame_legs') {
                const groupFrame = new THREE.Group();
                const w = params.width || 1.2;
                const h = params.height || 1.0;
                const legGeom = new THREE.BoxGeometry(0.08, h, 0.08);
                [[-w/2, h/2, -w/2], [w/2, h/2, -w/2], [-w/2, h/2, w/2], [w/2, h/2, w/2]].forEach(p => {
                    const leg = new THREE.Mesh(legGeom, materials.steel);
                    leg.position.set(p[0], p[1], p[2]);
                    groupFrame.add(leg);
                });
                mesh = groupFrame;
            } else {
                mesh = new THREE.Mesh(new THREE.BoxGeometry(1, 1, 1), materials.steel);
            }

            mesh.position.set(pos[0], pos[1], pos[2]);
            currentAssemblyGroup.add(mesh);

            // 🏷️ 3D HTML PIN CÍMKE LÉTREHOZÁSA
            const pinElem = document.createElement('div');
            pinElem.className = '3d-pin-label';
            pinElem.innerHTML = `<i class="fas fa-tag me-1"></i> ${comp.name}`;
            pinsContainer.appendChild(pinElem);

            assemblyComponents.push({
                mesh: mesh,
                pinElem: pinElem,
                basePos: new THREE.Vector3(pos[0], pos[1], pos[2]),
                explodeDir: new THREE.Vector3(explodeDir[0], explodeDir[1], explodeDir[2])
            });

            // Beépített BOM Table sornövelés
            const priceFormatted = new Intl.NumberFormat('hu-HU').format(comp.price_huf || 0) + ' Ft';
            bomTableBody.insertAdjacentHTML('beforeend', `
                <tr>
                    <td class="fw-bold text-truncate" style="max-width: 110px;">${comp.name}</td>
                    <td><span class="badge bg-secondary">${comp.material}</span></td>
                    <td>${comp.weight_kg}k</td>
                    <td class="text-end fw-bold text-success">${priceFormatted}</td>
                </tr>
            `);
        });

        scene.add(currentAssemblyGroup);

        // HUD frissítése
        hudWeight.textContent = (shapeData.total_weight || 0).toFixed(1) + ' kg';
        hudPrice.textContent = new Intl.NumberFormat('hu-HU').format(shapeData.total_price || 0) + ' Ft';

        // Kamera pozicionálás
        const box = new THREE.Box3().setFromObject(currentAssemblyGroup);
        const size = box.getSize(new THREE.Vector3());
        const maxDim = Math.max(size.x, size.y, size.z);
        const cameraZ = maxDim * 2.5;

        camera.position.set(cameraZ * 0.8, size.y + 1, cameraZ);
        controls.target.set(0, size.y / 2, 0);
        controls.update();

        explodeSlider.value = 0;
        explodeVal.textContent = '0%';
    }

    /**
     * 🏷️ 3D PIN CÍMKÉK POZÍCIONÁLÁSA A KÉPERNYŐN (WORLD TO SCREEN TRANSFORMATION)
     */
    function update3DPins() {
        if (!assemblyComponents.length) return;

        const tempV = new THREE.Vector3();
        const widthHalf = container.clientWidth / 2;
        const heightHalf = container.clientHeight / 2;

        assemblyComponents.forEach(item => {
            // Elem 3D pozíciójának kiszámítása a világban
            item.mesh.getWorldPosition(tempV);
            tempV.y += 0.3; // Kicsit fölé emeljük a címkét

            // Mátrix vetítés a kamera képernyő-koordinátájára
            tempV.project(camera);

            // Ha a tárgy a kamera mögött van, elrejtjük
            if (tempV.z > 1) {
                item.pinElem.style.opacity = '0';
                return;
            }

            const x = (tempV.x * widthHalf) + widthHalf;
            const y = -(tempV.y * heightHalf) + heightHalf;

            item.pinElem.style.left = `${x}px`;
            item.pinElem.style.top = `${y}px`;
            item.pinElem.style.opacity = '1';
        });
    }

    // 💥 ROBBANTOTT NÉZET CSÚSZKA ESEMÉNYKEZELŐ
    explodeSlider.addEventListener('input', function () {
        const factor = parseFloat(explodeSlider.value) / 50;
        explodeVal.textContent = explodeSlider.value + '%';

        assemblyComponents.forEach(item => {
            item.mesh.position.x = item.basePos.x + (item.explodeDir.x * factor);
            item.mesh.position.y = item.basePos.y + (item.explodeDir.y * factor);
            item.mesh.position.z = item.basePos.z + (item.explodeDir.z * factor);
        });
    });

    // Kezdeti üres modell
    buildCADAssembly({ assembly_tree: [{ name: 'Főváz', material: 'S235 Acél', weight_kg: 12.0, price_huf: 45000, position: [0,0,0], explode_dir: [0,1,0] }] });

    function animate() {
        requestAnimationFrame(animate);
        controls.update();
        renderer.render(scene, camera);
        update3DPins(); // 💥 LEBEGŐ 3D CÍMKÉK FRISSÍTÉSE RENDERELÉSKOR!
    }
    animate();

    // Export STL
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

    // Chat küldés
    chatForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const userMsg = chatInput.value.trim();
        if (!userMsg) return;

        appendMessage('user', userMsg, currentUploadedFilePath);
        chatInput.value = '';

        fetch('<?= Url::to(['ai3d/process-chat']) ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({
                message: userMsg,
                attachment_path: currentUploadedFilePath || '',
                current_parameters: JSON.stringify(currentShapeData)
            })
        })
        .then(res => res.json())
        .then(data => {
            appendMessage('ai', data.ai_reply);
            if (data.shape_data) {
                currentShapeData = data.shape_data;
                buildCADAssembly(data.shape_data);
            }
            btnSubmitOrder.disabled = false;
        });

        currentUploadedFilePath = null;
        attachmentPreview.classList.add('d-none');
    });

    function appendMessage(sender, text, attachmentPath = null) {
        const isUser = sender === 'user';
        const msgHtml = `
            <div class="d-flex align-items-start mb-3 ${isUser ? 'justify-content-end' : ''}">
                <div class="p-3 shadow-sm rounded-3 border ${isUser ? 'bg-primary text-white' : 'bg-light text-dark'}" style="max-width: 85%;">
                    <div class="fw-bold mb-1 font-size-12">${isUser ? 'Ön' : '<i class="fas fa-robot me-1 text-primary"></i> AI Asszisztens'}</div>
                    <p class="mb-0 small" style="white-space: pre-line;">${text}</p>
                </div>
            </div>
        `;
        chatMessages.insertAdjacentHTML('beforeend', msgHtml);
        chatMessages.scrollTop = chatMessages.scrollHeight;
        chatHistory.push({ sender: sender, text: text, attachment: attachmentPath });
    }

    btnSubmitOrder.addEventListener('click', function () {
        if (confirm('Továbbítja ezt a komplett CAD megrendelést az adminisztrátornak?')) {
            fetch('<?= Url::to(['ai3d/submit-order']) ?>', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({
                    title: 'Komplex CAD Gépészeti Megrendelés',
                    parameters: JSON.stringify(currentShapeData),
                    chat_history: JSON.stringify(chatHistory)
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert('🎉 Megrendelés sikeresen továbbítva az adminisztrátornak!');
                    window.location.reload();
                }
            });
        }
    });
});
</script>