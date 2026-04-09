 <?php
// frontend/feed.php
// Pagina principale — feed dei post

$pageTitle = 'Feed';
$pageCSS   = 'feed.css';
$pageJS    = 'feed.js';

// Connessione DB
require_once __DIR__ . '/../backend/config/Database.php';
$pdo = Database::getInstance()->getConnection();

// Header (include controllo sessione + apertura HTML)
require_once __DIR__ . '/layout/header.php';

// ── QUERY FEED ──────────────────────────────
// Mostra i post degli utenti seguiti + i propri, ordinati per data
// TODO: attivare questa query quando il backend è pronto
/*
$stmt = $pdo->prepare("
    SELECT
        p.id,
        p.title_work,
        p.content,
        p.rating,
        p.cuisine_type,
        p.image_path,
        p.likes_count,
        p.comments_count,
        p.created_at,
        u.id         AS user_id,
        u.username,
        u.avatar_url,
        EXISTS (
            SELECT 1 FROM likes l
            WHERE l.post_id = p.id AND l.user_id = :me
        ) AS liked,
        EXISTS (
            SELECT 1 FROM saves s
            WHERE s.post_id = p.id AND s.user_id = :me2
        ) AS saved
    FROM posts p
    JOIN users u ON u.id = p.user_id
    WHERE p.user_id = :me3
       OR p.user_id IN (
           SELECT followed_id FROM follows WHERE follower_id = :me4
       )
    ORDER BY p.created_at DESC
    LIMIT 20
");
$stmt->execute([
    'me'  => $sessionUser['id'],
    'me2' => $sessionUser['id'],
    'me3' => $sessionUser['id'],
    'me4' => $sessionUser['id'],
]);
$posts = $stmt->fetchAll();
*/

// Dati mock — rimuovere quando il backend è attivo
$posts = [
    [
        'id'             => 1,
        'user_id'        => 2,
        'username'       => 'giulia_food',
        'avatar_url'     => null,
        'title_work'     => 'Tagliatelle al Ragù della Nonna',
        'content'        => 'Finalmente ho trovato la trattoria perfetta nel centro storico. Il ragù cuoce 4 ore, si sente tutto il sapore autentico. Pasta tirata a mano, pomodoro San Marzano. Un\'esperienza da ripetere assolutamente.',
        'cuisine_type'   => 'Cucina Bolognese',
        'rating'         => 5,
        'likes_count'    => 142,
        'comments_count' => 18,
        'image_path'     => null,
        'created_at'     => '2 ore fa',
        'liked'          => false,
        'saved'          => false,
    ],
    [
        'id'             => 2,
        'user_id'        => 3,
        'username'       => 'luca_gourmet',
        'avatar_url'     => null,
        'title_work'     => 'Sushi Omakase — Sakura',
        'content'        => 'Il nuovo locale giapponese in via Indipendenza è una sorpresa. Lo chef sceglie 12 pezzi al momento in base al pescato del giorno. Prezzo onesto, qualità altissima.',
        'cuisine_type'   => 'Cucina Giapponese',
        'rating'         => 4,
        'likes_count'    => 87,
        'comments_count' => 9,
        'image_path'     => null,
        'created_at'     => '5 ore fa',
        'liked'          => true,
        'saved'          => false,
    ],
    [
        'id'             => 3,
        'user_id'        => 4,
        'username'       => 'sofia_bakes',
        'avatar_url'     => null,
        'title_work'     => 'Croissant al Burro — Forno Storico',
        'content'        => 'Sfogliatura perfetta, burro di qualità, crosta che scrocchia. Questo forno merita ogni fila della mattina. Abbinato al cappuccino diventa una cerimonia.',
        'cuisine_type'   => 'Pasticceria',
        'rating'         => 5,
        'likes_count'    => 210,
        'comments_count' => 31,
        'image_path'     => null,
        'created_at'     => 'ieri',
        'liked'          => false,
        'saved'          => true,
    ],
];

// Helper stelle rating
function renderStars(int $rating): string {
    $html = '<span class="stars" aria-label="' . $rating . ' stelle su 5">';
    for ($i = 1; $i <= 5; $i++) {
        $html .= '<span class="star' . ($i <= $rating ? ' filled' : '') . '" aria-hidden="true">★</span>';
    }
    $html .= '</span>';
    return $html;
}

// Helper tempo relativo (da usare con dati reali)
function timeAgo(string $datetime): string {
    $diff = time() - strtotime($datetime);
    if ($diff < 60)     return 'adesso';
    if ($diff < 3600)   return floor($diff/60) . ' min fa';
    if ($diff < 86400)  return floor($diff/3600) . ' ore fa';
    if ($diff < 604800) return floor($diff/86400) . ' giorni fa';
    return date('d M', strtotime($datetime));
}
?>

 Stories 
<?php require_once __DIR__ . '/layout/stories.php'; ?>

 ── FEED ────────────────────────────────── 
<main class="feed" id="feed">

    <?php if (empty($posts)): ?>
        <div style="text-align:center; padding: 60px 20px; color: var(--brown-light);">
            <div style="font-size:48px; margin-bottom:12px;">🍽️</div>
            <p style="font-size:15px;">Nessun post ancora.<br>Segui qualcuno o pubblica la tua prima recensione!</p>
            <a href="/frontend/explore.php" class="btn-primary" style="margin-top:20px; max-width:200px;">
                Esplora
            </a>
        </div>
    <?php else: ?>

        <?php foreach ($posts as $post): ?>
        <article class="card" id="post-<?= $post['id'] ?>">

             Header card 
            <div class="card__header">
                <a href="/frontend/profile.php?user=<?= urlencode($post['username']) ?>"
                   class="card__avatar"
                   aria-label="Profilo di <?= htmlspecialchars($post['username']) ?>">
                    <?php if (!empty($post['avatar_url'])): ?>
                        <img src="<?= htmlspecialchars($post['avatar_url']) ?>"
                             alt="<?= htmlspecialchars($post['username']) ?>">
                    <?php else: ?>
                        <?= mb_strtoupper(mb_substr($post['username'], 0, 1)) ?>
                    <?php endif; ?>
                </a>

                <div class="card__user-info">
                    <a href="/frontend/profile.php?user=<?= urlencode($post['username']) ?>"
                       class="card__username">
                        <?= htmlspecialchars($post['username']) ?>
                    </a>
                    <div class="card__meta">
                        <?php if (!empty($post['cuisine_type'])): ?>
                            <span class="card__cuisine"><?= htmlspecialchars($post['cuisine_type']) ?></span>
                        <?php endif; ?>
                        <span class="card__time"><?= htmlspecialchars($post['created_at']) ?></span>
                    </div>
                </div>

                <button class="card__more"
                        aria-label="Opzioni post"
                        onclick="openPostMenu(<?= $post['id'] ?>, <?= $post['user_id'] ?>)">
                    ···
                </button>
            </div>

             Immagine 
            <div class="card__image">
                <?php if (!empty($post['image_path'])): ?>
                    <img src="<?= htmlspecialchars($post['image_path']) ?>"
                         alt="<?= htmlspecialchars($post['title_work']) ?>"
                         loading="lazy">
                <?php else: ?>
                    🍽️
                <?php endif; ?>
            </div>

             Corpo 
            <div class="card__body">
                <h2 class="card__title"><?= htmlspecialchars($post['title_work']) ?></h2>
                <?= renderStars((int)$post['rating']) ?>
                <p class="card__content" id="content-<?= $post['id'] ?>">
                    <?= htmlspecialchars($post['content']) ?>
                </p>
                <button class="card__readmore"
                        onclick="toggleReadMore(this, <?= $post['id'] ?>)">
                    Leggi tutto →
                </button>
            </div>

             Azioni 
            <div class="card__actions">
                <button class="action-btn <?= $post['liked'] ? 'liked' : '' ?>"
                        data-action="like"
                        data-post-id="<?= $post['id'] ?>"
                        aria-label="<?= $post['liked'] ? 'Rimuovi like' : 'Metti like' ?>"
                        onclick="toggleLike(this)">
                    <span class="icon"><?= $post['liked'] ? '❤️' : '🤍' ?></span>
                    <span class="count"><?= number_format($post['likes_count']) ?></span>
                </button>

                <a href="/frontend/post.php?id=<?= $post['id'] ?>#comments"
                   class="action-btn" aria-label="Commenti">
                    <span class="icon">💬</span>
                    <span><?= $post['comments_count'] ?></span>
                </a>

                <button class="action-btn <?= $post['saved'] ? 'saved' : '' ?>"
                        data-action="save"
                        data-post-id="<?= $post['id'] ?>"
                        aria-label="Salva"
                        onclick="toggleSave(this)">
                    <span class="icon"><?= $post['saved'] ? '🔖' : '🔖' ?></span>
                </button>

                <button class="action-btn share"
                        aria-label="Condividi"
                        onclick="sharePost(<?= $post['id'] ?>, '<?= addslashes(htmlspecialchars($post['title_work'])) ?>')">
                    <span class="icon">↗</span>
                    <span>Condividi</span>
                </button>
            </div>

        </article>
        <?php endforeach; ?>

    <?php endif; ?>
</main>
 /feed 

 Footer (bottom nav + chiusura HTML) 
<?php require_once __DIR__ . '/layout/footer.php'; ?> 
