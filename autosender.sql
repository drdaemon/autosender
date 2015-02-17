--
-- Database: `autosender`
--

-- --------------------------------------------------------

--
-- Table `email`
--

CREATE TABLE IF NOT EXISTS `email` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `filename_text` varchar(100) NOT NULL,
  `filename_html` varchar(100) DEFAULT NULL,
  `hash` varchar(36) NOT NULL,
  `dt_add` datetime NOT NULL,
  `dt_upd` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hash` (`hash`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `mail_list`
--

CREATE TABLE IF NOT EXISTS `mail_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_project` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `url` varchar(100) NOT NULL,
  `from_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `dt_add` datetime NOT NULL,
  `dt_upd` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_project` (`id_project`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Relationship of table `mail_list`:
--   `id_project`
--       `project` -> `id`
--

-- --------------------------------------------------------

--
-- Table `project`
--

CREATE TABLE IF NOT EXISTS `project` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `domain` varchar(100) NOT NULL,
  `url` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mnd_key` varchar(100) NOT NULL,
  `mnd_cb` varchar(100) NOT NULL,
  `is_disabled` tinyint(1) NOT NULL DEFAULT '0',
  `dt_add` datetime NOT NULL,
  `dt_mod` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `schedule`
--

CREATE TABLE IF NOT EXISTS `schedule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_list` int(11) NOT NULL,
  `id_email` int(11) NOT NULL,
  `id_email_prev` int(11) DEFAULT NULL,
  `delay_days` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_list` (`id_list`,`id_email`),
  KEY `id_email` (`id_email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Relationship of table `schedule`:
--   `id_email`
--       `email` -> `id`
--   `id_list`
--       `mail_list` -> `id`
--

-- --------------------------------------------------------

--
-- Table `send_list`
--

CREATE TABLE IF NOT EXISTS `send_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_sub` int(11) NOT NULL,
  `id_email` int(11) NOT NULL,
  `dt_sent` datetime DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `send_count` int(11) NOT NULL DEFAULT '0',
  `mnd_id` varchar(32) DEFAULT NULL,
  `mnd_ts` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_sub` (`id_sub`,`id_email`),
  KEY `id_email` (`id_email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Relationship of table `send_list`:
--   `id_email`
--       `email` -> `id`
--   `id_sub`
--       `sub` -> `id`
--

-- --------------------------------------------------------

--
-- Table `sub`
--

CREATE TABLE IF NOT EXISTS `sub` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `is_disabled` tinyint(1) NOT NULL DEFAULT '0',
  `hash` varchar(36) NOT NULL,
  `hash_parent` varchar(36) DEFAULT NULL,
  `ip` varchar(15) DEFAULT NULL,
  `dt_add` datetime NOT NULL,
  `dt_uns` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hash` (`hash`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `sub_list`
--

CREATE TABLE IF NOT EXISTS `sub_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_sub` int(11) NOT NULL,
  `id_list` int(11) NOT NULL,
  `dt_add` datetime NOT NULL,
  `dt_uns` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_sub` (`id_sub`,`id_list`),
  KEY `id_list` (`id_list`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Relationship of table `sub_list`:
--   `id_sub`
--       `sub` -> `id`
--   `id_list`
--       `mail_list` -> `id`
--

--
-- constraints of tables
--

--
-- constraints of table `mail_list`
--
ALTER TABLE `mail_list`
  ADD CONSTRAINT `mail_list_ibfk_1` FOREIGN KEY (`id_project`) REFERENCES `project` (`id`);

--
-- constraints of table `schedule`
--
ALTER TABLE `schedule`
  ADD CONSTRAINT `schedule_ibfk_1` FOREIGN KEY (`id_email`) REFERENCES `email` (`id`),
  ADD CONSTRAINT `schedule_ibfk_2` FOREIGN KEY (`id_list`) REFERENCES `mail_list` (`id`);

--
-- constraints of table `send_list`
--
ALTER TABLE `send_list`
  ADD CONSTRAINT `send_list_ibfk_1` FOREIGN KEY (`id_email`) REFERENCES `email` (`id`),
  ADD CONSTRAINT `send_list_ibfk_2` FOREIGN KEY (`id_sub`) REFERENCES `sub` (`id`);

--
-- constraints of table `sub_list`
--
ALTER TABLE `sub_list`
  ADD CONSTRAINT `sub_list_ibfk_1` FOREIGN KEY (`id_sub`) REFERENCES `sub` (`id`),
  ADD CONSTRAINT `sub_list_ibfk_2` FOREIGN KEY (`id_list`) REFERENCES `mail_list` (`id`);
