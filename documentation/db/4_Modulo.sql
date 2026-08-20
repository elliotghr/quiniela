/*
-- Query: SELECT * FROM photos.Modulo
-- Date: 2022-10-09 18:59
*/
DELETE FROM `Modulo`;
ALTER TABLE `Modulo` AUTO_INCREMENT=1;
INSERT INTO `Modulo` (`modulo_padre_id`,`titulo`,`descripcion`,`url`,`icono`,`orden`) VALUES (NULL,'Inicio','Página de Inicio','home','home',1);
INSERT INTO `Modulo` (`modulo_padre_id`,`titulo`,`descripcion`,`url`,`icono`,`orden`) VALUES (NULL,'Configuración','Configuración General',NULL,'tools',3);
INSERT INTO `Modulo` (`modulo_padre_id`,`titulo`,`descripcion`,`url`,`icono`,`orden`) VALUES (2,'Mi Cuenta','Administrar Cuenta','config/account','user',4);
INSERT INTO `Modulo` (`modulo_padre_id`,`titulo`,`descripcion`,`url`,`icono`,`orden`) VALUES (2,'Usuarios','CRUD Usuarios','config/users','users',2);
INSERT INTO `Modulo` (`modulo_padre_id`,`titulo`,`descripcion`,`url`,`icono`,`orden`) VALUES (2,'Roles','CRUD Roles','config/rols','tag',1);
INSERT INTO `Modulo` (`modulo_padre_id`,`titulo`,`descripcion`,`url`,`icono`,`orden`) VALUES (2,'Accesos','Asignación de Accesos','config/access','shield-alt',3);
INSERT INTO `Modulo` (`modulo_padre_id`,`titulo`,`descripcion`,`url`,`icono`,`orden`) VALUES (NULL,'Quinielas','Listado de Quinielas',NULL,'clipboard-list',2);
INSERT INTO `Modulo` (`modulo_padre_id`,`titulo`,`descripcion`,`url`,`icono`,`orden`) VALUES (7,'Mis Quinielas','Lista de mis quinielas','quinielas/mis-quinielas','tasks',1);