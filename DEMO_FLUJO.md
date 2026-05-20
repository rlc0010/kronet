# KRONET — Guía de presentación completa

> **Datos de prueba:** importar `DEMO_DATOS.sql` en la base de datos `kronet_db` antes de la demo.  
> **Contraseña de todos los usuarios:** `Kronet123`  
> **URL base:** `http://localhost/kronet/public/`

---

## Resumen del sistema

Kronet es un **banco de tiempo** comunitario. Los usuarios intercambian habilidades usando **créditos de tiempo** como moneda:
- 1 crédito = 1 hora de servicio básico (servicios especializados valen más)
- Al registrarse se reciben **5 créditos** de bienvenida
- El sistema calcula el precio automáticamente según categoría y duración

---

## BLOQUE 1 — Vista pública (usuario anónimo)

### 1.1 Página de inicio
- Ir a `http://localhost/kronet/public/`
- **Qué se ve:** hero de bienvenida, sección "Cómo funciona" (3 pasos), botones de registro/login
- **Puntos clave:** la web es **pública para explorar**, login solo necesario para actuar

### 1.2 Explorar anuncios sin cuenta
- Clic en **"Explorar"** en la navbar
- **Qué se ve:** listado de anuncios activos, barra de búsqueda y filtros (tipo + categoría)
- **Demostrar:**
  - Filtrar por tipo "Oferta" → ver todos los servicios que se ofrecen
  - Filtrar por tipo "Demanda" → ver lo que la gente necesita
  - Filtrar por categoría "Idiomas" → aparecen los anuncios de Laura
  - Buscar "guitarra" → aparece Sofía

### 1.3 Ficha de anuncio pública
- Clic en cualquier anuncio (p. ej. "Clases de inglés" de Laura)
- **Qué se ve:** título, descripción completa, precio en créditos, plazas, datos del ofertante
- **Nota:** los botones de acción (solicitar, contactar) redirigen al login → sistema seguro

---

## BLOQUE 2 — Registro e inicio de sesión

### 2.1 Registro de nuevo usuario
- Clic en **"Registrarse"**
- Completar formulario: nombre, email, contraseña (`Kronet123` para demostrar validación)
- **Validaciones en tiempo real:** longitud, mayúscula, minúscula, número
- Al registrar → cuenta activa con **5 créditos** iniciales

### 2.2 Login con usuario existente
- Ir a `http://localhost/kronet/public/login`
- Probar primero con credenciales **incorrectas** → mensaje de error claro
- Luego entrar con `carlos@demo.com` / `Kronet123`
- **Toast de confirmación** + redirige al inicio ya logueado

---

## BLOQUE 3 — Perfil de usuario

### 3.1 Ver mi perfil
- Clic en el avatar (letra "C") arriba a la derecha
- **Qué se ve:**
  - Información personal: nombre, descripción, valoración media
  - **Saldo de créditos** (Carlos tiene 18h)
  - Estadísticas: intercambios confirmados, pendientes, anuncios publicados
  - Historial de intercambios
  - Botones de acción rápida

### 3.2 Editar perfil
- Clic en **"Editar perfil"** → despliega formulario inline con scroll automático
- Cambiar la descripción → clic **"Guardar cambios"**
- **Toast de confirmación** → página se actualiza

---

## BLOQUE 4 — Gestión de anuncios

### 4.1 Publicar un anuncio
- Clic en **"Publicar"** en la navbar (requiere login)
- Rellenar formulario:
  - Tipo: **Oferta** (ofrezco) o **Demanda** (busco)
  - Categoría: ej. "Tecnología"
  - Duración: 2 horas
  - Plazas: 1
- **El precio en créditos se calcula automáticamente** (categoría × multiplicador)
  - Tecnología × 2h = **4 créditos**
  - Reparaciones × 2h = **5 créditos** (más especializado = vale más)
- Publicar → toast de éxito + redirige al anuncio creado

### 4.2 Mis anuncios
- Menú **"Mis anuncios"** → listado con estado (activo/completo/cancelado)
- Opciones por anuncio:
  - **Editar:** cambiar título, descripción, duración
  - **Destacar:** pagar para aparecer primero en búsquedas
  - **Eliminar**

### 4.3 Destacar un anuncio
- Entrar como `laura@demo.com` (Premium)
- Ir a "Mis anuncios" → clic **"Destacar"** en un anuncio
- **Los Premium tienen 1 destacado gratuito por semana** (ya usado: descuento)
- Los no-premium pagan 2.50€ simulados
- Anuncios destacados aparecen primero en búsquedas

---

## BLOQUE 5 — Intercambios (el núcleo del sistema)

### 5.1 Solicitar un intercambio
- Entrar como `maria@demo.com` (5 créditos)
- Buscar anuncio de "Reparación ordenadores" de Carlos
- Clic **"Solicitar intercambio"** en la ficha
- **Sistema comprueba saldo:** si no hay suficientes créditos → error claro
- Si hay saldo → intercambio queda en estado **"Pendiente"**
- El ofertante recibe una **notificación automática**

### 5.2 Ver ofertas recibidas y aceptar/rechazar
- Entrar como `carlos@demo.com` (tiene solicitud pendiente de María)
- Badge rojo en "Mensajes" indica pendientes
- Ir a **"Mis Servicios"** → **"Ofertas recibidas"**
- Ver la solicitud de María con sus datos
- **Aceptar** → los créditos se transfieren automáticamente:
  - María pierde créditos
  - Carlos gana créditos
  - Estado cambia a "Confirmado"
- **Rechazar** → estado "Cancelado", créditos no se mueven

### 5.3 Ver historial de intercambios
- **"Mis Servicios"** → listado de todos los intercambios (como ofertante o solicitante)
- Se ven estados: pendiente, confirmado, cancelado
- En los confirmados aparece botón **"Valorar usuario"**

---

## BLOQUE 6 — Mensajes

### 6.1 Enviar un mensaje
- Entrar como `maria@demo.com`
- Ir a la ficha de cualquier anuncio → clic **"Enviar mensaje"**
- Escribir mensaje → se envía vía AJAX (sin recargar)
- El receptor recibe **notificación**

### 6.2 Bandeja de mensajes
- Clic en **"Mensajes"** en la navbar
- **Qué se ve:** listado de conversaciones agrupadas por anuncio
- Cada conversación muestra: otro usuario, último mensaje, fecha, no leídos
- Clic en conversación → hilo de mensajes estilo chat
- Los mensajes se marcan como leídos automáticamente

### 6.3 Badge de no leídos
- El número rojo en el icono de mensajes se actualiza al entrar a cada conversación
- Entrar como `carlos@demo.com` → badge muestra 1 (mensaje de María sin leer)

---

## BLOQUE 7 — Valoraciones

### 7.1 Valorar a otro usuario
- Solo se puede valorar tras un **intercambio confirmado**
- **"Mis Servicios"** → intercambios confirmados → clic **"Valorar usuario"**
- Formulario: puntuación 1-5 estrellas + comentario opcional
- Aparece en el perfil del valorado (promedio visible)

### 7.2 Ver mis valoraciones
- **Perfil** → sección de estadísticas → badge con nota media y nº reseñas
- **"Mis valoraciones"** en el menú del perfil → lista completa de valoraciones recibidas
- Entrar como `laura@demo.com` → tiene 5 ⭐ de Carlos

---

## BLOQUE 8 — Suscripción Premium

### 8.1 Ver ventajas Premium
- Clic en **"Hazte Premium"** (en perfil o navbar de usuario no suscrito)
- **Ventajas explicadas:** 1 destacado gratuito/semana, mayor visibilidad
- Precio: **5€/mes** (simulado)

### 8.2 Activar suscripción
- Elegir método de pago: tarjeta / PayPal / Bizum (todos simulados)
- Clic **"Activar Premium"**
- Toast de confirmación + pago registrado + tipo de usuario cambia a "suscrito"
- Notificación de bienvenida Premium

### 8.3 Cancelar suscripción
- Misma pantalla → aparece botón **"Cancelar suscripción"**
- Al cancelar → tipo de usuario vuelve a "registrado"

---

## BLOQUE 9 — Notificaciones

### 9.1 Ver notificaciones
- Icono de campana en navbar (si se implementó) o menú de perfil → **"Notificaciones"**
- **URL:** `http://localhost/kronet/public/notificaciones`
- Listado cronológico de: mensajes nuevos, valoraciones, intercambios, sistema
- Las no leídas se resaltan visualmente

### 9.2 Marcar todas como leídas
- Botón **"Marcar todas como leídas"** → AJAX, sin recarga de página

---

## BLOQUE 10 — Perfil ajeno y contactos

### 10.1 Ver perfil de otro usuario
- Desde la ficha de un anuncio → clic en el nombre del anunciante
- **URL:** `http://localhost/kronet/public/perfil/{id}`
- **Qué se ve:** nombre, descripción, valoración media, anuncios activos, reseñas
- **Acciones:** Añadir a contactos / Bloquear usuario / Denunciar anuncio

### 10.2 Contactos y bloqueos
- **Añadir contacto:** guarda relación bidireccional entre usuarios
- **Bloquear:** impide mensajes y aparece en lista de bloqueados
- **Desbloquear:** desde perfil ajeno si ya estaba bloqueado

---

## BLOQUE 11 — Seguridad y sesión

### 11.1 Control de acceso
- Todas las rutas de acción (publicar, intercambiar, mensajes) **requieren login**
- Los anónimos son redirigidos al login; las llamadas AJAX reciben JSON 401
- Las sesiones se validan en cada petición (usuario borrado o bloqueado → cierre automático)

### 11.2 Cierre de sesión
- Clic en **"Cerrar sesión"** en perfil o footer
- Sesión destruida → redirige al login

---

## FLUJO RECOMENDADO PARA LA PRESENTACIÓN (10 min)

```
PASO 1 — Concepto y exploración (1:30 min)
  · Abrir http://localhost/kronet/public/ → explicar en 2 frases: banco de tiempo,
    créditos = horas, sin dinero real.
  · Clic "Explorar" → mostrar el listado de anuncios variados (ofertas y demandas).
  · Filtrar por "Idiomas" → aparecen los anuncios de Laura.
  · Clic en "Clases de inglés" → mostrar ficha. Intentar "Solicitar" → pide login.
    → MENSAJE: "cualquiera puede ver, solo actúan los registrados."

PASO 2 — Login y perfil (1:30 min)
  · Login con carlos@demo.com / Kronet123 → toast de bienvenida.
  · Clic avatar "C" → mostrar perfil: 18 créditos, historial de intercambios,
    valoraciones recibidas (5⭐ de Laura).
  · Clic "Editar perfil" → formulario aparece, editar descripción, Guardar → toast OK.

PASO 3 — Publicar un anuncio (1:30 min)
  · Navbar "Publicar" → formulario de nuevo anuncio.
  · Rellenar: tipo Oferta, título "Soporte Linux", categoría Tecnología, 2 horas.
  · Mostrar que el PRECIO SE CALCULA SOLO: Tecnología × 2h = 4 créditos.
  · Publicar → redirige a la ficha del anuncio recién creado.

PASO 4 — Solicitar un intercambio (2 min)
  · Logout → login con maria@demo.com / Kronet123 (5 créditos, usuaria nueva).
  · Buscar anuncio "Reparación de ordenadores" de Carlos → Solicitar intercambio.
  · Mostrar que el sistema descuenta créditos al solicitante y queda en "Pendiente".
  · → MENSAJE: "Carlos recibirá una notificación y decidirá si acepta o rechaza."

PASO 5 — Aceptar y transferir créditos (2 min)
  · Logout → login con carlos@demo.com.
  · Badge rojo en Mensajes → ir a "Mis Servicios" → "Ofertas recibidas".
  · Ver la solicitud de María → clic "Aceptar".
  · → Los créditos se transfieren automáticamente (María pierde, Carlos gana).
  · Ir a "Mis Servicios" → historial → intercambio aparece como "Confirmado".
  · Clic "Valorar usuario" → dar 5⭐ a María + comentario → Guardar.

PASO 6 — Premium y cierre (1:30 min)
  · Logout → login con laura@demo.com / Kronet123 (Premium, 42 créditos).
  · Mostrar icono corona en perfil y el destacado gratuito semanal.
  · Clic "Mensajes" → mostrar la conversación ya existente con Carlos.
  · Ir a "Notificaciones" → mostrar el listado (valoración, sistema Premium...).
  · Logout → resumen: "Sistema completo, sin dinero real, solo tiempo compartido."
```

---

## Usuarios de demostración

| Email | Contraseña | Tipo | Créditos | Perfil |
|-------|------------|------|----------|--------|
| admin@kronet.com | Kronet123 | Admin | 999 | Administración |
| laura@demo.com | Kronet123 | **Premium** | 42 | Profesora idiomas |
| carlos@demo.com | Kronet123 | Registrado | 18 | Técnico IT |
| sofia@demo.com | Kronet123 | Registrado | 27 | Profesora guitarra |
| miguel@demo.com | Kronet123 | Registrado | 35 | Fontanero/electricista |
| ana@demo.com | Kronet123 | **Premium** | 53 | Chef/cocinera |
| pablo@demo.com | Kronet123 | Registrado | 12 | Diseñador gráfico |
| elena@demo.com | Kronet123 | Registrado | 22 | Cuidadora |
| javier@demo.com | Kronet123 | Registrado | 8 | Jardinero+transporte |
| maria@demo.com | Kronet123 | Registrado | 5 | Nueva usuaria |

---

## Anuncios destacados para la demo

| # | Anuncio | Tipo | Categoría | Precio | Estado |
|---|---------|------|-----------|--------|--------|
| 1 | Clases de inglés A1-B2 | Oferta | Idiomas | 3h | Activo ⭐ |
| 3 | Reparación de ordenadores | Oferta | Tecnología | 4h | Activo ⭐ |
| 5 | Clases de guitarra | Oferta | Música | 2h | Activo |
| 7 | Fontanería — averías | Oferta | Reparaciones | 5h | Activo |
| 9 | Taller de repostería | Oferta | Cocina | 3h | Activo ⭐ |
| 18 | Busco clases inglés | Demanda | Idiomas | 2h | Activo |
| 19 | Necesito fontanero | Demanda | Reparaciones | 3h | Activo |
| 25 | Taller inglés 3 personas | Oferta | Idiomas | 3h | **Completo** |

---

## Multiplicadores de precio por categoría

| Categoría | Multiplicador | Ejemplo (2h) |
|-----------|:---:|:---:|
| Reparaciones (fontanería, electricidad) | ×2.5 | 5 créditos |
| Tecnología / Salud | ×2.0 | 4 créditos |
| Asesoramiento | ×1.8 | 4 créditos |
| Idiomas / Educación / Música | ×1.5 | 3 créditos |
| Arte / Cuidados | ×1.3 | 3 créditos |
| Deporte | ×1.2 | 2 créditos |
| Cocina / Jardinería / Hogar / Mascotas / Transporte | ×1.0–1.1 | 2 créditos |
