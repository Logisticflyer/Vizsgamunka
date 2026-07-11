<?php

use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'Főoldal';

if (!isset($signupModel)) {
    $signupModel = new \app\models\SignupForm();
}
?>

<div class="landing-container" style="max-width: 1200px; margin: 0 auto; padding: 40px 20px;">
    <div class="hero-section">
        <h1>Digitális Precizitás & Automatizáció</h1>
        <p>Üdvözlünk a platformon. Optimalizáld a munkafolyamataidat fejlett AI asszisztensekkel vagy egyedi 3D tervezéssel egyetlen felületen.</p>

        <div class="hero-buttons">
            <a href="<?= Url::to(['/site/choose']) ?>" class="btn-main">Szolgáltatások felfedezése</a>
            <a href="#ajanlat-zone" class="btn-secondary" style="color: #0071e3; font-weight: 500; text-decoration: none;">Ajánlatkérés</a>
            <a href="#auth-zone" class="btn-secondary">Csatlakozás most</a>
        </div>
    </div>

    <div class="partner-strip">
        <span>Támogatott technológiák:</span>
        <div class="partner-logos">
            <i class="fab fa-google"></i> Google Cloud
            <i class="fab fa-aws"></i> AWS
            <i class="fas fa-brain"></i> OpenAI API
        </div>
    </div>

    <div id="auth-zone" class="auth-section">
        <div class="auth-card">
            <div class="auth-tabs">
                <button id="tab-login" class="auth-tab active" type="button" onclick="switchAuth('login')">Bejelentkezés</button>
                <button id="tab-register" class="auth-tab" type="button" onclick="switchAuth('register')">Regisztráció</button>
            </div>

            <div id="form-login" class="auth-form-wrapper">
                <?php $formLogin = ActiveForm::begin([
                    'id' => 'login-form',
                    'action' => ['site/index'],
                    'options' => ['class' => 'minimal-form', 'method' => 'post'],
                    'fieldConfig' => [
                        'template' => "{input}\n{error}",
                    ],
                ]); ?>

                    <div class="form-group" style="margin-bottom: 20px;">
                        <label style="display: block; font-weight: 600; text-transform: uppercase; font-size: 12px; color: #86868b; margin-bottom: 8px; text-align: left;">Felhasználónév</label>
                        <?= $formLogin->field($model, 'username')->textInput([
                            'class' => 'form-control',
                            'placeholder' => 'Felhasználónév vagy email',
                            'style' => 'width: 100%; padding: 14px; border: 1px solid #d2d2d7; border-radius: 12px; font-size: 16px; background-color: #f5f5f7;'
                        ]) ?>
                    </div>

                    <div class="form-group" style="margin-bottom: 25px;">
                        <label style="display: block; font-weight: 600; text-transform: uppercase; font-size: 12px; color: #86868b; margin-bottom: 8px; text-align: left;">Jelszó</label>
                        <?= $formLogin->field($model, 'password')->passwordInput([
                            'class' => 'form-control',
                            'placeholder' => '••••••••',
                            'style' => 'width: 100%; padding: 14px; border: 1px solid #d2d2d7; border-radius: 12px; font-size: 16px; background-color: #f5f5f7;'
                        ]) ?>
                    </div>

                    <div class="form-group checkbox-wrapper" style="margin-bottom: 20px; font-size: 14px; color: #86868b; text-align: left; display: flex; align-items: center; gap: 8px;">
                        <?= $formLogin->field($model, 'rememberMe')->checkbox([
                            'template' => "<div class='custom-control custom-checkbox'>{input} {label}</div>\n{error}",
                        ])->label('Emlékezz rám') ?>
                    </div>

                    <button type="submit" class="btn-auth" name="login-button">Belépés</button>

                    <div class="oauth-divider"><span>vagy lépj be amivel kényelmesebb</span></div>

                    <a href="#" class="btn-google">
                        <i class="fab fa-google"></i> Belépés Google fiókkal
                    </a>

                <?php ActiveForm::end(); ?>
            </div>

            <div id="form-register" class="auth-form-wrapper" style="display: none;">
                <?php $formSignup = ActiveForm::begin([
                    'id' => 'signup-form',
                    'action' => ['site/signup'],
                    'options' => ['class' => 'minimal-form', 'method' => 'post'],
                    'fieldConfig' => [
                        'template' => "{input}\n{error}",
                    ],
                ]); ?>

                    <p style="color: #86868b; text-align: center; margin-bottom: 20px;">Hozd létre a fiókodat a folytatáshoz.</p>

                    <div class="form-group" style="margin-bottom: 20px;">
                        <label style="display: block; font-weight: 600; text-transform: uppercase; font-size: 12px; color: #86868b; margin-bottom: 8px; text-align: left;">Felhasználónév</label>
                        <?= $formSignup->field($signupModel, 'username')->textInput([
                            'class' => 'form-control',
                            'placeholder' => 'Pl. ujfelhasznalo',
                            'style' => 'width: 100%; padding: 14px; border: 1px solid #d2d2d7; border-radius: 12px; font-size: 16px; background-color: #f5f5f7;'
                        ]) ?>
                    </div>

                    <div class="form-group" style="margin-bottom: 20px;">
                        <label style="display: block; font-weight: 600; text-transform: uppercase; font-size: 12px; color: #86868b; margin-bottom: 8px; text-align: left;">E-mail cím</label>
                        <?= $formSignup->field($signupModel, 'email')->input('email', [
                            'class' => 'form-control',
                            'placeholder' => 'pelda@domain.com',
                            'style' => 'width: 100%; padding: 14px; border: 1px solid #d2d2d7; border-radius: 12px; font-size: 16px; background-color: #f5f5f7;'
                        ]) ?>
                    </div>

                    <div class="form-group" style="margin-bottom: 25px;">
                        <label style="display: block; font-weight: 600; text-transform: uppercase; font-size: 12px; color: #86868b; margin-bottom: 8px; text-align: left;">Jelszó</label>
                        <?= $formSignup->field($signupModel, 'password')->passwordInput([
                            'class' => 'form-control',
                            'placeholder' => '••••••••',
                            'style' => 'width: 100%; padding: 14px; border: 1px solid #d2d2d7; border-radius: 12px; font-size: 16px; background-color: #f5f5f7;'
                        ]) ?>
                    </div>

                    <button type="submit" class="btn-auth" name="signup-button">Regisztráció</button>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>

    <div id="ajanlat-zone" class="ajanlat-section">
        <div class="ajanlat-card">
            <h2 class="ajanlat-title"><i class="fas fa-paper-plane text-primary mr-2"></i> Kérjen Egyedi Ajánlatot</h2>
            <p class="ajanlat-subtitle">Küldje el elképzeléseit aszinkron módon, és munkatársunk azonnal feldolgozza azt.</p>

            <div id="form-alert" class="custom-alert d-none"></div>

            <form id="ajax-ajanlatkeres-form" class="minimal-form" action="javascript:void(0);" method="post">
                <div class="form-group">
                    <label class="form-label">Az Ön Neve</label>
                    <input type="text" name="nev" class="form-input" placeholder="Pl.: Teszt Máté" required>
                </div>

                <div class="form-group">
                    <label class="form-label">E-mail Címe</label>
                    <input type="email" name="email" class="form-input" placeholder="minta@email.com" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Projekt Tárgya</label>
                    <input type="text" name="targy" class="form-input" placeholder="Pl.: AI ügyfélszolgálati automatizáció" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Projekt Típusa</label>
                    <select name="tipus" class="form-input" style="height: 52px; appearance: none; -webkit-appearance: none; background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%2386868b%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E'); background-repeat: no-repeat; background-position: right 16px top 50%; background-size: 12px auto;" required>
                        <option value="" disabled selected>Válasszon szolgáltatást...</option>
                        <option value="ai">AI Automatizáció Projekt</option>
                        <option value="3d">3D Tervezés Projekt</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Projekt Rövid Leírása</label>
                    <textarea name="leiras" rows="4" class="form-input" style="height: auto; resize: none;" placeholder="Írja le röviden a projekt elképzeléseit és céljait..." required></textarea>
                </div>

                <button type="submit" id="submit-btn" class="btn-auth" style="background: #0071e3;">
                    <i class="fas fa-paper-plane mr-1"></i> Ajánlatkérés Elküldése
                </button>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function switchAuth(type) {
    const loginForm = document.getElementById('form-login');
    const registerForm = document.getElementById('form-register');
    const loginTab = document.getElementById('tab-login');
    const registerTab = document.getElementById('tab-register');

    if (type === 'login') {
        loginForm.style.display = 'block';
        registerForm.style.display = 'none';
        loginTab.classList.add('active');
        registerTab.classList.remove('active');
    } else {
        loginForm.style.display = 'none';
        registerForm.style.display = 'block';
        loginTab.classList.remove('active');
        registerTab.classList.add('active');
    }
}

$(document).ready(function () {
    const urlParams = new URLSearchParams(window.location.search);

    if (urlParams.has('type')) {
        let projectType = urlParams.get('type');
        let $selectField = $('select[name="tipus"]');

        if ($selectField.length > 0) {
            $selectField.val(projectType).trigger('change');
        }

        setTimeout(function () {
            let $target = $("#ajanlat-zone");
            if ($target.length > 0) {
                $('html, body').animate({
                    scrollTop: $target.offset().top - 40
                }, 800);
            }
        }, 200);
    }

    $('#ajax-ajanlatkeres-form').on('submit', function (e) {
        e.preventDefault();

        let $form = $(this);
        let $submitBtn = $('#submit-btn');
        let $alert = $('#form-alert');

        $submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Feldolgozás...');
        $alert.addClass('d-none').removeClass('alert-error alert-success').text('');

        $.ajax({
            url: '<?= Url::to(['ajanlatkeres/bekuldes']) ?>',
            type: 'POST',
            data: $form.serialize(),
            dataType: 'json',
            success: function (response) {
                $alert.removeClass('d-none alert-error alert-success');

                if (response.success) {
                    $alert.addClass('alert-success').text(response.message);
                    $form[0].reset();
                } else {
                    $alert.addClass('alert-error').text(response.message || 'A mentés sikertelen.');
                }
            },
            error: function () {
                $alert.removeClass('d-none alert-success').addClass('alert-error').text('Szerver kommunikációs hiba történt!');
            },
            complete: function () {
                $submitBtn.prop('disabled', false).html('<i class="fas fa-paper-plane mr-1"></i> Ajánlatkérés Elküldése');
            }
        });
    });
});
</script>