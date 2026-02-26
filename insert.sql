-- 1. USUARIOS 
INSERT INTO usuarios (nombre, apellido1, correo, password_hash, rol, suscrito) VALUES ('Buseif', 'Admin', 'buseif@fitness.com', '$2y$10$jbz8r/2Z7DtSNRVhYLihDe04S3bpges1OH.0zjZxhgJjwEH5Jztze', 'administrador', true), ('Hodei', 'Admin', 'hodei@fitness.com', '$2y$10$IrGfPrOzqB0BBT2giEgr9./BPvQeOeIaZvqKbShtsRV2vz/0HU.IG', 'administrador', true), ('Miguel', 'Admin', 'miguel@fitness.com', '$2y$10$QM1RcUawYlRMO5bj8u89w.z1nu7Z8z0Tf8dX3tLOVI2efhme6GiQe', 'administrador', true), ('Eva', 'Entrenadora', 'eva@fitness.com', '$2y$10$.wbFZK1ePyBzxFm3aLXBLuMKtyJv1LM0sBdkPD3QSM/IfqPbYZN/2', 'entrenador', true), ('Pepe', 'Entrenador', 'pepe@fitness.com', '$2y$10$zyaWtoz2ErJdDCtv1UuAwedeWrTr4qb4iA26j1T3OIWKljpBVbiZq', 'entrenador', true), ('Ana', 'García', 'ana@gmail.com', '$2y$10$bA3Ki08omJGOuUjuzrxRbeitTJWLvY8L7mZKqDzalXV7ju3.u2rdG', 'usuario', false), ('Luis', 'Pérez', 'luis@gmail.com', '$2y$10$pfN.qNsSoiPg5trBj2ozmu03GXGIh.fEf.y6R8TntLsEjYykpjebC', 'usuario', false);

-- 2. EJERCICIOS
INSERT INTO ejercicios (nombre, descripcion, instrucciones, categoria, dificultad, imagen_url) VALUES 
('Press Banca', 'Pecho básico', 'Baja la barra al pecho y empuja.', 'Pecho', 'Intermedio', NULL),
('Sentadilla', 'Pierna global', 'Baja la cadera manteniendo espalda recta.', 'Pierna', 'Intermedio', NULL),
('Peso Muerto', 'Cadena posterior', 'Levanta la barra desde el suelo.', 'Espalda', 'Avanzado', NULL),
('Curl de Bíceps', 'Aislamiento brazo', 'Flexiona el codo con mancuerna.', 'Brazo', 'Principiante', NULL),
('Press Militar', 'Hombro fuerza', 'Empuja la barra sobre la cabeza.', 'Hombro', 'Intermedio', NULL),
('Dominadas', 'Tracción vertical', 'Sube el pecho hasta la barra.', 'Espalda', 'Avanzado', NULL),
('Zancadas', 'Pierna unilateral', 'Da un paso largo y baja la rodilla.', 'Pierna', 'Principiante',NULL),
('Plancha', 'Core isométrico', 'Mantén el cuerpo recto sobre codos.', 'Core', 'Principiante', NULL),
('Fondos', 'Tríceps y pecho', 'Baja el cuerpo entre dos barras.', 'Brazo', 'Intermedio', NULL),
('Remo con Barra', 'Tracción horizontal', 'Tira de la barra hacia el ombligo.', 'Espalda', 'Intermedio', NULL);

-- 3. COMIDAS 
INSERT INTO comidas (nombre, descripcion, calorias, tipo) VALUES ('Tortilla Francesa', '2 huevos con poco aceite', 150, 'desayuno'), ('Pollo con Arroz', '150g pollo y 100g arroz', 450, 'almuerzo'), ('Salmón a la plancha', 'Rico en Omega 3', 400, 'cena'), ('Batido Proteína', 'Suero de leche', 120, 'snack'), ('Avena con Yogur', 'Carbohidrato lento', 300, 'desayuno'), ('Ensalada de Atún', 'Ligera y proteica', 250, 'almuerzo'), ('Frutos Secos', 'Nueces y almendras', 200, 'snack'), ('Ternera con Brócoli', 'Hierro y fibra', 500, 'cena'), ('Tostada con Aguacate', 'Grasas saludables', 280, 'desayuno'), ('Pasta Integral', 'Energía para entrenar', 420, 'almuerzo'); 
-- 4. RUTINAS 
INSERT INTO rutinas (nombre, objetivo, creada_por, es_predefinida) VALUES ('Full Body Eva', 'Acondicionamiento', 4, true), ('Hipertrofia Pepe', 'Ganar músculo', 5, true), ('Pérdida Grasa', 'Definición', 4, true), ('Fuerza 5x5', 'Ganar fuerza', 5, false), ('Torso-Pierna', 'Equilibrio', 4, true), ('Rutina Verano', 'Estética', 4, false), ('Especial Glúteo', 'Volumen inferior', 4, false), ('Empuje/Tirón', 'Frecuencia 2', 5, true), ('Cardio HIIT', 'Quema rápida', 4, true), ('Senior Fit', 'Salud mayores', 5, true); 
-- 5. DIETAS 
INSERT INTO dietas (nombre, descripcion, creada_por, es_predefinida) VALUES ('Dieta Volumen', 'Superávit calórico', 4, true), ('Dieta Definición', 'Déficit controlado', 5, true), ('Keto Gym', 'Baja en hidratos', 4, false), ('Paleo Diet', 'Comida natural', 4, true), ('Vegana Fit', 'Proteína vegetal', 5, true), ('Ayuno Intermitente', 'Protocolo 16/8', 4, false), ('Mediterránea', 'Equilibrada', 5, true), ('Baja en Sodio', 'Para hipertensos', 4, false), ('Alta en Proteína', 'Para culturismo', 5, true), ('Sin Gluten', 'Para celíacos', 5, true); 
-- 6. DIETA-COMIDA
INSERT INTO dieta_comida (id_dieta, id_comida, dia_semana, momento, orden_momento) VALUES
(1, 1, 'lunes', 'mañana', 1),
(1, 2, 'lunes', 'mediodía', 1),
(1, 3, 'lunes', 'noche', 1),
(1, 4, 'lunes', 'tarde', 1),
(2, 5, 'martes', 'mañana', 1),
(2, 6, 'martes', 'mediodía', 1),
(2, 7, 'martes', 'tarde', 1),
(2, 8, 'martes', 'noche', 1),
(3, 9, 'miércoles', 'mañana', 1),
(3, 10, 'miércoles', 'mediodía', 1);

 -- 7. RUTINA-EJERCICIO  
 INSERT INTO rutina_ejercicio (id_rutina, id_ejercicio, orden, series, repeticiones, tiempo_descanso) VALUES
(1, 1, 1, 3, 10, 60),
(1, 2, 2, 3, 12, 60),
(1, 8, 3, 3, 30, 30),
(2, 3, 1, 4, 8, 90),
(2, 5, 2, 4, 10, 60),
(2, 6, 3, 4, 8, 90),
(3, 7, 1, 3, 12, 45),
(3, 4, 2, 3, 15, 45),
(4, 1, 1, 5, 5, 120),
(4, 3, 2, 5, 5, 120);
