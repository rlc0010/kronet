-- ============================================================
-- KRONET — SCHEMA SQL COMPLETO (Sprint 4)
--
-- Base de datos: kronet_db
-- Importa este fichero entero para crear el sistema desde cero.
-- ============================================================

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS notificaciones;
DROP TABLE IF EXISTS denuncias;
DROP TABLE IF EXISTS bloqueos;
DROP TABLE IF EXISTS contactos;
DROP TABLE IF EXISTS pagos;
DROP TABLE IF EXISTS suscripciones;
DROP TABLE IF EXISTS valoraciones;
DROP TABLE IF EXISTS mensajes;
DROP TABLE IF EXISTS intercambios;
DROP TABLE IF EXISTS anuncios;
DROP TABLE IF EXISTS usuarios;

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- USUARIOS
-- ============================================================
CREATE TABLE usuarios (
    id_usuario          INT AUTO_INCREMENT PRIMARY KEY,
    nombre              VARCHAR(100)            NOT NULL,
    email               VARCHAR(150) UNIQUE     NOT NULL,
    contrasenia_hash    VARCHAR(255)            NOT NULL,
    descripcion         TEXT,
    tipo_usuario        VARCHAR(50)             DEFAULT 'registrado',  -- 'registrado' | 'suscrito' | 'admin'
    fecha_registro      DATETIME                DEFAULT CURRENT_TIMESTAMP,
    saldo_monedas       INT                     DEFAULT 5,             -- créditos iniciales de bienvenida
    estado_cuenta       VARCHAR(50)             DEFAULT 'activa'       -- 'activa' | 'bloqueada'
);

-- ============================================================
-- ANUNCIOS
-- ============================================================
CREATE TABLE anuncios (
    id_anuncio          INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario          INT                     NOT NULL,
    titulo              VARCHAR(255)            NOT NULL,
    descripcion         TEXT,
    tipo_anuncio        VARCHAR(50),                                   -- 'oferta' | 'demanda'
    categoria           VARCHAR(100),
    duracion_estimada   INT                     DEFAULT 1,             -- duración en horas
    precio_creditos     INT                     DEFAULT 1,             -- créditos calculados por el algoritmo
    plazas_totales      INT                     DEFAULT 1,             -- nº de personas que pueden apuntarse
    plazas_ocupadas     INT                     DEFAULT 0,             -- contador
    fecha_publicacion   DATETIME                DEFAULT CURRENT_TIMESTAMP,
    estado              VARCHAR(50)             DEFAULT 'activo',      -- 'activo' | 'completo' | 'cancelado'
    destacado           TINYINT(1)              DEFAULT 0,
    destacado_hasta     DATETIME                DEFAULT NULL,
    imagen              VARCHAR(255)            DEFAULT NULL,

    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
        ON DELETE CASCADE
);

-- ============================================================
-- INTERCAMBIOS
-- ============================================================
-- Convención del sistema:
--   id_usuario_ofertante   = el dueño del anuncio (el que publica)
--   id_usuario_solicitante = el que responde / se apunta
-- El sentido del cobro depende de tipo_anuncio (ver lógica en
-- IntercambioController e Intercambio model).
CREATE TABLE intercambios (
    id_intercambio          INT AUTO_INCREMENT PRIMARY KEY,
    id_anuncio              INT,
    id_usuario_ofertante    INT,
    id_usuario_solicitante  INT,
    fecha_inicio            DATETIME                DEFAULT CURRENT_TIMESTAMP,
    fecha_fin               DATETIME,
    monedas_intercambio     INT,
    id_usuario_pagador      INT,                                       -- a quién se le congelaron los créditos
    id_usuario_receptor     INT,                                       -- a quién le llegarán al confirmar
    estado                  VARCHAR(50)             DEFAULT 'pendiente', -- 'pendiente' | 'confirmado' | 'cancelado'

    FOREIGN KEY (id_anuncio) REFERENCES anuncios(id_anuncio)
        ON DELETE SET NULL,
    FOREIGN KEY (id_usuario_ofertante) REFERENCES usuarios(id_usuario)
        ON DELETE CASCADE,
    FOREIGN KEY (id_usuario_solicitante) REFERENCES usuarios(id_usuario)
        ON DELETE CASCADE
);

-- ============================================================
-- MENSAJES (chat estilo Wallapop: una conversación por anuncio
-- entre el ofertante y el solicitante)
-- ============================================================
CREATE TABLE mensajes (
    id_mensaje      INT AUTO_INCREMENT PRIMARY KEY,
    id_anuncio      INT                 DEFAULT NULL,
    id_emisor       INT                 NOT NULL,
    id_receptor     INT                 NOT NULL,
    contenido       TEXT,
    fecha_envio     DATETIME            DEFAULT CURRENT_TIMESTAMP,
    leido           TINYINT(1)          DEFAULT 0,

    FOREIGN KEY (id_anuncio)  REFERENCES anuncios(id_anuncio)  ON DELETE SET NULL,
    FOREIGN KEY (id_emisor)   REFERENCES usuarios(id_usuario)  ON DELETE CASCADE,
    FOREIGN KEY (id_receptor) REFERENCES usuarios(id_usuario)  ON DELETE CASCADE,

    INDEX idx_conv (id_anuncio, id_emisor, id_receptor)
);

-- ============================================================
-- VALORACIONES
-- ============================================================
CREATE TABLE valoraciones (
    id_valoracion        INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario_autor     INT,
    id_usuario_destino   INT,
    puntuacion           INT CHECK (puntuacion BETWEEN 1 AND 5),
    comentario           TEXT,
    fecha                DATETIME    DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (id_usuario_autor)   REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (id_usuario_destino) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
);

-- ============================================================
-- SUSCRIPCIONES (RF.3)
-- ============================================================
CREATE TABLE suscripciones (
    id_suscripcion  INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario      INT,
    fecha_inicio    DATETIME    DEFAULT CURRENT_TIMESTAMP,
    fecha_fin       DATETIME,
    destacados_usados_semana INT DEFAULT 0,
    semana_destacados DATE     DEFAULT NULL,
    estado          VARCHAR(50) DEFAULT 'activa',

    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
);

-- ============================================================
-- PAGOS (RF.7, RF.9)
-- ============================================================
CREATE TABLE pagos (
    id_pago         INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario      INT,
    tipo_pago       VARCHAR(50),                                       -- 'suscripcion' | 'destacado'
    metodo_pago     VARCHAR(50),                                       -- 'tarjeta' | 'paypal' | 'bizum'
    importe         DECIMAL(10,2),
    fecha_pago      DATETIME    DEFAULT CURRENT_TIMESTAMP,
    estado_pago     VARCHAR(50) DEFAULT 'completado',                  -- 'completado' | 'pendiente' | 'fallido'
    referencia      VARCHAR(100),                                      -- id de anuncio o de suscripción

    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
);

-- ============================================================
-- CONTACTOS / AMIGOS (RF.21)
-- ============================================================
CREATE TABLE contactos (
    id_contacto       INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario        INT,
    id_usuario_amigo  INT,
    fecha             DATETIME    DEFAULT CURRENT_TIMESTAMP,

    UNIQUE KEY uq_contacto (id_usuario, id_usuario_amigo),
    FOREIGN KEY (id_usuario)       REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (id_usuario_amigo) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
);

-- ============================================================
-- BLOQUEOS (RF.22)
-- ============================================================
CREATE TABLE bloqueos (
    id_bloqueo            INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario            INT,
    id_usuario_bloqueado  INT,
    fecha                 DATETIME    DEFAULT CURRENT_TIMESTAMP,

    UNIQUE KEY uq_bloqueo (id_usuario, id_usuario_bloqueado),
    FOREIGN KEY (id_usuario)           REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (id_usuario_bloqueado) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
);

-- ============================================================
-- DENUNCIAS (RF.23)
-- ============================================================
CREATE TABLE denuncias (
    id_denuncia       INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario        INT,                                             -- quien denuncia
    id_anuncio        INT,                                             -- anuncio denunciado
    motivo            VARCHAR(100),
    descripcion       TEXT,
    fecha             DATETIME    DEFAULT CURRENT_TIMESTAMP,
    estado            VARCHAR(50) DEFAULT 'pendiente',                 -- 'pendiente' | 'gestionada' | 'desestimada'

    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)  ON DELETE CASCADE,
    FOREIGN KEY (id_anuncio) REFERENCES anuncios(id_anuncio)  ON DELETE CASCADE
);

-- ============================================================
-- NOTIFICACIONES
-- ============================================================
CREATE TABLE notificaciones (
    id_notificacion  INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario       INT,
    tipo             VARCHAR(50),                                      -- 'mensaje' | 'oferta' | 'intercambio' | 'valoracion' | 'sistema'
    titulo           VARCHAR(255),
    contenido        TEXT,
    enlace           VARCHAR(255),
    leida            TINYINT(1)   DEFAULT 0,
    fecha            DATETIME     DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    INDEX idx_user_leida (id_usuario, leida)
);
