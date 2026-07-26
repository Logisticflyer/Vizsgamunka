<?php
/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

use yii\helpers\Html;
use yii\widgets\ListView;

$this->title = 'Beérkezett Ajánlatkérések (Leadek)';
?>

<div class="container-fluid py-4" style="max-width: 1400px;">
    
    <!-- Fejléc kártya -->
    <div class="p-4 mb-4 bg-white rounded-4 shadow-sm border border-light d-flex justify-content-between align-items-center">
        <div>
            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill mb-2 fw-bold" style="font-size: 12px;">Adminisztrációs Panel</span>
            <h2 class="fw-bold text-dark mb-0"><i class="fas fa-inbox text-primary me-2"></i> Beérkezett Ajánlatkérések</h2>
        </div>
        <div>
            <a href="<?= \yii\helpers\Url::to(['ai/automation']) ?>" class="btn btn-outline-secondary rounded-pill px-4 shadow-sm">
                <i class="fas fa-arrow-left me-2"></i> Vissza az Automatizációhoz
            </a>
        </div>
    </div>

    <!-- Táblázat kártya -->
    <div class="card shadow-sm border border-light rounded-4 bg-white overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-secondary text-uppercase fs-7" style="font-size: 12px; letter-spacing: 0.5px;">
                        <tr>
                            <th class="py-3 px-4">#ID</th>
                            <th class="py-3">Ügyfél / Cégnév</th>
                            <th class="py-3">E-mail Cím</th>
                            <th class="py-3">Projekt Leírása</th>
                            <th class="py-3">Dátum</th>
                            <th class="py-3 text-end px-4">Műveletek</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $models = $dataProvider->getModels();
                        if (empty($models)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">Még nincsenek beérkezett ajánlatkérések.</td>
                        </tr>
                        <?php else: foreach ($models as $model): ?>
                        <tr>
                            <td class="px-4 fw-bold text-secondary">#<?= $model->id ?></td>
                            <td class="fw-bold text-dark"><?= Html::encode($model->name) ?></td>
                            <td><a href="mailto:<?= Html::encode($model->email) ?>" class="text-decoration-none text-primary"><?= Html::encode($model->email) ?></a></td>
                            <td class="text-secondary text-truncate" style="max-width: 300px;">
                                <?= Html::encode($model->message) ?>
                            </td>
                            <td class="text-muted small"><?= Html::encode($model->created_at) ?></td>
                            <td class="text-end px-4">
                                <?php if (!empty($model->chat_context)): ?>
                                    <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm view-chat-btn" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#chatModal" 
                                            data-context="<?= Html::encode($model->chat_context) ?>">
                                        <i class="fas fa-robot me-1"></i> Chat Előzmény
                                    </button>
                                <?php else: ?>
                                    <span class="badge bg-light text-muted border px-3 py-2 rounded-pill">Nincs chat</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- MODAL: Chat Előzmény Megtekintése -->
<div class="modal fade" id="chatModal" tabindex="-1" aria-labelledby="chatModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            
            <div class="modal-header bg-dark text-white p-4 rounded-top-4">
                <h5 class="modal-title fw-bold" id="chatModalLabel"><i class="fas fa-robot text-primary me-2"></i> Ügyfél Chat Előzményei</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Bezárás"></button>
            </div>

            <div class="modal-body p-4 bg-light">
                <div class="p-3 bg-white rounded-4 border border-light shadow-sm">
                    <pre id="modal-chat-content" class="mb-0 text-secondary" style="white-space: pre-wrap; font-family: inherit; font-size: 14px; line-height: 1.6;"></pre>
                </div>
            </div>

            <div class="modal-footer bg-white p-3 rounded-bottom-4 border-top">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Bezárás</button>
            </div>

        </div>
    </div>
</div>

<!-- JavaScript a Chat adatok átadásához a Modalba -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const chatModal = document.getElementById('chatModal');
    chatModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const context = button.getAttribute('data-context');
        const modalContent = document.getElementById('modal-chat-content');
        modalContent.textContent = context;
    });
});
</script>