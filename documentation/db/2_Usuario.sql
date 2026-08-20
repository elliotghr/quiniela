/*
-- Query: SELECT * FROM quinieland.Usuario
-- Date: 2022-10-09 19:01
*/
DELETE FROM `Usuario`;
ALTER TABLE `Usuario` AUTO_INCREMENT=1;
INSERT INTO `Usuario` (`rol_id`,`usuario`,`clave`,`primera_vez`,`cambio_clave`,`fecha_cambio_clave`) VALUES (1,'hackmon.08@gmail.com','$2y$12$L5G.cUfNkYowMsp0bIIuVeiTTXsl/BT9ofMzbihbvoRpCFJbvYBue',0,0,NOW());
