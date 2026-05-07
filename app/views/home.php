<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="/kronet/public/assets/images/logo.png">
    <title>Kronet - Intercambia tiempo, comparte habilidades</title>
    <link rel="stylesheet" href="/kronet/public/assets/css/kronet.css">
</head>
<body>

<!-- ===== NAVBAR ===== -->
<nav class="navbar">
    <a href="/kronet/public/" class="logo-container">
        <div class="logo-icon">
            <img src="/kronet/public/assets/images/logo.png" alt="KRONET" class="logo-icon">
        </div>
        <span class="logo-text">Kronet</span>
    </a>

    <ul class="nav-menu">
        <li>
            <a href="/kronet/public/" class="active">
                <i class="fas fa-home"></i> Inicio
            </a>
        </li>
        <li>
            <a href="/kronet/public/anuncios/buscar">
                <i class="fas fa-search"></i> Explorar
            </a>
        </li>
        <li>
            <a href="/kronet/public/anuncios/crear">
                <i class="fas fa-plus-circle"></i> Publicar
            </a>
        </li>
        <li>
            <a href="/kronet/public/intercambios/mis-intercambios">
                <i class="fas fa-exchange-alt"></i> Mis Servicios
            </a>
        </li>
        <li>
            <a href="/kronet/public/mensajes">
                <i class="fas fa-envelope"></i> Mensajes
            </a>
        </li>
    </ul>

    <div class="nav-user">
        <?php if (isset($_SESSION['creditos'])): ?>
        <div class="credits-badge">
            <i class="fas fa-crown" style="color:#F59E0B;"></i>
            Créditos: <strong><?= htmlspecialchars($_SESSION['creditos'] ?? '0') ?>h</strong>
            <i class="fas fa-clock" style="opacity:0.5;font-size:12px;"></i>
        </div>
        <?php endif; ?>
        <a href="/kronet/public/perfil" class="avatar-nav">
            <i class="fas fa-user"></i>
        </a>
    </div>
</nav>

<!-- ===== HERO ===== -->
<section class="hero-section">
    <h1>Intercambia tiempo, comparte habilidades</h1>
    <p>Kronet es tu banco de tiempo donde puedes ofrecer tus servicios y solicitar ayuda de otros. El tiempo es tu moneda.</p>
    <div class="hero-btns">
        <a href="/kronet/public/anuncios/crear" class="btn btn-white btn-lg">
            Publicar servicio
        </a>
        <a href="/kronet/public/anuncios/buscar" class="btn btn-white-outline btn-lg">
            Explorar servicios →
        </a>
    </div>
</section>

<!-- ===== CÓMO FUNCIONA ===== -->
<section class="how-section">
    <h2>¿Cómo funciona Kronet?</h2>
    <p>Tres pasos simples para empezar a intercambiar tiempo</p>

    <div class="how-cards">
        <div class="how-card">
            <div class="how-icon">
                <i class="fas fa-edit"></i>
            </div>
            <h3>1. Publica tu servicio</h3>
            <p>Comparte lo que sabes hacer. Cada hora que ofreces suma créditos a tu cuenta.</p>
        </div>
        <div class="how-card">
            <div class="how-icon">
                <i class="fas fa-search"></i>
            </div>
            <h3>2. Explora y conecta</h3>
            <p>Busca servicios que necesitas y contacta con otros usuarios de la comunidad.</p>
        </div>
        <div class="how-card">
            <div class="how-icon">
                <i class="fas fa-handshake"></i>
            </div>
            <h3>3. Intercambia tiempo</h3>
            <p>Realiza el intercambio y valora la experiencia. El tiempo es la única moneda.</p>
        </div>
    </div>
</section>

<!-- ===== ACCESOS RÁPIDOS ===== -->
<section style="padding: 60px 10%; background: white;">
    <h2 style="font-size:28px; margin-bottom:8px; text-align:center;">Tu panel rápido</h2>
    <p style="text-align:center; color:var(--gris-suave); margin-bottom:40px;">Accede directamente a lo que necesitas</p>

    <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(220px,1fr)); gap:20px;">

        <a href="/kronet/public/anuncios/crear" class="how-card" style="text-decoration:none; display:flex; flex-direction:column; align-items:center; padding:30px 20px; transition:transform 0.2s, box-shadow 0.2s;">
            <div class="how-icon" style="background:var(--gradiente-kronet);">
                <i class="fas fa-plus"></i>
            </div>
            <h3 style="font-size:15px; margin-bottom:6px;">Publicar anuncio</h3>
            <p style="font-size:13px;">Ofrece o solicita un servicio</p>
        </a>

        <a href="/kronet/public/anuncios/mis-anuncios" class="how-card" style="text-decoration:none; display:flex; flex-direction:column; align-items:center; padding:30px 20px; transition:transform 0.2s, box-shadow 0.2s;">
            <div class="how-icon" style="background: linear-gradient(135deg,#00B3B3,#2ED573);">
                <i class="fas fa-list-alt"></i>
            </div>
            <h3 style="font-size:15px; margin-bottom:6px;">Mis anuncios</h3>
            <p style="font-size:13px;">Gestiona tus publicaciones</p>
        </a>

        <a href="/kronet/public/intercambios/ofertas-recibidas" class="how-card" style="text-decoration:none; display:flex; flex-direction:column; align-items:center; padding:30px 20px; transition:transform 0.2s, box-shadow 0.2s;">
            <div class="how-icon" style="background: linear-gradient(135deg,#F59E0B,#EF4444);">
                <i class="fas fa-inbox"></i>
            </div>
            <h3 style="font-size:15px; margin-bottom:6px;">Ofertas recibidas</h3>
            <p style="font-size:13px;">Revisa propuestas de intercambio</p>
        </a>

        <a href="/kronet/public/mensajes" class="how-card" style="text-decoration:none; display:flex; flex-direction:column; align-items:center; padding:30px 20px; transition:transform 0.2s, box-shadow 0.2s;">
            <div class="how-icon" style="background: linear-gradient(135deg,#6366F1,#8B5CF6);">
                <i class="fas fa-envelope"></i>
            </div>
            <h3 style="font-size:15px; margin-bottom:6px;">Mensajes</h3>
            <p style="font-size:13px;">Tu bandeja de mensajes</p>
        </a>

        <a href="/kronet/public/intercambios/mis-intercambios" class="how-card" style="text-decoration:none; display:flex; flex-direction:column; align-items:center; padding:30px 20px; transition:transform 0.2s, box-shadow 0.2s;">
            <div class="how-icon" style="background: linear-gradient(135deg,#10B981,#059669);">
                <i class="fas fa-exchange-alt"></i>
            </div>
            <h3 style="font-size:15px; margin-bottom:6px;">Mis intercambios</h3>
            <p style="font-size:13px;">Historial de intercambios</p>
        </a>

        <a href="/kronet/public/valoraciones/mis-valoraciones" class="how-card" style="text-decoration:none; display:flex; flex-direction:column; align-items:center; padding:30px 20px; transition:transform 0.2s, box-shadow 0.2s;">
            <div class="how-icon" style="background: linear-gradient(135deg,#F59E0B,#D97706);">
                <i class="fas fa-star"></i>
            </div>
            <h3 style="font-size:15px; margin-bottom:6px;">Mis valoraciones</h3>
            <p style="font-size:13px;">Tu reputación en Kronet</p>
        </a>

    </div>
</section>

<!-- ===== FOOTER ===== -->
<footer class="footer">
    <div class="footer-grid">
        <div class="footer-brand">
            <a href="/kronet/public/" class="logo-container" style="margin-bottom:12px; display:inline-flex;">
                <div class="logo-icon"><i class="fas fa-clock"></i></div>
                <span class="logo-text">Kronet</span>
            </a>
            <p>Tu banco de tiempo. Intercambia habilidades con tu comunidad usando el tiempo como moneda.</p>
        </div>
        <div class="footer-col">
            <h4>Plataforma</h4>
            <ul>
                <li><a href="/kronet/public/anuncios/buscar">Explorar servicios</a></li>
                <li><a href="/kronet/public/anuncios/crear">Publicar servicio</a></li>
                <li><a href="/kronet/public/intercambios/mis-intercambios">Mis intercambios</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h4>Mi cuenta</h4>
            <ul>
                <li><a href="/kronet/public/perfil">Mi perfil</a></li>
                <li><a href="/kronet/public/mensajes">Mensajes</a></li>
                <li><a href="/kronet/public/valoraciones/mis-valoraciones">Valoraciones</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h4>Acciones</h4>
            <ul>
                <li><a href="/kronet/public/intercambios/ofertas-recibidas">Ofertas recibidas</a></li>
                <li><a href="/kronet/public/anuncios/mis-anuncios">Mis anuncios</a></li>
                <li><a href="/kronet/public/logout" style="color:#EF4444;">Cerrar sesión</a></li>
            </ul>
        </div>
    </div>
    <div class="footer-bottom">
        <p>© <?= date('Y') ?> Kronet. Intercambia tiempo, comparte habilidades.</p>
    </div>
</footer>

</body>
</html>
