<?php

use yii\helpers\Url;

$this->title = 'Szolgáltatások kiválasztása';
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');

// Three.js könyvtár és a saját szkriptünk fix betöltése a Yii2 segítségével az oldal végén
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js', ['position' => \yii\web\View::POS_END]);
$this->registerJsFile('@web/js/three-init.js', ['position' => \yii\web\View::POS_END, 'depends' => [\yii\web\YiiAsset::class]]);
?>

<div class="choose-container">
    <div class="choose-header">
        <h2>Miben segíthetünk ma?</h2>
        <p>Válaszd ki a számodra megfelelő irányt, és indítsd el a projektet.</p>
    </div>

    <div class="row justify-content-center g-4">
        <div class="col-12 col-md-6 col-lg-5 d-flex justify-content-center">
            <a href="<?= Url::to(['site/contact', 'type' => 'ai']) ?>" class="service-card-link w-100">
                <div class="service-card">
                    <div class="service-icon-wrap">
                        <i class="fas fa-robot"></i>
                    </div>

                    <h3>AI Automatizáció</h3>
                    <p>Munkafolyamatok optimalizálása, egyedi ágensek és Python alapú háttér-megoldások.</p>

                    <span class="card-arrow">
                        Elindítás <i class="fas fa-arrow-right"></i>
                    </span>
                </div>
            </a>
        </div>

        <div class="col-12 col-md-6 col-lg-5 d-flex justify-content-center">
            <a href="<?= Url::to(['site/contact', 'type' => '3d']) ?>" class="service-card-link w-100">
                <div class="service-card">
                    <div class="service-icon-wrap">
                        <i class="fas fa-cube"></i>
                    </div>

                    <h3>3D Tervezés</h3>
                    <p>Digitális termékmodellezés, vizualizáció és mérnöki pontosságú látványtervek.</p>

                    <span class="card-arrow">
                        Elindítás <i class="fas fa-arrow-right"></i>
                    </span>
                </div>
            </a>
        </div>
    </div>

    <div class="row justify-content-center mt-5">
        <div class="col-12 col-lg-10">
            <div class="three-box-wrapper" style="background: #ffffff; padding: 30px; border-radius: 20px; border: 1px solid #e5e5e7; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);">
                <h4 class="text-center mb-3 text-muted" style="font-size: 16px; font-weight: 500; letter-spacing: 0.5px; text-transform: uppercase;">Interaktív 3D Élmény</h4>
                <div id="3d-container" style="width: 100%; height: 350px; background-color: #1d1d1f; border-radius: 12px; display: block; position: relative;"></div>
            </div>
        </div>
    </div>
</div>

<style>
.choose-container {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    background-color: #f5f5f7;
    min-height: calc(100vh - 60px);
    padding: 80px 20px;
}

.choose-header {
    text-align: center;
    margin-bottom: 50px;
}

.choose-header h2 {
    font-size: 36px;
    font-weight: 700;
    letter-spacing: -0.5px;
    color: #1d1d1f;
    margin-bottom: 12px;
    display: block;
}

.choose-header p {
    font-size: 19px;
    color: #86868b;
    max-width: 600px;
    margin: 0 auto;
}

.service-card-link {
    text-decoration: none !important;
    display: block;
}

.service-card {
    background: #ffffff;
    border-radius: 20px;
    padding: 40px 30px;
    text-align: center;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
    border: 1px solid #e5e5e7;
    color: #1d1d1f;
    transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
    height: 100%;
    overflow: hidden;
    cursor: pointer;
}

.service-card-link:hover .service-card {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
    border-color: #0071e3;
}

.service-icon-wrap {
    font-size: 32px;
    color: #0071e3;
    background: #f5f5f7;
    width: 80px;
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    margin-bottom: 25px;
    transition: 0.3s;
}

.service-card-link:hover .service-icon-wrap {
    background: #0071e3;
    color: #ffffff;
}

.service-card h3 {
    font-size: 22px;
    font-weight: 600;
    margin-bottom: 15px;
    color: #1d1d1f;
}

.service-card p {
    font-size: 15px;
    color: #86868b;
    line-height: 1.5;
    margin-bottom: 25px;
    flex-grow: 1;
}

.card-arrow {
    font-size: 14px;
    font-weight: 500;
    color: #0071e3;
    background: #f5f5f7;
    padding: 8px 18px;
    border-radius: 18px;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s ease;
}

.service-card-link:hover .card-arrow {
    background: #0071e3;
    color: #ffffff;
    gap: 12px;
}

@media (max-width: 768px) {
    .choose-container {
        padding: 60px 16px;
    }

    .choose-header h2 {
        font-size: 30px;
    }

    .choose-header p {
        font-size: 17px;
    }

    .service-card {
        padding: 32px 24px;
    }
}
</style>