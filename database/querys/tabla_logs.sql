DROP TABLE IF EXISTS `logs`;
CREATE TABLE `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `typo` varchar(255) DEFAULT NULL,
  `tabla` varchar(255) DEFAULT NULL,
  `old` text,
  `new` text,
  `valor_alterado` text,
  `usuario` varchar(255) DEFAULT NULL,
  `ip` varchar(255) DEFAULT NULL,
  `fecha` timestamp(6) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1379 DEFAULT CHARSET=utf8;

SET FOREIGN_KEY_CHECKS = 1;