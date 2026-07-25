<?php

use yii\helpers\Url;
use yii\helpers\Html;

$this->title = 'Vezérlőpult';
?>

<div class="choose-container">
    <div class="choose-header">
        <h2 class="mb-4">Üdvözöllek, <?= Html::encode(Yii::$app->user->identity->username) ?>!</h2>
        <p>Válaszd ki, milyen feladaton szeretnél dolgozni.</p>
    </div>

    <div class="row justify-content-center g-4">
        
        <!-- AI Asszisztens Kártya -->
        <div class="col-12 col-md-6 col-lg-5 d-flex justify-content-center">
            <a href="<?= Url::to(['/ai/index']) ?>" class="service-card-link w-100">
                <div class="service-card">
                    <div class="service-icon-wrap">
                        <i class="fas fa-robot"></i>
                    </div>

                    <h3>AI Asszisztens</h3>
                    <p>Kérdezz az AI-tól, vagy elemezd adataidat a saját tudásbázisod alapján.</p>

                    <span class="card-arrow">
                        Megnyitás <i class="fas fa-arrow-right"></i>
                    </span>
                </div>
            </a>
        </div>

        <!-- Tudásbázis Kártya -->
        <div class="col-12 col-md-6 col-lg-5 d-flex justify-content-center">
            <a href="<?= Url::to(['/ai-knowledge/index']) ?>" class="service-card-link w-100">
                <div class="service-card">
                    <div class="service-icon-wrap">
                        <i class="fas fa-database"></i>
                    </div>

                    <h3>Tudásbázis</h3>
                    <p>Kezeld és bővítsd az AI tudását. Itt tárolhatod a projektekhez kapcsolódó információkat.</p>

                    <span class="card-arrow">
                        Szerkesztés <i class="fas fa-arrow-right"></i>
                    </span>
                </div>
            </a>
        </div>
        
    </div>
</div>

<style>
/* A meglévő stílusodat használtam, ez maradhat 1:1-ben */
.choose-container {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    background-color: #f5f5f7;
    min-height: calc(100vh - 60px);
    padding: 80px 20px;
}
.choose-header { text-align: center; margin-bottom: 50px; }
.choose-header h2 { font-size: 36px; font-weight: 700; color: #1d1d1f; margin-bottom: 12px; }
.choose-header p { font-size: 19px; color: #86868b; max-width: 600px; margin: 0 auto; }
.service-card-link { text-decoration: none !important; display: block; }
.service-card {
    background: #ffffff; border-radius: 20px; padding: 40px 30px; text-align: center;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03); border: 1px solid #e5e5e7;
    transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    display: flex; flex-direction: column; align-items: center; height: 100%;
}
.service-card-link:hover .service-card { transform: translateY(-8px); box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08); border-color: #0071e3; }
.service-icon-wrap { font-size: 32px; color: #0071e3; background: #f5f5f7; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; border-radius: 50%; margin-bottom: 25px; transition: 0.3s; }
.service-card-link:hover .service-icon-wrap { background: #0071e3; color: #ffffff; }
.service-card h3 { font-size: 22px; font-weight: 600; margin-bottom: 15px; color: #1d1d1f; }
.service-card p { font-size: 15px; color: #86868b; line-height: 1.5; margin-bottom: 25px; flex-grow: 1; }
.card-arrow { font-size: 14px; font-weight: 500; color: #0071e3; background: #f5f5f7; padding: 8px 18px; border-radius: 18px; display: flex; align-items: center; gap: 8px; transition: all 0.2s ease; }
.service-card-link:hover .card-arrow { background: #0071e3; color: #ffffff; gap: 12px; }
</style>