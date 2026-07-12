document.addEventListener("DOMContentLoaded", function() {
    const container = document.getElementById('3d-container');
    if (!container) return;

    // Tesztképpen beleírunk egy piros szöveget közvetlenül a dobozba
    container.innerHTML = '<h1 style="color: #ff3b30; text-align: center; padding-top: 130px; font-family: sans-serif;">A JAVASCRIPT KÓDOD MŰKÖDIK!</h1>';
});