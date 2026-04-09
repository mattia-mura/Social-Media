// frontend/assets/js/feed.js
// Logica interattiva del feed: like, save, condivisione, read more

'use strict';

/* ── LIKE ────────────────────────────────────── */
async function toggleLike(btn) {
    const postId = btn.dataset.postId;
    const liked  = btn.classList.toggle('liked');
    const icon   = btn.querySelector('.icon');
    const count  = btn.querySelector('.count');

    // UI immediata (ottimistica)
    icon.textContent = liked ? '❤️' : '🤍';
    btn.setAttribute('aria-label', liked ? 'Rimuovi like' : 'Metti like');
    const n = parseInt(count.textContent.replace(/\D/g, ''), 10);
    count.textContent = (n + (liked ? 1 : -1)).toLocaleString('it-IT');

    // Chiamata API
    try {
        const res = await fetch('/backend/api/likes.php', {
            method:  liked ? 'POST' : 'DELETE',
            headers: { 'Content-Type': 'application/json' },
            body:    JSON.stringify({ post_id: postId }),
        });
        if (!res.ok) throw new Error('Errore like');
    } catch (err) {
        // Rollback UI in caso di errore
        btn.classList.toggle('liked');
        icon.textContent = liked ? '🤍' : '❤️';
        count.textContent = n.toLocaleString('it-IT');
        console.error(err);
    }
}

/* ── SAVE ────────────────────────────────────── */
async function toggleSave(btn) {
    const postId = btn.dataset.postId;
    const saved  = btn.classList.toggle('saved');

    btn.setAttribute('aria-label', saved ? 'Rimuovi dai salvati' : 'Salva');

    try {
        await fetch('/backend/api/saves.php', {
            method:  saved ? 'POST' : 'DELETE',
            headers: { 'Content-Type': 'application/json' },
            body:    JSON.stringify({ post_id: postId }),
        });
    } catch (err) {
        btn.classList.toggle('saved');
        console.error(err);
    }
}

/* ── CONDIVISIONE ───────────────────────────── */
async function sharePost(postId, title) {
    const url = `${window.location.origin}/frontend/post.php?id=${postId}`;

    if (navigator.share) {
        try {
            await navigator.share({ title: `Gustagram — ${title}`, url });
        } catch (err) {
            if (err.name !== 'AbortError') console.error(err);
        }
    } else {
        // Fallback: copia negli appunti
        try {
            await navigator.clipboard.writeText(url);
            showToast('Link copiato negli appunti!');
        } catch {
            showToast('Impossibile copiare il link.');
        }
    }
}

/* ── READ MORE ──────────────────────────────── */
function toggleReadMore(btn, postId) {
    const content = document.getElementById(`content-${postId}`);
    const expanded = content.style.webkitLineClamp === 'unset';

    if (expanded) {
        content.style.webkitLineClamp = '3';
        content.style.overflow = 'hidden';
        content.style.display = '-webkit-box';
        btn.textContent = 'Leggi tutto →';
    } else {
        content.style.webkitLineClamp = 'unset';
        content.style.overflow = 'visible';
        content.style.display = 'block';
        btn.textContent = 'Mostra meno ↑';
    }
}

/* ── MENU POST (opzioni ···) ────────────────── */
function openPostMenu(postId, authorId) {
    // TODO: aprire un bottom sheet con opzioni
    // (segnala, copia link, elimina se autore)
    console.log('Menu post', postId, 'autore', authorId);
}

/* ── TOAST ──────────────────────────────────── */
function showToast(message, type = 'info') {
    const existing = document.getElementById('gustagram-toast');
    if (existing) existing.remove();

    const toast = document.createElement('div');
    toast.id = 'gustagram-toast';
    toast.textContent = message;
    Object.assign(toast.style, {
        position:     'fixed',
        bottom:       '80px',
        left:         '50%',
        transform:    'translateX(-50%)',
        background:   type === 'error' ? '#D63B3B' : '#3D1A06',
        color:        '#fff',
        padding:      '10px 20px',
        borderRadius: '20px',
        fontSize:     '13.5px',
        fontWeight:   '500',
        zIndex:       '9999',
        boxShadow:    '0 4px 16px rgba(0,0,0,0.2)',
        animation:    'toastIn .25s ease',
        whiteSpace:   'nowrap',
    });

    // Animazione CSS inline
    if (!document.getElementById('toast-style')) {
        const style = document.createElement('style');
        style.id = 'toast-style';
        style.textContent = `
            @keyframes toastIn {
                from { opacity:0; transform: translateX(-50%) translateY(10px); }
                to   { opacity:1; transform: translateX(-50%) translateY(0); }
            }
        `;
        document.head.appendChild(style);
    }

    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 2800);
}
