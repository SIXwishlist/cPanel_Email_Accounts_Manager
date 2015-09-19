
CREATE TABLE IF NOT EXISTS `usuarios` (

  `id` int(10) NOT NULL AUTO_INCREMENT,

  `usuario` varchar(15) NOT NULL,

  `password` varchar(250) NOT NULL,
  
  `correo_alt` varchar(500) NOT NULL,
  
  `nivel` int(1) NOT NULL,

  PRIMARY KEY (`id`),

  UNIQUE KEY `id` (`id`,`usuario`)

) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;