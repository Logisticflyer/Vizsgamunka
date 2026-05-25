<?php
/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?> - Admin Control Center</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="admin-wrapper">
    <aside class="admin-sidebar">
        <div class="admin-logo">
            <i class="fas fa-sliders-h"></i> <span>Dashboard</span>
        </div>
        <nav class="admin-nav">
            <a href="<?= Url::to(['/site/index']) ?>" class="nav-item"><i class="fas fa-home"></i> Főoldalra</a>
            <a href="<?= Url::to(['/ajanlatkeres/index']) ?>" class="nav-item active"><i class="fas fa-paper-plane"></i> Ajánlatkérések</a>
            <a href="<?= Url::to(['/site/logout']) ?>" data-method="post" class="nav-item logout"><i class="fas fa-sign-out-alt"></i> Kijelentkezés</a>
        </nav>
    </aside>

    <main class="admin-content">
        <div class="admin-card-container">
            <?= $content ?>
        </div>
    </main>
</div>

<style>
    body {
        background-color: #f5f5f7;
        margin: 0;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }
    .admin-wrapper {
        display: flex;
        min-height: 100vh;
    }
    .admin-sidebar {
        width: 260px;
        background: #1d1d1f;
        color: #fff;
        padding: 30px 20px;
        display: flex;
        flex-direction: column;
    }
    .admin-logo {
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 40px;
        display: flex;
        align-items: center;
        gap: 10px;
        color: #f5f5f7;
    }
    .admin-nav {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    .nav-item {
        color: #86868b;
        text-decoration: none;
        padding: 12px 16px;
        border-radius: 10px;
        font-weight: 500;
        transition: 0.2s;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .nav-item:hover, .nav-item.active {
        background: rgba(255, 255, 255, 0.1);
        color: #fff;
    }
    .nav-item.logout {
        margin-top: auto;
        color: #ff3b30;
    }
    .admin-content {
        flex: 1;
        padding: 40px;
        overflow-y: auto;
    }
    .admin-card-container {
        background: #ffffff;
        border-radius: 20px;
        padding: 30px;
        border: 1px solid #e5e5e7;
        box-shadow: 0 4px 20px rgba(0,0,0,0.02);
    }
</style>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>