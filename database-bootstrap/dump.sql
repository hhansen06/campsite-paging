use `durchsagen`;

DROP TABLE IF EXISTS `durchsagen`;

CREATE TABLE `durchsagen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time_created` int(11) NOT NULL,
  `text` text NOT NULL,
  `filename` varchar(255) NOT NULL,
  `quell_id` int(11) NOT NULL,
  `deleted` int(11) NOT NULL,
  `speechtotext` tinyint(1) NOT NULL,
  `pushed` int(11) NOT NULL,
  `samplerate` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1599 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `durchsagen_quellen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `durchsagen_quellen` (
  `quelle_id` int(11) NOT NULL AUTO_INCREMENT,
  `rufnummer` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `erlaubt` tinyint(1) NOT NULL,
  PRIMARY KEY (`quelle_id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `durchsagen_warteschlange`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `durchsagen_warteschlange` (
  `play_id` int(11) NOT NULL AUTO_INCREMENT,
  `durchsage_id` int(11) NOT NULL,
  `play_time` int(11) NOT NULL,
  `played` tinyint(1) NOT NULL,
  PRIMARY KEY (`play_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `set_name` varchar(255) NOT NULL,
  `set_value` varchar(255) NOT NULL,
  UNIQUE KEY `key` (`set_name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
