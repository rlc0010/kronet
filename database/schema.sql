-- ======================
-- TABLA USUARIOS
-- ======================
CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    contrasenia_hash VARCHAR(255),
    descripcion TEXT,
    tipo_usuario VARCHAR(50),
    fecha_registro DATE,
    saldo_monedas INT DEFAULT 0,
    estado_cuenta VARCHAR(50)
);

-- ======================
-- TABLA ANUNCIOS
-- ======================
CREATE TABLE anuncios (
    id_anuncio INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    titulo VARCHAR(255),
    descripcion TEXT,
    tipo_anuncio VARCHAR(50),
    categoria VARCHAR(100),
    duracion_estimada INT,
    fecha_publicacion DATETIME,
    estado VARCHAR(50),
    destacado BOOLEAN DEFAULT FALSE,

    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
        ON DELETE CASCADE
);

-- ======================
-- TABLA INTERCAMBIOS
-- ======================
CREATE TABLE intercambios (
    id_intercambio INT AUTO_INCREMENT PRIMARY KEY,
    id_anuncio INT,
    id_usuario_ofertante INT,
    id_usuario_solicitante INT,
    fecha_inicio DATETIME,
    fecha_fin DATETIME,
    monedas_intercambio INT,
    estado VARCHAR(50),

    FOREIGN KEY (id_anuncio) REFERENCES anuncios(id_anuncio)
        ON DELETE SET NULL,
    FOREIGN KEY (id_usuario_ofertante) REFERENCES usuarios(id_usuario)
        ON DELETE CASCADE,
    FOREIGN KEY (id_usuario_solicitante) REFERENCES usuarios(id_usuario)
        ON DELETE CASCADE
);

-- ======================
-- TABLA VALORACIONES
-- ======================
-- Nota: id_usuario_autor = quien escribe la valoración
--       id_usuario_destino = quien la recibe
CREATE TABLE valoraciones (
    id_valoracion INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario_autor INT,
    id_usuario_destino INT,
    puntuacion INT CHECK (puntuacion BETWEEN 1 AND 5),
    comentario TEXT,
    fecha DATETIME,

    FOREIGN KEY (id_usuario_autor) REFERENCES usuarios(id_usuario)
        ON DELETE CASCADE,
    FOREIGN KEY (id_usuario_destino) REFERENCES usuarios(id_usuario)
        ON DELETE CASCADE
);

-- ======================
-- TABLA MENSAJES
-- ======================
CREATE TABLE mensajes (
    id_mensaje INT AUTO_INCREMENT PRIMARY KEY,
    id_emisor INT,
    id_receptor INT,
    contenido TEXT,
    fecha_envio DATETIME,
    leido BOOLEAN DEFAULT FALSE,

    FOREIGN KEY (id_emisor) REFERENCES usuarios(id_usuario)
        ON DELETE CASCADE,
    FOREIGN KEY (id_receptor) REFERENCES usuarios(id_usuario)
        ON DELETE CASCADE
);

-- ======================
-- TABLA SUSCRIPCIONES
-- ======================
CREATE TABLE suscripciones (
    id_suscripcion INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    fecha_inicio DATETIME,
    fecha_fin DATETIME,
    estado VARCHAR(50),

    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
        ON DELETE CASCADE
);

-- ======================
-- TABLA PAGOS
-- ======================
CREATE TABLE pagos (
    id_pago INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    tipo_pago VARCHAR(50),
    importe DECIMAL(10,2),
    fecha_pago DATETIME,
    estado_pago VARCHAR(50),

    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
        ON DELETE CASCADE
);
