<?php
$pageTitle = 'Mi perfil';
$navActive = '';
require __DIR__ . '/../partials/head.php';
require __DIR__ . '/../partials/navbar.php';
?>

<section class="hero-section hero-compact">
    <h1>Mi perfil</h1>
    <p>Gestiona tu información y revisa tu actividad en Kronet</p>
</section>

<div class="page-wrap-wide">

    <a class="back-link" href="/kronet/public/"><i class="fas fa-arrow-left"></i> Inicio</a>

    <div class="profile-layout">

        <div>
            <div class="user-card">
                <div class="avatar-wrapper">
                    <div class="main-avatar-placeholder">
                        <?= strtoupper(substr($usuario['nombre'] ?? 'U', 0, 1)) ?>
                    </div>
                </div>
                <h2><?= htmlspecialchars($usuario['nombre']) ?></h2>
                <p class="user-desc"><?= htmlspecialchars($usuario['descripcion'] ?: 'Sin descripción aún. Edita tu perfil para añadir una.') ?></p>

                <?php if ($media['total'] > 0): ?>
                    <div class="rating-badge">
                        <i class="fas fa-star"></i>
                        <?= number_format($media['media'], 1) ?> / 5
                        <span style="font-weight:500; opacity:0.85;">(<?= (int)$media['total'] ?>)</span>
                    </div>
                <?php endif; ?>

                <div class="credits-box">
                    <div class="cb-label"><i class="fas fa-coins"></i> Saldo de créditos</div>
                    <div class="cb-amount"><?= (int)$usuario['saldo_monedas'] ?>h</div>
                </div>

                <?php if ($suscripcion): ?>
                    <div class="flash flash-info" style="text-align:left; margin-bottom:10px;">
                        <i class="fas fa-crown" style="color:#F59E0B;"></i>
                        Premium activo hasta <?= date('d/m/Y', strtotime($suscripcion['fecha_fin'])) ?>
                    </div>
                <?php endif; ?>

                <div class="action-strip" style="flex-direction:column;">
                    <button type="button" class="btn btn-outline btn-sm" onclick="const f=document.getElementById('editForm');f.classList.toggle('hidden');if(!f.classList.contains('hidden'))f.scrollIntoView({behavior:'smooth',block:'nearest'})">
                        <i class="fas fa-edit"></i> Editar perfil
                    </button>
                    <a class="btn btn-outline btn-sm" href="/kronet/public/anuncios/mis-anuncios">
                        <i class="fas fa-bullhorn"></i> Mis anuncios
                    </a>
                    <a class="btn btn-outline btn-sm" href="/kronet/public/valoraciones/mis-valoraciones">
                        <i class="fas fa-star"></i> Mis valoraciones
                    </a>
                    <?php if ($suscripcion): ?>
                        <a class="btn btn-outline btn-sm" href="/kronet/public/suscripcion">
                            <i class="fas fa-crown" style="color:#F59E0B;"></i> Gestionar suscripción
                        </a>
                    <?php else: ?>
                        <a class="btn btn-secondary btn-sm" href="/kronet/public/suscripcion">
                            <i class="fas fa-crown"></i> Hazte Premium
                        </a>
                    <?php endif; ?>
                    <a class="btn btn-danger btn-sm" href="/kronet/public/logout">
                        <i class="fas fa-sign-out-alt"></i> Cerrar sesión
                    </a>
                </div>
            </div>

            <!-- Formulario edición -->
            <div class="card hidden" id="editForm" style="margin-top:16px;">
                <h3 style="margin-bottom:14px;"><i class="fas fa-user-edit"></i> Editar perfil</h3>
                <form id="profileForm" method="post" action="/kronet/public/perfil/editar" novalidate>
                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" id="ed_nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" id="ed_email" value="<?= htmlspecialchars($usuario['email']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Descripción</label>
                        <textarea id="ed_desc" rows="3" maxlength="500" placeholder="Cuéntale a la comunidad qué haces..."><?= htmlspecialchars($usuario['descripcion'] ?? '') ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-save"></i> Guardar cambios
                    </button>
                </form>
            </div>
        </div>

        <div class="profile-main-col">

            <!-- Estadísticas -->
            <div class="stats-row">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-exchange-alt"></i></div>
                    <div class="stat-value"><?= (int)$stats['confirmados'] ?></div>
                    <div class="stat-label">Confirmados</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="background:linear-gradient(135deg,#F59E0B,#D97706);"><i class="fas fa-hourglass-half"></i></div>
                    <div class="stat-value"><?= (int)$stats['pendientes'] ?></div>
                    <div class="stat-label">Pendientes</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="background:linear-gradient(135deg,#6366F1,#8B5CF6);"><i class="fas fa-bullhorn"></i></div>
                    <div class="stat-value"><?= (int)$totalAnuncios ?></div>
                    <div class="stat-label">Anuncios publicados</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="background:linear-gradient(135deg,#EC4899,#DB2777);"><i class="fas fa-star"></i></div>
                    <div class="stat-value"><?= $media['total'] > 0 ? number_format($media['media'], 1) : '—' ?></div>
                    <div class="stat-label">Valoración media</div>
                </div>
            </div>

            <!-- Historial -->
            <div class="card">
                <div class="page-header" style="margin-bottom:18px;">
                    <h2><i class="fas fa-history"></i> Historial de intercambios</h2>
                </div>

                <?php if (empty($historial)): ?>
                    <div class="empty-state">
                        <div class="empty-icon"><i class="fas fa-exchange-alt"></i></div>
                        <p>Todavía no has participado en ningún intercambio.</p>
                    </div>
                <?php else: ?>
                    <div class="intercambios-list">
                        <?php foreach (array_slice($historial, 0, 10) as $i):
                            $esOfertante = ($i['id_usuario_ofertante'] == $_SESSION['id_usuario']);
                            $idOtro = $esOfertante ? $i['id_usuario_solicitante'] : $i['id_usuario_ofertante'];
                            $rolLabel = $esOfertante ? 'Ofertante' : 'Solicitante';
                        ?>
                            <div class="intercambio-card">
                                <div class="ic-header">
                                    <h3><?= htmlspecialchars($i['titulo_anuncio'] ?? '(anuncio eliminado)') ?></h3>
                                    <span class="badge badge-<?= htmlspecialchars($i['estado']) ?>"><?= ucfirst($i['estado']) ?></span>
                                </div>
                                <div class="ic-meta">
                                    <span><i class="fas fa-coins"></i> <?= (int)$i['monedas_intercambio'] ?> créd.</span>
                                    <span><i class="fas fa-calendar"></i> <?= htmlspecialchars($i['fecha_inicio']) ?></span>
                                    <span><i class="fas fa-user-tag"></i> <?= $rolLabel ?></span>
                                </div>
                                <?php if ($i['estado'] === 'confirmado'): ?>
                                    <div class="ic-actions">
                                        <a class="btn btn-valorar btn-sm" href="/kronet/public/valoraciones/crear?id_usuario=<?= (int)$idOtro ?>">
                                            <i class="fas fa-star"></i> Valorar usuario
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php if (count($historial) > 10): ?>
                        <div style="text-align:center; margin-top:18px;">
                            <a class="btn btn-ghost btn-sm" href="/kronet/public/intercambios/mis-intercambios">
                                Ver todo el historial
                            </a>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>

    </div>

</div>

<style>.hidden { display: none; }</style>

<script>
document.getElementById('profileForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    try {
        const data = await apiPost('/kronet/public/perfil/editar', {
            nombre: document.getElementById('ed_nombre').value.trim(),
            email:  document.getElementById('ed_email').value.trim(),
            descripcion: document.getElementById('ed_desc').value.trim()
        });
        showToast(data.msg, data.ok ? 'ok' : 'error');
        if (data.ok) setTimeout(() => window.location.reload(), 800);
    } catch (err) {
        showToast('Error al guardar los cambios. Inténtalo de nuevo.', 'error');
    }
});
</script>

<?php require __DIR__ . '/../partials/footer.php'; ?>
