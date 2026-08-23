<?php
/** @var yii\web\View $this */
$this->title = 'AI Automatizációs Megoldások';

// Chart.js betöltése az interaktív diagramhoz
$this->registerJsFile('https://cdn.jsdelivr.net/npm/chart.js', ['position' => \yii\web\View::POS_HEAD]);
?>

<style>
/* SÖTÉT TÉMA ALAPSTÍLUSOK */
body, html {
    background-color: #0b0d12 !important;
    color: #f8fafc !important;
}

.dark-card {
    background: #141824 !important;
    border: 1px solid #1e293b !important;
    color: #f8fafc !important;
}

.dark-inner-box {
    background: #1e293b !important;
    border: 1px solid #334155 !important;
}

.text-slate-light {
    color: #cbd5e1 !important;
}

.text-slate-muted {
    color: #94a3b8 !important;
}

/* Chat Input & Buborékok */
.chat-bubble-bot {
    background-color: #1e293b !important;
    color: #f8fafc !important;
    border: 1px solid #334155 !important;
}

.chat-input-wrapper {
    background-color: #1e293b !important;
    border: 1px solid #334155 !important;
    transition: all 0.2s ease-in-out;
}

.chat-input-wrapper:focus-within {
    border-color: #38bdf8 !important;
    box-shadow: 0 0 0 0.25rem rgba(56, 189, 248, 0.25) !important;
    background: #1e293b !important;
}

#chat-input {
    color: #ffffff !important;
}

#chat-input::placeholder {
    color: #64748b !important;
}

/* Modal és Beviteli mezők Sötétítése */
.futuristic-modal .modal-content {
    background: #141824 !important;
    border: 1px solid #1e293b !important;
    color: #f8fafc !important;
}

.ai-preview-card {
    background: linear-gradient(135deg, rgba(13, 110, 253, 0.1) 0%, rgba(56, 189, 248, 0.15) 100%) !important;
    border: 1px solid rgba(56, 189, 248, 0.3) !important;
}

.dark-input {
    background-color: #1e293b !important;
    border: 1px solid #334155 !important;
    color: #ffffff !important;
}

.dark-input:focus {
    border-color: #38bdf8 !important;
    box-shadow: 0 0 0 0.25rem rgba(56, 189, 248, 0.25) !important;
}

.dark-input::placeholder {
    color: #64748b !important;
}

.chat-bubble {
    max-width: 85%;
    font-size: 14px;
    text-align: left;
    line-height: 1.6;
    white-space: pre-wrap;
    word-break: break-word;
}
</style>

<div class="container-fluid py-4" style="max-width: 1400px; background-color: #0b0d12;">
    <div class="row g-4">
        
        <!-- BAL OLDAL: Ismertető, Statisztikák, Diagram és CTA Gomb (8 oszlop) -->
        <div class="col-lg-8">
            
            <!-- Hero Szekció -->
            <div class="p-5 mb-4 dark-card rounded-4 shadow-sm position-relative overflow-hidden">
                <div class="position-absolute top-0 end-0 p-4 opacity-10 d-none d-md-block" style="z-index: 1;">
                    <i class="fas fa-brain text-primary" style="font-size: 130px;"></i>
                </div>
                
                <div class="position-relative" style="z-index: 2; padding-right: 140px;">
                    <span class="badge bg-primary bg-opacity-20 text-info border border-info border-opacity-30 px-3 py-2 rounded-pill mb-3 fw-bold" style="font-size: 12px;">Vállalati Innováció & AI Agent</span>
                    <h1 class="display-5 fw-bold text-white mb-3">AI Automatizáció a <span class="text-primary">Jövő Üzletéért</span></h1>
                    <p class="lead text-slate-light mb-4" style="font-size: 16px; line-height: 1.6;">
                        Szüntesd meg a monoton, ismétlődő feladatokat. Egyedi mesterséges intelligencia ügynökeink és munkafolyamat-automatizációink 24/7-ben dolgoznak a vállalkozásod hatékonyságáért.
                    </p>
                    <div class="d-flex gap-3">
                        <button type="button" class="btn btn-primary btn-lg px-4 rounded-pill shadow-sm fw-bold" data-bs-toggle="modal" data-bs-target="#offerModal">
                            <i class="fas fa-paper-plane me-2"></i> Ajánlatkérés Indítása
                        </button>
                    </div>
                </div>
            </div>

            <!-- Gyors Statisztika Kártyák -->
            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="p-4 dark-card rounded-4 shadow-sm text-center h-100 d-flex flex-column justify-content-center">
                        <div class="text-primary fs-2 fw-bold mb-1">73%</div>
                        <h6 class="fw-bold text-white mb-2" style="font-size: 14px;">Időmegtakarítás</h6>
                        <p class="text-slate-muted small mb-0">Adminisztratív folyamatok csökkenése.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 dark-card rounded-4 shadow-sm text-center h-100 d-flex flex-column justify-content-center">
                        <div class="text-success fs-2 fw-bold mb-1">24/7</div>
                        <h6 class="fw-bold text-white mb-2" style="font-size: 14px;">Folyamatos Működés</h6>
                        <p class="text-slate-muted small mb-0">Hibamentes futás felügyelet nélkül.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 dark-card rounded-4 shadow-sm text-center h-100 d-flex flex-column justify-content-center">
                        <div class="text-warning fs-2 fw-bold mb-1">99.8%</div>
                        <h6 class="fw-bold text-white mb-2" style="font-size: 14px;">Pontosság</h6>
                        <p class="text-slate-muted small mb-0">Adatfeldolgozás emberi hiba nélkül.</p>
                    </div>
                </div>
            </div>

            <!-- INTERAKTÍV DIAGRAM SZEKCIÓ -->
            <div class="p-4 mb-4 dark-card rounded-4 shadow-sm">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h4 class="fw-bold text-white mb-1"><i class="fas fa-chart-line text-primary me-2"></i> Piaci Elterjedés & Statisztikák</h4>
                        <p class="text-slate-light small mb-0">Az AI automatizációt bevezető cégek számának növekedése (ezres nagyságrendben).</p>
                    </div>
                </div>
                <hr class="border-secondary opacity-25">
                <div style="position: relative; height: 280px; width: 100%;">
                    <canvas id="adoptionChart"></canvas>
                </div>
            </div>

            <!-- Részletes Ismertető Kártya -->
            <div class="p-4 dark-card rounded-4 shadow-sm">
                <h4 class="fw-bold text-white mb-3"><i class="fas fa-cogs text-primary me-2"></i> Hogyan alakítjuk át a céged?</h4>
                <p class="text-slate-light" style="font-size: 15px; line-height: 1.6;">
                    A modern piac nem bocsátja meg a lassúságot. Azok a vállalkozások, amelyek manuálisan végzik az adatrögzítést és az ügyfélkezelést, jelentős versenyhátrányba kerülnek. Rendszereink zökkenőmentesen integrálódnak a meglévő szoftveres környezetedbe.
                </p>
                <div class="row g-3 mt-2">
                    <div class="col-md-6">
                        <div class="p-3 dark-inner-box rounded-3">
                            <h6 class="fw-bold text-white mb-1"><i class="fas fa-check-circle text-success me-2"></i> Dokumentumfeldolgozás</h6>
                            <p class="text-slate-muted small mb-0">Számlák és szerződések automatikus elemzése.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 dark-inner-box rounded-3">
                            <h6 class="fw-bold text-white mb-1"><i class="fas fa-check-circle text-success me-2"></i> Intelligens Routing</h6>
                            <p class="text-slate-muted small mb-0">Beérkező ügyféligények azonnali rendszerezése.</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- JOBB OLDAL: Dedikált Fókuszált AI Chatbot -->
        <div class="col-lg-4">
            <div style="position: sticky; top: 20px;">
                <div class="card shadow-sm rounded-4 dark-card overflow-hidden">
                    
                    <!-- Chat Header -->
                    <div class="card-header bg-dark text-white p-3 px-4 d-flex align-items-center justify-content-between border-bottom border-secondary">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary rounded-circle p-2 me-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 40px; height: 40px;">
                                <i class="fas fa-robot text-white fs-6"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold text-white" style="font-size: 15px;">Automatizációs Szakértő AI</h6>
                                <small style="font-size: 11px; color: #38bdf8 !important;">Kizárólag az automatizációról</small>
                            </div>
                        </div>
                    </div>

                    <!-- Chat Body / Messages -->
                    <div id="chat-messages" class="card-body p-3 px-4 overflow-auto" style="height: 450px; background: #0b0d12;">
                        <div class="mb-3 text-start">
                            <div class="d-inline-block p-3 rounded-4 chat-bubble-bot shadow-sm chat-bubble">Üdvözöllek! Én vagyok az AI Automatizációs asszisztensed. Kérdezz bátran a munkafolyamat-optimalizálásról, és ha készen állsz, kérj személyre szabott ajánlatot a bal oldali gombbal!</div>
                        </div>
                    </div>

                    <!-- Chat Input -->
                    <div class="card-footer p-3 px-4 dark-card border-top border-secondary">
                        <div class="chat-input-wrapper p-2 rounded-4 d-flex align-items-center shadow-sm">
                            <textarea id="chat-input" class="form-control border-0 bg-transparent px-2 shadow-none" rows="1" placeholder="Írd meg a kérdésed..." style="resize: none; max-height: 120px; overflow-y: auto; line-height: 1.5; font-size: 14px; outline: none;"></textarea>
                            <button class="btn btn-primary rounded-circle shadow-sm ms-2 flex-shrink-0 d-flex align-items-center justify-content-center" type="button" id="send-chat-btn" style="width: 38px; height: 38px;">
                                <i class="fas fa-paper-plane" style="font-size: 13px;"></i>
                            </button>
                        </div>
                        <div class="d-flex justify-content-end align-items-center mt-2 px-1 text-slate-muted" style="font-size: 10px;">
                            <span><kbd style="font-size: 9px; background: #334155; color: #cbd5e1; border-radius: 3px; padding: 1px 4px;">Enter</kbd> küldés &nbsp;|&nbsp; <kbd style="font-size: 9px; background: #334155; color: #cbd5e1; border-radius: 3px; padding: 1px 4px;">Shift+Enter</kbd> új sor</span>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>

<!-- MODAL: Futurisztikus Next-Level AI Ajánlatkérő -->
<div class="modal fade" id="offerModal" tabindex="-1" aria-labelledby="offerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-secondary shadow-lg overflow-hidden futuristic-modal">
            
            <div class="modal-header bg-dark text-white p-4 border-bottom border-secondary">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-25 text-primary rounded-circle p-2 me-3 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                        <i class="fas fa-brain fs-5"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0 text-white" id="offerModalLabel">Next-Level AI Ajánlatkérés</h5>
                        <small class="text-info" style="font-size: 12px;"><span class="spinner-grow spinner-grow-sm text-primary me-1" role="status"></span> AI Kontextus Valós Időben Szinkronizálva</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Bezárás"></button>
            </div>

            <form id="offerForm" action="<?= \yii\helpers\Url::to(['ai/automation']) ?>" method="POST">
                <div class="modal-body p-4" style="background-color: #0b0d12;">
                    <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>" value="<?= Yii::$app->request->csrfToken ?>" />
                    
                    <input type="hidden" id="chat_context_input" name="OfferForm[chat_context]" value="">

                    <!-- FUTURISZTIKUS AI PROJEKTKÁRTYA -->
                    <div class="p-4 mb-4 rounded-4 ai-preview-card shadow-sm">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="badge bg-primary text-white px-3 py-1 rounded-pill fw-bold" style="font-size: 11px;">
                                <i class="fas fa-sparkles me-1"></i> AI Asszisztens Által Generált Projektterv
                            </span>
                            <span class="text-info small"><i class="fas fa-sync-alt fa-spin me-1"></i> Élő szinkron</span>
                        </div>
                        <div id="ai-live-summary" class="text-slate-light small fst-italic" style="line-height: 1.6; white-space: pre-wrap;">"Még nem beszélgettél az AI asszisztenssel. Írd meg a céljaidat lent, vagy kérdezz tőle a jobb oldali chatben, és a rendszer automatikusan kiegészíti ezt a tervet!"</div>
                    </div>

                    <!-- Input Mezők -->
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-slate-light small">Neved / Cégneved <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-lg rounded-3 dark-input shadow-sm" name="OfferForm[name]" required placeholder="Pl. Kovács János">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-slate-light small">E-mail Cím <span class="text-danger">*</span></label>
                            <input type="email" class="form-control form-control-lg rounded-3 dark-input shadow-sm" name="OfferForm[email]" required placeholder="pelda@ceg.hu">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold text-slate-light small">Részletes Igény / Projekt leírása <span class="text-danger">*</span></label>
                            <textarea class="form-control rounded-3 dark-input shadow-sm" name="OfferForm[message]" id="offer_message" rows="5" required placeholder="Írd le a céljaidat..." style="line-height: 1.5;"></textarea>
                            <div class="form-text text-slate-muted" style="font-size: 11px;">Ezt a mezőt tetszés szerint átírhatod vagy kiegészítheted.</div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer dark-card p-3 rounded-bottom-4 border-top border-secondary">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4 text-slate-light" data-bs-dismiss="modal">Mégse</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-5 shadow-sm fw-bold">
                        <i class="fas fa-paper-plane me-2"></i> Ajánlatkérés Elküldése
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // 1. Chart.js diagram (Sötét témára igazított feliratokkal és ráccsal)
    const ctx = document.getElementById('adoptionChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['2023', '2024', '2025', '2026 (Jelenleg)'],
            datasets: [{
                label: 'Automatizációt használó vállalkozások (ezer)',
                data: [120, 260, 510, 890],
                borderColor: '#38bdf8',
                backgroundColor: 'rgba(56, 189, 248, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#38bdf8',
                pointRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { font: { family: 'inherit', size: 12 }, color: '#cbd5e1' }
                }
            },
            scales: {
                y: {
                    grid: { color: 'rgba(255,255,255,0.08)' },
                    ticks: { font: { family: 'inherit' }, color: '#cbd5e1' }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { family: 'inherit' }, color: '#cbd5e1' }
                }
            }
        }
    });

    // 2. Chat Állapot Menedzsment
    loadChatFromStorage();
});

const chatMessages = document.getElementById('chat-messages');
const chatInput = document.getElementById('chat-input');
const sendBtn = document.getElementById('send-chat-btn');
const offerMessage = document.getElementById('offer_message');
const chatContextInput = document.getElementById('chat_context_input');
const aiLiveSummary = document.getElementById('ai-live-summary');
const offerModal = document.getElementById('offerModal');
const offerForm = document.getElementById('offerForm');

let fullChatTranscript = "--- Chat Előzmény a Modalhoz ---\n";
let messageHistory = [];

const STORAGE_MESSAGES_KEY = 'nexus_automation_messages';
const STORAGE_TRANSCRIPT_KEY = 'nexus_automation_transcript';

chatInput.addEventListener('input', function() {
    this.style.height = 'auto';
    this.style.height = (this.scrollHeight) + 'px';
});

chatInput.addEventListener('keydown', function(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        sendQuery();
    }
});

function cleanMarkdown(text) {
    if (!text) return '';
    return text
        .replace(/\*\*/g, '')
        .replace(/\*/g, '')
        .replace(/###/g, '')
        .replace(/##/g, '')
        .replace(/#/g, '')
        .trim();
}

function saveChatToStorage() {
    sessionStorage.setItem(STORAGE_MESSAGES_KEY, JSON.stringify(messageHistory));
    sessionStorage.setItem(STORAGE_TRANSCRIPT_KEY, fullChatTranscript);
}

function loadChatFromStorage() {
    const savedTranscript = sessionStorage.getItem(STORAGE_TRANSCRIPT_KEY);
    const savedMessages = sessionStorage.getItem(STORAGE_MESSAGES_KEY);

    if (savedTranscript) {
        fullChatTranscript = savedTranscript;
        chatContextInput.value = fullChatTranscript;
    }

    if (savedMessages) {
        try {
            messageHistory = JSON.parse(savedMessages);
            messageHistory.forEach(msg => {
                appendMessageToDOM(msg.sender, msg.text, msg.isError);
            });
        } catch(e) {
            console.error("Hiba a chat memória betöltésekor:", e);
        }
    }
}

function clearChatStorage() {
    sessionStorage.removeItem(STORAGE_MESSAGES_KEY);
    sessionStorage.removeItem(STORAGE_TRANSCRIPT_KEY);
}

function appendMessageToDOM(sender, text, isError = false) {
    const isUser = sender === 'user';
    const alignClass = isUser ? 'text-end' : 'text-start';
    let bgClass = isUser ? 'bg-primary text-white' : 'chat-bubble-bot shadow-sm';
    
    if (isError) {
        bgClass = 'bg-danger text-white border-0 shadow-sm';
    }

    const cleanedText = cleanMarkdown(text);

    const msgDiv = document.createElement('div');
    msgDiv.className = `mb-3 ${alignClass}`;
    msgDiv.innerHTML = `<div class="d-inline-block p-3 rounded-4 ${bgClass} chat-bubble">${cleanedText}</div>`;
    chatMessages.appendChild(msgDiv);
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

function appendMessage(sender, text, isError = false) {
    const cleanedText = cleanMarkdown(text);
    appendMessageToDOM(sender, cleanedText, isError);

    if (!isError) {
        const isUser = sender === 'user';
        fullChatTranscript += (isUser ? "Felhasználó: " : "AI Szakértő: ") + cleanedText + "\n\n";
        chatContextInput.value = fullChatTranscript;

        messageHistory.push({ sender: sender, text: cleanedText, isError: isError });
        saveChatToStorage();
    }
}

function showTypingIndicator() {
    const typingDiv = document.createElement('div');
    typingDiv.id = 'typing-indicator';
    typingDiv.className = 'mb-3 text-start';
    typingDiv.innerHTML = `<div class="d-inline-block p-3 rounded-4 chat-bubble-bot shadow-sm chat-bubble"><span class="spinner-grow spinner-grow-sm text-primary me-1" role="status"></span><span class="spinner-grow spinner-grow-sm text-primary me-1" role="status"></span><span class="spinner-grow spinner-grow-sm text-primary me-2" role="status"></span><span class="small text-slate-light">Az AI szakértő gondolkodik...</span></div>`;
    chatMessages.appendChild(typingDiv);
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

function removeTypingIndicator() {
    const typingDiv = document.getElementById('typing-indicator');
    if (typingDiv) typingDiv.remove();
}

function sendQuery() {
    const text = chatInput.value.trim();
    if (!text) return;

    const previousHistory = fullChatTranscript;

    appendMessage('user', text);
    chatInput.value = '';
    chatInput.style.height = 'auto';
    
    showTypingIndicator();

    const bodyData = 'message=' + encodeURIComponent(text) + '&chatHistory=' + encodeURIComponent(previousHistory);

    fetch('<?= \yii\helpers\Url::to(['ai/chat-ajax']) ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-CSRF-Token': '<?= Yii::$app->request->getCsrfToken() ?>'
        },
        body: bodyData
    })
    .then(response => response.json())
    .then(data => {
        removeTypingIndicator();
        if (data.success) {
            appendMessage('bot', data.reply);
        } else {
            appendMessage('bot', 'Elnézést, hiba történt a válaszadás során.', true);
        }
    })
    .catch(error => {
        removeTypingIndicator();
        appendMessage('bot', 'Hálózati hiba lépett fel a kapcsolatban.', true);
    });
}

sendBtn.addEventListener('click', sendQuery);

// 3. MODAL MEGNYITÁSAKOR: Projekt-Specifikáció Elemzése
if (offerModal) {
    offerModal.addEventListener('show.bs.modal', function () {
        if (!fullChatTranscript || fullChatTranscript.trim() === "--- Chat Előzmény a Modalhoz ---") {
            return;
        }

        aiLiveSummary.innerHTML = '<span class="spinner-border spinner-border-sm text-primary me-2" role="status"></span> <em class="text-primary">AI projekt-specifikáció elemzése és generálása a beszélgetés alapján...</em>';

        fetch('<?= \yii\helpers\Url::to(['ai/summarize-chat']) ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-CSRF-Token': '<?= Yii::$app->request->getCsrfToken() ?>'
            },
            body: 'chatHistory=' + encodeURIComponent(fullChatTranscript)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.summary) {
                offerMessage.value = data.summary;
                aiLiveSummary.innerText = data.summary;
            }
        })
        .catch(err => {
            console.error("Összefoglaló hiba:", err);
            aiLiveSummary.innerText = "Hiba történt a projekt-specifikáció generálása során.";
        });
    });
}

// 4. Munkamenet törlése beküldéskor
if (offerForm) {
    offerForm.addEventListener('submit', function() {
        clearChatStorage();
    });
}
</script>