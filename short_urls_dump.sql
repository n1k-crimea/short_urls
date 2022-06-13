CREATE TABLE `short_urls` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `long_url` text NOT NULL,
  `short_code` varchar(32) NOT NULL,
  `date_created` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `short_code` (`short_code`)
)