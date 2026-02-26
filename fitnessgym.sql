CREATE DATABASE IF NOT EXISTS fitnessgym;
USE fitnessgym;

-- TABLAS INDEPENDIENTES (Catálogos)
CREATE TABLE ejercicios (
    id_ejercicio INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    instrucciones TEXT,
    categoria VARCHAR(50), 
    dificultad ENUM('Principiante', 'Intermedio', 'Avanzado'),
    imagen_url VARCHAR(255)
);

CREATE TABLE comidas (
    id_comida INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    calorias INT,
    tipo ENUM('desayuno', 'almuerzo', 'cena', 'snack')
);

-- TABLA USUARIOS
CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    apellido1 VARCHAR(50) NOT NULL,
    apellido2 VARCHAR(50),
    telefono VARCHAR(15),
    correo VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    rol ENUM('usuario', 'entrenador', 'administrador') DEFAULT 'usuario',
    suscrito BOOLEAN DEFAULT FALSE,
    fecha_fin_suscripcion DATE, 
    id_entrenador INT,
    id_rutina_activa INT,
    id_dieta_activa INT,
    FOREIGN KEY (id_entrenador) REFERENCES usuarios(id_usuario) ON DELETE SET NULL
);

-- RUTINAS Y DIETAS
CREATE TABLE rutinas (
    id_rutina INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    objetivo VARCHAR(100),
    creada_por INT NOT NULL,
    es_predefinida BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (creada_por) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
);

CREATE TABLE dietas (
    id_dieta INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    creada_por INT NOT NULL,
    es_predefinida BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (creada_por) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
);

-- CONEXIÓN DE ACTIVAS
ALTER TABLE usuarios 
ADD FOREIGN KEY (id_rutina_activa) REFERENCES rutinas(id_rutina) ON DELETE SET NULL,
ADD FOREIGN KEY (id_dieta_activa) REFERENCES dietas(id_dieta) ON DELETE SET NULL;

-- RELACIONES N:M
CREATE TABLE rutina_ejercicio (
    id_rutina INT,
    id_ejercicio INT,
    orden INT,
    series INT,
    repeticiones INT,
    tiempo_descanso INT,
    PRIMARY KEY (id_rutina, id_ejercicio),
    FOREIGN KEY (id_rutina) REFERENCES rutinas(id_rutina) ON DELETE CASCADE,
    FOREIGN KEY (id_ejercicio) REFERENCES ejercicios(id_ejercicio) ON DELETE CASCADE
);

CREATE TABLE dieta_comida (
    id_dieta INT,
    id_comida INT,
    dia_semana ENUM('lunes','martes','miércoles','jueves','viernes','sábado','domingo'),
    momento ENUM('mañana','mediodía','tarde','noche'),
    orden_momento INT DEFAULT 1,
    PRIMARY KEY (id_dieta, id_comida, dia_semana, momento),
    FOREIGN KEY (id_dieta) REFERENCES dietas(id_dieta) ON DELETE CASCADE,
    FOREIGN KEY (id_comida) REFERENCES comidas(id_comida) ON DELETE CASCADE
);

-- ASIGNACIONES E HISTORIAL
CREATE TABLE rutina_usuario (
    id_usuario INT,
    id_rutina INT,
    fecha_asignacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_fin DATE,
    PRIMARY KEY (id_usuario, id_rutina, fecha_asignacion),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (id_rutina) REFERENCES rutinas(id_rutina) ON DELETE CASCADE
);

CREATE TABLE dieta_usuario (
    id_usuario INT,
    id_dieta INT,
    fecha_asignacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_fin DATE,
    PRIMARY KEY (id_usuario, id_dieta, fecha_asignacion),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (id_dieta) REFERENCES dietas(id_dieta) ON DELETE CASCADE
);

-- VIDEOLLAMADAS Y PAGOS
CREATE TABLE videollamadas (
    id_videollamada INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_entrenador INT NOT NULL,
    fecha DATETIME NOT NULL,
    duracion_minutos INT DEFAULT 30,
    enlace_sala VARCHAR(255),
    estado ENUM('programada', 'realizada', 'cancelada') DEFAULT 'programada',
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (id_entrenador) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
);

CREATE TABLE pagos (
    id_pago INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    cantidad DECIMAL(10,2) NOT NULL,
    metodo ENUM('tarjeta', 'paypal', 'transferencia') DEFAULT 'tarjeta',
    estado ENUM('pendiente', 'completado', 'fallido') DEFAULT 'pendiente',
    referencia_pago VARCHAR(100),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
);

-- ARCHIVOS COMPARTIDOS EN SALAS
CREATE TABLE archivos_sala (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_entrenador INT NOT NULL,
    nombre_archivo VARCHAR(255) NOT NULL,
    ruta_archivo TEXT NOT NULL,
    fecha_subida DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (id_entrenador) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
);

-- NOTIFICACIONES COMPARTIDAS EN SALAS
CREATE TABLE notificaciones_sala (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_entrenador INT NOT NULL,
    tipo ENUM('archivo', 'ayuda', 'otro') NOT NULL,
    contenido TEXT NOT NULL,
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (id_entrenador) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
);


CREATE TABLE historial_llamadas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_entrenador INT NOT NULL,
    sala VARCHAR(100) NOT NULL,
    fecha_inicio DATETIME NOT NULL,
    fecha_fin DATETIME NOT NULL,
    duracion INT NOT NULL, -- en minutos
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (id_entrenador) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
);
