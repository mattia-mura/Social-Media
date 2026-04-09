<?php
// frontend/layout/stories.php
// Includi dopo header.php nelle pagine che mostrano le stories
//
// Richiede: $pdo (connessione DB)
// Mostra: gli utenti seguiti che hanno postato nelle ultime 24h

// TODO: sostituire con query reale
// Esempio query reale:
// $stmt = $pdo->prepare("
//     SELECT u.id, u.username, u.avatar_url
//     FROM follows f
//     JOIN users u ON u.id = f.followed_id
//     JOIN posts p ON p.user_id = u.id
//     WHERE f.follower_id = ?
//       AND p.created_at >= NOW() - INTERVAL 24 HOUR
//     GROUP BY u.id
//     ORDER BY MAX(p.created_at) DESC
//     LIMIT 15
// ");
// $stmt->execute([$sessionUser['id']]);
// $storyUsers = $stmt->fetchAll();

// Dati mock
$storyUsers = [
    ['id'=>2, 'username'=>'giulia_food',  'avatar_url'=>null, 'emoji'=>'🍝'],
    ['id'=>3, 'username'=>'luca_gourmet', 'avatar_url'=>null, 'emoji'=>'🍱'],
    ['id'=>4, 'username'=>'sofia_bakes',  'avatar_url'=>null, 'emoji'=>'🥐'],
    ['id'=>5, 'username'=>'toni_pizza',   'avatar_url'=>null, 'emoji'=>'🍕'],
    ['id'=>6, 'username'=>'chiara_veg',   'avatar_url'=>null, 'emoji'=>'🥗'],
    ['id'=>7, 'username'=>'marco_chef',   'avatar_url'=>null, 'emoji'=>'🍣'],
    ['id'=>8, 'username'=>'elena_dolci',  'avatar_url'=>null, 'emoji'=>'🍰'],
];
?>

<!-- ── STORIES ──────────────────────────────── -->
<div class="stories-wrap">
    <div class="stories">

        <!-- Aggiungi la tua story -->
        <div class="story">
            <a href="/frontend/new-post.php" class="story__add-ring" aria-label="Aggiungi post">＋</a>
            <span class="story__name">La tua</span>
        </div>

        <?php foreach ($storyUsers as $su): ?>
        <div class="story">
            <a href="/frontend/profile.php?user=<?= urlencode($su['username']) ?>"
               class="story__ring"
               aria-label="<?= htmlspecialchars($su['username']) ?>">
                <div class="story__avatar">
                    <?php if (!empty($su['avatar_url'])): ?>
                        <img src="<?= htmlspecialchars($su['avatar_url']) ?>"
                             alt="<?= htmlspecialchars($su['username']) ?>">
                    <?php else: ?>
                        <?= $su['emoji'] ?? '👤' ?>
                    <?php endif; ?>
                </div>
            </a>
            <span class="story__name"><?= htmlspecialchars($su['username']) ?></span>
        </div>
        <?php endforeach; ?>

    </div>
</div>
<!-- /stories -->
