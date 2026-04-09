<?php
// frontend/layout/footer.php
// Includi questo file in fondo a ogni pagina protetta
// $currentPage è già definita in header.php
?>

<!-- ── BOTTOM NAV ──────────────────────────── 
<nav class="bottomnav">
    <a href="/frontend/feed.php"
       class="nav-item <?= $currentPage === 'feed' ? 'active' : '' ?>"
       aria-label="Home">
        <span class="nav-icon">🏠</span>
        <span>Home</span>
    </a>

    <a href="/frontend/explore.php"
       class="nav-item <?= $currentPage === 'explore' ? 'active' : '' ?>"
       aria-label="Esplora">
        <span class="nav-icon">🔍</span>
        <span>Esplora</span>
    </a>

    <!-- FAB: nuova recensione 
    <div class="fab-wrap">
        <a href="/frontend/new-post.php" class="fab" aria-label="Nuova recensione">＋</a>
    </div>

    <a href="/frontend/notifications.php"
       class="nav-item <?= $currentPage === 'notifications' ? 'active' : '' ?>"
       aria-label="Attività">
        <span class="nav-icon">❤️</span>
        <span>Attività</span>
    </a>

    <a href="/frontend/profile.php?user=<?= urlencode($sessionUser['username']) ?>"
       class="nav-item <?= $currentPage === 'profile' ? 'active' : '' ?>"
       aria-label="Profilo">
        <span class="nav-icon">👤</span>
        <span>Profilo</span>
    </a>
</nav>
<!-- /bottomnav 

<!-- JS pagina specifica 
<?php if (isset($pageJS)): ?>
    <script src="/frontend/assets/js/<?= htmlspecialchars($pageJS) ?>"></script>
<?php endif; ?>

</body>
</html>
