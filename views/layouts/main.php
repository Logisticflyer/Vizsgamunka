<?php
/** @var yii\web\View $this */
/** @var string $content */

use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

$controller = Yii::$app->controller->id;
$action = Yii::$app->controller->action->id;
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; }
        
        /* Modern, minimalista navbar */
        .navbar { background: #1d1d1f !important; padding: 12px 20px !important; }
        
        /* Erőltetett középre igazítás a navbarban */
        .navbar-collapse {
            flex-grow: 1 !important;
        }
        .navbar-nav {
            width: 100% !important;
            display: flex !important;
            justify-content: center !important;
            flex-direction: row !important; /* Biztosítja, hogy vízszintes maradjon */
        }

        /* Az üres brand helyének eltüntetése, hogy ne tolja el a menüt */
        .navbar-brand {
            display: none !important;
        }
        
        /* Menüpontok stílusa (CSAK A FELSŐ SÁVBAN!) */
        .navbar .nav-link { 
            color: #f5f5f7 !important; 
            opacity: 0.6; 
            transition: all 0.3s ease; 
            font-size: 14px; 
            font-weight: 500;
            padding: 8px 16px !important;
        }
        .navbar .nav-link:hover { opacity: 1; color: #fff !important; }
        .navbar .nav-link.active { opacity: 1 !important; color: #fff !important; background: rgba(255,255,255,0.1); border-radius: 8px; }
        
        /* A belépési panel füleinek (tabok) javítása */
        .nav-pills .nav-link, .nav-tabs .nav-link {
            color: #86868b !important; /* Szép szürke az inaktívnak */
            font-weight: 600;
            opacity: 1;
        }
        .nav-pills .nav-link.active, .nav-tabs .nav-link.active {
            color: #0d6efd !important; /* Bootstrap kék az aktívnak */
            background-color: transparent !important;
        }

        .logout { color: #f5f5f7 !important; opacity: 0.6; text-decoration: none; }
        .logout:hover { opacity: 1; }
    </style>

    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100 bg-light">

<?php $this->beginBody() ?>

<!-- CSAK BEJELENTKEZETT FELHASZNÁLÓK LÁTJÁK A FELSŐ SÁVOT -->
<?php if (!Yii::$app->user->isGuest): ?>
<header id="header">
    <?php
    NavBar::begin([
        'brandLabel' => '', // Eltüntetve a NexusAI / Vizsgamunka felirat
        'options' => ['class' => 'navbar navbar-expand-md navbar-dark shadow-sm'],
    ]);
    
    // Mivel a header csak bejelentkezve látszik, a feltételeket (isGuest) kivehettük a menüből!
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav'], 
        'items' => [
            ['label' => '<i class="fas fa-home fa-fw"></i> Főoldal', 'encode' => false, 'url' => ['/site/index'], 'active' => ($controller == 'site' && $action == 'index')],
            ['label' => '<i class="fas fa-tachometer-alt fa-fw"></i> Vezérlőpult', 'encode' => false, 'url' => ['/site/choose'], 'active' => ($controller == 'site' && $action == 'choose')],
            ['label' => '<i class="fas fa-robot fa-fw"></i> AI Asszisztens', 'encode' => false, 'url' => ['/ai/index'], 'active' => ($controller == 'ai')],
            
            // --- CSAK ADMIN (Tudásbázis védelem) ---
            (Yii::$app->user->identity->username === 'admin') ? ['label' => '<i class="fas fa-book fa-fw"></i> Tudásbázis', 'encode' => false, 'url' => ['/ai-knowledge/index'], 'active' => ($controller == 'ai-knowledge')] : '',
            
            ['label' => '<i class="fas fa-user-circle fa-fw"></i> Profilom', 'encode' => false, 'url' => ['/site/profile'], 'active' => ($controller == 'site' && $action == 'profile')],
            
            '<li class="nav-item">'
            . Html::beginForm(['/site/logout'], 'post', ['class' => 'd-flex'])
            . Html::submitButton('<i class="fas fa-sign-out-alt fa-fw"></i> Kijelentkezés', ['class' => 'nav-link btn btn-link logout'])
            . Html::endForm()
            . '</li>'
        ],
    ]);
    NavBar::end();
    ?>
</header>
<?php endif; ?>

<main id="main" class="flex-shrink-0" role="main">
    <div class="container-fluid p-4">
        <?php foreach (Yii::$app->session->getAllFlashes() as $key => $message): ?>
            <div class="alert alert-<?= $key ?> alert-dismissible fade show shadow-sm border-0" role="alert" style="border-radius: 15px;">
                <i class="fas fa-info-circle me-2"></i> <?= $message ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endforeach; ?>

        <?= $content ?>
    </div>
</main>

<footer id="footer" class="mt-auto py-4 text-center text-muted" style="font-size: 13px;">
    &copy; Vizsgamunka <?= date('Y') ?>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>