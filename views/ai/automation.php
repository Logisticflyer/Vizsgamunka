<?php
/** @var yii\web\View $this */
$this->title = 'AI Automatizációs Megoldások';
?>

<div class="container-fluid py-4">
    <div class="row">
        
        <!-- BAL OLDAL: Ismertető, Statisztikák, Fun Factek és Okos Űrlap (8 oszlop) -->
        <div class="col-lg-8 mb-4">
            
            <!-- Hero Szekció -->
            <div class="p-5 mb-4 bg-white rounded-4 shadow-sm border">
                <h1 class="display-5 fw-bold text-dark mb-3">AI Automatizáció a <span class="text-primary">Jövő Üzletéért</span></h1>
                <p class="lead text-secondary mb-4">
                    Szüntesd meg a monoton, ismétlődő feladatokat. Egyedi mesterséges intelligencia ügynökeink és munkafolyamat-automatizációink 24/7-ben dolgoznak a vállalkozásod hatékonyságáért.
                </p>
                <div class="d-flex gap-3">
                    <a href="#offer-form" class="btn btn-primary btn-lg px-4 rounded-pill shadow-sm">
                        <i class="fas fa-paper-plane me-2"></i> Ajánlatkérés
                    </a>
                </div>
            </div>

            <!-- Statisztikák / Fun Factek -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="p-4 bg-white rounded-4 shadow-sm border text-center h-100">
                        <div class="text-primary fs-1 fw-bold mb-2">73%</div>
                        <h6 class="fw-bold text-dark">Időmegtakarítás</h6>
                        <p class="text-muted small mb-0">Az adminisztratív folyamatok átlagos csökkenése az első hónapban.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 bg-white rounded-4 shadow-sm border text-center h-100">
                        <div class="text-success fs-1 fw-bold mb-2">24/7</div>
                        <h6 class="fw-bold text-dark">Folyamatos Működés</h6>
                        <p class="text-muted small mb-0">A rendszerek emberi felügyelet nélkül, hibamentesen futnak.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 bg-white rounded-4 shadow-sm border text-center h-100">
                        <div class="text-warning fs-1 fw-bold mb-2">0%</div>
                        <h6 class="fw-bold text-dark">Adatvesztési Rákfene</h6>
                        <p class="text-muted small mb-0">Biztonságos, titkosított adattovábbítás és API kezelés.</p>
                    </div>
                </div>
            </div>

            <!-- Részletes Ismertető -->
            <div class="p-4 mb-4 bg-white rounded-4 shadow-sm border">
                <h3 class="fw-bold text-dark mb-3"><i class="fas fa-cogs text-primary me-2"></i> Hogyan alakítjuk át a céged?</h3>
                <p class="text-secondary">
                    A modern piac nem bocsátja meg a lassúságot. Azok a cégek, amelyek manuálisan rögzítik az adatokat vagy válaszolnak az ügyfelekre, hamar lemaradnak. Rendszereink zökkenőmentesen kapcsolódnak össze a meglévő szoftvereiddel (CRM, webshop, adatbázisok).
                </p>
                <ul class="list-unstyled text-secondary">
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Egyedi prompt-alapú dokumentumfeldolgozás</li>
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Automatikus számla- és adatrögzítés</li>
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Intelligens ügyfélszolgálati routing</li>
                </ul>
            </div>

            <!-- SMART AJÁNLATKÉRŐ ŰRLAP -->
            <div id="offer-form" class="p-5 bg-white rounded-4 shadow-sm border">
                <h3 class="fw-bold text-dark mb-2">Kérj Személyre Szabott Ajánlatot</h3>
                <p class="text-muted small mb-4">Ha beszélgettél a jobb oldali AI asszisztenssel, a chatbot kontextusa automatikusan hozzácsatolódik az üzenetedhez!</p>

                <form action="<?= \yii\helpers\Url::to(['ai/automation']) ?>" method="POST">
                    <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>" value="<?= Yii::$app->request->csrfToken ?>" />
                    
                    <!-- Rejtett mező a chat kontextusnak -->
                    <input type="hidden" id="chat_context_input" name="OfferForm[chat_context]" value="">

                    <div class="mb-3">
                        <label class="form-label fw-bold">Neved / Cégneved</label>
                        <input type="text" class="form-control form-control-lg rounded-3" name="OfferForm[name]" required placeholder="Pl. Kovács János">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">E-mail Cím</label>
                        <input type="email5" class="form-control form-control-lg rounded-3" name="OfferForm[email]" required placeholder="pelda@ceg.hu">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Milyen automatizációra lenne szükséged?</label>
                        <textarea class="form-control form-control-lg rounded-3" name="OfferForm[message]" id="offer_message" rows="4" required placeholder="Írd le röviden a projektet, vagy használd a jobb oldali AI chatet, ami kitölti neked..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100 rounded-3 shadow-sm py-3">
                        <i class="fas fa-paper-plane me-2"></i> Ajánlatkérés Elküldése
                    </button>
                </form>
            </div>

        </div>

        <!-- JOBB OLDAL: Dedikált Fókuszált AI Chatbot (Sticky Sidebar - 4 oszlop) -->
        <div class="col-lg-4">
            <div class="sticky-top" style="top: 20px;">
                <div class="card shadow-sm border rounded-4 bg-white overflow-hidden">
                    
                    <!-- Chat Header -->
                    <div class="card-header bg-dark text-white p-3 d-flex align-items-center">
                        <div class="bg-primary rounded-circle p-2 me-2 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                            <i class="fas fa-robot text-white"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold">Automatizációs Szakértő AI</h6>
                            <small class="text-muted" style="font-size: 11px; color: #a1a1a6 !important;">Csak az automatizációról</small>
                        </div>
                    </div>

                    <!-- Chat Body / Messages -->
                    <div id="chat-messages" class="card-body p-3 overflow-auto" style="height: 400px; background: #fbfbfd;">
                        <div class="mb-3 text-start">
                            <div class="d-inline-block p-3 rounded-4 bg-white shadow-sm border text-dark" style="max-size: 85%; font-size: 14px;">
                                Üdvözöllek! Én vagyok az AI Automatizációs asszisztensed. Kérdezz bátran a munkafolyamat-optimalizálásról, és ha készen állsz, a beszélgetésünket átviheted az ajánlatkérőbe!
                            </div>
                        </div>
                    </div>

                    <!-- Chat Input -->
                    <div class="card-footer bg-white p-3 border-top">
                        <div class="input-group">
                            <input type="text" id="chat-input" class="form-control rounded-start-pill border-end-0" placeholder="Kérdezz az automatizációról...">
                            <button class="btn btn-primary rounded-end-pill px-3" type="button" id="send-chat-btn">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>

<!-- JavaScript a Chat és az Okos Űrlap összekötéséhez -->
<script>
const chatMessages = document.getElementById('chat-messages');
const chatInput = document.getElementById('chat-input');
const sendBtn = document.getElementById('send-chat-btn');
const offerMessage = document.getElementById('offer_message');
const chatContextInput = document.getElementById('chat_context_input');

let fullChatTranscript = "--- Chat Előzmény ---\n";

function appendMessage(sender, text) {
    const isUser = sender === 'user';
    const alignClass = isUser ? 'text-end' : 'text-start';
    const bgClass = isUser ? 'bg-primary text-white' : 'bg-white text-dark border shadow-sm';

    const msgDiv = document.createElement('div');
    msgDiv.className = `mb-3 ${alignClass}`;
    msgDiv.innerHTML = `<div class="d-inline-block p-3 rounded-4 ${bgClass}" style="max-width: 85%; font-size: 14px; text-align: left;">${text}</div>`;
    chatMessages.appendChild(msgDiv);
    chatMessages.scrollTop = chatMessages.scrollHeight;

    // Kontextus gyűjtése a háttérben az űrlaphoz
    fullChatTranscript += (isUser ? "Felhasználó: " : "AI Szakértő: ") + text + "\n";
    chatContextInput.value = fullChatTranscript;
    
    // Opcionálisan beírhatjuk az üzenetmezőbe is összefoglalóként
    if (!isUser) {
        offerMessage.value = "A chatbot-al folytatott konzultáció alapján ehhez a témához kérek ajánlatot:\n" + text;
    }
}

function sendQuery() {
    const text = chatInput.value.trim();
    if (!text) return;

    appendMessage('user', text);
    chatInput.value = '';

    // AJAX hívás a szerver felé
    fetch('<?= \yii\helpers\Url::to(['ai/chat-ajax']) ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-CSRF-Token': '<?= Yii::$app->request->getCsrfToken() ?>'
        },
        body: 'message=' + encodeURIComponent(text)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            appendMessage('bot', data.reply);
        } else {
            appendMessage('bot', 'Hiba történt a válaszadás során.');
        }
    })
    .catch(error => {
        appendMessage('bot', 'Hálózati hiba lépett fel.');
    });
}

sendBtn.addEventListener('click', sendQuery);
chatInput.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        sendQuery();
    }
});
</script>