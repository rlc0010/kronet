<footer class="footer">
    <div class="footer-grid">
        <div class="footer-brand">
            <a href="/kronet/public/" class="logo-container" style="margin-bottom:12px; display:inline-flex;">
                <div class="logo-icon">
                    <img src="/kronet/public/assets/images/logo.png" alt="Kronet">
                </div>
                <span class="logo-text">Kronet</span>
            </a>
            <p>Tu banco de tiempo. Intercambia habilidades con tu comunidad usando el tiempo como moneda.</p>
        </div>
        <div class="footer-col">
            <h4>Plataforma</h4>
            <ul>
                <li><a href="/kronet/public/anuncios/buscar">Explorar servicios</a></li>
                <li><a href="/kronet/public/anuncios/crear">Publicar servicio</a></li>
                <li><a href="/kronet/public/suscripcion">Hazte Premium</a></li>
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

<div class="toast-container" id="toastContainer"></div>

<script src="/kronet/public/assets/js/kronet.js"></script>
</body>
</html>
