/*
-- Query: SELECT * FROM photos.Datos_Usuario
-- Date: 2022-10-09 19:00
*/
DELETE FROM `Datos_Usuario`;
ALTER TABLE `Datos_Usuario` AUTO_INCREMENT=1;
INSERT INTO `Datos_Usuario` (`usuario_id`,`nombre`,`apellido_paterno`,`apellido_materno`,`fecha_nacimiento`) VALUES (1,'Ramón Alejandro','Quiroz','Quiroz','1984-11-08');
