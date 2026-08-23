<?php

use yii\helpers\Html;

$this->title = 'Vezérlőpult';
?>

<div class="choose-container">
    <div class="choose-header">
        <h2 class="mb-2">Üdvözöllek, <?= Html::encode(Yii::$app->user->identity->username) ?>!</h2>
        <p>
            Válaszd ki, milyen feladaton szeretnél dolgozni. 
            (Tipp: Használhatod az 1-<?= count($cards) ?> gyorsbillentyűket is!)
        </p>
    </div>

    <div class="row justify-content-center g-4">
        <?php foreach ($cards as $card): ?>
            <?= $this->render('_card', ['card' => $card]) ?>
        <?php endforeach; ?>
    </div>
</div>

<style>
.choose-container {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    background-color: #0b0d12;
    min-height: calc(100vh - 60px);
    padding: 60px 20px;
    color: #f8fafc;
}
.choose-header { text-align: center; margin-bottom: 40px; }
.choose-header h2 { font-size: 34px; font-weight: 700; color: #f8fafc; margin-bottom: 10px; }
.choose-header p { font-size: 16px; color: #94a3b8; max-width: 600px; margin: 0 auto; }

.service-card-link { text-decoration: none !important; display: block; }

.service-card {
    background: #141824; 
    border-radius: 20px; 
    padding: 35px 20px; 
    text-align: center;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2); 
    border: 1px solid #1e293b;
    transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
    display: flex; 
    flex-direction: column; 
    align-items: center; 
    height: 100%;
    position: relative;
    overflow: hidden;
}

/* Badge és Gyorsbillentyű tároló a kártya sarkában */
.card-badges-wrapper {
    position: absolute;
    top: 15px;
    right: 15px;
    display: flex;
    gap: 6px;
    align-items: center;
}

.shortcut-badge {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: #94a3b8;
    font-size: 10px;
    font-family: monospace;
    font-weight: 600;
    padding: 3px 6px;
    border-radius: 6px;
}

.card-badge {
    background: rgba(56, 189, 248, 0.1);
    color: #38bdf8;
    font-size: 11px;
    font-weight: 600;
    padding: 4px 10px;
    border-radius: 20px;
    letter-spacing: 0.5px;
}

.service-card-link:hover .service-card { 
    transform: translateY(-8px); 
    box-shadow: 0 20px 40px rgba(56, 189, 248, 0.1); 
    border-color: #38bdf8; 
}

.service-icon-wrap { 
    font-size: 28px; 
    color: #38bdf8; 
    background: #1e293b; 
    width: 70px; 
    height: 70px; 
    display: flex; 
    align-items: center; 
    justify-content: center; 
    border-radius: 50%; 
    margin-bottom: 20px; 
    transition: 0.3s; 
}

.service-card-link:hover .service-icon-wrap { 
    background: #38bdf8; 
    color: #0b0d12; 
    transform: scale(1.05);
}

.service-card h3 { font-size: 18px; font-weight: 600; margin-bottom: 12px; color: #f8fafc; }
.service-card p { font-size: 13px; color: #94a3b8; line-height: 1.5; margin-bottom: 20px; flex-grow: 1; }

.card-arrow { 
    font-size: 13px; 
    font-weight: 500; 
    color: #38bdf8; 
    background: #1e293b; 
    padding: 8px 16px; 
    border-radius: 16px; 
    display: flex; 
    align-items: center; 
    gap: 8px; 
    transition: all 0.25s ease; 
}

.service-card-link:hover .card-arrow { 
    background: #38bdf8; 
    color: #0b0d12; 
    gap: 12px; 
}
</style>

<script>
document.addEventListener('keydown', function(event) {
    if (['INPUT', 'TEXTAREA', 'SELECT'].includes(document.activeElement.tagName)) {
        return;
    }

    const key = event.key;
    const targetLink = document.querySelector(`[data-shortcut="${key}"]`);
    
    if (targetLink) {
        event.preventDefault();
        window.location.href = targetLink.href;
    }
});
</script>