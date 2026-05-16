<?php
$pageTitle = 'Mensajes';
$navActive = 'mensajes';
require __DIR__ . '/../partials/head.php';
require __DIR__ . '/../partials/navbar.php';
?>

<section class="hero-section hero-compact">
    <h1>Mensajes</h1>
    <p>Tus chats sobre los anuncios de Kronet</p>
</section>

<div class="page-wrap-wide">

    <a class="back-link" href="/kronet/public/"><i class="fas fa-arrow-left"></i> Inicio</a>

    <?php if (empty($conversaciones)): ?>
        <div class="empty-state">
            <div class="empty-icon"><i class="fas fa-comments"></i></div>
            <p>No tienes conversaciones todavía.</p>
            <a href="/kronet/public/anuncios/buscar" class="btn btn-primary">
                <i class="fas fa-search"></i> Explorar servicios
            </a>
        </div>
    <?php else: ?>
        <div class="chat-layout">

            <!-- Lista de conversaciones -->
            <aside class="chat-sidebar">
                <div class="cs-title"><i class="fas fa-comments"></i> Tus chats</div>
                <?php foreach ($conversaciones as $c):
                    $activeClass = ($convAbierta &&
                                   $convAbierta['id_anuncio'] == $c['id_anuncio'] &&
                                   $convAbierta['otro_usuario'] == $c['otro_usuario'])
                                   ? ' active' : '';
                ?>
                    <a class="conv<?= $activeClass ?>"
                       href="/kronet/public/mensajes?anuncio=<?= (int)$c['id_anuncio'] ?>&usuario=<?= (int)$c['otro_usuario'] ?>">
                        <div class="conv-top">
                            <span class="name"><?= htmlspecialchars($c['nombre_otro']) ?></span>
                            <?php if ($c['no_leidos'] > 0): ?>
                                <span class="unread-dot" title="<?= (int)$c['no_leidos'] ?> sin leer"></span>
                            <?php endif; ?>
                        </div>
                        <div class="anuncio-ref">
                            <i class="fas fa-bullhorn"></i>
                            <?= htmlspecialchars($c['titulo_anuncio'] ?? '(anuncio eliminado)') ?>
                        </div>
                        <div class="preview"><?= htmlspecialchars($c['ultimo_mensaje'] ?? '') ?></div>
                    </a>
                <?php endforeach; ?>
            </aside>

            <!-- Conversación abierta -->
            <main class="chat-main">
                <?php if ($convAbierta && $anuncio && $otroUsuario): ?>
                    <div class="chat-header">
                        <div>
                            <div class="ch-name">
                                <a href="/kronet/public/perfil/<?= (int)$otroUsuario['id_usuario'] ?>" style="color:inherit;">
                                    <?= htmlspecialchars($otroUsuario['nombre']) ?>
                                </a>
                            </div>
                            <div class="ch-anuncio">
                                <i class="fas fa-bullhorn"></i>
                                <a href="/kronet/public/anuncios/<?= (int)$anuncio['id_anuncio'] ?>" style="color:var(--gris-texto);">
                                    <?= htmlspecialchars($anuncio['titulo']) ?>
                                </a>
                            </div>
                        </div>
                        <a class="btn btn-ghost btn-sm" href="/kronet/public/anuncios/<?= (int)$anuncio['id_anuncio'] ?>">
                            <i class="fas fa-external-link-alt"></i> Ver anuncio
                        </a>
                    </div>

                    <div class="chat-body" id="chatBody">
                        <?php if (empty($mensajes)): ?>
                            <div class="chat-empty">
                                <i class="fas fa-comments" style="font-size:32px; margin-bottom:8px; display:block;"></i>
                                Aún no hay mensajes. Escribe el primero.
                            </div>
                        <?php else: ?>
                            <?php foreach ($mensajes as $m):
                                $mio = $m['id_emisor'] == $_SESSION['id_usuario'];
                            ?>
                                <div class="chat-bubble <?= $mio ? 'bubble-me' : 'bubble-other' ?>">
                                    <?= nl2br(htmlspecialchars($m['contenido'])) ?>
                                    <span class="bubble-time"><?= htmlspecialchars($m['fecha_envio']) ?></span>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <div class="chat-input">
                        <textarea id="chatText" rows="2" placeholder="Escribe un mensaje..."></textarea>
                        <button class="btn btn-secondary btn-sm" onclick="enviarChat()">
                            <i class="fas fa-paper-plane"></i> Enviar
                        </button>
                    </div>
                <?php else: ?>
                    <div class="chat-empty" style="margin:auto;">
                        <i class="fas fa-comments" style="font-size:48px; margin-bottom:14px; display:block; opacity:0.4;"></i>
                        <p>Selecciona una conversación de la lista.</p>
                    </div>
                <?php endif; ?>
            </main>

        </div>
    <?php endif; ?>

</div>

<?php if ($convAbierta && $anuncio && $otroUsuario): ?>
<script>
const ID_ANUNCIO = <?= (int)$anuncio['id_anuncio'] ?>;
const ID_OTRO    = <?= (int)$otroUsuario['id_usuario'] ?>;
const YO         = <?= (int)$_SESSION['id_usuario'] ?>;

async function enviarChat() {
    const ta = document.getElementById('chatText');
    const txt = ta.value.trim();
    if (!txt) return;
    const data = await apiPost('/kronet/public/mensaje/enviar', {
        id_anuncio:  ID_ANUNCIO,
        id_receptor: ID_OTRO,
        mensaje:     txt
    });
    if (data.ok) {
        ta.value = '';
        refrescarChat();
    } else {
        showToast(data.msg, 'error');
    }
}

async function refrescarChat() {
    const res = await fetch('/kronet/public/api/conversacion?anuncio=' + ID_ANUNCIO + '&usuario=' + ID_OTRO);
    const data = await res.json();
    if (!data.ok) return;

    const body = document.getElementById('chatBody');
    if (!body) return;
    if (!data.mensajes.length) {
        body.innerHTML = '<div class="chat-empty"><i class="fas fa-comments" style="font-size:32px; margin-bottom:8px; display:block;"></i>Aún no hay mensajes. Escribe el primero.</div>';
        return;
    }
    body.innerHTML = data.mensajes.map(m => {
        const mio = (m.id_emisor == YO);
        const cls = mio ? 'bubble-me' : 'bubble-other';
        const txt = escapeHtml(m.contenido).replace(/\n/g, '<br>');
        return `<div class="chat-bubble ${cls}">${txt}<span class="bubble-time">${m.fecha_envio}</span></div>`;
    }).join('');
    body.scrollTop = body.scrollHeight;
}
function escapeHtml(s) { return (s+'').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c])); }

window.addEventListener('DOMContentLoaded', () => {
    const body = document.getElementById('chatBody');
    if (body) body.scrollTop = body.scrollHeight;
    setInterval(refrescarChat, 8000);
});

const ta = document.getElementById('chatText');
if (ta) ta.addEventListener('keydown', e => {
    if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); enviarChat(); }
});
</script>
<?php endif; ?>

<?php require __DIR__ . '/../partials/footer.php'; ?>
