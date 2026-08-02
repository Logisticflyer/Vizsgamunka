<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = '3D Modell Kezelő & Megjelenítő';
?>

<div class="model3d-index container-fluid mt-3">
    <div class="row">

        <!-- BAL OLDAL: Feltöltő Form és Lista -->
        <div class="col-lg-4 col-md-5">
            
            <!-- Feltöltő kártya -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white font-weight-bold">
                    📂 3D Modell Feltöltése
                </div>
                <div class="card-body">
                    <?= Html::beginForm(['model3d/upload'], 'post', ['enctype' => 'multipart/form-data']) ?>

                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold">Modell Neve</label>
                        <?= Html::textInput('title', '', ['class' => 'form-control', 'placeholder' => 'pl. Saját Alkatrész', 'required' => true]) ?>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold">Leírás</label>
                        <?= Html::textarea('description', '', ['class' => 'form-control', 'rows' => 3]) ?>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold">3D Fájl (.glb vagy .gltf)</label>
                        <?= Html::fileInput('file3d', null, ['class' => 'form-control-file', 'accept' => '.glb,.gltf', 'required' => true]) ?>
                    </div>

                    <?= Html::submitButton('Feltöltés', ['class' => 'btn btn-success btn-block w-100']) ?>

                    <?= Html::endForm() ?>
                </div>
            </div>

            <!-- Elérhető modellek listája -->
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white font-weight-bold">
                    📜 Elérhető Modellek
                </div>
                <div class="card-body p-0">
                    <?php if (empty($models)): ?>
                        <p class="text-muted text-center p-3 m-0">Még nincs feltöltött 3D modell.</p>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($models as $m): ?>
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong class="d-block"><?= Html::encode($m->title) ?></strong>
                                        <small class="text-muted"><?= strtoupper(Html::encode($m->file_type)) ?></small>
                                    </div>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-primary load-model-btn" 
                                                data-url="<?= Yii::getAlias('@web/') . Html::encode($m->file_path) ?>">
                                            Megtekintés
                                        </button>
                                        <?= Html::a('🗑️', ['model3d/delete', 'id' => $m->id], [
                                            'class' => 'btn btn-sm btn-outline-danger',
                                            'data-method' => 'post',
                                            'data-confirm' => 'Biztosan törölni szeretnéd ezt a modellt?',
                                            'title' => 'Törlés'
                                        ]) ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>

        <!-- JOBB OLDAL: 3D Interaktív Vászon & Vezérlők -->
        <div class="col-lg-8 col-md-7">
            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                    <span>🎨 3D Interaktív Nézet</span>
                    <small>Egérrel forgatható, görgővel nagyítható</small>
                </div>
                
                <!-- 3D Vezérlő Eszköztár -->
                <div class="bg-light p-2 border-bottom d-flex flex-wrap align-items-center justify-content-between gap-2">
                    <div class="btn-group btn-group-sm">
                        <button id="btn-auto-rotate" class="btn btn-outline-dark">🔄 Auto-Forgatás: KI</button>
                        <button id="btn-reset-view" class="btn btn-outline-secondary">🎯 Kamera Reset</button>
                    </div>

                    <div class="d-flex align-items-center">
                        <small class="font-weight-bold mr-2 me-2">Háttér:</small>
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-dark change-bg-btn" data-color="#1a1a1a">Sötét</button>
                            <button class="btn btn-secondary change-bg-btn" data-color="#4a4a4a">Szürke</button>
                            <button class="btn btn-light border change-bg-btn" data-color="#f8f9fa">Világos</button>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0 position-relative">
                    <div id="canvas-container" style="width: 100%; height: 550px; background-color: #1a1a1a;"></div>
                    <div id="loading-spinner" class="position-absolute top-50 start-50 translate-middle text-white text-center d-none" style="z-index: 10;">
                        <div class="spinner-border text-light mb-2" role="status"></div>
                        <div>3D Modell betöltése...</div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Three.js és GLTFLoader JS kötelező modulok -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/controls/OrbitControls.js"></script>
<script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/loaders/GLTFLoader.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const container = document.getElementById('canvas-container');
    const spinner = document.getElementById('loading-spinner');

    // 1. Scene, Camera, Renderer setup
    const scene = new THREE.Scene();
    scene.background = new THREE.Color(0x1a1a1a);

    const camera = new THREE.PerspectiveCamera(45, container.clientWidth / container.clientHeight, 0.1, 1000);
    camera.position.set(0, 2, 5);

    const renderer = new THREE.WebGLRenderer({ antialias: true });
    renderer.setSize(container.clientWidth, container.clientHeight);
    renderer.setPixelRatio(window.devicePixelRatio);
    renderer.shadowMap.enabled = true;
    container.appendChild(renderer.domElement);

    // 2. Controls
    const controls = new THREE.OrbitControls(camera, renderer.domElement);
    controls.enableDamping = true;
    controls.dampingFactor = 0.05;

    // 3. Lighting (3-Pontos megvilágítás)
    const ambientLight = new THREE.AmbientLight(0xffffff, 1.2);
    scene.add(ambientLight);

    const dirLight1 = new THREE.DirectionalLight(0xffffff, 1.5);
    dirLight1.position.set(5, 10, 7);
    scene.add(dirLight1);

    const dirLight2 = new THREE.DirectionalLight(0xffffff, 0.8);
    dirLight2.position.set(-5, -5, -5);
    scene.add(dirLight2);

    // 4. Loader & State
    const loader = new THREE.GLTFLoader();
    let currentModel = null;
    let isAutoRotating = false;

    function loadModel(url) {
        if (!url) return;
        spinner.classList.remove('d-none');

        if (currentModel) {
            scene.remove(currentModel);
        }

        loader.load(url, function (gltf) {
            currentModel = gltf.scene;

            // Modell középre igazítása és méretezése
            const box = new THREE.Box3().setFromObject(currentModel);
            const center = box.getCenter(new THREE.Vector3());
            const size = box.getSize(new THREE.Vector3());

            currentModel.position.x += (currentModel.position.x - center.x);
            currentModel.position.y += (currentModel.position.y - center.y);
            currentModel.position.z += (currentModel.position.z - center.z);

            const maxDim = Math.max(size.x, size.y, size.z);
            if (maxDim > 0) {
                const scale = 2.5 / maxDim;
                currentModel.scale.set(scale, scale, scale);
            }

            scene.add(currentModel);
            spinner.classList.add('d-none');
            resetCamera();
        }, undefined, function (error) {
            console.error(error);
            spinner.classList.add('d-none');
            alert('Hiba történt a 3D modell betöltésekor!');
        });
    }

    function resetCamera() {
        camera.position.set(0, 2, 5);
        controls.target.set(0, 0, 0);
        controls.update();
    }

    // 5. Animációs ciklus
    function animate() {
        requestAnimationFrame(animate);
        controls.autoRotate = isAutoRotating;
        controls.update();
        renderer.render(scene, camera);
    }
    animate();

    // 6. Eseménykezelők (Buttons & Interactivity)

    // Megtekintés gombok
    document.querySelectorAll('.load-model-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const url = this.getAttribute('data-url');
            loadModel(url);
        });
    });

    // Auto-forgatás gomb
    const autoRotateBtn = document.getElementById('btn-auto-rotate');
    autoRotateBtn.addEventListener('click', function () {
        isAutoRotating = !isAutoRotating;
        this.textContent = isAutoRotating ? '🔄 Auto-Forgatás: BE' : '🔄 Auto-Forgatás: KI';
        this.classList.toggle('btn-success', isAutoRotating);
        this.classList.toggle('btn-outline-dark', !isAutoRotating);
    });

    // Kamera Reset gomb
    document.getElementById('btn-reset-view').addEventListener('click', resetCamera);

    // Háttérszín váltó gombok
    document.querySelectorAll('.change-bg-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const colorHex = this.getAttribute('data-color');
            scene.background = new THREE.Color(colorHex);
        });
    });

    // Ablak átméretezés kezelése
    window.addEventListener('resize', function () {
        camera.aspect = container.clientWidth / container.clientHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(container.clientWidth, container.clientHeight);
    });

    // Automatikusan betöltjük az első modellt, ha van a listában
    const firstBtn = document.querySelector('.load-model-btn');
    if (firstBtn) {
        firstBtn.click();
    }
});
</script>