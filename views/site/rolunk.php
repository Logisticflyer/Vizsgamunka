<?php
/** @var yii\web\View $this */

use yii\helpers\Url;

$this->title = 'Rólunk';

$this->registerCss("
/* ============================================================
   GLOBAL PAGE RESET & BASE
   ============================================================ */
.content-wrapper {
    background: #f4f6fb !important;
    min-height: 100vh;
}

.rolunk-wrapper {
    max-width: 1100px;
    margin: 0 auto;
    padding: 2.5rem 1.5rem 3rem;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
}

/* ============================================================
   HERO SECTION
   ============================================================ */
.hero-section {
    position: relative;
    background: linear-gradient(135deg, #1a1f36 0%, #2d3561 60%, #3d2b6b 100%);
    border-radius: 22px;
    padding: 4rem 3rem 3.5rem;
    margin-bottom: 2rem;
    overflow: hidden;
    text-align: center;
}

/* Geometric grid overlay */
.hero-section::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image:
        linear-gradient(rgba(99,120,255,0.12) 1px, transparent 1px),
        linear-gradient(90deg, rgba(99,120,255,0.12) 1px, transparent 1px);
    background-size: 48px 48px;
    border-radius: 22px;
}

/* Glow orbs */
.hero-section::after {
    content: '';
    position: absolute;
    top: -80px;
    right: -80px;
    width: 320px;
    height: 320px;
    background: radial-gradient(circle, rgba(167,139,250,0.18) 0%, transparent 70%);
    pointer-events: none;
}

.hero-orb-left {
    position: absolute;
    bottom: -60px;
    left: -60px;
    width: 260px;
    height: 260px;
    background: radial-gradient(circle, rgba(99,120,255,0.15) 0%, transparent 70%);
    pointer-events: none;
}

.hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(99,120,255,0.18);
    border: 1px solid rgba(99,120,255,0.35);
    border-radius: 50px;
    padding: 0.35rem 1rem;
    font-size: 0.78rem;
    font-weight: 600;
    color: #a78bfa;
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
    background: #a78bfa;
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
    color: #ffffff;
    line-height: 1.15;
    margin-bottom: 1rem;
    letter-spacing: -1px;
}

.hero-title span {
    background: linear-gradient(135deg, #6378ff, #a78bfa);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.hero-subtitle {
    position: relative;
    z-index: 1;
    font-size: 1.05rem;
    color: rgba(255,255,255,0.65);
    max-width: 580px;
    margin: 0 auto 0;
    line-height: 1.7;
    min-height: 2rem;
}

.typing-cursor {
    display: inline-block;
    width: 2px;
    height: 1.1em;
    background: #a78bfa;
    margin-left: 2px;
    vertical-align: text-bottom;
    animation: blink 1s step-end infinite;
}

@keyframes blink {
    0%, 100% { opacity: 1; }
    50% { opacity: 0; }
}

/* ============================================================
   STATS BAR
   ============================================================ */
.stats-bar {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.2rem;
    margin-bottom: 2rem;
    opacity: 0;
    transform: translateY(20px);
    transition: opacity 0.6s ease, transform 0.6s ease;
}

.stats-bar.visible {
    opacity: 1;
    transform: translateY(0);
}

.stat-card {
    background: rgba(255,255,255,0.72);
    border: 1px solid rgba(99,120,255,0.14);
    border-radius: 16px;
    padding: 1.6rem 1.4rem;
    text-align: center;
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    box-shadow: 0 4px 24px rgba(99,120,255,0.07),
                inset 0 0 0 1px rgba(255,255,255,0.7);
    transition: transform 0.25s ease, box-shadow 0.25s ease;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 32px rgba(99,120,255,0.14),
                inset 0 0 0 1px rgba(255,255,255,0.8);
}

.stat-number {
    font-size: 2.4rem;
    font-weight: 800;
    background: linear-gradient(135deg, #6378ff, #a78bfa);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    line-height: 1.1;
    margin-bottom: 0.35rem;
}

.stat-label {
    font-size: 0.82rem;
    color: #6b7280;
    font-weight: 500;
    letter-spacing: 0.3px;
}

/* ============================================================
   SERVICE CARDS
   ============================================================ */
.section-label {
    font-size: 0.78rem;
    font-weight: 700;
    letter-spacing: 1.2px;
    text-transform: uppercase;
    color: #6378ff;
    margin-bottom: 0.5rem;
}

.section-title {
    font-size: 1.6rem;
    font-weight: 800;
    color: #1a1f36;
    margin-bottom: 1.4rem;
    letter-spacing: -0.4px;
}

.services-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.4rem;
    margin-bottom: 2rem;
    opacity: 0;
    transform: translateY(24px);
    transition: opacity 0.6s ease 0.1s, transform 0.6s ease 0.1s;
}

.services-grid.visible {
    opacity: 1;
    transform: translateY(0);
}

.service-card {
    background: rgba(255,255,255,0.72);
    border: 1px solid rgba(99,120,255,0.12);
    border-radius: 18px;
    padding: 2rem 2rem 2.2rem;
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    box-shadow: 0 4px 24px rgba(99,120,255,0.07),
                inset 0 0 0 1px rgba(255,255,255,0.65);
    transition: transform 0.28s ease, box-shadow 0.28s ease, border-color 0.28s ease;
    position: relative;
    overflow: hidden;
}

.service-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: linear-gradient(90deg, #6378ff, #a78bfa);
    opacity: 0;
    transition: opacity 0.28s ease;
    border-radius: 18px 18px 0 0;
}

.service-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 16px 40px rgba(99,120,255,0.15),
                inset 0 0 0 1px rgba(255,255,255,0.8);
    border-color: rgba(99,120,255,0.25);
}

.service-card:hover::before {
    opacity: 1;
}

.service-icon-wrap {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    background: linear-gradient(135deg, rgba(99,120,255,0.12), rgba(167,139,250,0.12));
    border: 1px solid rgba(99,120,255,0.18);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1.3rem;
    transition: transform 0.28s ease;
}

.service-card:hover .service-icon-wrap {
    transform: scale(1.08) rotate(-4deg);
}

.service-icon-wrap i {
    font-size: 1.4rem;
    background: linear-gradient(135deg, #6378ff, #a78bfa);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.service-card h3 {
    font-size: 1.25rem;
    font-weight: 800;
    color: #1a1f36;
    margin-bottom: 0.9rem;
    letter-spacing: -0.3px;
}

.service-card p {
    font-size: 0.9rem;
    color: #6b7280;
    line-height: 1.75;
    margin: 0;
}

/* ============================================================
   TECH STACK ROW
   ============================================================ */
.tech-stack-section {
    background: rgba(255,255,255,0.55);
    border: 1px solid rgba(99,120,255,0.1);
    border-radius: 16px;
    padding: 1.6rem 2rem;
    margin-bottom: 2rem;
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    opacity: 0;
    transform: translateY(20px);
    transition: opacity 0.6s ease 0.2s, transform 0.6s ease 0.2s;
}

.tech-stack-section.visible {
    opacity: 1;
    transform: translateY(0);
}

.tech-stack-label {
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: #6b7280;
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
    background: rgba(99,120,255,0.07);
    border: 1px solid rgba(99,120,255,0.15);
    border-radius: 50px;
    padding: 0.38rem 0.9rem;
    font-size: 0.8rem;
    font-weight: 600;
    color: #4b5563;
    transition: background 0.2s, border-color 0.2s, color 0.2s;
}

.tech-pill:hover {
    background: rgba(99,120,255,0.14);
    border-color: rgba(99,120,255,0.3);
    color: #6378ff;
}

.tech-pill i {
    font-size: 0.75rem;
    color: #6378ff;
}

/* ============================================================
   MISSION STATEMENT
   ============================================================ */
.mission-section {
    background: linear-gradient(135deg, rgba(99,120,255,0.06), rgba(167,139,250,0.06));
    border: 1px solid rgba(99,120,255,0.14);
    border-radius: 18px;
    padding: 2.2rem 2.5rem;
    margin-bottom: 2rem;
    position: relative;
    overflow: hidden;
    opacity: 0;
    transform: translateY(20px);
    transition: opacity 0.6s ease 0.3s, transform 0.6s ease 0.3s;
}

.mission-section.visible {
    opacity: 1;
    transform: translateY(0);
}

.mission-section::before {
    content: '\201C';
    position: absolute;
    top: -0.5rem;
    left: 1.5rem;
    font-size: 8rem;
    line-height: 1;
    color: rgba(99,120,255,0.08);
    font-family: Georgia, serif;
    pointer-events: none;
    user-select: none;
}

.mission-text {
    font-size: 1.15rem;
    font-weight: 600;
    color: #1a1f36;
    line-height: 1.7;
    position: relative;
    z-index: 1;
    max-width: 780px;
}

.mission-text em {
    font-style: normal;
    background: linear-gradient(135deg, #6378ff, #a78bfa);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.mission-author {
    margin-top: 1rem;
    font-size: 0.82rem;
    color: #9ca3af;
    font-weight: 500;
}

/* ============================================================
   CTA STRIP
   ============================================================ */
.cta-strip {
    margin: 0;
    padding: 2.2rem 2.8rem;
    background: rgba(255,255,255,0.65);
    border: 1px solid rgba(99,120,255,0.18);
    border-radius: 18px;
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    box-shadow: 0 4px 24px rgba(99,120,255,0.08),
                inset 0 0 0 1px rgba(255,255,255,0.6);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1.5rem;
    position: relative;
    overflow: hidden;
    opacity: 0;
    transform: translateY(20px);
    transition: opacity 0.6s ease 0.4s, transform 0.6s ease 0.4s;
}

.cta-strip.visible {
    opacity: 1;
    transform: translateY(0);
}

.cta-strip::before {
    content: '';
    position: absolute;
    left: 0;
    top: 15%;
    height: 70%;
    width: 4px;
    border-radius: 0 4px 4px 0;
    background: linear-gradient(180deg, #6378ff, #a78bfa);
}

.cta-text h3 {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1a1f36;
    margin: 0 0 0.3rem 0;
    letter-spacing: -0.3px;
}

.cta-text p {
    font-size: 0.9rem;
    color: #6b7280;
    margin: 0;
}

.cta-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.9rem;
    background: linear-gradient(135deg, #6378ff 0%, #a78bfa 100%);
    color: #fff !important;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.9rem;
    text-decoration: none !important;
    white-space: nowrap;
    border: none;
    box-shadow: 0 4px 16px rgba(99,120,255,0.35);
    transition: transform 0.22s ease, box-shadow 0.22s ease;
}

.cta-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 28px rgba(99,120,255,0.48);
    color: #fff !important;
}

.cta-btn i {
    font-size: 0.85rem;
    transition: transform 0.22s ease;
}

.cta-btn:hover i {
    transform: translateX(3px);
}

/* ============================================================
   RESPONSIVE
   ============================================================ */
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

    <!-- ============================================================
         HERO
         ============================================================ -->
    <div class="hero-section">
        <div class="hero-orb-left"></div>

        <div class="hero-badge">
            <span class="hero-badge-dot"></span>
            Aktív fejlesztés alatt
        </div>

        <h1 class="hero-title">
            AI & 3D — ahol a <span>technológia</span><br>találkozik a precizitással
        </h1>

        <p class="hero-subtitle" id="typingTarget"></p>
    </div>

    <!-- ============================================================
         STATS BAR
         ============================================================ -->
<div class="stats-bar" id="statsBar">
    <div class="stat-card">
        <i class="fas fa-user-astronaut" style="font-size: 2rem; background: linear-gradient(135deg, #6378ff, #a78bfa); -webkit-background-clip: text; -webkit-text-fill-color: transparent; margin-bottom: 10px;"></i>
        <div class="stat-number" style="font-size: 1.5rem;">Emberközpontú</div>
        <div class="stat-label">Élményalapú fejlesztés</div>
    </div>
    
    <div class="stat-card">
        <i class="fas fa-brain" style="font-size: 2rem; background: linear-gradient(135deg, #6378ff, #a78bfa); -webkit-background-clip: text; -webkit-text-fill-color: transparent; margin-bottom: 10px;"></i>
        <div class="stat-number" style="font-size: 1.5rem;">AI-Vezérelt</div>
        <div class="stat-label">Intelligens folyamatok</div>
    </div>
    
    <div class="stat-card">
        <i class="fas fa-rocket" style="font-size: 2rem; background: linear-gradient(135deg, #6378ff, #a78bfa); -webkit-background-clip: text; -webkit-text-fill-color: transparent; margin-bottom: 10px;"></i>
        <div class="stat-number" style="font-size: 1.5rem;">Skálázható</div>
        <div class="stat-label">Jövőálló digitális alapok</div>
    </div>
</div>
    <!-- ============================================================
         SERVICE CARDS
         ============================================================ -->
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

    <!-- ============================================================
         TECH STACK
         ============================================================ -->
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

    <!-- ============================================================
         MISSION STATEMENT
         ============================================================ -->
    <div class="mission-section" id="missionSection">
        <p class="mission-text">
            Nem azt hiszem, hogy az AI <em>helyettesíti</em> az embert
            azt hiszem, hogy <em>megsokszorozza</em> azt, aki érti és használja.
            A célom egy olyan rendszer felépítése, ahol az automatizáció, a vizuális pontosság
            és az emberi kreativitás egymást erősítik. Ez nem jövőkép. Ez <em>napi munka</em>.
        </p>
    </div>

    <!-- ============================================================
         CTA STRIP
         ============================================================ -->
    <div class="cta-strip" id="ctaStrip">
        <div class="cta-text">
            <h3>Készen állsz az automatizálásra?</h3>
            <p>Beszéljük meg, hogyan növelhetjük a hatékonyságodat.</p>
        </div>
        <a href="<?= Url::to(['/site/choose']) ?>" class="cta-btn">
        Kapcsolat felvétele <i class="fas fa-arrow-right"></i>
        </a>
    </div>

</div>

<?php
$this->registerJs("
(function() {

    /* ------------------------------------------------------------------
       1. TYPING EFFECT
    ------------------------------------------------------------------ */
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

    /* ------------------------------------------------------------------
       2. INTERSECTION OBSERVER — reveal + counter
    ------------------------------------------------------------------ */
    var revealTargets = [
        { el: document.getElementById('statsBar'),     counter: true  },
        { el: document.getElementById('servicesGrid'), counter: false },
        { el: document.getElementById('techStack'),    counter: false },
        { el: document.getElementById('missionSection'), counter: false },
        { el: document.getElementById('ctaStrip'),     counter: false }
    ];

    function animateCounter(el) {
        var target   = parseInt(el.dataset.target, 10);
        var suffix   = el.dataset.suffix || '';
        var duration = 1400;
        var start    = performance.now();

        function step(now) {
            var progress = Math.min((now - start) / duration, 1);
            var ease     = 1 - Math.pow(1 - progress, 3);
            el.textContent = Math.floor(ease * target) + suffix;
            if (progress < 1) requestAnimationFrame(step);
        }
        requestAnimationFrame(step);
    }

    var observer = new IntersectionObserver(function(entries) {
    entries.forEach(function(entry) {
        if (!entry.isIntersecting) return;
        
        entry.target.classList.add('visible'); // Ez maradjon, mert ez úsztatja be a kártyákat!
        
        observer.unobserve(entry.target);
    });
}, { threshold: 0.15 });

    revealTargets.forEach(function(item) {
        if (item.el) observer.observe(item.el);
    });

})();
");
?>