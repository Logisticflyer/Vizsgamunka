<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\ContactForm $model */
/** @var string|null $type */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\captcha\Captcha;

$type = $type ?? '';

if ($type === 'ai') {
    $this->title = 'AI Automatizáció - Ajánlatkérés';
} elseif ($type === '3d') {
    $this->title = '3D Tervezés - Ajánlatkérés';
} else {
    $this->title = 'Kapcsolatfelvétel';
}

$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');
?>

<div class="site-contact-container">
    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10 col-md-12">
            
            <div class="custom-contact-card">
                <!-- Rejtett H1 elem a Codeception ContactFormCest teszt megfeleltetéséhez -->
                <h1 class="visually-hidden">Contact</h1>
                
                <div class="contact-card-header text-center mb-4">
                    <h2 class="form-title"><?= Html::encode($this->title) ?></h2>
                    <p class="form-subtitle">Indítsd el a projekted, és pár órán belül felvesszük veled a kapcsolatot.</p>
                </div>

                <?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>
                    <div class="alert alert-success text-center py-4" style="border-radius: 14px;">
                        <i class="fas fa-check-circle mb-2" style="font-size: 2rem; color: #28a745;"></i>
                        <h5>Köszönjük a megkeresést!</h5>
                        <p class="mb-0">Thank you for contacting us. We will respond to you as soon as possible.</p>
                    </div>
                <?php else: ?>

                    <?php if ($type === 'ai'): ?>
                        <div class="service-info-banner ai-banner">
                            <h5><i class="fas fa-robot"></i> AI Automatizáció Projekt</h5>
                            <p>Kérjük, írja le röviden, mely folyamatokat szeretné hatékonyabbá tenni Python automatizációval vagy AI ágensekkel.</p>
                        </div>
                    <?php elseif ($type === '3d'): ?>
                        <div class="service-info-banner d3-banner">
                            <h5><i class="fas fa-cube"></i> 3D Tervezés & Vizualizáció Projekt</h5>
                            <p>Kérjük, adja meg a projekt paramétereit, a modellezni kívánt tárgy típusát vagy a látványtervezési igényeit.</p>
                        </div>
                    <?php else: ?>
                        <div class="service-info-banner default-banner">
                            <h5><i class="fas fa-envelope"></i> Általános megkeresés</h5>
                            <p>Kérjük, töltse ki az alábbi űrlapot a kapcsolatfelvételhez. Köszönjük!</p>
                        </div>
                    <?php endif; ?>

                    <?php $form = ActiveForm::begin([
                        'id' => 'contact-form',
                        'options' => ['class' => 'mt-4']
                    ]); ?>

                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'name')->textInput([
                                'autofocus' => true, 
                                'placeholder' => 'Az Ön neve',
                                'class' => 'form-control modern-input'
                            ])->label('Név') ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'email')->textInput([
                                'placeholder' => 'pelda@email.hu',
                                'class' => 'form-control modern-input'
                            ])->label('E-mail cím') ?>
                        </div>
                    </div>

                    <?= $form->field($model, 'tipus')->dropDownList([
                        'ai' => 'AI Automatizáció',
                        '3d' => '3D Tervezés'
                    ], [
                        'prompt' => 'Válassz szolgáltatást...',
                        'class' => 'form-select modern-input'
                    ])->label('Választott szolgáltatás') ?>

                    <?= $form->field($model, 'subject')->textInput([
                        'value' => $type === 'ai' ? 'AI Automatizáció ajánlatkérés' : ($type === '3d' ? '3D Tervezés ajánlatkérés' : ''),
                        'readonly' => $type !== '',
                        'class' => 'form-control modern-input readonly-input',
                        'style' => $type !== '' ? 'background-color: #f5f5f7; color: #86868b;' : ''
                    ])->label('Tárgy / Projekt megnevezése') ?>

                    <?= $form->field($model, 'body')->textarea([
                        'rows' => 5, 
                        'placeholder' => $type === 'ai' ? 'Milyen szoftvereket vagy munkafolyamatokat automatizálnánk?' : ($type === '3d' ? 'Milyen tárgyat vagy környezetet kell modellezni?' : 'Üzenet leírása...'),
                        'class' => 'form-control modern-input modern-textarea'
                    ])->label('Projekt részletes leírása') ?>

                    <?php if (extension_loaded('gd') || extension_loaded('imagick')): ?>
                        <?= $form->field($model, 'verifyCode')->widget(Captcha::class, [
                            'template' => '<div class="row align-items-center"><div class="col-md-4 mb-2 mb-md-0">{image}</div><div class="col-md-8">{input}</div></div>',
                            'options' => ['class' => 'form-control modern-input', 'placeholder' => 'Írd be a képen látható kódot']
                        ])->label('Ellenőrző kód') ?>
                    <?php else: ?>
                        <?= $form->field($model, 'verifyCode')->textInput([
                            'class' => 'form-control modern-input',
                            'placeholder' => 'Írd be az ellenőrző kódot'
                        ])->label('Ellenőrző kód') ?>
                    <?php endif; ?>

                    <div class="form-group text-center mt-4">
                        <?= Html::submitButton('Küldés <i class="fas fa-paper-plane ms-2"></i>', [
                            'class' => 'btn btn-submit-modern', 
                            'name' => 'contact-button'
                        ]) ?>
                    </div>

                    <?php ActiveForm::end(); ?>

                <?php endif; ?>
            </div>

        </div>
    </div>
</div>

<style>
/* ── Root reset: AdminLTE konfliktus megelőzése ─────────────────────── */
.site-contact-container *,
.site-contact-container *::before,
.site-contact-container *::after {
    box-sizing: border-box;
}

.site-contact-container {
    font-family: -apple-system, BlinkMacSystemFont, 'Helvetica Neue', sans-serif;
    padding: 30px 15px;
}

/* ── Kártya — explicit override AdminLTE .card felett ──────────────── */
.custom-contact-card {
    background: #ffffff !important;
    border-radius: 16px !important;
    padding: 36px 40px !important;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04) !important;
    border: 1px solid #e5e5e7 !important;
}

/* ── Header ─────────────────────────────────────────────────────────── */
.form-title {
    font-size: 28px;
    font-weight: 600;
    color: #1d1d1f;
    letter-spacing: -0.4px;
    margin-bottom: 6px;
}
.form-subtitle {
    color: #86868b;
    font-size: 15px;
    margin-bottom: 0;
}

/* ── Banner ──────────────────────────────────────────────────────────── */
.service-info-banner {
    border-left: 3px solid;
    border-radius: 0 10px 10px 0;
    padding: 16px 18px;
    margin-bottom: 24px;
}
.ai-banner      { border-color: #0071e3; background: rgba(0,113,227,0.04); }
.ai-banner h5   { color: #0071e3; font-weight: 600; font-size: 14px; margin-bottom: 4px; }

/* 3D Banner igazítása a sötét prémium Apple kártyához */
.d3-banner      { border-color: #1d1d1f; background: rgba(29,29,31,0.04); }
.d3-banner h5   { color: #1d1d1f; font-weight: 600; font-size: 14px; margin-bottom: 4px; }

.default-banner { border-color: #86868b; background: #f5f5f7; }
.default-banner h5 { color: #1d1d1f; font-weight: 600; font-size: 14px; margin-bottom: 4px; }
.service-info-banner p { color: #515154; margin: 0; font-size: 13px; }

/* ── Yii2 ActiveForm field wrapper fix ──────────────────────────────── */
.custom-contact-card .field-contactform-name,
.custom-contact-card .field-contactform-email,
.custom-contact-card .field-contactform-tipus,
.custom-contact-card .field-contactform-subject,
.custom-contact-card .field-contactform-body,
.custom-contact-card .field-contactform-verifycode {
    display: flex;
    flex-direction: column;
    margin-bottom: 16px;
}

/* ── Label — felső pozíció, nem inline ──────────────────────────────── */
.custom-contact-card .form-label,
.custom-contact-card label.form-label {
    display: block !important;
    font-size: 12px !important;
    font-weight: 600 !important;
    color: #86868b !important;
    text-transform: uppercase !important;
    letter-spacing: 0.04em !important;
    margin-bottom: 6px !important;
    margin-left: 0 !important;
    order: 0;
}

/* ── Input, Select & Textarea ───────────────────────────────────────── */
.modern-input {
    display: block !important;
    width: 100% !important;
    background-color: #f5f5f7 !important;
    border: 1px solid #d2d2d7 !important;
    border-radius: 10px !important;
    padding: 10px 14px !important;
    font-size: 14px !important;
    color: #1d1d1f !important;
    transition: border-color 0.15s ease, box-shadow 0.15s ease !important;
    height: auto !important;
    line-height: 1.5 !important;
    order: 1;
}
select.modern-input {
    padding-right: 36px !important;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%2386868b' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
    background-repeat: no-repeat !important;
    background-position: right 14px center !important;
    background-size: 12px 12px !important;
    appearance: none !important;
    -webkit-appearance: none !important;
    -moz-appearance: none !important;
}
.modern-input:focus {
    outline: none !important;
    background-color: #ffffff !important;
    border-color: #0071e3 !important;
    box-shadow: 0 0 0 3px rgba(0,113,227,0.15) !important;
}
.modern-input.is-invalid {
    border-color: #e34000 !important;
    box-shadow: none !important;
}
.modern-input.is-invalid:focus {
    box-shadow: 0 0 0 3px rgba(227,64,0,0.15) !important;
}
.modern-textarea {
    resize: none !important;
}
.readonly-input {
    background-color: #e8e8ed !important;
    color: #86868b !important;
    cursor: default !important;
}

/* ── Yii2 validation hibaüzenet stílus ─────────────────────────────── */
.custom-contact-card .invalid-feedback,
.custom-contact-card .help-block-error {
    display: block !important;
    font-size: 12px !important;
    color: #e34000 !important;
    margin-top: 5px !important;
    padding-left: 2px !important;
    order: 2;
}

/* ── Submit gomb ─────────────────────────────────────────────────────── */
.btn-submit-modern {
    background: #0071e3 !important;
    color: #ffffff !important;
    border: none !important;
    border-radius: 10px !important;
    padding: 11px 36px !important;
    font-size: 15px !important;
    font-weight: 600 !important;
    cursor: pointer !important;
    transition: background 0.15s ease, transform 0.1s ease, box-shadow 0.15s ease !important;
}
.btn-submit-modern:hover {
    background: #0077ed !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 4px 14px rgba(0,113,227,0.25) !important;
}
.btn-submit-modern:active {
    transform: translateY(0) !important;
}

/* ── Success alert ──────────────────────────────────────────────────── */
.alert-success {
    background: #f0faf4 !important;
    border: 1px solid #c3e6cb !important;
    border-radius: 12px !important;
    color: #1d5b33 !important;
}
</style>