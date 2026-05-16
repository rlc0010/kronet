<?php
$pageTitle = $anuncio['titulo'];
$navActive = 'explorar';
require __DIR__ . '/../partials/head.php';
require __DIR__ . '/../partials/navbar.php';
require_once __DIR__ . '/../../helpers/Categorias.php';
require_once __DIR__ . '/../../models/Denuncia.php';

$catSlug   = $anuncio['categoria'];
$catNombre = Categorias::nombre($catSlug);
$catIcon   = Categorias::icono($catSlug);
$plazasLibres = max(0, (int)$anuncio['plazas_totales'] - (int)$anuncio['plazas_ocupadas']);

$miId     = $_SESSION['id_usuario'] ?? 0;
$anonimo  = !$miId;
?>

<section class="hero-section hero-compact">
    <h1><?= htmlspecialchars($anuncio['titulo']) ?></h1>
    <p>
        Publicado por
        <?php if ($anonimo): ?>
            <span style="color:white; font-weight:600;"><?= htmlspecialchars($anuncio['nombre_usuario']) ?></span>
        <?php else: ?>
            <a href="/kronet/public/perfil/<?= $anuncio['id_usuario'] ?>" style="color:white; text-decoration:underline;">
                <?= htmlspecialchars($anuncio['nombre_usuario']) ?>
            </a>
        <?php endif; ?>
    </p>
</section>

<div class="page-wrap">

    <a class="back-link" href="/kronet/public/anuncios/buscar"><i class="fas fa-arrow-left"></i> Volver a explorar</a>

    <?php if ($anonimo): ?>
        <div class="flash flash-info">
            <i class="fas fa-info-circle"></i>
            Estás viendo este anuncio como invitado. Para apuntarte o escribir al autor necesitas
            <a href="/kronet/public/login" style="font-weight:600;">iniciar sesión</a> o
            <a href="/kronet/public/register" style="font-weight:600;">crear una cuenta gratis</a>.
        </div>
    <?php endif; ?>

    <div style="display:grid; grid-template-columns: 2fr 1fr; gap:24px; align-items:start;" class="ficha-grid">

        <!-- Detalle -->
        <div class="card">
            <div class="tags-row">
                <span class="badge <?= $anuncio['tipo_anuncio'] === 'oferta' ? 'badge-oferta' : 'badge-demanda' ?>">
                    <?= ucfirst($anuncio['tipo_anuncio']) ?>
                </span>
                <span class="badge badge-<?= $anuncio['estado'] ?>"><?= ucfirst($anuncio['estado']) ?></span>
                <?php if ($anuncio['destacado']): ?>
                    <span class="badge badge-destacado"><i class="fas fa-star"></i> Destacado</span>
                <?php endif; ?>
            </div>

            <h2 style="margin-bottom:12px;"><?= htmlspecialchars($anuncio['titulo']) ?></h2>

            <p style="line-height:1.6; color:rgba(15,28,63,0.8); white-space:pre-wrap;"><?= htmlspecialchars($anuncio['descripcion']) ?></p>

            <div class="tags-row" style="margin-top:18px;">
                <span class="tag tag-cat"><i class="<?= $catIcon ?>"></i> <?= htmlspecialchars($catNombre) ?></span>
                <span class="tag"><i class="fas fa-clock"></i> <?= (int)$anuncio['duracion_estimada'] ?>h</span>
                <span class="tag"><i class="fas fa-coins"></i> <?= (int)$anuncio['precio_creditos'] ?> créditos</span>
                <span class="tag"><i class="fas fa-users"></i> <?= (int)$anuncio['plazas_ocupadas'] ?>/<?= (int)$anuncio['plazas_totales'] ?> plazas</span>
            </div>

            <?php if ($anonimo): ?>
                <!-- Anónimo: solo CTA a login -->
                <div class="action-strip" style="margin-top:24px; flex-direction:row;">
                    <a href="/kronet/public/login" class="btn btn-primary">
                        <i class="fas fa-sign-in-alt"></i> Iniciar sesión para apuntarme
                    </a>
                    <a href="/kronet/public/register" class="btn btn-outline">
                        <i class="fas fa-user-plus"></i> Crear cuenta
                    </a>
                </div>
            <?php elseif (!$esDueno): ?>
                <div class="action-strip" style="margin-top:24px; flex-direction:row;">
                    <?php if ($yaInscrito): ?>
                        <span class="btn btn-ghost" style="cursor:default;">
                            <i class="fas fa-check"></i> Ya tienes una solicitud en este anuncio
                        </span>
                    <?php elseif ($plazasLibres <= 0 || $anuncio['estado'] !== 'activo'): ?>
                        <span class="btn btn-ghost" style="cursor:default;">
                            <i class="fas fa-ban"></i> Sin plazas disponibles
                        </span>
                    <?php else: ?>
                        <button class="btn btn-primary" onclick="solicitar(<?= (int)$anuncio['id_anuncio'] ?>)">
                            <i class="fas fa-handshake"></i> Apuntarme (<?= (int)$anuncio['precio_creditos'] ?> créditos)
                        </button>
                    <?php endif; ?>
                    <button class="btn btn-danger btn-sm" onclick="denunciar(<?= (int)$anuncio['id_anuncio'] ?>)">
                        <i class="fas fa-flag"></i> Denunciar
                    </button>
                </div>
            <?php else: ?>
                <div class="flash flash-info" style="margin-top:24px;">
                    <i class="fas fa-info-circle"></i>
                    Este es tu anuncio. Las solicitudes que recibas aparecerán en
                    <a href="/kronet/public/intercambios/ofertas-recibidas">Ofertas recibidas</a>.
                </div>
            <?php endif; ?>
        </div>

        <!-- Chat / Autor -->
        <div class="card" style="padding:0; overflow:hidden;">
            <?php if ($anonimo): ?>
                <!-- Anónimo: tarjeta CTA en lugar de chat -->
                <div style="padding:24px; text-align:center;">
                    <div style="font-size:38px; color:var(--azul-verdoso); margin-bottom:10px;">
                        <i class="fas fa-comments"></i>
                    </div>
                    <h3 style="margin-bottom:10px;">¿Quieres contactar?</h3>
                    <p style="font-size:14px; color:var(--gris-texto); margin-bottom:18px;">
                        Inicia sesión para chatear con <strong><?= htmlspecialchars($anuncio['nombre_usuario']) ?></strong>
                        sobre este servicio.
                    </p>
                    <a href="/kronet/public/login" class="btn btn-secondary btn-block">
                        <i class="fas fa-sign-in-alt"></i> Iniciar sesión
                    </a>
                    <a href="/kronet/public/register" class="btn btn-outline btn-block" style="margin-top:8px;">
                        <i class="fas fa-user-plus"></i> Crear cuenta gratis
                    </a>
                </div>
            <?php elseif (!$esDueno): ?>
                <div class="chat-header">
                    <div>
                        <div class="ch-name">
                            <a href="/kronet/public/perfil/<?= $anuncio['id_usuario'] ?>" style="color:inherit;">
                                <?= htmlspecialchars($anuncio['nombre_usuario']) ?>
                            </a>
                        </div>
                        <div class="ch-anuncio">Chat sobre este anuncio</div>
                    </div>
                </div>

                <div class="chat-body" id="chatBody">
                    <?php if (empty($mensajes)): ?>
                        <div class="chat-empty">
                            <i class="fas fa-comments" style="font-size:32px; margin-bottom:8px; display:block;"></i>
                            Aún no hay mensajes. Sé el primero en escribir.
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
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            <?php else: ?>
                <div style="padding:24px;">
                    <h3 style="margin-bottom:10px;">Conversaciones</h3>
                    <p style="font-size:14px; color:var(--gris-texto); margin-bottom:14px;">
                        Cuando alguien te escriba sobre este anuncio podrás verlo en tu bandeja de mensajes.
                    </p>
                    <a href="/kronet/public/mensajes" class="btn btn-secondary btn-block">
                        <i class="fas fa-envelope"></i> Ir a mensajes
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<?php if (!$anonimo && !$esDueno): ?>
<!-- Modal denuncia (solo logueados que no son dueños) -->
<div id="modalDenuncia" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.55); z-index:2500; align-items:center; justify-content:center; padding:20px;">
    <div style="background:white; border-radius:var(--radio-lg); padding:30px; max-width:480px; width:100%;">
        <h3 style="margin-bottom:14px;"><i class="fas fa-flag" style="color:#dc2626;"></i> Denunciar anuncio</h3>
        <p style="font-size:14px; color:var(--gris-texto); margin-bottom:18px;">Indica el motivo de la denuncia. Un administrador la revisará.</p>

        <div class="form-group">
            <label>Motivo</label>
            <select id="denMotivo">
                <?php foreach (Denuncia::MOTIVOS as $k => $v): ?>
                    <option value="<?= $k ?>"><?= htmlspecialchars($v) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Comentario (opcional)</label>
            <textarea id="denDesc" rows="3" maxlength="500" placeholder="Más detalles..."></textarea>
        </div>

        <div style="display:flex; justify-content:flex-end; gap:10px;">
            <button class="btn btn-ghost" onclick="cerrarDenuncia()">Cancelar</button>
            <button class="btn btn-danger" id="btnDenEnviar" onclick="enviarDenuncia()">
                <i class="fas fa-paper-plane"></i> Enviar denuncia
            </button>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
const ID_ANUNCIO  = <?= (int)$anuncio['id_anuncio'] ?>;
const ID_DUENO    = <?= (int)$anuncio['id_usuario'] ?>;
const YO          = <?= (int)$miId ?>;
const ES_DUENO    = <?= $esDueno ? 'true' : 'false' ?>;
const ANONIMO     = <?= $anonimo ? 'true' : 'false' ?>;

async function solicitar(id) {
    if (!confirmar('¿Confirmas apuntarte a este servicio?')) return;
    const data = await apiPost('/kronet/public/intercambios/solicitar', { id_anuncio: id });
    showToast(data.msg, data.ok ? 'ok' : 'error');
    if (data.ok) setTimeout(() => window.location.reload(), 800);
}

function denunciar(id) {
    document.getElementById('modalDenuncia').style.display = 'flex';
}
function cerrarDenuncia() {
    const m = document.getElementById('modalDenuncia');
    if (m) m.style.display = 'none';
}
async function enviarDenuncia() {
    const motivo = document.getElementById('denMotivo').value;
    const desc   = document.getElementById('denDesc').value.trim();
    const btn = document.getElementById('btnDenEnviar');
    btn.disabled = true;
    const data = await apiPost('/kronet/public/anuncios/' + ID_ANUNCIO + '/denunciar', { motivo, descripcion: desc });
    showToast(data.msg, data.ok ? 'ok' : 'error');
    btn.disabled = false;
    if (data.ok) cerrarDenuncia();
}

async function enviarChat() {
    if (ES_DUENO || ANONIMO) return;
    const ta = document.getElementById('chatText');
    const txt = ta.value.trim();
    if (!txt) return;
    const data = await apiPost('/kronet/public/mensaje/enviar', {
        id_anuncio: ID_ANUNCIO,
        id_receptor: ID_DUENO,
        mensaje: txt
    });
    if (data.ok) {
        ta.value = '';
        refrescarChat();
    } else {
        showToast(data.msg, 'error');
    }
}

async function refrescarChat() {
    if (ES_DUENO || ANONIMO) return;
    const res = await fetch('/kronet/public/api/conversacion?anuncio=' + ID_ANUNCIO + '&usuario=' + ID_DUENO);
    const data = await res.json();
    if (!data.ok) return;

    const body = document.getElementById('chatBody');
    if (!data.mensajes.length) {
        body.innerHTML = '<div class="chat-empty"><i class="fas fa-comments" style="font-size:32px; margin-bottom:8px; display:block;"></i>Aún no hay mensajes. Sé el primero en escribir.</div>';
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
function escapeHtml(s) {
    return (s+'').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
}

// Auto-scroll inicial y refresco periódico (solo si hay chat)
window.addEventListener('DOMContentLoaded', () => {
    if (ANONIMO) return;
    const body = document.getElementById('chatBody');
    if (body) body.scrollTop = body.scrollHeight;
    if (!ES_DUENO) setInterval(refrescarChat, 8000);
});

// Enviar con Enter
const ta = document.getElementById('chatText');
if (ta) {
    ta.addEventListener('keydown', e => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            enviarChat();
        }
    });
}
</script>

<style>
@media (max-width: 900px) { .ficha-grid { grid-template-columns: 1fr !important; } }
</style>

<?php require __DIR__ . '/../partials/footer.php'; ?>
