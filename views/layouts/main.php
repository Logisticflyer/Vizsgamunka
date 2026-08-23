<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

// Yii2 Asset nyaláb regisztrálása a JS és CSRF működéshez
AppAsset::register($this);

$controller = Yii::$app->controller->id;
$action = Yii::$app->controller->action->id;

// Admin jogosultság ellenőrzése
$user = Yii::$app->user->identity;
$isAdmin = !Yii::$app->user->isGuest && (($user->is_admin ?? false) || ($user->username ?? '') === 'admin');
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { 
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; 
            background-color: #0b0d12 !important; 
            color: #f8fafc; 
        }
        
        .navbar { 
            background: #141824 !important; 
            padding: 12px 20px !important; 
            border-bottom: 1px solid #1e293b !important;
        }
        
        .navbar-collapse {
            flex-grow: 1 !important;
        }
        .navbar-nav {
            width: 100% !important;
            display: flex !important;
            justify-content: center !important;
            flex-direction: row !important; 
            gap: 10px; 
        }

        .navbar-brand {
            display: none !important;
        }
        
        .navbar .nav-link { 
            color: #94a3b8 !important; 
            opacity: 0.8; 
            transition: all 0.3s ease; 
            font-size: 14px; 
            font-weight: 500;
            padding: 8px 16px !important;
            border-radius: 8px;
        }
        .navbar .nav-link:hover { 
            opacity: 1; 
            color: #38bdf8 !important; 
            background: rgba(56, 189, 248, 0.05);
        }
        .navbar .nav-link.active { 
            opacity: 1 !important; 
            color: #38bdf8 !important; 
            background: rgba(56, 189, 248, 0.1); 
            font-weight: 600;
        }
        
        .logout { color: #ef4444 !important; opacity: 0.8; text-decoration: none; }
        .logout:hover { opacity: 1; background: rgba(239, 68, 68, 0.1); }
        
        /* Felugró értesítések (Alerts) magas kontrasztú sötét téma stílusa */
        .alert {
            background-color: #1e293b !important;
            color: #ffffff !important;
            border: 1px solid #334155 !important;
            font-weight: 500;
            font-size: 0.95rem;
        }

        /* Biztosítja, hogy az értesítésben lévő minden szöveg tiszta fehér legyen */
        .alert, 
        .alert span, 
        .alert div {
            color: #ffffff !important;
        }

        /* Hiba üzenet (alert-danger / alert-error) egyedi pirosas kiemelése */
        .alert-danger, .alert-error {
            background-color: rgba(239, 68, 68, 0.15) !important;
            border-color: rgba(239, 68, 68, 0.4) !important;
            color: #ffffff !important;
        }

        /* Sikeres üzenet (alert-success) zöldes kiemelése */
        .alert-success {
            background-color: rgba(16, 185, 129, 0.15) !important;
            border-color: rgba(16, 185, 129, 0.4) !important;
            color: #ffffff !important;
        }

        /* Bezáró (X) gomb fehéritése */
        .alert .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%) !important;
            opacity: 0.8;
        }
        .alert .btn-close:hover {
            opacity: 1;
        }
    </style>

    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">

<?php $this->beginBody() ?>

<!-- CSAK BEJELENTKEZETT FELHASZNÁLÓK LÁTJÁK A FELSŐ SÁVOT -->
<?php if (!Yii::$app->user->isGuest): ?>
<header id="header">
    <?php
    $menuItems = [
        ['label' => '<i class="fas fa-home fa-fw me-1"></i> Főoldal', 'encode' => false, 'url' => ['/site/index'], 'active' => ($controller == 'site' && ($action == 'index' || $action == 'choose'))],
    ];

    // Admin specifikus gyorslinkek (Tudásbázis + Ajánlatok)
    if ($isAdmin) {
        $menuItems[] = ['label' => '<i class="fas fa-database fa-fw me-1"></i> Tudásbázis', 'encode' => false, 'url' => ['/ai-knowledge/index'], 'active' => ($controller == 'ai-knowledge')];
        $menuItems[] = ['label' => '<i class="fas fa-inbox fa-fw me-1"></i> Ajánlatok', 'encode' => false, 'url' => ['/ai/offers'], 'active' => ($controller == 'ai' && $action == 'offers')];
    }

    $menuItems[] = ['label' => '<i class="fas fa-user-circle fa-fw me-1"></i> Profilom', 'encode' => false, 'url' => ['/site/profile'], 'active' => ($controller == 'site' && $action == 'profile')];

    $menuItems[] = '<li class="nav-item">'
        . Html::beginForm(['/site/logout'], 'post', ['class' => 'd-flex m-0'])
        . Html::submitButton('<i class="fas fa-sign-out-alt fa-fw me-1"></i> Kijelentkezés', ['class' => 'nav-link btn btn-link logout'])
        . Html::endForm()
        . '</li>';

    NavBar::begin([
        'brandLabel' => '', 
        'options' => ['class' => 'navbar navbar-expand-md navbar-dark shadow-sm'],
    ]);
    
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav'], 
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>
</header>
<?php endif; ?>

<main id="main" class="flex-shrink-0" role="main">
    <div class="container-fluid p-0">
        <div class="px-4 pt-3">
            <?php foreach (Yii::$app->session->getAllFlashes() as $key => $message): ?>
                <div class="alert alert-<?= $key ?> alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 12px;">
                    <i class="fas fa-info-circle me-2 text-info"></i> <span><?= $message ?></span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endforeach; ?>
        </div>

        <?= $content ?>
    </div>
</main>

<footer id="footer" class="mt-auto py-4 text-center" style="font-size: 13px; color: #475569;">
    &copy; Vizsgamunka <?= date('Y') ?>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>