<?php
/** @var yii\web\View $this */

use yii\helpers\Url;

$this->title = 'Rólunk';

$this->registerCss("
/* ============================================================
   GLOBAL DARK OVERRIDES (NAVBAR & LAYOUT FIX)
   ============================================================ */
body, html, #main, .content-fluid, .wrap, main, .container, .container-fluid {
    background-color: #0b0d12 !important;
    color: #f8fafc !important;
}

/* Fehér felső sáv és Bootstrap elemek sötétítése */
header, .navbar, nav.navbar, .bg-light, .bg-white, .navbar-light, .navbar-expand-md {
    background-color: #141824 !important;
    border-bottom: 1px solid #1e293b !important;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3) !important;
}

.navbar-brand, .navbar-nav .nav-link, .nav-link, .logout {
    color: #94a3b8 !important;
    transition: color 0.2s ease !important;
}

.navbar-nav .nav-link:hover, 
.navbar-nav .nav-link.active,
.navbar-brand:hover {
    color: #38bdf8 !important;
}

/* ============================================================
   RÓLUNK PAGE STYLES
   ============================================================ */
.rolunk-wrapper {
    max-width: 1100px;
    margin: 0 auto;
    padding: 2.5rem 1.5rem 3rem;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    color: #f8fafc;
}

/* HERO SECTION */
.hero-section {
    position: relative;
    background: linear-gradient(135deg, #141824 0%, #1e293b 60%, #0f172a 100%);
    border: 1px solid #1e293b;
    border-radius: 22px;
    padding: 4rem 3rem 3.5rem;
    margin-bottom: 2rem;
    overflow: hidden;
    text-align: center;
}

.hero-section::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image:
        linear-gradient(rgba(56,189,248,0.04) 1px, transparent 1px),
        linear-gradient(90deg, rgba(56,189,248,0.04) 1px, transparent 1px);
    background-size: 48px 48px;
    border-radius: 22px;
}

.hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(56,189,248,0.1);
    border: 1px solid rgba(56,189,248,0.25);
    border-radius: 50px;
    padding: 0.35rem 1rem;
    font-size: 0.78rem;
    font-weight: 600;
    color: #38bdf8;
    letter-spacing: 0.8px;
    text-transform: uppercase;
    margin-bottom: 1.5rem;
    position: relative;
    z-index: 1;
}

.hero-badge-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #38bdf8;
    animation: pulse-dot 2s ease-in-out infinite;
}

@keyframes pulse-dot {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.4; transform: scale(0.7); }
}

.hero-title {
    position: relative;
    z-index: 1;
    font-size: clamp(2rem, 5vw, 3.2rem);
    font-weight: 800;
    color: #f8fafc;
    line-height: 1.15;
    margin-bottom: 1rem;
    letter-spacing: -1px;
}

.hero-title span {
    background: linear-gradient(135deg, #38bdf8, #818cf8);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.hero-subtitle {
    position: relative;
    z-index: 1;
    font-size: 1.05rem;
    color: #94a3b8;
    max-width: 580px;
    margin: 0 auto;
    line-height: 1.7;
    min-height: 2rem;
}

.typing-cursor {
    display: inline-block;
    width: 2px;
    height: 1.1em;
    background: #38bdf8;
    margin-left: 2px;
    vertical-align: text-bottom;
    animation: blink 1s step-end infinite;
}

@keyframes blink {
    0%, 100% { opacity: 1; }
    50% { opacity: 0; }
}

/* STATS BAR */
.stats-bar {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.2rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: #141824;
    border: 1px solid #1e293b;
    border-radius: 16px;
    padding: 1.6rem 1.4rem;
    text-align: center;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    transition: transform 0.25s ease, border-color 0.25s ease;
}

.stat-card:hover {
    transform: translateY(-4px);
    border-color: #38bdf8;
}

.stat-number {
    font-size: 1.3rem;
    font-weight: 700;
    color: #f8fafc;
    margin-bottom: 0.35rem;
}

.stat-label {
    font-size: 0.82rem;
    color: #94a3b8;
    font-weight: 500;
}

/* SERVICE CARDS */
.section-label {
    font-size: 0.78rem;
    font-weight: 700;
    letter-spacing: 1.2px;
    text-transform: uppercase;
    color: #38bdf8;
    margin-bottom: 0.5rem;
}

.section-title {
    font-size: 1.6rem;
    font-weight: 800;
    color: #f8fafc;
    margin-bottom: 1.4rem;
    letter-spacing: -0.4px;
}

.services-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.4rem;
    margin-bottom: 2rem;
}

.service-card {
    background: #141824;
    border: 1px solid #1e293b;
    border-radius: 18px;
    padding: 2rem 2rem 2.2rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    transition: transform 0.28s ease, box-shadow 0.28s ease, border-color 0.28s ease;
    position: relative;
    overflow: hidden;
}

.service-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: linear-gradient(90deg, #38bdf8, #818cf8);
    opacity: 0;
    transition: opacity 0.28s ease;
    border-radius: 18px 18px 0 0;
}

.service-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 20px 40px rgba(56, 189, 248, 0.1);
    border-color: #38bdf8;
}

.service-card:hover::before {
    opacity: 1;
}

.service-icon-wrap {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    background: #1e293b;
    border: 1px solid #334155;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1.3rem;
    transition: transform 0.28s ease;
}

.service-card:hover .service-icon-wrap {
    transform: scale(1.08) rotate(-4deg);
    background: #38bdf8;
    color: #0b0d12;
}

.service-icon-wrap i {
    font-size: 1.4rem;
    background: linear-gradient(135deg, #38bdf8, #818cf8);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.service-card h3 {
    font-size: 1.25rem;
    font-weight: 800;
    color: #f8fafc;
    margin-bottom: 0.9rem;
    letter-spacing: -0.3px;
}

.service-card p {
    font-size: 0.9rem;
    color: #94a3b8;
    line-height: 1.75;
    margin: 0;
}

/* TECH STACK */
.tech-stack-section {
    background: #141824;
    border: 1px solid #1e293b;
    border-radius: 16px;
    padding: 1.6rem 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}

.tech-stack-label {
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: #94a3b8;
    margin-bottom: 1rem;
}

.tech-pills {
    display: flex;
    flex-wrap: wrap;
    gap: 0.6rem;
}

.tech-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    background: #1e293b;
    border: 1px solid #334155;
    border-radius: 50px;
    padding: 0.38rem 0.9rem;
    font-size: 0.8rem;
    font-weight: 600;
    color: #cbd5e1;
    transition: background 0.2s, border-color 0.2s, color 0.2s;
}

.tech-pill:hover {
    background: rgba(56,189,248,0.1);
    border-color: #38bdf8;
    color: #38bdf8;
}

.tech-pill i {
    font-size: 0.75rem;
    color: #38bdf8;
}

/* MISSION STATEMENT */
.mission-section {
    background: #141824;
    border: 1px solid #1e293b;
    border-radius: 18px;
    padding: 2.2rem 2.5rem;
    margin-bottom: 2rem;
    position: relative;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}

.mission-section::before {
    content: '\201C';
    position: absolute;
    top: -0.5rem;
    left: 1.5rem;
    font-size: 8rem;
    line-height: 1;
    color: rgba(56,189,248,0.04);
    font-family: Georgia, serif;
    pointer-events: none;
    user-select: none;
}

.mission-text {
    font-size: 1.15rem;
    font-weight: 600;
    color: #f8fafc;
    line-height: 1.7;
    position: relative;
    z-index: 1;
    max-width: 780px;
}

.mission-text em {
    font-style: normal;
    background: linear-gradient(135deg, #38bdf8, #818cf8);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* CTA STRIP */
.cta-strip {
    margin: 0;
    padding: 2.2rem 2.8rem;
    background: #141824;
    border: 1px solid #1e293b;
    border-radius: 18px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1.5rem;
    position: relative;
    overflow: hidden;
}

.cta-strip::before {
    content: '';
    position: absolute;
    left: 0;
    top: 15%;
    height: 70%;
    width: 4px;
    border-radius: 0 4px 4px 0;
    background: linear-gradient(180deg, #38bdf8, #818cf8);
}

.cta-text h3 {
    font-size: 1.25rem;
    font-weight: 700;
    color: #f8fafc;
    margin: 0 0 0.3rem 0;
    letter-spacing: -0.3px;
}

.cta-text p {
    font-size: 0.9rem;
    color: #94a3b8;
    margin: 0;
}

.cta-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.9rem;
    background: linear-gradient(135deg, #0071e3 0%, #38bdf8 100%);
    color: #fff !important;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.9rem;
    text-decoration: none !important;
    white-space: nowrap;
    border: none;
    box-shadow: 0 4px 16px rgba(56,189,248,0.25);
    transition: transform 0.22s ease, box-shadow 0.22s ease;
}

.cta-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 28px rgba(56,189,248,0.4);
    color: #fff !important;
}

.cta-btn i {
    font-size: 0.85rem;
    transition: transform 0.22s ease;
}

.cta-btn:hover i {
    transform: translateX(3px);
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .rolunk-wrapper { padding: 1.5rem 1rem 2rem; }
    .hero-section { padding: 2.8rem 1.5rem 2.5rem; }
    .stats-bar { grid-template-columns: 1fr; }
    .services-grid { grid-template-columns: 1fr; }
    .cta-strip { flex-direction: column; align-items: flex-start; padding: 1.8rem 1.8rem 1.8rem 2rem; }
    .cta-btn { width: 100%; justify-content: center; }
    .mission-section { padding: 1.8rem 1.5rem; }
}
");
?>

<div class="rolunk-wrapper">

    <!-- HERO -->
    <div class="hero-section">
        <div class="hero-badge">
            <span class="hero-badge-dot"></span>
            Aktív fejlesztés alatt
        </div>

        <h1 class="hero-title">
            AI & 3D — ahol a <span>technológia</span><br>találkozik a precizitással
        </h1>

        <p class="hero-subtitle" id="typingTarget"></p>
    </div>

    <!-- STATS BAR -->
    <div class="stats-bar" id="statsBar">
        <div class="stat-card">
            <i class="fas fa-user-astronaut" style="font-size: 2rem; color: #38bdf8; margin-bottom: 10px;"></i>
            <div class="stat-number">Emberközpontú</div>
            <div class="stat-label">Élményalapú fejlesztés</div>
        </div>
        
        <div class="stat-card">
            <i class="fas fa-brain" style="font-size: 2rem; color: #38bdf8; margin-bottom: 10px;"></i>
            <div class="stat-number">AI-Vezérelt</div>
            <div class="stat-label">Intelligens folyamatok</div>
        </div>
        
        <div class="stat-card">
            <i class="fas fa-rocket" style="font-size: 2rem; color: #38bdf8; margin-bottom: 10px;"></i>
            <div class="stat-number">Skálázható</div>
            <div class="stat-label">Jövőálló digitális alapok</div>
        </div>
    </div>

    <!-- SERVICE CARDS -->
    <div class="section-label">Szakterületek</div>
    <h2 class="section-title">Amiben igazán jók vagyunk</h2>

    <div class="services-grid" id="servicesGrid">
        <div class="service-card">
            <div class="service-icon-wrap">
                <i class="fas fa-microchip"></i>
            </div>
            <h3>AI Workflow Optimalizáció</h3>
            <p>
                A legtöbb vállalkozás ugyanolyan feladatokat végez el naponta százszor kézzel. Mi ezt megszüntetjük.
                Python-alapú automatizációkat és egyedi AI ágenseket építünk, amelyek átveszik az ismétlődő folyamatokat,
                hibamentesen, 0–24-ben. Az eredmény nem csupán időmegtakarítás: egy olyan rendszer, amely akkor is
                dolgozik helyetted, amikor te nem. Ez nem eszköz. Ez digitális infrastruktúra.
            </p>
        </div>

        <div class="service-card">
            <div class="service-icon-wrap">
                <i class="fas fa-cubes"></i>
            </div>
            <h3>3D Tervezés &amp; Vizualizáció</h3>
            <p>
                Egy termék addig nem létezik a vásárló szemében, amíg nem látja pontosan, élethűen, meggyőzően.
                Komplex objektumokat és környezeteket modellezünk olyan precizitással, ahol a technikai pontosság és
                a vizuális hatás nem kompromisszum, hanem alapkövetelmény. Legyen szó termékbemutatóról,
                architekturális vizualizációról vagy digitális prototípusról — amit tervezünk, az eladja magát.
            </p>
        </div>
    </div>

    <!-- TECH STACK -->
    <div class="tech-stack-section" id="techStack">
        <div class="tech-stack-label">Technológiai stack</div>
        <div class="tech-pills">
            <span class="tech-pill"><i class="fab fa-python"></i> Python</span>
            <span class="tech-pill"><i class="fab fa-react"></i> React / Next.js</span>
            <span class="tech-pill"><i class="fas fa-wind"></i> Tailwind CSS</span>
            <span class="tech-pill"><i class="fas fa-robot"></i> AI Ágensek</span>
            <span class="tech-pill"><i class="fas fa-shield-alt"></i> Kiberbiztonság</span>
            <span class="tech-pill"><i class="fas fa-cube"></i> 3D Modellezés</span>
            <span class="tech-pill"><i class="fas fa-terminal"></i> Linux / Bash</span>
            <span class="tech-pill"><i class="fas fa-database"></i> Adatelemzés</span>
        </div>
    </div>

    <!-- MISSION STATEMENT -->
    <div class="mission-section" id="missionSection">
        <p class="mission-text">
            Nem azt hiszem, hogy az AI <em>helyettesíti</em> az embert,
            azt hiszem, hogy <em>megsokszorozza</em> azt, aki érti és használja.
            A célom egy olyan rendszer felépítése, ahol az automatizáció, a vizuális pontosság
            és az emberi kreativitás egymást erősítik. Ez nem jövőkép. Ez <em>napi munka</em>.
        </p>
    </div>

    <!-- CTA STRIP -->
    <div class="cta-strip" id="ctaStrip">
        <div class="cta-text">
            <h3>Készen állsz az automatizálásra?</h3>
            <p>Beszéljük meg, hogyan növelhetjük a hatékonyságodat.</p>
        </div>
        <a href="<?= Url::to(['/site/index']) ?>" class="cta-btn">
            Főoldal <i class="fas fa-arrow-right"></i>
        </a>
    </div>

</div>

<?php
$this->registerJs("
(function() {
    var phrases = [
        'Python automatizáció. AI ágensek. 3D vizualizáció.',
        'Rendszerszintű megoldások, amelyek valóban működnek.',
        'Ahol a kód találkozik a kreativitással.'
    ];
    var target = document.getElementById('typingTarget');
    var phraseIdx = 0, charIdx = 0, deleting = false;
    var cursor = document.createElement('span');
    cursor.className = 'typing-cursor';

    function type() {
        var current = phrases[phraseIdx];
        if (!deleting) {
            charIdx++;
            target.textContent = current.slice(0, charIdx);
            if (charIdx === current.length) {
                deleting = true;
                setTimeout(type, 2200);
                return;
            }
        } else {
            charIdx--;
            target.textContent = current.slice(0, charIdx);
            if (charIdx === 0) {
                deleting = false;
                phraseIdx = (phraseIdx + 1) % phrases.length;
            }
        }
        target.appendChild(cursor);
        setTimeout(type, deleting ? 38 : 72);
    }
    type();
})();
");
?>