-- ============================================================
-- KRONET — DATOS DE DEMOSTRACIÓN
-- Contraseña de todos los usuarios: Kronet123
-- Hash: $2y$10$9zNcNkyXjg1juCBF.2b/9OuOTKTtQfnVkD1RD6H8O77cGsT91QEfe
-- ============================================================

SET FOREIGN_KEY_CHECKS = 0;
SET NAMES utf8mb4;

-- ============================================================
-- LIMPIAR DATOS PREVIOS (sólo datos, no estructura)
-- ============================================================
DELETE FROM notificaciones;
DELETE FROM denuncias;
DELETE FROM bloqueos;
DELETE FROM contactos;
DELETE FROM pagos;
DELETE FROM suscripciones;
DELETE FROM valoraciones;
DELETE FROM mensajes;
DELETE FROM intercambios;
DELETE FROM anuncios;
DELETE FROM usuarios;

SET FOREIGN_KEY_CHECKS = 1;
ALTER TABLE usuarios       AUTO_INCREMENT = 1;
ALTER TABLE anuncios        AUTO_INCREMENT = 1;
ALTER TABLE intercambios    AUTO_INCREMENT = 1;
ALTER TABLE mensajes        AUTO_INCREMENT = 1;
ALTER TABLE valoraciones    AUTO_INCREMENT = 1;
ALTER TABLE suscripciones   AUTO_INCREMENT = 1;
ALTER TABLE contactos       AUTO_INCREMENT = 1;
ALTER TABLE bloqueos        AUTO_INCREMENT = 1;
ALTER TABLE notificaciones  AUTO_INCREMENT = 1;
ALTER TABLE pagos           AUTO_INCREMENT = 1;

-- ============================================================
-- USUARIOS (10 perfiles variados)
-- Contraseña para todos: Kronet123
-- ============================================================
INSERT INTO usuarios
  (id_usuario, nombre, email, contrasenia_hash, descripcion, tipo_usuario,
   fecha_registro, saldo_monedas, estado_cuenta)
VALUES
-- 1 — Admin
(1,
 'Admin Kronet',
 'admin@kronet.com',
 '$2y$10$9zNcNkyXjg1juCBF.2b/9OuOTKTtQfnVkD1RD6H8O77cGsT91QEfe',
 'Cuenta de administración de la plataforma Kronet.',
 'admin',
 '2024-01-01 09:00:00', 999, 'activa'),

-- 2 — Suscrito (premium) con muchos créditos
(2,
 'Laura Fernández',
 'laura@demo.com',
 '$2y$10$9zNcNkyXjg1juCBF.2b/9OuOTKTtQfnVkD1RD6H8O77cGsT91QEfe',
 'Profesora de inglés y francés. Apasionada de los idiomas y la enseñanza. Imparto clases particulares a todos los niveles.',
 'suscrito',
 '2024-02-10 10:30:00', 42, 'activa'),

-- 3 — Registrado, técnico IT
(3,
 'Carlos Ruiz',
 'carlos@demo.com',
 '$2y$10$9zNcNkyXjg1juCBF.2b/9OuOTKTtQfnVkD1RD6H8O77cGsT91QEfe',
 'Desarrollador web y técnico en soporte informático. Más de 10 años de experiencia. Ayudo con ordenadores, redes y desarrollo de páginas web.',
 'registrado',
 '2024-02-15 11:00:00', 18, 'activa'),

-- 4 — Registrado, músico
(4,
 'Sofía Martín',
 'sofia@demo.com',
 '$2y$10$9zNcNkyXjg1juCBF.2b/9OuOTKTtQfnVkD1RD6H8O77cGsT91QEfe',
 'Guitarrista y profesora de música. Doy clases de guitarra clásica y moderna para principiantes y nivel medio. ¡La música conecta personas!',
 'registrado',
 '2024-03-01 09:15:00', 27, 'activa'),

-- 5 — Registrado, fontanero
(5,
 'Miguel Gómez',
 'miguel@demo.com',
 '$2y$10$9zNcNkyXjg1juCBF.2b/9OuOTKTtQfnVkD1RD6H8O77cGsT91QEfe',
 'Fontanero y electricista autónomo con 15 años de experiencia. Reparaciones del hogar, instalaciones y mantenimiento. Trabajo serio y de calidad.',
 'registrado',
 '2024-03-10 08:00:00', 35, 'activa'),

-- 6 — Suscrito, cocinera
(6,
 'Ana López',
 'ana@demo.com',
 '$2y$10$9zNcNkyXjg1juCBF.2b/9OuOTKTtQfnVkD1RD6H8O77cGsT91QEfe',
 'Chef aficionada especializada en cocina mediterránea y repostería. Ofrezco talleres de cocina en casa y preparación de menús para eventos.',
 'suscrito',
 '2024-03-20 12:00:00', 53, 'activa'),

-- 7 — Registrado, diseñador
(7,
 'Pablo Torres',
 'pablo@demo.com',
 '$2y$10$9zNcNkyXjg1juCBF.2b/9OuOTKTtQfnVkD1RD6H8O77cGsT91QEfe',
 'Diseñador gráfico freelance. Logos, carteles, identidad visual e ilustración digital. Hago realidad tus ideas visuales.',
 'registrado',
 '2024-04-05 14:30:00', 12, 'activa'),

-- 8 — Registrado, cuidadora
(8,
 'Elena Sánchez',
 'elena@demo.com',
 '$2y$10$9zNcNkyXjg1juCBF.2b/9OuOTKTtQfnVkD1RD6H8O77cGsT91QEfe',
 'Cuidadora de personas mayores y niños con experiencia. Titulada en auxiliar de enfermería. Paciente, responsable y cariñosa.',
 'registrado',
 '2024-04-12 09:45:00', 22, 'activa'),

-- 9 — Registrado, jardinero y transporte
(9,
 'Javier Moreno',
 'javier@demo.com',
 '$2y$10$9zNcNkyXjg1juCBF.2b/9OuOTKTtQfnVkD1RD6H8O77cGsT91QEfe',
 'Jardinero aficionado con furgoneta. Mantenimiento de jardines, poda, plantación y mudanzas pequeñas. Disponible fines de semana.',
 'registrado',
 '2024-04-20 16:00:00', 8, 'activa'),

-- 10 — Registrado, nuevo usuario (pocos créditos)
(10,
 'María Castro',
 'maria@demo.com',
 '$2y$10$9zNcNkyXjg1juCBF.2b/9OuOTKTtQfnVkD1RD6H8O77cGsT91QEfe',
 'Recién llegada a Kronet. Busco clases de idiomas y ayuda con el ordenador. ¡Puedo ayudar con paseos de mascotas!',
 'registrado',
 '2024-05-01 17:30:00', 5, 'activa');

-- ============================================================
-- ANUNCIOS — OFERTAS (el dueño ofrece su servicio)
-- precio_creditos = duración * multiplicador de categoría
-- ============================================================
INSERT INTO anuncios
  (id_anuncio, id_usuario, titulo, descripcion, tipo_anuncio, categoria,
   duracion_estimada, precio_creditos, plazas_totales, plazas_ocupadas,
   fecha_publicacion, estado, destacado, destacado_hasta)
VALUES

-- Idiomas
(1, 2, 'Clases de inglés — nivel A1 a B2',
 'Imparto clases personalizadas de inglés para principiantes y nivel intermedio. Metodología comunicativa, material propio y ejercicios prácticos adaptados a tu ritmo. Primera clase de prueba sin compromiso.',
 'oferta', 'idiomas', 2, 3, 3, 1,
 '2024-05-02 10:00:00', 'activo', 1, '2025-06-01 00:00:00'),

(2, 2, 'Conversación en francés para viajeros',
 'Sesión práctica de conversación en francés enfocada en situaciones reales: restaurantes, transporte, compras y turismo. Ideal antes de un viaje a Francia.',
 'oferta', 'idiomas', 1, 2, 4, 0,
 '2024-05-03 11:00:00', 'activo', 0, NULL),

-- Tecnología
(3, 3, 'Reparación y limpieza de ordenadores',
 'Elimino virus, optimizo el rendimiento, instalo y configuro Windows y programas. Servicio a domicilio o en remoto. PC y Mac.',
 'oferta', 'tecnologia', 2, 4, 2, 0,
 '2024-05-04 09:30:00', 'activo', 1, '2025-06-15 00:00:00'),

(4, 3, 'Creación de página web básica',
 'Diseño y desarrollo de una landing page o web corporativa sencilla con HTML/CSS/WordPress. Incluye formulario de contacto y adaptación móvil.',
 'oferta', 'tecnologia', 4, 8, 1, 0,
 '2024-05-05 14:00:00', 'activo', 0, NULL),

-- Música
(5, 4, 'Clases de guitarra para principiantes',
 'Aprende guitarra desde cero. Enseño acordes, rasgueos, lectura de tablaturas y canciones sencillas. Clases de 1 hora en mi domicilio o el tuyo (zona centro).',
 'oferta', 'musica', 1, 2, 3, 2,
 '2024-05-06 10:00:00', 'activo', 0, NULL),

(6, 4, 'Ensayo y grabación de maqueta',
 'Ayudo a grupos o solistas a preparar una maqueta de 3 canciones. Dispongo de equipo de grabación básico. Perfecto para bandas que quieren demostrar su trabajo.',
 'oferta', 'musica', 3, 5, 2, 0,
 '2024-05-07 16:00:00', 'activo', 0, NULL),

-- Reparaciones
(7, 5, 'Reparación de averías de fontanería',
 'Arreglo grifos que gotean, desagües atascados, cisternas rotas y pequeñas tuberías. Material a cargo del cliente. Disponible entre semana.',
 'oferta', 'reparaciones', 2, 5, 1, 1,
 '2024-05-08 08:00:00', 'activo', 0, NULL),

(8, 5, 'Instalación eléctrica básica',
 'Cambio de enchufes, interruptores, instalación de luminarias y revisión del cuadro eléctrico. Todo conforme a normativa. Solicita presupuesto primero.',
 'oferta', 'reparaciones', 3, 8, 1, 0,
 '2024-05-09 08:30:00', 'activo', 0, NULL),

-- Cocina
(9, 6, 'Taller de repostería casera',
 'Aprende a hacer tartas, galletas y bizcochos esponjosos en casa. Taller de 2 horas, máximo 4 personas. Ingredientes incluidos. ¡Nos comemos lo que hacemos!',
 'oferta', 'cocina', 2, 3, 4, 3,
 '2024-05-10 12:00:00', 'activo', 1, '2025-05-30 00:00:00'),

(10, 6, 'Menú mediterráneo para 4 personas',
 'Preparo un menú completo de 3 platos de cocina mediterránea en tu domicilio. Incluye entrante, plato principal y postre. Ingredientes a cargo del cliente.',
 'oferta', 'cocina', 3, 4, 1, 0,
 '2024-05-11 13:00:00', 'activo', 0, NULL),

-- Arte y diseño
(11, 7, 'Diseño de logotipo profesional',
 'Creo tu logotipo desde cero: estudio de marca, propuestas de diseño y entrega en formatos vectoriales (AI, PDF, PNG). Revisiones incluidas.',
 'oferta', 'arte', 3, 4, 2, 0,
 '2024-05-12 15:00:00', 'activo', 0, NULL),

(12, 7, 'Cartel o flyer para evento',
 'Diseño un cartel o flyer para tu evento, negocio o actividad. Formato digital listo para imprimir o redes sociales. Entrega en 48 horas.',
 'oferta', 'arte', 1, 2, 5, 1,
 '2024-05-13 10:30:00', 'activo', 0, NULL),

-- Cuidados
(13, 8, 'Cuidado de personas mayores a domicilio',
 'Acompañamiento, ayuda con higiene personal, preparación de comidas y toma de medicación. Disponible mañanas entre semana. Referencias disponibles.',
 'oferta', 'cuidados', 3, 4, 2, 0,
 '2024-05-14 09:00:00', 'activo', 0, NULL),

(14, 8, 'Cuidado de niños (0-6 años)',
 'Cuido a tus hijos en tu domicilio mientras trabajas o tienes una cita. Titulada en auxiliar de enfermería, experiencia con bebés y niños pequeños.',
 'oferta', 'cuidados', 2, 3, 1, 0,
 '2024-05-15 09:30:00', 'activo', 0, NULL),

-- Jardinería
(15, 9, 'Poda y mantenimiento de jardín',
 'Podo árboles y arbustos, siego el césped, planto flores de temporada y ordeno el jardín. Herramientas propias. Zona metropolitana.',
 'oferta', 'jardineria', 3, 3, 2, 0,
 '2024-05-16 08:00:00', 'activo', 0, NULL),

-- Transporte
(16, 9, 'Mudanza o transporte de muebles',
 'Dispongo de furgoneta de 8 m³. Ayudo con mudanzas pequeñas, transporte de muebles o recogida en tiendas. Desmontaje y montaje básico incluido.',
 'oferta', 'transporte', 2, 2, 1, 0,
 '2024-05-17 09:00:00', 'activo', 0, NULL),

-- Mascotas
(17, 10, 'Paseos de perros (1 hora)',
 'Saco a pasear a tu perro 1 hora diaria por el parque. Puntual y responsable. Zona norte de la ciudad. Máximo 2 perros simultáneos.',
 'oferta', 'mascotas', 1, 1, 2, 0,
 '2024-05-18 10:00:00', 'activo', 0, NULL),

-- ============================================================
-- ANUNCIOS — DEMANDAS (el dueño pide un servicio)
-- ============================================================

(18, 3, 'Busco clases de inglés conversacional',
 'Necesito mejorar mi inglés hablado para entrevistas de trabajo. Busco sesiones semanales de 1 hora, preferiblemente online o zona centro.',
 'demanda', 'idiomas', 1, 2, 1, 0,
 '2024-05-08 10:00:00', 'activo', 0, NULL),

(19, 7, 'Necesito fontanero para fuga de agua',
 'Tengo una pequeña fuga bajo el fregadero de la cocina. Necesito que lo revisen esta semana si es posible. Piso en el centro.',
 'demanda', 'reparaciones', 1, 3, 1, 0,
 '2024-05-09 11:30:00', 'activo', 0, NULL),

(20, 9, 'Busco clases de guitarra para mi hijo',
 'Mi hijo de 12 años quiere aprender guitarra. Busco profesor/a paciente con niños. Clases en casa, 1 vez por semana.',
 'demanda', 'musica', 1, 2, 1, 1,
 '2024-05-10 09:00:00', 'activo', 0, NULL),

(21, 10, 'Necesito ayuda con mi ordenador lento',
 'Mi portátil va muy lento y se cuelga. Necesito que alguien lo revise y lo limpie de virus. Preferiblemente a domicilio.',
 'demanda', 'tecnologia', 1, 2, 1, 0,
 '2024-05-11 12:00:00', 'activo', 0, NULL),

(22, 5, 'Busco clases de cocina mediterránea',
 'Me encantaría aprender a cocinar platos mediterráneos saludables. Busco alguien que me enseñe en mi cocina, 2 horas los sábados.',
 'demanda', 'cocina', 2, 3, 1, 0,
 '2024-05-12 14:00:00', 'activo', 0, NULL),

(23, 6, 'Busco diseñador para logo de mi negocio',
 'Abro una pequeña tienda de productos naturales y necesito un logo sencillo y profesional. Tengo referencias de estilo.',
 'demanda', 'arte', 2, 3, 1, 1,
 '2024-05-13 16:00:00', 'activo', 0, NULL),

(24, 4, 'Necesito transporte de piano',
 'Tengo un piano vertical que mover desde el piso 2 (con ascensor) a otro domicilio a 5 km. Necesito furgoneta grande y una persona de apoyo.',
 'demanda', 'transporte', 2, 2, 1, 0,
 '2024-05-14 11:00:00', 'activo', 0, NULL),

-- Anuncio completado (plazas llenas)
(25, 2, 'Taller de inglés para 3 personas',
 'Organizo un taller de inglés intensivo para 3 personas a la vez. Fines de semana, 2 horas cada sesión. Nivel A2-B1.',
 'oferta', 'idiomas', 2, 3, 3, 3,
 '2024-04-01 10:00:00', 'completo', 0, NULL),

-- Anuncio cancelado
(26, 3, 'Soporte técnico en empresa',
 'Ofrezco soporte IT a pequeñas empresas una tarde por semana. Mantenimiento preventivo de equipos.',
 'oferta', 'tecnologia', 4, 8, 1, 0,
 '2024-04-10 10:00:00', 'cancelado', 0, NULL);

-- ============================================================
-- SUSCRIPCIONES (usuarios 2 y 6 son premium)
-- ============================================================
INSERT INTO suscripciones
  (id_suscripcion, id_usuario, fecha_inicio, fecha_fin,
   destacados_usados_semana, semana_destacados, estado)
VALUES
(1, 2, '2025-01-15 00:00:00', '2025-07-15 00:00:00', 1, '2025-05-12', 'activa'),
(2, 6, '2025-03-01 00:00:00', '2025-09-01 00:00:00', 0, '2025-05-12', 'activa');

-- ============================================================
-- INTERCAMBIOS
-- ============================================================
INSERT INTO intercambios
  (id_intercambio, id_anuncio, id_usuario_ofertante, id_usuario_solicitante,
   fecha_inicio, fecha_fin, monedas_intercambio,
   id_usuario_pagador, id_usuario_receptor, estado)
VALUES
-- Confirmado: Carlos pide clase de inglés a Laura (oferta, paga Carlos)
(1, 1, 2, 3, '2024-05-10 10:00:00', '2024-05-11 10:00:00',
 3, 3, 2, 'confirmado'),

-- Confirmado: Javier pide clase de guitarra a Sofía (oferta, paga Javier)
(2, 5, 4, 9, '2024-05-12 09:00:00', '2024-05-13 11:00:00',
 2, 9, 4, 'confirmado'),

-- Confirmado: Ana pide reparación fontanería a Miguel (oferta, paga Ana)
(3, 7, 5, 6, '2024-05-13 08:00:00', '2024-05-14 09:00:00',
 5, 6, 5, 'confirmado'),

-- Pendiente: María solicita reparación de ordenador a Carlos (demanda de María, paga Carlos)
(4, 21, 10, 3, '2024-05-18 12:00:00', NULL,
 2, 3, 10, 'pendiente'),

-- Pendiente: Pablo solicita a Miguel que arregle la fuga
(5, 19, 7, 5, '2024-05-17 11:00:00', NULL,
 5, 7, 5, 'pendiente'),

-- Cancelado: intento de taller que no llegó a confirmarse
(6, 9, 6, 7, '2024-04-20 12:00:00', NULL,
 2, 7, 6, 'cancelado');

-- ============================================================
-- MENSAJES (conversaciones entre usuarios)
-- ============================================================
INSERT INTO mensajes
  (id_mensaje, id_anuncio, id_emisor, id_receptor,
   contenido, fecha_envio, leido)
VALUES
-- Conversación Carlos ↔ Laura sobre clases de inglés (anuncio 1)
(1, 1, 3, 2, '¡Hola Laura! Me interesa tu anuncio de clases de inglés. ¿Tienes disponibilidad los martes por la tarde?', '2024-05-10 10:05:00', 1),
(2, 1, 2, 3, 'Hola Carlos, claro que sí. Los martes a partir de las 18h me van bien. ¿Empezamos la semana que viene?', '2024-05-10 10:30:00', 1),
(3, 1, 3, 2, 'Perfecto, el martes que viene a las 18h. ¿Dónde quedamos?', '2024-05-10 11:00:00', 1),
(4, 1, 2, 3, 'Puedo ir a tu casa o darte la clase online por videollamada, tú decides.', '2024-05-10 11:15:00', 1),
(5, 1, 3, 2, '¡Mejor online! Te mando el enlace el mismo día. ¡Gracias!', '2024-05-10 11:20:00', 1),

-- Conversación Sofía ↔ Javier sobre guitarra (anuncio 5)
(6, 5, 9, 4, 'Hola Sofía, vi tu anuncio de clases de guitarra. ¿Las das para niños de 12 años también?', '2024-05-12 09:10:00', 1),
(7, 5, 4, 9, 'Hola Javier, sí, trabajo con niños. Es una edad perfecta para empezar. ¿Qué horario te va mejor?', '2024-05-12 09:45:00', 1),
(8, 5, 9, 4, 'Los sábados por la mañana nos vienen perfecto. ¿Quedamos a las 11h?', '2024-05-12 10:00:00', 1),
(9, 5, 4, 9, '¡Genial! El próximo sábado en tu domicilio. Mándame la dirección cuando puedas.', '2024-05-12 10:30:00', 1),

-- Conversación María ↔ Carlos sobre el ordenador (anuncio 21)
(10, 21, 10, 3, 'Hola Carlos, mi portátil va fatal. ¿Podrías venir a verlo esta semana?', '2024-05-18 12:05:00', 1),
(11, 21, 3, 10, 'Hola María, claro. Esta tarde si quieres. ¿A qué hora te viene bien?', '2024-05-18 12:30:00', 0),

-- Conversación Pablo ↔ Miguel sobre la fuga (anuncio 19)
(12, 19, 7, 5, 'Hola Miguel, tengo una fuga de agua bajo el fregadero. ¿Cuándo puedes venir?', '2024-05-17 11:05:00', 1),
(13, 19, 5, 7, 'Mañana por la mañana puedo. ¿A las 9h te va bien?', '2024-05-17 11:40:00', 1),
(14, 19, 7, 5, 'Perfecto, mañana a las 9h. Te mando la dirección por aquí.', '2024-05-17 12:00:00', 1),
(15, 19, 7, 5, 'Calle Mayor 12, 3ºB. Código de portal: 1234.', '2024-05-17 12:01:00', 1);

-- ============================================================
-- VALORACIONES
-- ============================================================
INSERT INTO valoraciones
  (id_valoracion, id_usuario_autor, id_usuario_destino,
   puntuacion, comentario, fecha)
VALUES
-- Carlos valora a Laura (tras intercambio 1)
(1, 3, 2, 5,
 'Clase de inglés excelente. Laura es muy paciente y explica todo con mucha claridad. Totalmente recomendable.',
 '2024-05-12 09:00:00'),

-- Laura valora a Carlos (intercambio 1 recíproco)
(2, 2, 3, 5,
 'Carlos es un alumno muy aplicado y puntual. ¡Un placer!',
 '2024-05-12 10:00:00'),

-- Javier valora a Sofía (tras intercambio 2)
(3, 9, 4, 5,
 'Mi hijo está encantado con las clases de guitarra. Sofía es una profesora increíble, muy buena con los niños.',
 '2024-05-14 11:00:00'),

-- Sofía valora a Javier
(4, 4, 9, 4,
 'Familia muy agradable. El niño tiene mucho potencial musical.',
 '2024-05-14 12:00:00'),

-- Ana valora a Miguel (tras intercambio 3)
(5, 6, 5, 4,
 'Miguel solucionó la avería rápido y sin líos. Trabajo limpio y muy buen precio en créditos. Repetiré.',
 '2024-05-15 09:00:00'),

-- Miguel valora a Ana
(6, 5, 6, 5,
 'Ana es una clienta estupenda, muy amable y clara con lo que necesitaba. ¡Gracias!',
 '2024-05-15 10:00:00');

-- ============================================================
-- CONTACTOS
-- ============================================================
INSERT INTO contactos (id_contacto, id_usuario, id_usuario_amigo, fecha)
VALUES
(1, 3, 2, '2024-05-11 09:00:00'),  -- Carlos ↔ Laura
(2, 2, 3, '2024-05-11 09:01:00'),
(3, 9, 4, '2024-05-13 11:00:00'),  -- Javier ↔ Sofía
(4, 4, 9, '2024-05-13 11:01:00'),
(5, 6, 5, '2024-05-14 09:30:00'),  -- Ana ↔ Miguel
(6, 5, 6, '2024-05-14 09:31:00');

-- ============================================================
-- DENUNCIAS
-- ============================================================
INSERT INTO denuncias
  (id_denuncia, id_usuario, id_anuncio, motivo, descripcion, fecha, estado)
VALUES
(1, 3, 26, 'spam',
 'Este anuncio parece ser una copia de otro y no ofrece información real sobre el servicio.',
 '2024-04-12 10:00:00', 'gestionada');

-- ============================================================
-- NOTIFICACIONES
-- ============================================================
INSERT INTO notificaciones
  (id_notificacion, id_usuario, tipo, titulo, contenido, enlace, leida, fecha)
VALUES
(1, 2, 'sistema',     '¡Bienvenida a Kronet Premium!',
 'Disfruta de tus ventajas: destacados gratuitos, visibilidad extra y mucho más.',
 '/kronet/public/perfil', 1, '2025-01-15 00:05:00'),

(2, 3, 'oferta',      'Nuevo intercambio solicitado',
 'María Castro ha solicitado tu servicio de soporte informático.',
 '/kronet/public/intercambios/ofertas-recibidas', 0, '2024-05-18 12:06:00'),

(3, 5, 'oferta',      'Solicitud de reparación recibida',
 'Pablo Torres quiere contratar tu servicio de fontanería.',
 '/kronet/public/intercambios/ofertas-recibidas', 0, '2024-05-17 11:06:00'),

(4, 2, 'valoracion',  'Nueva valoración recibida',
 'Carlos Ruiz te ha valorado con 5 estrellas. ¡Enhorabuena!',
 '/kronet/public/valoraciones/mis-valoraciones', 1, '2024-05-12 09:01:00'),

(5, 4, 'valoracion',  'Nueva valoración recibida',
 'Javier Moreno te ha valorado con 5 estrellas.',
 '/kronet/public/valoraciones/mis-valoraciones', 1, '2024-05-14 11:01:00'),

(6, 3, 'mensaje',     'Nuevo mensaje de María Castro',
 'María: "Hola Carlos, mi portátil va fatal..."',
 '/kronet/public/mensajes', 0, '2024-05-18 12:05:00'),

(7, 6, 'sistema',     '¡Bienvenida a Kronet Premium!',
 'Tu suscripción Premium está activa hasta septiembre de 2025.',
 '/kronet/public/perfil', 1, '2025-03-01 00:05:00'),

(8, 10, 'sistema',    '¡Bienvenida a Kronet!',
 'Has recibido 5 créditos de bienvenida. ¡Empieza a explorar servicios!',
 '/kronet/public/anuncios/buscar', 1, '2024-05-01 17:30:00');

-- ============================================================
-- PAGOS
-- ============================================================
INSERT INTO pagos
  (id_pago, id_usuario, tipo_pago, metodo_pago, importe,
   fecha_pago, estado_pago, referencia)
VALUES
(1, 2, 'suscripcion', 'tarjeta', 5.00, '2025-01-15 00:00:00', 'completado', 'suscripcion:1'),
(2, 6, 'suscripcion', 'paypal',  5.00, '2025-03-01 00:00:00', 'completado', 'suscripcion:2'),
(3, 2, 'destacado',   'tarjeta', 2.50, '2024-05-02 10:05:00', 'completado', 'anuncio:1'),
(4, 6, 'destacado',   'paypal',  2.50, '2024-05-10 12:05:00', 'completado', 'anuncio:9');

-- ============================================================
-- FIN DEL SCRIPT
-- Usuarios de prueba (contraseña: Kronet123):
--   admin@kronet.com     — Admin
--   laura@demo.com       — Premium, profesora idiomas (42 créditos)
--   carlos@demo.com      — Técnico IT (18 créditos)
--   sofia@demo.com       — Músico, guitarra (27 créditos)
--   miguel@demo.com      — Fontanero/electricista (35 créditos)
--   ana@demo.com         — Premium, cocinera (53 créditos)
--   pablo@demo.com       — Diseñador gráfico (12 créditos)
--   elena@demo.com       — Cuidadora (22 créditos)
--   javier@demo.com      — Jardinero+transporte (8 créditos)
--   maria@demo.com       — Nueva usuaria (5 créditos)
-- ============================================================
