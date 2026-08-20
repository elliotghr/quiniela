/*
-- Query: SELECT * FROM quinieland.Rol
-- Date: 2022-10-09 19:01
*/
DELETE FROM `Rol`;
ALTER TABLE `Rol` AUTO_INCREMENT=1;
INSERT INTO `Rol` (`descripcion`) VALUES ('Administrador');
INSERT INTO `Rol` (`descripcion`) VALUES ('Jugador');
