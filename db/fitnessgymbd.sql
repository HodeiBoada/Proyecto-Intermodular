-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 27, 2026 at 08:16 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

CREATE DATABASE fitnessgymbd;
USE fitnessgymbd;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fitnessgymbd`
--

-- --------------------------------------------------------

--
-- Table structure for table `archivos_sala`
--

CREATE TABLE `archivos_sala` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_entrenador` int(11) NOT NULL,
  `nombre_archivo` varchar(255) NOT NULL,
  `ruta_archivo` text NOT NULL,
  `fecha_subida` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `archivos_sala`
--

INSERT INTO `archivos_sala` (`id`, `id_usuario`, `id_entrenador`, `nombre_archivo`, `ruta_archivo`, `fecha_subida`) VALUES
(1, 6, 4, 'PROYECTO TÃ‰CNICO Requisitos funcionales Hodei, Buseif, Miguel (4).pdf', 'uploads/1772104507_PROYECTO_T__CNICO_Requisitos_funcionales_Hodei__Buseif__Miguel__4_.pdf', '2026-02-26 12:15:07'),
(2, 6, 4, 'T3.pdf', 'uploads/1772104568_T3.pdf', '2026-02-26 12:16:08'),
(3, 5, 5, 'PROYECTO TÃ‰CNICO Requisitos funcionales Hodei, Buseif, Miguel (1).pdf', 'uploads/1772104864_PROYECTO_T__CNICO_Requisitos_funcionales_Hodei__Buseif__Miguel__1_.pdf', '2026-02-26 12:21:04'),
(4, 6, 4, 'TEST RESPASO UT6.pdf', 'uploads/1772110149_TEST_RESPASO_UT6.pdf', '2026-02-26 13:49:09'),
(5, 6, 4, 'Tarea_Evaluable_UT1_SBD.pdf', 'uploads/1772110168_Tarea_Evaluable_UT1_SBD.pdf', '2026-02-26 13:49:28'),
(6, 7, 5, 'tema4 (1).pdf', 'uploads/1772110251_tema4__1_.pdf', '2026-02-26 13:50:51'),
(7, 7, 5, 'ActividadUD3_miguelsedano.pdf', 'uploads/1772110265_ActividadUD3_miguelsedano.pdf', '2026-02-26 13:51:05'),
(8, 7, 5, '60525023ea18f45b4caf4c99_UI Style Guide.pdf', 'uploads/1772110649_60525023ea18f45b4caf4c99_UI_Style_Guide.pdf', '2026-02-26 13:57:29'),
(9, 7, 5, 'Tarea_Evaluable_UT1_SBD.pdf', 'uploads/1772110696_Tarea_Evaluable_UT1_SBD.pdf', '2026-02-26 13:58:16'),
(10, 6, 4, '1772117237_Lienzo_EcoBusiness_CANVA.pdf', 'uploads/1772117237_Lienzo_EcoBusiness_CANVA.pdf', '2026-02-26 15:47:17'),
(11, 6, 4, '1772117624_EXA_9.pdf', 'uploads/1772117624_EXA_9.pdf', '2026-02-26 15:53:44'),
(12, 7, 5, '1772128614_Lienzo_EcoBusiness_CANVA.pdf', 'uploads/1772128614_Lienzo_EcoBusiness_CANVA.pdf', '2026-02-26 18:56:54'),
(13, 7, 5, '1772128766_Lienzo_EcoBusiness_CANVA.pdf', 'uploads/1772128766_Lienzo_EcoBusiness_CANVA.pdf', '2026-02-26 18:59:26'),
(14, 7, 5, '1772129016_Hoja2_Unidad_3.pdf', 'uploads/1772129016_Hoja2_Unidad_3.pdf', '2026-02-26 19:03:36');

-- --------------------------------------------------------

--
-- Table structure for table `comidas`
--

CREATE TABLE `comidas` (
  `id_comida` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `calorias` int(11) DEFAULT NULL,
  `tipo` enum('desayuno','almuerzo','cena','snack') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `comidas`
--

INSERT INTO `comidas` (`id_comida`, `nombre`, `descripcion`, `calorias`, `tipo`) VALUES
(1, 'Tortilla Francesa', '2 huevos con poco aceite', 150, 'desayuno'),
(2, 'Pollo con Arroz', '150g pollo y 100g arroz', 450, 'almuerzo'),
(3, 'Salmón a la plancha', 'Rico en Omega 3', 400, 'cena'),
(4, 'Batido Proteína', 'Suero de leche', 120, 'snack'),
(5, 'Avena con Yogur', 'Carbohidrato lento', 300, 'desayuno'),
(6, 'Ensalada de Atún', 'Ligera y proteica', 250, 'almuerzo'),
(7, 'Frutos Secos', 'Nueces y almendras', 200, 'snack'),
(8, 'Ternera con Brócoli', 'Hierro y fibra', 500, 'cena'),
(9, 'Tostada con Aguacate', 'Grasas saludables', 280, 'desayuno'),
(10, 'Pasta Integral', 'Energía para entrenar', 420, 'almuerzo'),
(11, 'Pechuga de Pollo', 'Una pechuga muy fresh', 34, ''),
(12, 'Papas', 'Papas fritas', 12, 'almuerzo');

-- --------------------------------------------------------

--
-- Table structure for table `dietas`
--

CREATE TABLE `dietas` (
  `id_dieta` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `creada_por` int(11) NOT NULL,
  `es_predefinida` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `dietas`
--

INSERT INTO `dietas` (`id_dieta`, `nombre`, `descripcion`, `creada_por`, `es_predefinida`) VALUES
(1, 'Dieta Volumen', 'Superávit calórico', 4, 1),
(2, 'Dieta Definición', 'Déficit controlado', 5, 1),
(3, 'Keto Gym', 'Baja en hidratos', 4, 1),
(4, 'Paleo Diet', 'Comida natural', 4, 1),
(5, 'Vegana Fit', 'Proteína vegetal', 5, 1),
(6, 'Ayuno Intermitente', 'Protocolo 16/8', 4, 1),
(7, 'Mediterránea', 'Equilibrada', 5, 1),
(8, 'Baja en Sodio', 'Para hipertensos', 4, 1),
(9, 'Alta en Proteína', 'Para culturismo', 5, 1),
(10, 'Sin Gluten', 'Para celíacos', 5, 1),
(11, 'Dieta Vegana', 'Muy vegana', 1, 1),
(12, 'Vegana', 'Muy vegana', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `dieta_comida`
--

CREATE TABLE `dieta_comida` (
  `id_dieta` int(11) NOT NULL,
  `id_comida` int(11) NOT NULL,
  `dia_semana` enum('lunes','martes','miércoles','jueves','viernes','sábado','domingo') NOT NULL,
  `momento` enum('mañana','mediodía','tarde','noche') NOT NULL,
  `orden_momento` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `dieta_comida`
--

INSERT INTO `dieta_comida` (`id_dieta`, `id_comida`, `dia_semana`, `momento`, `orden_momento`) VALUES
(1, 1, 'lunes', 'mañana', 1),
(1, 2, 'lunes', 'mediodía', 1),
(1, 3, 'lunes', 'noche', 1),
(1, 4, 'lunes', 'tarde', 1),
(2, 5, 'martes', 'mañana', 1),
(2, 6, 'martes', 'mediodía', 1),
(2, 7, 'martes', 'tarde', 1),
(2, 8, 'martes', 'noche', 1),
(3, 9, 'miércoles', 'mañana', 1),
(3, 10, 'miércoles', 'mediodía', 1),
(12, 4, 'miércoles', '', 2),
(12, 5, 'martes', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `dieta_usuario`
--

CREATE TABLE `dieta_usuario` (
  `id_usuario` int(11) NOT NULL,
  `id_dieta` int(11) NOT NULL,
  `fecha_asignacion` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_fin` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `dieta_usuario`
--

INSERT INTO `dieta_usuario` (`id_usuario`, `id_dieta`, `fecha_asignacion`, `fecha_fin`) VALUES
(6, 1, '2026-02-27 19:47:20', NULL),
(6, 1, '2026-02-27 20:03:42', NULL),
(6, 4, '2026-02-27 19:43:22', NULL),
(6, 6, '2026-02-27 19:44:58', NULL),
(8, 3, '2026-02-26 16:12:07', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ejercicios`
--

CREATE TABLE `ejercicios` (
  `id_ejercicio` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `instrucciones` text DEFAULT NULL,
  `categoria` varchar(50) DEFAULT NULL,
  `dificultad` enum('Principiante','Intermedio','Avanzado') DEFAULT NULL,
  `imagen_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `ejercicios`
--

INSERT INTO `ejercicios` (`id_ejercicio`, `nombre`, `descripcion`, `instrucciones`, `categoria`, `dificultad`, `imagen_url`) VALUES
(1, 'Press Banca', 'Pecho básico', 'Baja la barra al pecho y empuja.', 'Pecho', 'Intermedio', NULL),
(2, 'Sentadilla', 'Pierna global', 'Baja la cadera manteniendo espalda recta.', 'Pierna', 'Intermedio', NULL),
(3, 'Peso Muerto', 'Cadena posterior', 'Levanta la barra desde el suelo.', 'Espalda', 'Avanzado', NULL),
(4, 'Curl de Bíceps', 'Aislamiento brazo', 'Flexiona el codo con mancuerna.', 'Brazo', 'Principiante', NULL),
(5, 'Press Militar', 'Hombro fuerza', 'Empuja la barra sobre la cabeza.', 'Hombro', 'Intermedio', NULL),
(6, 'Dominadas', 'Tracción vertical', 'Sube el pecho hasta la barra.', 'Espalda', 'Avanzado', NULL),
(7, 'Zancadas', 'Pierna unilateral', 'Da un paso largo y baja la rodilla.', 'Pierna', 'Principiante', NULL),
(8, 'Plancha', 'Core isométrico', 'Mantén el cuerpo recto sobre codos.', 'Core', 'Principiante', NULL),
(9, 'Fondos', 'Tríceps y pecho', 'Baja el cuerpo entre dos barras.', 'Brazo', 'Intermedio', NULL),
(10, 'Remo con Barra', 'Tracción horizontal', 'Tira de la barra hacia el ombligo.', 'Espalda', 'Intermedio', NULL),
(11, 'Prueba', 'Es una prueba de espalda', 'Una prueba de un ejercicio de espalda', 'Espalda', 'Intermedio', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `historial_llamadas`
--

CREATE TABLE `historial_llamadas` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_entrenador` int(11) NOT NULL,
  `sala` varchar(100) NOT NULL,
  `fecha_inicio` datetime NOT NULL,
  `fecha_fin` datetime NOT NULL,
  `duracion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `historial_llamadas`
--

INSERT INTO `historial_llamadas` (`id`, `id_usuario`, `id_entrenador`, `sala`, `fecha_inicio`, `fecha_fin`, `duracion`) VALUES
(1, 7, 5, 'sala-5-7', '2026-02-26 14:02:41', '2026-02-26 14:07:12', 5),
(2, 7, 5, 'sala-5-7', '2026-02-26 14:05:07', '2026-02-26 14:07:56', 3),
(3, 6, 4, 'sala-4-6', '2026-02-26 15:55:56', '2026-02-26 15:57:13', 1),
(4, 6, 4, 'sala-4-6', '2026-02-26 15:54:18', '2026-02-26 15:57:26', 3);

-- --------------------------------------------------------

--
-- Table structure for table `notificaciones_sala`
--

CREATE TABLE `notificaciones_sala` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_entrenador` int(11) NOT NULL,
  `tipo` enum('archivo','ayuda','otro') NOT NULL,
  `contenido` text NOT NULL,
  `timestamp` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `notificaciones_sala`
--

INSERT INTO `notificaciones_sala` (`id`, `id_usuario`, `id_entrenador`, `tipo`, `contenido`, `timestamp`) VALUES
(1, 6, 4, 'ayuda', 'El usuario ha solicitado ayuda para unirse a la videollamada.', '2026-02-26 11:40:15'),
(2, 6, 4, 'ayuda', 'El usuario ha solicitado ayuda para unirse a la videollamada.', '2026-02-26 11:42:05'),
(3, 6, 4, 'ayuda', 'El usuario ha solicitado ayuda para unirse a la videollamada.', '2026-02-26 11:48:23'),
(4, 6, 4, 'ayuda', 'El usuario ha solicitado ayuda para unirse a la videollamada.', '2026-02-26 12:14:34'),
(5, 6, 4, 'ayuda', 'El usuario ha solicitado ayuda para unirse a la videollamada.', '2026-02-26 12:14:50'),
(6, 6, 4, 'archivo', 'PROYECTO TÃ‰CNICO Requisitos funcionales Hodei, Buseif, Miguel (4).pdf', '2026-02-26 12:15:08'),
(7, 6, 4, 'archivo', 'T3.pdf', '2026-02-26 12:16:08'),
(8, 7, 5, 'ayuda', 'El usuario ha solicitado ayuda para unirse a la videollamada.', '2026-02-26 12:19:40'),
(9, 5, 5, 'archivo', 'PROYECTO TÃ‰CNICO Requisitos funcionales Hodei, Buseif, Miguel (1).pdf', '2026-02-26 12:21:04'),
(10, 6, 4, 'ayuda', 'El usuario ha solicitado ayuda para unirse a la videollamada.', '2026-02-26 13:48:54'),
(11, 6, 4, 'archivo', 'TEST RESPASO UT6.pdf', '2026-02-26 13:49:09'),
(12, 6, 4, 'archivo', 'Tarea_Evaluable_UT1_SBD.pdf', '2026-02-26 13:49:28'),
(13, 7, 5, 'ayuda', 'El usuario ha solicitado ayuda para unirse a la videollamada.', '2026-02-26 13:50:40'),
(14, 7, 5, 'archivo', 'tema4 (1).pdf', '2026-02-26 13:50:51'),
(15, 7, 5, 'archivo', 'ActividadUD3_miguelsedano.pdf', '2026-02-26 13:51:05'),
(16, 7, 5, 'archivo', '60525023ea18f45b4caf4c99_UI Style Guide.pdf', '2026-02-26 13:57:29'),
(17, 7, 5, 'archivo', 'Tarea_Evaluable_UT1_SBD.pdf', '2026-02-26 13:58:16'),
(18, 7, 5, 'ayuda', 'El usuario ha solicitado ayuda para unirse a la videollamada.', '2026-02-26 13:59:15'),
(19, 7, 5, 'ayuda', 'El usuario ha solicitado ayuda para unirse a la videollamada.', '2026-02-26 13:59:23'),
(20, 7, 5, 'ayuda', 'El usuario ha solicitado ayuda para unirse a la videollamada.', '2026-02-26 13:59:26'),
(21, 6, 4, 'ayuda', 'El usuario ha solicitado ayuda para unirse a la videollamada.', '2026-02-26 15:32:25'),
(22, 6, 4, 'ayuda', 'El usuario ha solicitado ayuda para unirse a la videollamada.', '2026-02-26 15:32:36'),
(23, 6, 4, 'ayuda', 'El usuario ha solicitado ayuda para unirse a la videollamada.', '2026-02-26 15:34:28'),
(24, 6, 4, 'archivo', '1772117237_Lienzo_EcoBusiness_CANVA.pdf', '2026-02-26 15:47:17'),
(25, 6, 4, 'archivo', '1772117624_EXA_9.pdf', '2026-02-26 15:53:44'),
(26, 6, 4, 'ayuda', 'El usuario ha solicitado ayuda para unirse a la videollamada.', '2026-02-26 15:53:52'),
(27, 6, 4, 'ayuda', 'El usuario ha solicitado ayuda para unirse a la videollamada.', '2026-02-26 17:26:31'),
(28, 6, 4, 'ayuda', 'El usuario ha solicitado ayuda para unirse a la videollamada.', '2026-02-26 17:27:05'),
(29, 6, 4, 'ayuda', 'El usuario ha solicitado ayuda para unirse a la videollamada.', '2026-02-26 18:12:04'),
(30, 6, 4, 'ayuda', 'El usuario ha solicitado ayuda para unirse a la videollamada.', '2026-02-26 18:12:13'),
(31, 6, 4, 'ayuda', 'El usuario ha solicitado ayuda para unirse a la videollamada.', '2026-02-26 18:13:10'),
(32, 7, 5, 'ayuda', 'El usuario solicita ayuda en la videollamada.', '2026-02-26 18:42:12'),
(33, 7, 5, 'ayuda', 'El usuario solicita ayuda en la videollamada.', '2026-02-26 18:46:51'),
(34, 7, 5, 'ayuda', 'El usuario solicita ayuda en la videollamada.', '2026-02-26 18:47:01'),
(35, 7, 5, 'ayuda', 'El usuario solicita ayuda en la videollamada.', '2026-02-26 18:47:16'),
(36, 7, 5, 'archivo', '1772128614_Lienzo_EcoBusiness_CANVA.pdf', '2026-02-26 18:56:54'),
(37, 7, 5, 'archivo', '1772128766_Lienzo_EcoBusiness_CANVA.pdf', '2026-02-26 18:59:26'),
(38, 7, 5, 'archivo', '1772129016_Hoja2_Unidad_3.pdf', '2026-02-26 19:03:36'),
(39, 7, 5, 'ayuda', 'El usuario solicita ayuda en la videollamada.', '2026-02-26 19:06:05'),
(40, 6, 4, 'ayuda', 'El usuario solicita ayuda en la videollamada.', '2026-02-26 19:54:33'),
(41, 6, 4, 'ayuda', 'El usuario solicita ayuda en la videollamada.', '2026-02-26 19:55:32'),
(42, 6, 4, 'ayuda', 'El usuario solicita ayuda en la videollamada.', '2026-02-26 19:55:32'),
(43, 6, 4, 'ayuda', 'El usuario solicita ayuda en la videollamada.', '2026-02-26 19:55:33'),
(44, 6, 4, 'ayuda', 'El usuario solicita ayuda en la videollamada.', '2026-02-26 20:03:15'),
(45, 6, 4, 'ayuda', 'El usuario solicita ayuda en la videollamada.', '2026-02-26 20:04:55'),
(46, 6, 4, 'ayuda', 'El usuario solicita ayuda en la videollamada.', '2026-02-26 20:08:41'),
(47, 6, 4, 'ayuda', 'El usuario solicita ayuda en la videollamada.', '2026-02-26 20:09:13'),
(48, 6, 4, 'ayuda', 'El usuario solicita ayuda en la videollamada.', '2026-02-26 20:09:14'),
(49, 6, 4, 'ayuda', 'El usuario solicita ayuda en la videollamada.', '2026-02-26 20:09:23');

-- --------------------------------------------------------

--
-- Table structure for table `pagos`
--

CREATE TABLE `pagos` (
  `id_pago` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha` datetime DEFAULT current_timestamp(),
  `cantidad` decimal(10,2) NOT NULL,
  `metodo` enum('tarjeta','paypal','transferencia') DEFAULT 'tarjeta',
  `estado` enum('pendiente','completado','fallido') DEFAULT 'pendiente',
  `referencia_pago` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rutinas`
--

CREATE TABLE `rutinas` (
  `id_rutina` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `objetivo` varchar(100) DEFAULT NULL,
  `creada_por` int(11) NOT NULL,
  `es_predefinida` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `rutinas`
--

INSERT INTO `rutinas` (`id_rutina`, `nombre`, `objetivo`, `creada_por`, `es_predefinida`) VALUES
(1, 'Full Body Eva', 'Acondicionamiento', 4, 1),
(2, 'Hipertrofia Pepe', 'Ganar músculo', 5, 1),
(3, 'Pérdida Grasa', 'Definición', 4, 1),
(4, 'Fuerza 5x5', 'Ganar fuerza', 5, 1),
(5, 'Torso-Pierna', 'Equilibrio', 4, 1),
(6, 'Rutina Verano', 'Estética', 4, 1),
(7, 'Especial Glúteo', 'Volumen inferior', 4, 1),
(8, 'Empuje/Tirón', 'Frecuencia 2', 5, 1),
(9, 'Cardio HIIT', 'Quema rápida', 4, 1),
(10, 'Senior Fit', 'Salud mayores', 5, 1),
(11, 'Epica', 'Ponerse mamado', 4, 0),
(12, 'Super', 'Superepica guay', 4, 0),
(13, 'PPL', 'Ganancia de masa muscular', 4, 0),
(14, 'Personalizada', 'Ganancia de masa muscular', 4, 0),
(15, 'Pro', 'Hipertrofia', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `rutina_ejercicio`
--

CREATE TABLE `rutina_ejercicio` (
  `id_rutina` int(11) NOT NULL,
  `id_ejercicio` int(11) NOT NULL,
  `dia` int(1) NOT NULL COMMENT '1:Lunes, 2:Martes, 3:Miércoles, 4:Jueves, 5:Viernes, 6:Sábado, 7:Domingo',
  `orden` int(11) DEFAULT NULL,
  `series` int(11) DEFAULT NULL,
  `repeticiones` int(11) DEFAULT NULL,
  `tiempo_descanso` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `rutina_ejercicio`
--

INSERT INTO `rutina_ejercicio` (`id_rutina`, `id_ejercicio`, `dia`, `orden`, `series`, `repeticiones`, `tiempo_descanso`) VALUES
(1, 1, 1, 1, 3, 10, 60),
(1, 2, 2, 2, 3, 12, 60),
(1, 8, 3, 3, 3, 30, 30),
(2, 3, 2, 1, 4, 8, 90),
(2, 5, 4, 2, 4, 10, 60),
(2, 6, 2, 3, 4, 8, 90),
(3, 4, 4, 2, 3, 15, 45),
(3, 7, 2, 1, 3, 12, 45),
(4, 1, 3, 1, 5, 5, 120),
(4, 3, 4, 2, 5, 5, 120),
(11, 1, 1, 1, 3, 12, 90),
(11, 3, 2, 2, 0, 0, 0),
(11, 5, 3, 3, 2, 20, 90),
(11, 6, 4, 4, 0, 0, 0),
(12, 1, 2, 1, 12, 12, 12),
(12, 3, 3, 2, 11, 11, 11),
(12, 5, 4, 3, 10, 10, 10),
(13, 1, 1, 1, 3, 6, 60),
(13, 2, 3, 2, 3, 6, 60),
(13, 3, 3, 3, 3, 5, 90),
(13, 4, 2, 4, 2, 10, 70),
(13, 5, 4, 5, 3, 7, 50),
(14, 1, 1, 1, 2, 2, 90),
(14, 2, 2, 2, 2, 2, 90),
(14, 3, 3, 3, 12, 2, 90),
(15, 4, 1, 1, 2, 23, 67),
(15, 6, 1, 2, 56, 56, 56);

-- --------------------------------------------------------

--
-- Table structure for table `rutina_usuario`
--

CREATE TABLE `rutina_usuario` (
  `id_usuario` int(11) NOT NULL,
  `id_rutina` int(11) NOT NULL,
  `fecha_asignacion` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_fin` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `rutina_usuario`
--

INSERT INTO `rutina_usuario` (`id_usuario`, `id_rutina`, `fecha_asignacion`, `fecha_fin`) VALUES
(6, 1, '2026-02-27 20:05:36', NULL),
(6, 9, '2026-02-27 19:48:55', NULL),
(6, 11, '2026-02-26 10:57:06', NULL),
(6, 12, '2026-02-26 11:07:19', NULL),
(8, 3, '2026-02-26 16:11:59', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido1` varchar(50) NOT NULL,
  `apellido2` varchar(50) DEFAULT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `correo` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `rol` enum('usuario','entrenador','administrador') DEFAULT 'usuario',
  `suscrito` tinyint(1) DEFAULT 0,
  `fecha_fin_suscripcion` date DEFAULT NULL,
  `id_entrenador` int(11) DEFAULT NULL,
  `id_rutina_activa` int(11) DEFAULT NULL,
  `id_dieta_activa` int(11) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `apellido1`, `apellido2`, `telefono`, `correo`, `password_hash`, `rol`, `suscrito`, `fecha_fin_suscripcion`, `id_entrenador`, `id_rutina_activa`, `id_dieta_activa`, `activo`) VALUES
(1, 'Buseif', 'Admin', NULL, NULL, 'buseif@fitness.com', '$2y$10$jbz8r/2Z7DtSNRVhYLihDe04S3bpges1OH.0zjZxhgJjwEH5Jztze', 'administrador', 1, NULL, NULL, NULL, NULL, 1),
(2, 'Hodei', 'Admin', NULL, NULL, 'hodei@fitness.com', '$2y$10$IrGfPrOzqB0BBT2giEgr9./BPvQeOeIaZvqKbShtsRV2vz/0HU.IG', 'administrador', 1, NULL, NULL, NULL, NULL, 1),
(3, 'Miguel', 'Admin', NULL, NULL, 'miguel@fitness.com', '$2y$10$QM1RcUawYlRMO5bj8u89w.z1nu7Z8z0Tf8dX3tLOVI2efhme6GiQe', 'administrador', 1, NULL, NULL, NULL, NULL, 1),
(4, 'Eva', 'Entrenadora', NULL, NULL, 'eva@fitness.com', '$2y$10$.wbFZK1ePyBzxFm3aLXBLuMKtyJv1LM0sBdkPD3QSM/IfqPbYZN/2', 'entrenador', 1, NULL, NULL, NULL, NULL, 1),
(5, 'Pepe', 'Entrenador', NULL, NULL, 'pepe@fitness.com', '$2y$10$zyaWtoz2ErJdDCtv1UuAwedeWrTr4qb4iA26j1T3OIWKljpBVbiZq', 'entrenador', 1, NULL, NULL, NULL, NULL, 1),
(6, 'Ana', 'García', 'Mecha', '666555444', 'ana@gmail.com', '$2y$10$l.Vu1lO9tWoEr9hpk2DiXeksPa10fAWJfHTVZHZzKFg6asdnONIhi', 'usuario', 0, NULL, 4, 1, 1, 1),
(7, 'Luis', 'Pérez', NULL, NULL, 'luis@gmail.com', '$2y$10$pfN.qNsSoiPg5trBj2ozmu03GXGIh.fEf.y6R8TntLsEjYykpjebC', 'usuario', 0, NULL, 5, NULL, NULL, 1),
(8, 'Miguelin', 'Sedan', 'Izurieta', '600112233', 'maik@gmail.com', '$2y$10$Xf2glKXxO3yuSqkkeRXFLualFeFFmCSsfrx7GpgF4Be6cXBQI1uvi', 'usuario', 0, NULL, 4, 3, 3, 1),
(9, 'Jose', 'Lito', '', '666555444', 'jose@gmail.com', '$2y$10$OgJuSGoUDQuRIcmtMiNkk.0RMvdZcatAZcJzXZ0mKNLOeNFo1x9Dy', 'usuario', 0, NULL, NULL, NULL, NULL, 0),
(11, 'asdasd', 'asdasd', 'asdasda', NULL, 'asdas@gmail.com', '$2y$10$oLFrBK.o4IC5eGhHS1.Ut.UT7zbvG8LiHBIPBSShK5/HSmP66dTDO', 'usuario', 0, NULL, 15, NULL, NULL, 1),
(14, 'fsdfs', 'dfsfs', 'sdfsf', '666555444', 'ssss@gmail.com', '$2y$10$NtgJAO0S8mI7BijO/k.02Og/o4w6xcdzJLlLcNoposLMamtBb0l72', 'usuario', 0, NULL, 5, NULL, NULL, 1),
(15, 'Miguel', 'Sedano', 'Mecha', '601334455', 'miguel87@fitness.com', '$2y$10$6Qg7w2sMBXhiW6Ko4SguGOeq/TQQv1KzNIQNOrzo99hIpeNGDJDPS', 'entrenador', 1, NULL, NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `videollamadas`
--

CREATE TABLE `videollamadas` (
  `id_videollamada` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_entrenador` int(11) NOT NULL,
  `fecha` datetime NOT NULL,
  `duracion_minutos` int(11) DEFAULT 30,
  `enlace_sala` varchar(255) DEFAULT NULL,
  `estado` enum('programada','realizada','cancelada') DEFAULT 'programada'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `archivos_sala`
--
ALTER TABLE `archivos_sala`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_entrenador` (`id_entrenador`);

--
-- Indexes for table `comidas`
--
ALTER TABLE `comidas`
  ADD PRIMARY KEY (`id_comida`);

--
-- Indexes for table `dietas`
--
ALTER TABLE `dietas`
  ADD PRIMARY KEY (`id_dieta`),
  ADD KEY `creada_por` (`creada_por`);

--
-- Indexes for table `dieta_comida`
--
ALTER TABLE `dieta_comida`
  ADD PRIMARY KEY (`id_dieta`,`id_comida`,`dia_semana`,`momento`),
  ADD KEY `id_comida` (`id_comida`);

--
-- Indexes for table `dieta_usuario`
--
ALTER TABLE `dieta_usuario`
  ADD PRIMARY KEY (`id_usuario`,`id_dieta`,`fecha_asignacion`),
  ADD KEY `id_dieta` (`id_dieta`);

--
-- Indexes for table `ejercicios`
--
ALTER TABLE `ejercicios`
  ADD PRIMARY KEY (`id_ejercicio`);

--
-- Indexes for table `historial_llamadas`
--
ALTER TABLE `historial_llamadas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_entrenador` (`id_entrenador`);

--
-- Indexes for table `notificaciones_sala`
--
ALTER TABLE `notificaciones_sala`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_entrenador` (`id_entrenador`);

--
-- Indexes for table `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id_pago`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indexes for table `rutinas`
--
ALTER TABLE `rutinas`
  ADD PRIMARY KEY (`id_rutina`),
  ADD KEY `creada_por` (`creada_por`);

--
-- Indexes for table `rutina_ejercicio`
--
ALTER TABLE `rutina_ejercicio`
  ADD PRIMARY KEY (`id_rutina`,`id_ejercicio`,`dia`),
  ADD KEY `id_ejercicio` (`id_ejercicio`);

--
-- Indexes for table `rutina_usuario`
--
ALTER TABLE `rutina_usuario`
  ADD PRIMARY KEY (`id_usuario`,`id_rutina`,`fecha_asignacion`),
  ADD KEY `id_rutina` (`id_rutina`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `correo` (`correo`),
  ADD KEY `id_entrenador` (`id_entrenador`),
  ADD KEY `id_rutina_activa` (`id_rutina_activa`),
  ADD KEY `id_dieta_activa` (`id_dieta_activa`);

--
-- Indexes for table `videollamadas`
--
ALTER TABLE `videollamadas`
  ADD PRIMARY KEY (`id_videollamada`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_entrenador` (`id_entrenador`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `archivos_sala`
--
ALTER TABLE `archivos_sala`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `comidas`
--
ALTER TABLE `comidas`
  MODIFY `id_comida` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `dietas`
--
ALTER TABLE `dietas`
  MODIFY `id_dieta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `ejercicios`
--
ALTER TABLE `ejercicios`
  MODIFY `id_ejercicio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `historial_llamadas`
--
ALTER TABLE `historial_llamadas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `notificaciones_sala`
--
ALTER TABLE `notificaciones_sala`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id_pago` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rutinas`
--
ALTER TABLE `rutinas`
  MODIFY `id_rutina` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `videollamadas`
--
ALTER TABLE `videollamadas`
  MODIFY `id_videollamada` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `archivos_sala`
--
ALTER TABLE `archivos_sala`
  ADD CONSTRAINT `archivos_sala_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `archivos_sala_ibfk_2` FOREIGN KEY (`id_entrenador`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Constraints for table `dietas`
--
ALTER TABLE `dietas`
  ADD CONSTRAINT `dietas_ibfk_1` FOREIGN KEY (`creada_por`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Constraints for table `dieta_comida`
--
ALTER TABLE `dieta_comida`
  ADD CONSTRAINT `dieta_comida_ibfk_1` FOREIGN KEY (`id_dieta`) REFERENCES `dietas` (`id_dieta`) ON DELETE CASCADE,
  ADD CONSTRAINT `dieta_comida_ibfk_2` FOREIGN KEY (`id_comida`) REFERENCES `comidas` (`id_comida`) ON DELETE CASCADE;

--
-- Constraints for table `dieta_usuario`
--
ALTER TABLE `dieta_usuario`
  ADD CONSTRAINT `dieta_usuario_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `dieta_usuario_ibfk_2` FOREIGN KEY (`id_dieta`) REFERENCES `dietas` (`id_dieta`) ON DELETE CASCADE;

--
-- Constraints for table `historial_llamadas`
--
ALTER TABLE `historial_llamadas`
  ADD CONSTRAINT `historial_llamadas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `historial_llamadas_ibfk_2` FOREIGN KEY (`id_entrenador`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Constraints for table `notificaciones_sala`
--
ALTER TABLE `notificaciones_sala`
  ADD CONSTRAINT `notificaciones_sala_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `notificaciones_sala_ibfk_2` FOREIGN KEY (`id_entrenador`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Constraints for table `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `pagos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Constraints for table `rutinas`
--
ALTER TABLE `rutinas`
  ADD CONSTRAINT `rutinas_ibfk_1` FOREIGN KEY (`creada_por`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Constraints for table `rutina_ejercicio`
--
ALTER TABLE `rutina_ejercicio`
  ADD CONSTRAINT `rutina_ejercicio_ibfk_1` FOREIGN KEY (`id_rutina`) REFERENCES `rutinas` (`id_rutina`) ON DELETE CASCADE,
  ADD CONSTRAINT `rutina_ejercicio_ibfk_2` FOREIGN KEY (`id_ejercicio`) REFERENCES `ejercicios` (`id_ejercicio`) ON DELETE CASCADE;

--
-- Constraints for table `rutina_usuario`
--
ALTER TABLE `rutina_usuario`
  ADD CONSTRAINT `rutina_usuario_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `rutina_usuario_ibfk_2` FOREIGN KEY (`id_rutina`) REFERENCES `rutinas` (`id_rutina`) ON DELETE CASCADE;

--
-- Constraints for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`id_entrenador`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL,
  ADD CONSTRAINT `usuarios_ibfk_2` FOREIGN KEY (`id_rutina_activa`) REFERENCES `rutinas` (`id_rutina`) ON DELETE SET NULL,
  ADD CONSTRAINT `usuarios_ibfk_3` FOREIGN KEY (`id_dieta_activa`) REFERENCES `dietas` (`id_dieta`) ON DELETE SET NULL;

--
-- Constraints for table `videollamadas`
--
ALTER TABLE `videollamadas`
  ADD CONSTRAINT `videollamadas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `videollamadas_ibfk_2` FOREIGN KEY (`id_entrenador`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
