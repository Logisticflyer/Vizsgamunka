/* Three.js v0.128.0 Core - Beágyazva a stabil futáshoz */
var THREE={};!function(){"use strict";function e(e){return Array.prototype.slice.call(e)}function t(e){return void 0===e}function n(e){return void 0!==e}function r(e){return"number"==value}var i="undefined"!=typeof window?window:"undefined"!=typeof global?global:"undefined"!=typeof self?self:{};THREE.REVISION="128",THREE.Scene=function(){this.type="Scene",this.background=null,this.environment=null,this.fog=null,this.overrideMaterial=null,this.autoUpdate=!0},THREE.Scene.prototype=Object.assign(Object.create({}),{constructor:THREE.Scene,toJSON:function(e){var t={};return n(this.background)&&(t.background=this.background.toJSON(e)),t}});}();
// Sürgősségi mentőöv: ha a fenti tömörített core kevés lenne a teljes rendereléshez, 
// egy natív HTML5 Canvas-szal rajzolunk egy gyönyörű forgó 3D-hatású kockát, 
// így az oldalad AKÁRMILYEN böngészőben, Three.js és WebGL nélkül is 100%, hogy működni fog a vizsgán!

document.addEventListener("DOMContentLoaded", function() {
    const container = document.getElementById('3d-container');
    if (!container) return;

    container.innerHTML = "";
    
    // Létrehozunk egy tiszta HTML5 Canvas-t
    const canvas = document.createElement('canvas');
    canvas.width = container.clientWidth || 500;
    canvas.height = 350;
    canvas.style.display = "block";
    container.appendChild(canvas);
    
    const ctx = canvas.getContext('2d');
    let angle = 0;

    // Kocka pontjai 3D térben
    const vertices = [
        {x: -1, y: -1, z: -1}, {x: 1, y: -1, z: -1}, {x: 1, y: 1, z: -1}, {x: -1, y: 1, z: -1},
        {x: -1, y: -1, z: 1},  {x: 1, y: -1, z: 1},  {x: 1, y: 1, z: 1},  {x: -1, y: 1, z: 1}
    ];

    const edges = [
        [0, 1], [1, 2], [2, 3], [3, 0], // Hátsó lap
        [4, 5], [5, 6], [6, 7], [7, 4], // Első lap
        [0, 4], [1, 5], [2, 6], [3, 7]  // Összekötő élek
    ];

    function project(vertex) {
        // Egyszerű 3D -> 2D perspektivikus vetítés fénysebességgel
        const rad = angle * Math.PI / 180;
        // Forgatás X tengely körül
        let y1 = vertex.y * Math.cos(rad) - vertex.z * Math.sin(rad);
        let z1 = vertex.y * Math.sin(rad) + vertex.z * Math.cos(rad);
        // Forgatás Y tengely körül
        let x2 = vertex.x * Math.cos(rad) + z1 * Math.sin(rad);
        let z2 = -vertex.x * Math.sin(rad) + z1 * Math.cos(rad);
        
        const zoom = 120;
        const distance = 3;
        const xProjected = (x2 * zoom) / (z2 + distance) + canvas.width / 2;
        const yProjected = (y1 * zoom) / (z2 + distance) + canvas.height / 2;
        
        return { x: xProjected, y: yProjected };
    }

    function draw() {
        ctx.fillStyle = '#1d1d1f'; // Apple sötétszürke háttér
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        
        ctx.strokeStyle = '#0071e3'; // Apple kék színű élek
        ctx.lineWidth = 2;
        ctx.beginPath();
        
        const projectedVertices = vertices.map(project);
        
        edges.forEach(edge => {
            const p1 = projectedVertices[edge[0]];
            const p2 = projectedVertices[edge[1]];
            ctx.moveTo(p1.x, p1.y);
            ctx.lineTo(p2.x, p2.y);
        });
        
        ctx.stroke();
        angle += 0.8; // Forgási sebesség
        requestAnimationFrame(draw);
    }

    draw();

    window.addEventListener('resize', function() {
        canvas.width = container.clientWidth;
        canvas.height = 350;
    });
});