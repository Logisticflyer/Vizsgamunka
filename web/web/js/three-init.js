// Megvárjuk, amíg a HTML oldal teljesen betöltődik
document.addEventListener("DOMContentLoaded", function() {
    
    // Megkeressük a dobozt, ahová a 3D-t szánjuk
    const container = document.getElementById('3d-container');
    
    // Ha nincs ilyen doboz ezen az oldalon, akkor nem csinálunk semmit!
    if (!container) return;

    // 1. Színpad
    const scene = new THREE.Scene();

    // 2. Kamera (a doboz méretéhez igazítva)
    const width = container.clientWidth || 500;
    const height = container.clientHeight || 400;
    const camera = new THREE.PerspectiveCamera(75, width / height, 0.1, 1000);

    // 3. Megjelenítő
    const renderer = new THREE.WebGLRenderer({ antialias: true });
    renderer.setSize(width, height);
    
    // FONTOS: Most NEM a body-hoz adjuk, hanem a kijelölt dobozhoz!
    container.appendChild(renderer.domElement);

    // 4. A kocka
    const geometry = new THREE.BoxGeometry();
    const material = new THREE.MeshBasicMaterial({ color: 0x00ff00 });
    const cube = new THREE.Mesh(geometry, material);
    scene.add(cube);

    camera.position.z = 5;

    // 5. Animáció
    function animate() {
        requestAnimationFrame(animate);
        cube.rotation.x += 0.01;
        cube.rotation.y += 0.01;
        renderer.render(scene, camera);
    }
    animate();
});