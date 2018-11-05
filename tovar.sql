

--
-- Table structure for table `tovar`
--

DROP TABLE IF EXISTS `tovar`;
CREATE TABLE IF NOT EXISTS `tovar` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `nazov` varchar(20) DEFAULT NULL,
  `vyrobca` varchar(20) NOT NULL,
  `popis` varchar(100) DEFAULT NULL,
  `farba` varchar(20) DEFAULT NULL,
  `cena` int(7) DEFAULT NULL,
  `kod` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tovar`
--

INSERT INTO `tovar` (`id`, `nazov`, `vyrobca`, `popis`, `farba`, `cena`, `kod`) VALUES
(4, 'CD', 'Panasonic', 'CD prehravac', 'striborna', 23, 'CD25416pan'),
(3, 'TV42 S', 'Samsung', 'Smasung smartTV 42"', 'striebornÃ¡', 899, 'samtv42'),
(5, 'Notebook', 'Lenovo', 'i5-5024,1Z,8G, GFX650', 'Äierna', 560, 'Len1485236'),
(6, 'BR player', 'JVC', 'BR prehravaÄ', 'striebornÃ¡', 129, 'jvc25br'),
(7, 'Notebook', 'Lenovo', 'i3-2323,500GB,4GB', 'Äierna', 385, 'Len2323-4'),
(8, 'notebook', 'ASUS', 'A-10-R7-1TG-256SSD-8G', 'strieborna', 656, 'AsusA10-r7');

