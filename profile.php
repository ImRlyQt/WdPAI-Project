<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- TO JEST KLUCZ -->
    <title>DeckHeaven - Profile</title>
    <link rel="stylesheet" href="./public/css/globals.css" />
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #101010;
            color: white;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* HEADER */
        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            padding: 1rem 2rem;
            font-size: 2rem;
            font-family: 'UnifrakturCook', cursive;
            background-color: #000;
            flex-shrink: 0;
        }

        header .logo {
            flex: 1;
        }

        .search-bar {
            display: flex;
            align-items: center;
            background: #ffbefb;
            padding: 0.25rem 0.5rem;
            border-radius: 2rem;
            width: 250px;
        }

        .search-bar input {
            flex: 1;
            border: none;
            outline: none;
            padding: 0.5rem;
            border-radius: 2rem;
            background: transparent;
            color: white;
            font-size: 0.9rem;
        }

        .search-bar i {
            margin-left: 0.5rem;
            color: white;
        }

        /* LAYOUT */
        .layout {
            display: flex;
            flex: 1;
            height: calc(100vh - 4rem);
            overflow: hidden;
            /* scroll tylko w panelu z kartami */
        }

        /* SIDEBAR */
        .sidebar {
            width: 250px;
            background: linear-gradient(180deg, #8497e9, #8497e9, #980597);
            padding: 2rem 1rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            flex-shrink: 0;
        }

        .sidebar img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            margin-bottom: 1rem;
        }

        .sidebar h2 {
            margin: 0.5rem 0;
            font-size: 1.25rem;
        }

        .sidebar p {
            text-align: center;
            font-size: 0.9rem;
            color: #eee;
            margin-bottom: 1.5rem;
        }

        .sidebar .search-bar {
            width: 100%;
            margin-bottom: 1.5rem;
            background: #ffbefb;
        }

        .sidebar .search-bar input {
            font-size: 0.85rem;
        }

        /* CONTENT */
        .content {
            flex: 1;
            background-color: #1a1a1a;
            /* border-radius: 1rem 0 0 1rem; */
            padding: 2rem;
            overflow-y: auto;
        }

        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 1rem;
        }

        .cards-grid img {
            width: 100%;
            border-radius: 0.5rem;
            transition: transform 0.2s;
        }

        .cards-grid img:hover {
            transform: scale(1.05);
        }

        /* RESPONSYWNOŚĆ */
        @media (max-width: 768px) {
            header {
                flex-direction: column;
                align-items: flex-start;
            }

            header .search-bar {
                width: 100%;
                margin-top: 0.5rem;
            }

            .layout {
                flex-direction: column;
                height: auto;
            }

            .sidebar {
                width: 100%;
                flex-direction: column;
                align-items: center;
            }

            .content {
                border-radius: 0;
                padding: 1rem;
            }

            .cards-grid {
                grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            }
        }

        @media (max-width: 480px) {
            .cards-grid {
                grid-template-columns: repeat(3, 1fr);
                /* 1 karta w rzędzie na bardzo małym ekranie */
            }

            .sidebar img {
                width: 90px;
                height: 90px;
            }
        }
    </style>
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
    </header>

    <div class="layout">
        <!-- Lewy panel profilowy -->
        <aside class="sidebar">
            <img src="https://avatars.githubusercontent.com/u/583231?v=4" alt="Profile picture">
            <h2><?php echo htmlspecialchars($_SESSION['nick']); ?></h2>
            <p>Welcome to your profile!</p>

            <!-- Search bar do kart w klaserze -->
            <div class="search-bar">
                <input type="text" placeholder="Search cards">
                <i class="fa fa-search"></i>
            </div>
        </aside>

        <!-- Panel z kartami -->
        <main class="content">
            <div class="cards-grid">
                <img src="https://gatherer.wizards.com/Handlers/Image.ashx?multiverseid=73908&type=card">


            </div>
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
        fetch('search_profiles.php?q=' + encodeURIComponent(q))
            .then(r => r.json())
            .then(data => {
                if (data.length === 0) {
                    resultsBox.innerHTML = '<div style="padding:0.5rem;color:#aaa;">No profiles found</div>';
                } else {
                    resultsBox.innerHTML = data.map(u => `<div style='padding:0.5rem;cursor:pointer;color:#fff;' onmouseover='this.style.background="#333"' onmouseout='this.style.background=""' onclick='window.location.href="public_profile.php?user_id=${u.id}"'>${u.nick}</div>`).join('');
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
</html>