<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Profilom';

$this->registerCss("
body, html, #main, .content-fluid, .wrap, main, .container, .container-fluid {
    background-color: #0b0d12 !important;
    color: #f8fafc !important;
}

.profile-wrapper {
    max-width: 1000px;
    margin: 0 auto;
    padding: 3rem 1.5rem;
    font-family: 'Inter', -apple-system, sans-serif;
}

.profile-card {
    background: #141824;
    border: 1px solid #1e293b;
    border-radius: 20px;
    padding: 2.5rem 2rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    height: 100%;
}

.avatar-circle {
    width: 90px;
    height: 90px;
    border-radius: 50%;
    background: #1e293b;
    border: 2px solid #38bdf8;
    color: #38bdf8;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    margin: 0 auto 1.2rem auto;
}

/* CÍMKÉK ÉS LEÍRÓ SZÖVEGEK OLVASHATÓSÁGÁNAK JAVÍTÁSA */
label, 
.form-label, 
.text-muted, 
small {
    color: #cbd5e1 !important;
    font-weight: 500 !important;
}

.profile-card p,
.profile-card .text-muted,
.profile-card small {
    color: #cbd5e1 !important;
    font-size: 0.95rem;
}

/* BEVITELI MEZŐK ÉS PLACEHOLDER SZÖVEGEK */
.form-control {
    background-color: #1e293b !important;
    border: 1px solid #334155 !important;
    color: #ffffff !important;
    border-radius: 12px !important;
    padding: 0.75rem 1rem !important;
}

.form-control:focus {
    border-color: #38bdf8 !important;
    box-shadow: 0 0 0 0.25rem rgba(56, 189, 248, 0.25) !important;
}

.form-control::placeholder {
    color: #64748b !important;
    opacity: 1 !important;
}

/* KIEMELT ALCÍMEK */
h4, h5, h6, .section-title {
    color: #38bdf8 !important;
    font-weight: 600 !important;
}

.btn-save-profile {
    background: linear-gradient(135deg, #0071e3 0%, #38bdf8 100%);
    border: none;
    color: #ffffff !important;
    font-weight: 600;
    padding: 0.75rem 2rem;
    border-radius: 50px;
    box-shadow: 0 4px 16px rgba(56, 189, 248, 0.25);
}

/* STATUS BADGES */
.status-badge {
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.status-pending {
    background: rgba(234, 179, 8, 0.15);
    color: #facc15;
    border: 1px solid rgba(234, 179, 8, 0.3);
}

.status-in_progress {
    background: rgba(56, 189, 248, 0.15);
    color: #38bdf8;
    border: 1px solid rgba(56, 189, 248, 0.3);
}

.status-completed {
    background: rgba(34, 197, 94, 0.15);
    color: #4ade80;
    border: 1px solid rgba(34, 197, 94, 0.3);
}

.offer-item {
    background: #1e293b;
    border: 1px solid #334155;
    border-radius: 14px;
    padding: 1.2rem 1.5rem;
    margin-bottom: 1rem;
    transition: border-color 0.2s ease;
}

.offer-item:hover {
    border-color: #38bdf8;
}
");
?>

<div class="profile-wrapper">
    <h2 class="mb-4 font-weight-bold" style="color: #f8fafc;">Profilom</h2>

    <div class="row g-4 mb-5">
        <!-- Bal oldali kártya -->
        <div class="col-12 col-md-4">
            <div class="profile-card text-center">
                <div class="avatar-circle">
                    <i class="fas fa-user"></i>
                </div>
                <h3 class="h4 font-weight-bold text-white mb-1"><?= Html::encode($user->username) ?></h3>
                <p class="text-muted small mb-0"><?= Html::encode($user->email ?? 'Nincs megadva e-mail') ?></p>
            </div>
        </div>

        <!-- Jobb oldali szerkesztő kártya -->
        <div class="col-12 col-md-8">
            <div class="profile-card">
                <h3 class="h5 font-weight-bold mb-4" style="color: #f8fafc;">Adatok szerkesztése</h3>

                <?php $form = ActiveForm::begin(['id' => 'profile-form']); ?>

                <div class="mb-3">
                    <?= $form->field($model, 'username')->textInput(['class' => 'form-control'])->label('Felhasználónév') ?>
                </div>

                <div class="mb-4">
                    <?= $form->field($model, 'email')->textInput(['class' => 'form-control'])->label('E-mail cím') ?>
                </div>

                <h4 class="h6 font-weight-bold mt-4 mb-3" style="color: #38bdf8;">Biztonság</h4>

                <div class="mb-4">
                    <?= $form->field($model, 'password')->passwordInput([
                        'class' => 'form-control',
                        'placeholder' => 'Új jelszó'
                    ])->label('Új jelszó (hagyd üresen, ha nem változtatod)') ?>
                </div>

                <div class="d-flex justify-content-end">
                    <?= Html::submitButton('<i class="fas fa-save me-2"></i> Módosítások mentése', ['class' => 'btn btn-save-profile']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>

    <!-- ÉLŐ AJÁNLATKÉRÉSI STÁTUSZPANEL -->
    <div class="profile-card">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h3 class="h5 font-weight-bold mb-0" style="color: #f8fafc;">
                <i class="fas fa-paper-plane text-info me-2"></i> Elküldött Ajánlatkéréseim
            </h3>
            <span class="badge bg-secondary"><?= count($myOffers) ?> db</span>
        </div>

        <?php if (!empty($myOffers)): ?>
            <?php foreach ($myOffers as $offer): ?>
                <div class="offer-item">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h5 class="h6 font-weight-bold mb-1 text-white">
                                <?= Html::encode($offer->targy ?: 'Általános ajánlatkérés') ?>
                            </h5>
                            <span class="text-muted small">
                                <i class="far fa-clock me-1"></i> Beküldve: <?= Html::encode($offer->created_at ?? 'Nemrégen') ?>
                            </span>
                        </div>

                        <!-- ÉLŐ STÁTUSZ BADGE -->
                        <?php if ($offer->status === 'completed'): ?>
                            <span class="status-badge status-completed">
                                <i class="fas fa-check-circle"></i> Megválaszolva
                            </span>
                        <?php elseif ($offer->status === 'in_progress'): ?>
                            <span class="status-badge status-in_progress">
                                <i class="fas fa-sync-alt fa-spin"></i> Feldolgozás alatt
                            </span>
                        <?php else: ?>
                            <span class="status-badge status-pending">
                                <i class="fas fa-hourglass-start"></i> Beküldve
                            </span>
                        <?php endif; ?>
                    </div>

                    <?php if (!empty($offer->leiras)): ?>
                        <p class="text-muted small mb-0 mt-2">
                            <?= Html::encode(mb_strimwidth($offer->leiras, 0, 120, '...')) ?>
                        </p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="text-center py-4 text-muted">
                <i class="fas fa-inbox fa-2x mb-2 d-block opacity-50"></i>
                Még nem küldtél be ajánlatkérést.
            </div>
        <?php endif; ?>
    </div>
</div>