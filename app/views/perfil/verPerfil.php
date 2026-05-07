<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi perfil - Kronet</title>
    <link rel="icon" type="image/png" href="/kronet/public/assets/images/logo.png">
    <link rel="stylesheet" href="/kronet/public/assets/css/kronet.css">
</head>
<body>

<div class="page-wrap">

    <a class="back-link" href="/kronet/public/"><i class="fas fa-arrow-left"></i> Inicio</a>

    <div class="page-header">
        <h1><i class="fas fa-user"></i> Mi perfil</h1>
    </div>

    <?php if (isset($error)): ?>
        <div class="flash flash-error"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div>
    <?php else: ?>

    <div class="profile-layout">

        <!-- Sidebar -->
        <div class="profile-sidebar">
            <div class="user-card">
                <div class="avatar-wrapper">
                    <div class="main-avatar-placeholder">
                        <?= strtoupper(substr($usuario['nombre'] ?? 'U', 0, 1)) ?>
                    </div>
                </div>
                <h2><?= htmlspecialchars($usuario['nombre'] ?? 'Sin nombre') ?></h2>
                <p class="user-desc"><?= htmlspecialchars($usuario['descripcion'] ?? 'Sin descripción') ?></p>

                <div class="credits-box">
                    <div class="cb-label"><i class="fas fa-coins"></i> Saldo de créditos</div>
                    <div class="cb-amount"><?= htmlspecialchars($usuario['saldo_monedas'] ?? 0) ?></div>
                </div>

                <?php if (isset($media) && $media['total'] > 0): ?>
                    <div class="rating-badge">
                        <i class="fas fa-star"></i>
                        <?= number_format($media['media'], 1) ?> / 5
                    </div>
                    <p style="font-size:13px; color:var(--gris-suave); margin-top:6px;">(<?= $media['total'] ?> valoraciones)</p>
                <?php endif; ?>

                <div class="action-strip">
                    <a class="btn btn-outline btn-sm" href="/kronet/public/intercambios/ofertas-recibidas">
                        <i class="fas fa-inbox"></i> Ofertas recibidas
                    </a>
                    <a class="btn btn-outline btn-sm" href="/kronet/public/intercambios/mis-intercambios">
                        <i class="fas fa-exchange-alt"></i> Mis intercambios
                    </a>
                    <a class="btn btn-outline btn-sm" href="/kronet/public/valoraciones/mis-valoraciones">
                        <i class="fas fa-star"></i> Mis valoraciones
                    </a>
                    <a class="btn btn-danger btn-sm" href="/kronet/public/logout">
                        <i class="fas fa-sign-out-alt"></i> Cerrar sesión
                    </a>
                </div>
            </div>
        </div>

        <!-- Columna principal -->
        <div class="profile-main-col">
            <div class="card">
                <div class="page-header">
                    <h2><i class="fas fa-exchange-alt"></i> Historial de intercambios</h2>
                </div>

                <?php if (empty($historial)): ?>
                    <div class="empty-state">
                        <div class="empty-icon"><i class="fas fa-exchange-alt"></i></div>
                        <p>Todavía no has participado en ningún intercambio.</p>
                    </div>
                <?php else: ?>
                    <div class="intercambios-list">
                        <?php foreach ($historial as $i): ?>
                            <?php
                            $esOfertante   = ($i['id_usuario_ofertante'] == $_SESSION['id_usuario']);
                            $idOtroUsuario = $esOfertante ? $i['id_usuario_solicitante'] : $i['id_usuario_ofertante'];
                            $rolLabel      = $esOfertante ? 'Ofertante' : 'Solicitante';
                            ?>
                            <div class="intercambio-card">
                                <div class="ic-header">
                                    <h3><?= htmlspecialchars($i['titulo_anuncio']) ?></h3>
                                    <span class="badge badge-<?= $i['estado'] ?>">
                                        <?= ucfirst($i['estado']) ?>
                                    </span>
                                </div>
                                <div class="ic-meta">
                                    <span><i class="fas fa-coins"></i> <?= htmlspecialchars($i['monedas_intercambio']) ?> créditos</span>
                                    <span><i class="fas fa-calendar"></i> <?= htmlspecialchars($i['fecha_inicio']) ?></span>
                                    <span><i class="fas fa-user-tag"></i> Rol: <?= $rolLabel ?></span>
                                </div>
                                <?php if ($i['estado'] === 'confirmado'): ?>
                                    <div class="ic-actions">
                                        <a class="btn btn-valorar btn-sm" href="/kronet/public/valoraciones/crear?id_usuario=<?= $idOtroUsuario ?>">
                                            <i class="fas fa-star"></i> Valorar usuario
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>

    <?php endif; ?>

</div>

</body>
</html>