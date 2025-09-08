<?php
session_start();
require_once __DIR__ . '/config/db.php';
$sessionUserId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
$viewedUserId = isset($_GET['user_id']) ? (int)$_GET['user_id'] : $sessionUserId;
if (!$viewedUserId) {
    // No target to show; require login for own profile
    header('Location: login.php');
    exit();
}
$isPublic = ($sessionUserId === 0) || ($viewedUserId !== $sessionUserId);

// Fetch display user info
$u = null;
try {
    $stmt = $conn->prepare('SELECT id, nick, dob FROM users WHERE id = :id');
    $stmt->execute([':id' => $viewedUserId]);
    $u = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (Throwable $e) {
    $u = null;
}
if (!$u) {
    http_response_code(404);
    echo 'User not found';
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- TO JEST KLUCZ -->
    <title>DeckHeaven - Profile</title>
    <link rel="stylesheet" href="/public/css/globals.css" />
    <link rel="stylesheet" href="/public/css/profile.css" />
</head>
<body>
    <header>
        <h1 class="logo">DeckHeaven</h1>
        <!-- Search bar do profili -->
        <div class="search-bar" style="position:relative;">
            <input type="text" id="profile-search" placeholder="Search Profiles" autocomplete="off">
            <i class="fa fa-search"></i>
            <div id="profile-search-results" style="position:absolute;top:110%;left:0;width:100%;background:#222;border-radius:0 0 1rem 1rem;z-index:10;display:none;max-height:200px;overflow-y:auto;"></div>
        </div>
        <?php if (!$isPublic): ?>
            <a class="logout-btn" href="/logout.php" title="Log out">Log out</a>
        <?php endif; ?>
    </header>

    <div class="layout">
        <!-- Lewy panel profilowy -->
        <aside class="sidebar">
            <img src="https://avatars.githubusercontent.com/u/583231?v=4" alt="Profile picture">
            <h2><?php echo htmlspecialchars($u['nick']); ?></h2>
            <p><?php echo $isPublic ? 'This is a public profile.' : 'Welcome to your profile!'; ?></p>

            <!-- Search bar do kart w klaserze -->
            <?php if (!$isPublic): ?>
                <div class="search-bar" style="position:relative;">
                    <input type="text" id="card-search" placeholder="Search cards" autocomplete="off">
                    <i class="fa fa-search"></i>
                    <div id="card-search-results" style="position:absolute;top:110%;left:0;width:100%;background:#222;border-radius:0 0 1rem 1rem;z-index:10;display:none;max-height:260px;overflow-y:auto;"></div>
                </div>
            <?php endif; ?>
        </aside>

        <!-- Panel z kartami -->
        <main class="content">
            <div id="my-cards" class="cards-grid"></div>
        </main>
    </div>
</body>
<script>
const searchInput = document.getElementById('profile-search');
const resultsBox = document.getElementById('profile-search-results');
let searchTimeout;
searchInput.addEventListener('input', function() {
    clearTimeout(searchTimeout);
    const q = this.value.trim();
    if (!q) {
        resultsBox.style.display = 'none';
        resultsBox.innerHTML = '';
        return;
    }
    searchTimeout = setTimeout(() => {
    fetch('/api/search_profiles.php?q=' + encodeURIComponent(q))
            .then(r => r.json())
            .then(data => {
                if (data.length === 0) {
                    resultsBox.innerHTML = '<div style="padding:0.5rem;color:#aaa;">No profiles found</div>';
                } else {
                    resultsBox.innerHTML = data.map(u => `<div style='padding:0.5rem;cursor:pointer;color:#fff;' onmouseover='this.style.background="#333"' onmouseout='this.style.background=""' onclick='window.location.href="profile.php?user_id=${u.id}"'>${u.nick}</div>`).join('');
                }
                resultsBox.style.display = 'block';
            });
    }, 250);
});
document.addEventListener('click', function(e) {
    if (!searchInput.contains(e.target) && !resultsBox.contains(e.target)) {
        resultsBox.style.display = 'none';
    }
});
</script>
<script>
// Cards search + add to collection
const isPublic = <?php echo $isPublic ? 'true' : 'false'; ?>;
const viewedUserId = <?php echo (int)$viewedUserId; ?>;
let cardInput = null, cardBox = null, cardTimeout;
if (!isPublic) {
    cardInput = document.getElementById('card-search');
    cardBox = document.getElementById('card-search-results');
    cardInput.addEventListener('input', function() {
        clearTimeout(cardTimeout);
        const q = this.value.trim();
        if (!q) { cardBox.style.display='none'; cardBox.innerHTML=''; return; }
        cardTimeout = setTimeout(()=>{
            fetch('/api/search_cards.php?q=' + encodeURIComponent(q))
                .then(r=>r.json())
                .then(list=>{
                    if (!Array.isArray(list) || list.length===0) {
                        cardBox.innerHTML = '<div style="padding:0.5rem;color:#aaa;">No cards</div>';
                    } else {
                        cardBox.innerHTML = list.map(c=>{
                            const img = c.image ? `<img src='${c.image}' style='height:40px;border-radius:4px;margin-right:8px;'>` : '';
                            return `<div style='display:flex;align-items:center;padding:0.5rem;gap:8px;cursor:pointer;color:#fff;' onmouseover='this.style.background=\"#333\"' onmouseout='this.style.background=\"\"' onclick='addCard(${JSON.stringify(c).replace(/\"/g, "&quot;")})'>${img}<div><div style=\"font-weight:600\">${c.name}</div><div style=\"color:#aaa;font-size:0.8rem\">${c.setName||''}</div></div></div>`;
                        }).join('');
                    }
                    cardBox.style.display='block';
                })
        }, 300);
    });
    document.addEventListener('click', (e)=>{
        if (!cardInput.contains(e.target) && !cardBox.contains(e.target)) {
            cardBox.style.display='none';
        }
    });
    function addCard(card){
        fetch('/api/add_card.php', {method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify(card)})
            .then(r=>r.json())
            .then(res=>{ if (cardBox) cardBox.style.display='none'; loadMyCards(); });
    }
}

function loadMyCards(){
        const url = isPublic ? ('/api/get_cards.php?user_id=' + encodeURIComponent(viewedUserId)) : '/api/get_cards.php';
    fetch(url)
        .then(r=>r.json())
        .then(cards=>{
            const wrap = document.getElementById('my-cards');
            if (!Array.isArray(cards) || cards.length===0){ wrap.innerHTML = '<div style="color:#aaa;">No cards yet. Search above to add some.</div>'; return; }
                    wrap.innerHTML = cards.map(c=>{
                        const src = c.image_url || (c.multiverseid ? `https://gatherer.wizards.com/Handlers/Image.ashx?multiverseid=${c.multiverseid}&type=card` : '');
                        const removeBtn = !isPublic ? `<div class='remove-btn' data-card-id='${c.card_id}' title='Remove' aria-label='Remove'>&times;</div>` : '';
                        return `
                        <div class='card-thumb' style="position:relative;">
                            <img src='${src}' alt='${c.name}' style="display:block;width:100%;border-radius:0.5rem;">
                            <span class='qty-badge' style="position:absolute;left:6px;bottom:6px;background:rgba(0,0,0,0.7);color:#fff;padding:2px 6px;border-radius:6px;font-size:0.8rem;opacity:0;transition:opacity .15s;">x${c.quantity||1}</span>
                            ${removeBtn}
                        </div>`;
                    }).join('');
        });
}
loadMyCards();
</script>
<script>
// Show quantity badge on hover
document.addEventListener('mouseover', function(e){
    const thumb = e.target.closest('.card-thumb');
    if (thumb){ const b = thumb.querySelector('.qty-badge'); if (b) b.style.opacity = '1'; }
});
document.addEventListener('mouseout', function(e){
    const thumb = e.target.closest('.card-thumb');
    if (thumb){ const b = thumb.querySelector('.qty-badge'); if (b) b.style.opacity = '0'; }
});

// Handle remove clicks (own profile only)
document.addEventListener('click', function(e){
    const btn = e.target.closest('.remove-btn');
    if (!btn) return;
    if (isPublic) return; // safety
    e.preventDefault();
    e.stopPropagation();
    const cardId = btn.getAttribute('data-card-id');
    if (!cardId) return;
    btn.style.pointerEvents = 'none';
    fetch('/api/remove_card.php', { method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify({ card_id: cardId }) })
        .then(r=>r.json())
        .then(() => { loadMyCards(); })
        .catch(()=>{})
        .finally(()=>{ btn.style.pointerEvents = ''; });
});
</script>
</html>