-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.5.24-log - MySQL Community Server (GPL)
-- Server OS:                    Win32
-- HeidiSQL version:             7.0.0.4053
-- Date/time:                    2013-03-26 10:14:59
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET FOREIGN_KEY_CHECKS=0 */;

-- Dumping structure for table pi.chat
CREATE TABLE IF NOT EXISTS `chat` (
  `cid` int(10) NOT NULL AUTO_INCREMENT,
  `sender` int(10) DEFAULT NULL,
  `recipient` int(10) DEFAULT NULL,
  `message` text,
  `sent_at` datetime DEFAULT NULL,
  `received_at` datetime DEFAULT NULL,
  PRIMARY KEY (`cid`),
  KEY `FK_chat_users` (`sender`),
  KEY `FK_chat_users_2` (`recipient`),
  CONSTRAINT `FK_chat_users` FOREIGN KEY (`sender`) REFERENCES `users` (`uid`) ON DELETE CASCADE,
  CONSTRAINT `FK_chat_users_2` FOREIGN KEY (`recipient`) REFERENCES `users` (`uid`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=317 DEFAULT CHARSET=utf8;

-- Dumping data for table pi.chat: ~22 rows (approximately)
/*!40000 ALTER TABLE `chat` DISABLE KEYS */;
INSERT INTO `chat` (`cid`, `sender`, `recipient`, `message`, `sent_at`, `received_at`) VALUES
	(295, 1, 3, 'ale coś znowu napiszemy', '2012-10-22 17:13:19', '2012-10-22 17:16:20'),
	(296, 1, 3, 'i ciekawe co teraz będzie :D', '2012-10-22 17:13:31', '2012-10-22 17:16:20'),
	(297, 1, 3, 'a teraz?', '2012-10-22 17:16:35', '2012-10-22 17:16:36'),
	(298, 3, 1, 'no jestem', '2012-10-22 17:16:42', '2012-10-22 17:16:43'),
	(299, 3, 1, 'asd', '2012-10-22 17:17:51', '2012-10-22 17:17:53'),
	(300, 3, 1, 'bum bum bum', '2012-10-22 17:18:08', '2012-10-22 17:18:09'),
	(301, 3, 1, 'wsad', '2012-10-22 17:18:30', '2012-10-22 17:18:32'),
	(302, 3, 1, 'bbb', '2012-10-22 17:18:43', '2012-10-22 17:18:44'),
	(303, 3, 1, 'tes', '2012-10-22 17:19:39', '2012-10-22 17:19:41'),
	(304, 3, 1, 'gg', '2012-10-22 17:24:59', '2012-10-22 17:25:01'),
	(305, 3, 1, 'asdasd', '2012-10-22 17:25:07', '2012-10-22 17:25:09'),
	(306, 3, 1, 'd', '2012-10-22 17:25:21', '2012-10-22 17:25:21'),
	(307, 3, 1, 'd', '2012-10-22 17:25:22', '2012-10-22 17:25:23'),
	(308, 3, 1, 'w', '2012-10-22 17:25:22', '2012-10-22 17:25:23'),
	(309, 3, 1, 'e', '2012-10-22 17:25:22', '2012-10-22 17:25:23'),
	(310, 3, 1, 'df', '2012-10-22 17:25:22', '2012-10-22 17:25:23'),
	(311, 3, 1, 'g', '2012-10-22 17:25:23', '2012-10-22 17:25:23'),
	(312, 3, 1, 'g', '2012-10-22 17:25:23', '2012-10-22 17:25:23'),
	(313, 3, 1, 'r', '2012-10-22 17:25:23', '2012-10-22 17:25:23'),
	(314, 3, 1, 'g', '2012-10-22 17:25:23', '2012-10-22 17:25:23'),
	(315, 1, 3, 'działą to wogóle?', '2013-02-04 16:37:22', '2013-02-04 16:43:44'),
	(316, 3, 1, 'działa? :D', '2013-02-04 16:43:50', '2013-02-04 16:47:30');
/*!40000 ALTER TABLE `chat` ENABLE KEYS */;


-- Dumping structure for table pi.dictionary
CREATE TABLE IF NOT EXISTS `dictionary` (
  `did` int(10) NOT NULL AUTO_INCREMENT,
  `key` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`did`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- Dumping data for table pi.dictionary: ~4 rows (approximately)
/*!40000 ALTER TABLE `dictionary` DISABLE KEYS */;
INSERT INTO `dictionary` (`did`, `key`) VALUES
	(1, '[admin-forms] Wystąpiły błędy podczas przetwarzania formularza.'),
	(2, '[admin-forms] Pole "%s" jest wymagane.'),
	(3, '[admin-forms] Pole "%s" zawiera niepoprawny adres email.'),
	(4, '[admin-login] Niepoprawna nazwa użytkownika lub hasło.');
/*!40000 ALTER TABLE `dictionary` ENABLE KEYS */;


-- Dumping structure for table pi.dictionary_translation
CREATE TABLE IF NOT EXISTS `dictionary_translation` (
  `dtid` int(10) NOT NULL AUTO_INCREMENT,
  `did` int(10) NOT NULL,
  `lid` int(10) NOT NULL,
  `value` text,
  PRIMARY KEY (`dtid`),
  KEY `FK_dictionary_translation_dictionary` (`did`),
  KEY `FK_dictionary_translation_languages` (`lid`),
  CONSTRAINT `FK_dictionary_translation_dictionary` FOREIGN KEY (`did`) REFERENCES `dictionary` (`did`) ON DELETE CASCADE,
  CONSTRAINT `FK_dictionary_translation_languages` FOREIGN KEY (`lid`) REFERENCES `languages` (`lid`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- Dumping data for table pi.dictionary_translation: ~8 rows (approximately)
/*!40000 ALTER TABLE `dictionary_translation` DISABLE KEYS */;
INSERT INTO `dictionary_translation` (`dtid`, `did`, `lid`, `value`) VALUES
	(1, 1, 1, 'Wystąpiły błędy podczas przetwarzania formularza!'),
	(2, 1, 2, 'There are few errors in form!'),
	(3, 2, 1, 'Pole "%s" jest wymagane!'),
	(4, 2, 2, 'Field %s are required!'),
	(5, 3, 1, 'Pole "%s" zawiera niepoprawny adres email.'),
	(6, 3, 2, 'Field "%s" contains an invalid email address.'),
	(7, 4, 1, 'Niepoprawna nazwa użytkownika lub hasło.'),
	(8, 4, 2, 'Incorrect username or password');
/*!40000 ALTER TABLE `dictionary_translation` ENABLE KEYS */;


-- Dumping structure for table pi.documents
CREATE TABLE IF NOT EXISTS `documents` (
  `did` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) DEFAULT NULL,
  `type` varchar(3) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `slug` text,
  `hash` varchar(32) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  `depth` int(11) DEFAULT NULL,
  `parent_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`did`),
  KEY `FK_documents_users` (`uid`),
  KEY `FK_documents_documents` (`parent_id`),
  CONSTRAINT `FK_documents_documents` FOREIGN KEY (`parent_id`) REFERENCES `documents` (`did`) ON DELETE CASCADE,
  CONSTRAINT `FK_documents_users` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- Dumping data for table pi.documents: ~2 rows (approximately)
/*!40000 ALTER TABLE `documents` DISABLE KEYS */;
INSERT INTO `documents` (`did`, `uid`, `type`, `name`, `slug`, `hash`, `created_at`, `modified_at`, `path`, `depth`, `parent_id`) VALUES
	(13, 1, 'DOC', 'wegweg', 'wegweg', 'b9dd25b0456393d220fe7e02119aa957', '2013-01-25 17:09:16', '2013-01-25 17:09:16', '13', 1, NULL),
	(14, 1, 'DIR', 'rtjrtj', 'rtjrtj', '88eb23e1358acec18de83c9b3c7a656e', '2013-01-25 17:09:22', '2013-01-25 17:09:22', '14', 1, NULL);
/*!40000 ALTER TABLE `documents` ENABLE KEYS */;


-- Dumping structure for table pi.documents_translation
CREATE TABLE IF NOT EXISTS `documents_translation` (
  `dtid` int(10) NOT NULL AUTO_INCREMENT,
  `did` int(10) DEFAULT NULL,
  `lid` int(10) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `attachments` text,
  `content` text,
  PRIMARY KEY (`dtid`),
  KEY `FK_documents_translation_documents` (`did`),
  KEY `FK_documents_translation_languages` (`lid`),
  CONSTRAINT `FK_documents_translation_documents` FOREIGN KEY (`did`) REFERENCES `documents` (`did`) ON DELETE CASCADE,
  CONSTRAINT `FK_documents_translation_languages` FOREIGN KEY (`lid`) REFERENCES `languages` (`lid`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;

-- Dumping data for table pi.documents_translation: ~4 rows (approximately)
/*!40000 ALTER TABLE `documents_translation` DISABLE KEYS */;
INSERT INTO `documents_translation` (`dtid`, `did`, `lid`, `title`, `attachments`, `content`) VALUES
	(25, 13, 1, 'wegweg', NULL, ''),
	(26, 13, 2, '', NULL, ''),
	(27, 14, 1, 'rtjrtj', NULL, NULL),
	(28, 14, 2, '', NULL, NULL);
/*!40000 ALTER TABLE `documents_translation` ENABLE KEYS */;


-- Dumping structure for table pi.domains
CREATE TABLE IF NOT EXISTS `domains` (
  `did` int(10) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) DEFAULT NULL,
  `ssl` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`did`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Dumping data for table pi.domains: ~1 rows (approximately)
/*!40000 ALTER TABLE `domains` DISABLE KEYS */;
INSERT INTO `domains` (`did`, `url`, `ssl`, `title`) VALUES
	(1, 'intranet', 'intranet', 'intranet');
/*!40000 ALTER TABLE `domains` ENABLE KEYS */;


-- Dumping structure for table pi.events
CREATE TABLE IF NOT EXISTS `events` (
  `eid` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) DEFAULT NULL,
  `type` int(10) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `starting_at` datetime DEFAULT NULL,
  `ending_at` datetime DEFAULT NULL,
  PRIMARY KEY (`eid`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

-- Dumping data for table pi.events: ~1 rows (approximately)
/*!40000 ALTER TABLE `events` DISABLE KEYS */;
INSERT INTO `events` (`eid`, `uid`, `type`, `created_at`, `starting_at`, `ending_at`) VALUES
	(13, 1, 1, '2013-01-08 21:48:01', '2013-01-06 12:00:00', '2013-01-12 12:00:00');
/*!40000 ALTER TABLE `events` ENABLE KEYS */;


-- Dumping structure for table pi.events_translation
CREATE TABLE IF NOT EXISTS `events_translation` (
  `etid` int(10) NOT NULL AUTO_INCREMENT,
  `eid` int(10) DEFAULT NULL,
  `lid` int(10) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `attachments` text,
  `content` text,
  PRIMARY KEY (`etid`),
  KEY `FK_events_translation_events` (`eid`),
  KEY `FK_events_translation_languages` (`lid`),
  CONSTRAINT `FK_events_translation_events` FOREIGN KEY (`eid`) REFERENCES `events` (`eid`) ON DELETE CASCADE,
  CONSTRAINT `FK_events_translation_languages` FOREIGN KEY (`lid`) REFERENCES `languages` (`lid`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;

-- Dumping data for table pi.events_translation: ~2 rows (approximately)
/*!40000 ALTER TABLE `events_translation` DISABLE KEYS */;
INSERT INTO `events_translation` (`etid`, `eid`, `lid`, `title`, `attachments`, `content`) VALUES
	(25, 13, 1, 'test', NULL, ''),
	(26, 13, 2, 'test', NULL, '');
/*!40000 ALTER TABLE `events_translation` ENABLE KEYS */;


-- Dumping structure for table pi.forms
CREATE TABLE IF NOT EXISTS `forms` (
  `fid` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `slug` text,
  `path` varchar(255) DEFAULT NULL,
  `depth` int(3) DEFAULT NULL,
  `parent_id` int(3) DEFAULT NULL,
  `is_deleted` int(1) DEFAULT '0',
  PRIMARY KEY (`fid`),
  KEY `FK_forms_forms` (`parent_id`),
  CONSTRAINT `FK_forms_forms` FOREIGN KEY (`parent_id`) REFERENCES `forms` (`fid`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;

-- Dumping data for table pi.forms: ~25 rows (approximately)
/*!40000 ALTER TABLE `forms` DISABLE KEYS */;
INSERT INTO `forms` (`fid`, `name`, `slug`, `path`, `depth`, `parent_id`, `is_deleted`) VALUES
	(1, 'intranet', 'intranet', '1', 1, NULL, 0),
	(2, 'login', 'intranet-login', '1.2', 2, 1, 0),
	(3, 'forms', 'intranet-forms', '1.3', 2, 1, 0),
	(4, 'field', 'intranet-forms-field', '1.3.4', 3, 3, 0),
	(5, 'settings', 'intranet-settings', '1.5', 2, 1, 0),
	(6, 'general', 'intranet-settings-general', '1.5.6', 3, 5, 0),
	(7, 'users', 'intranet-users', '1.7', 2, 1, 0),
	(8, 'general', 'intranet-users-general', '1.7.8', 3, 7, 0),
	(9, 'userdata', 'intranet-users-userdata', '1.7.9', 3, 7, 0),
	(10, 'permissions', 'intranet-users-permissions', '1.7.10', 3, 7, 0),
	(11, 'groups', 'intranet-groups', '1.11', 2, 1, 0),
	(12, 'general', 'intranet-groups-general', '1.11.12', 3, 11, 0),
	(13, 'permissions', 'intranet-groups-permissions', '1.11.13', 3, 11, 0),
	(14, 'permissions', 'intranet-permissions', '1.14', 2, 1, 0),
	(15, 'general', 'intranet-permissions-general', '1.14.15', 3, 14, 0),
	(16, 'groups', 'intranet-permissions-groups', '1.14.16', 3, 14, 0),
	(17, 'users', 'intranet-permissions-users', '1.14.17', 3, 14, 0),
	(21, 'dictionary', 'intranet-dictionary', '1.21', 2, 1, 0),
	(22, 'pm', 'intranet-pm', '1.22', 2, 1, 0),
	(23, 'documents', 'intranet-documents', '1.23', 2, 1, 0),
	(24, 'adddocument', 'intranet-documents-adddocument', '1.23.24', 3, 23, 0),
	(25, 'adddirectory', 'intranet-documents-adddirectory', '1.23.25', 3, 23, 0),
	(26, 'news', 'intranet-news', '1.26', 2, 1, 0),
	(27, 'events', 'intranet-events', '1.27', 2, 1, 0),
	(28, 'profile', 'intranet-users-profile', '1.7.28', 3, 7, 0);
/*!40000 ALTER TABLE `forms` ENABLE KEYS */;


-- Dumping structure for table pi.forms_fields
CREATE TABLE IF NOT EXISTS `forms_fields` (
  `ffid` int(10) NOT NULL AUTO_INCREMENT,
  `fid` int(10) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `filters` set('striptags','stripnewlines','stringtrim','stringtolower','slug') DEFAULT NULL,
  `validators` set('notempty','emailaddress','digits','required') DEFAULT NULL,
  `type` enum('text','textarea','wysiwyg','select','multiselect','checkbox','multicheckbox','list','radio','hidden','password','submit','button','treecheckboxes','permissions','treeselect','files','autocomplete','photo','datetime') DEFAULT NULL,
  `is_deleted` int(1) DEFAULT '0',
  `is_multilingual` int(1) DEFAULT '0',
  `sort` int(3) DEFAULT '999',
  PRIMARY KEY (`ffid`),
  KEY `FK_forms_fields_forms` (`fid`),
  CONSTRAINT `FK_forms_fields_forms` FOREIGN KEY (`fid`) REFERENCES `forms` (`fid`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=92 DEFAULT CHARSET=utf8;

-- Dumping data for table pi.forms_fields: ~76 rows (approximately)
/*!40000 ALTER TABLE `forms_fields` DISABLE KEYS */;
INSERT INTO `forms_fields` (`ffid`, `fid`, `name`, `filters`, `validators`, `type`, `is_deleted`, `is_multilingual`, `sort`) VALUES
	(1, 4, 'name', 'stringtolower,slug', 'notempty,required', 'text', 0, 0, 1),
	(2, 4, 'fid', NULL, 'notempty,required', 'treeselect', 0, 0, 2),
	(3, 4, 'filters', NULL, NULL, 'multicheckbox', 0, 0, 3),
	(4, 4, 'validators', NULL, NULL, 'multicheckbox', 0, 0, 4),
	(5, 4, 'type', NULL, NULL, 'select', 0, 0, 5),
	(6, 4, 'is_multilingual', NULL, NULL, 'checkbox', 0, 0, 6),
	(7, 4, 'label', NULL, 'notempty,required', 'text', 0, 1, 7),
	(8, 4, 'description', NULL, NULL, 'text', 0, 1, 8),
	(9, 4, 'default', NULL, NULL, 'text', 0, 1, 9),
	(10, 4, 'options', NULL, NULL, 'list', 0, 1, 10),
	(11, 4, 'save', NULL, NULL, 'submit', 0, 0, 11),
	(12, 2, 'username', NULL, 'notempty,emailaddress,required', 'text', 0, 0, 1),
	(13, 2, 'password', NULL, 'notempty,required', 'password', 0, 0, 2),
	(14, 2, 'login', NULL, NULL, 'submit', 0, 0, 3),
	(15, 3, 'name', 'stringtolower,slug', 'notempty,required', 'text', 0, 0, 1),
	(16, 3, 'parent_id', NULL, 'notempty,required', 'treeselect', 0, 0, 2),
	(17, 3, 'title', NULL, NULL, 'text', 0, 1, 3),
	(18, 3, 'description', NULL, NULL, 'text', 0, 1, 4),
	(19, 3, 'save', NULL, NULL, 'submit', 0, 0, 5),
	(20, 6, 'general_language_method', NULL, NULL, 'select', 0, 0, 1),
	(21, 6, 'general_site_theme', NULL, NULL, 'select', 0, 0, 2),
	(22, 6, 'general_admin_theme', NULL, NULL, 'select', 0, 0, 3),
	(23, 6, 'save', NULL, NULL, 'submit', 0, 0, 4),
	(24, 8, 'username', NULL, 'notempty,emailaddress,required', 'text', 0, 0, 1),
	(25, 8, 'password', NULL, 'notempty,required', 'password', 0, 0, 2),
	(26, 8, 'gid', NULL, 'notempty,required', 'treecheckboxes', 0, 0, 3),
	(27, 9, 'firstname', NULL, NULL, 'text', 0, 0, 1),
	(28, 9, 'lastname', NULL, NULL, 'text', 0, 0, 2),
	(29, 9, 'mobile', NULL, NULL, 'text', 0, 0, 3),
	(30, 10, 'permissions', NULL, NULL, 'permissions', 0, 0, 1),
	(31, 12, 'name', 'slug', 'notempty,required', 'text', 0, 0, 1),
	(32, 12, 'parent_id', NULL, 'notempty,required', 'treeselect', 0, 0, 2),
	(33, 12, 'title', NULL, 'notempty,required', 'text', 0, 1, 3),
	(34, 13, 'permissions', NULL, NULL, 'permissions', 0, 0, 1),
	(35, 15, 'name', 'slug', 'notempty,required', 'text', 0, 0, 1),
	(36, 15, 'parent_id', NULL, 'notempty,required', 'treeselect', 0, 0, 2),
	(37, 15, 'title', NULL, 'notempty,required', 'text', 0, 1, 3),
	(38, 16, 'groups', NULL, NULL, 'permissions', 0, 0, 1),
	(39, 17, 'users', NULL, NULL, 'permissions', 0, 0, 1),
	(52, 21, 'key', NULL, 'notempty,required', 'text', 0, 0, 999),
	(53, 21, 'value', NULL, NULL, 'text', 0, 1, 999),
	(54, 21, 'save', NULL, NULL, 'submit', 0, 0, 999),
	(55, 22, 'subject', NULL, NULL, 'text', 0, 0, 1),
	(56, 22, 'recipient', NULL, 'notempty,required', 'text', 0, 0, 2),
	(57, 22, 'message', NULL, NULL, 'wysiwyg', 0, 0, 4),
	(58, 22, 'send', NULL, NULL, 'submit', 0, 0, 5),
	(59, 22, 'attachments', NULL, NULL, 'files', 0, 0, 3),
	(60, 24, 'title', NULL, NULL, 'text', 0, 1, 3),
	(61, 24, 'name', NULL, 'notempty,required', 'text', 0, 0, 1),
	(62, 24, 'content', NULL, NULL, 'wysiwyg', 0, 1, 5),
	(63, 24, 'attachments', NULL, NULL, 'files', 0, 1, 4),
	(64, 24, 'save', NULL, NULL, 'submit', 0, 0, 6),
	(65, 24, 'parent_id', NULL, NULL, 'treeselect', 0, 0, 2),
	(66, 25, 'name', NULL, 'notempty,required', 'text', 0, 0, 1),
	(67, 25, 'title', NULL, NULL, 'text', 0, 1, 3),
	(68, 25, 'parent_id', NULL, NULL, 'treeselect', 0, 0, 2),
	(69, 25, 'save', NULL, NULL, 'submit', 0, 0, 999),
	(70, 9, 'photo', NULL, NULL, 'photo', 0, 0, 999),
	(72, 26, 'title', NULL, NULL, 'text', 0, 1, 999),
	(73, 26, 'type', NULL, NULL, 'checkbox', 0, 0, 999),
	(74, 26, 'attachments', NULL, NULL, 'files', 0, 1, 999),
	(75, 26, 'content', NULL, NULL, 'wysiwyg', 0, 1, 999),
	(76, 26, 'save', NULL, NULL, 'submit', 0, 0, 999),
	(77, 27, 'type', NULL, NULL, 'checkbox', 0, 0, 3),
	(78, 27, 'title', NULL, 'notempty,required', 'text', 0, 1, 4),
	(79, 27, 'attachments', NULL, NULL, 'files', 0, 1, 5),
	(80, 27, 'content', NULL, NULL, 'wysiwyg', 0, 1, 6),
	(81, 27, 'starting_at', NULL, 'notempty,required', 'datetime', 0, 0, 1),
	(82, 27, 'ending_at', NULL, NULL, 'datetime', 0, 0, 2),
	(83, 27, 'save', NULL, NULL, 'submit', 0, 0, 999),
	(84, 28, 'firstname', NULL, NULL, 'text', 0, 0, 1),
	(85, 28, 'lastname', NULL, NULL, 'text', 0, 0, 2),
	(86, 28, 'mobile', NULL, NULL, 'text', 0, 0, 3),
	(87, 28, 'password', NULL, NULL, 'password', 0, 0, 5),
	(90, 28, 'save', NULL, NULL, 'submit', 0, 0, 6),
	(91, 28, 'photo', NULL, NULL, 'photo', 0, 0, 4);
/*!40000 ALTER TABLE `forms_fields` ENABLE KEYS */;


-- Dumping structure for table pi.forms_fields_translation
CREATE TABLE IF NOT EXISTS `forms_fields_translation` (
  `fftid` int(10) NOT NULL AUTO_INCREMENT,
  `ffid` int(10) DEFAULT NULL,
  `lid` int(10) DEFAULT NULL,
  `label` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `default` varchar(255) DEFAULT NULL,
  `options` text,
  PRIMARY KEY (`fftid`),
  KEY `FK_forms_fields_translation_forms_fields` (`ffid`),
  KEY `FK_forms_fields_translation_languages` (`lid`),
  CONSTRAINT `FK_forms_fields_translation_forms_fields` FOREIGN KEY (`ffid`) REFERENCES `forms_fields` (`ffid`) ON DELETE CASCADE,
  CONSTRAINT `FK_forms_fields_translation_languages` FOREIGN KEY (`lid`) REFERENCES `languages` (`lid`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=171 DEFAULT CHARSET=utf8;

-- Dumping data for table pi.forms_fields_translation: ~152 rows (approximately)
/*!40000 ALTER TABLE `forms_fields_translation` DISABLE KEYS */;
INSERT INTO `forms_fields_translation` (`fftid`, `ffid`, `lid`, `label`, `description`, `default`, `options`) VALUES
	(1, 1, 1, 'Nazwa pola', 'Unikalna nazwa pola', '', NULL),
	(2, 1, 2, 'Field name', 'Unique field name', '', NULL),
	(3, 2, 1, 'Kategoria', '', '', NULL),
	(4, 2, 2, 'Category', '', '', NULL),
	(5, 3, 1, 'Filtry', '', '', '{"striptags":"Usu\\u0144 tagi HTML","stripnewlines":"Usu\\u0144 znaki nowej linii","stringtrim":"Usu\\u0144 znaki specjalne","stringtolower":"Zamie\\u0144 litery na ma\\u0142e","slug":"Zamie\\u0144 na posta\\u0107 SLUG"}'),
	(6, 3, 2, 'Filters', '', '', NULL),
	(7, 4, 1, 'Walidatory', '', '', '{"notempty":"Niepusty","emailaddress":"Adres email","digits":"Tylko cyfry","required":"Wymagane"}'),
	(8, 4, 2, 'Validators', '', '', NULL),
	(9, 5, 1, 'Typ pola', '', '', '{"text":"Pole tekstowe pojedy\\u0144cze","textarea":"Pole tekstowe","wysiwyg":"Edytor wizualny","select":"Lista wyboru","multiselect":"Lista wielokrotnego wyboru","checkbox":"Pole wyboru (checkbox)","multicheckbox":"Pole wielokrotnego wyboru (checkbox)","list":"Lista","radio":"Pole opcji wyboru","hidden":"Pole ukryte","password":"Pole has\\u0142a","permissions":"Uprawnienia","submit":"Przycisk wysy\\u0142ania","button":"Przycisk","treecheckboxes":"Drzewo wielokrotnego wyboru","treeselect":"Drzewo wyboru (select)","files":"Pliki","photo":"Obrazek","datetime":"Data i czas"}'),
	(10, 5, 2, 'Field type', '', '', NULL),
	(11, 6, 1, 'Pole wielojęzyczne', '', '', NULL),
	(12, 6, 2, 'Multilingual field', '', '', NULL),
	(13, 7, 1, 'Etykieta', '', '', NULL),
	(14, 7, 2, 'Label', '', '', NULL),
	(15, 8, 1, 'Opis pomocniczy', '', '', NULL),
	(16, 8, 2, 'Description', '', '', NULL),
	(17, 9, 1, 'Wartość domyślna', '', '', NULL),
	(18, 9, 2, 'Default value', '', '', NULL),
	(19, 10, 1, 'Opcje wartości', '', '', NULL),
	(20, 10, 2, 'Options', '', '', NULL),
	(21, 11, 1, 'Zapisz', '', '', NULL),
	(22, 11, 2, 'Save', '', '', NULL),
	(23, 12, 1, 'Nazwa użytkownika', '', '', NULL),
	(24, 12, 2, 'Username', '', '', NULL),
	(25, 13, 1, 'Hasło', '', '', NULL),
	(26, 13, 2, 'Password', '', '', NULL),
	(27, 14, 1, 'Zaloguj', '', '', NULL),
	(28, 14, 2, 'Log in', '', '', NULL),
	(29, 15, 1, 'Nazwa formularza', 'Unikalna nazwa formularza', '', NULL),
	(30, 15, 2, 'Form name', 'Unique form name', '', NULL),
	(31, 16, 1, 'Kategoria', '', '', NULL),
	(32, 16, 2, 'Category', '', '', NULL),
	(33, 17, 1, 'Tytuł', '', '', NULL),
	(34, 17, 2, 'Title', '', '', NULL),
	(35, 18, 1, 'Opis', '', '', NULL),
	(36, 18, 2, 'Description', '', '', NULL),
	(37, 19, 1, 'Zapisz', '', '', NULL),
	(38, 19, 2, 'Save', '', '', NULL),
	(39, 20, 1, 'Negocjuj język z przeglądarką:', '', '', '{"auto":"Automatycznie","preferred":"Manualnie"}'),
	(40, 20, 2, 'Negotiate language with browser', '', '', '{"auto":"Automatic","preferred":"Manualnie"}'),
	(41, 21, 1, 'Szablon strony www', '', '', '{"Public":"Domy\\u015blny"}'),
	(42, 21, 2, 'Site theme', '', '', '{"Public":"Default"}'),
	(43, 22, 1, 'Szablon panelu administracyjnego', '', '', '{"Admin":"Domy\\u015blny"}'),
	(44, 22, 2, 'Administration panel theme', '', '', '{"Admin":"Default"}'),
	(45, 23, 1, 'Zapisz', '', '', NULL),
	(46, 23, 2, 'Save', '', '', NULL),
	(47, 24, 1, 'Nazwa użytkownika', '', '', NULL),
	(48, 24, 2, 'Username', '', '', NULL),
	(49, 25, 1, 'Hasło', '', '', NULL),
	(50, 25, 2, 'Password', '', '', NULL),
	(51, 26, 1, 'Grupa', '', '', NULL),
	(52, 26, 2, 'Group', '', '', NULL),
	(53, 27, 1, 'Imię', '', '', NULL),
	(54, 27, 2, 'Firstname', '', '', NULL),
	(55, 28, 1, 'Nazwisko', '', '', NULL),
	(56, 28, 2, 'Lastname', '', '', NULL),
	(57, 29, 1, 'Telefon', '', '', NULL),
	(58, 29, 2, 'Mobile', '', '', NULL),
	(59, 30, 1, 'Uprawnienia', '', '', '{"1":"Zezwalaj","0":"Odm\\u00f3w"}'),
	(60, 30, 2, 'Permissions', '', '', NULL),
	(61, 31, 1, 'Nazwa grupy', '', '', NULL),
	(62, 31, 2, 'Name', '', '', NULL),
	(63, 32, 1, 'Grupa nadrzędna', '', '', NULL),
	(64, 32, 2, 'Parent group', '', '', NULL),
	(65, 33, 1, 'Tytuł grupy', '', '', NULL),
	(66, 33, 2, 'Group title', '', '', NULL),
	(67, 34, 1, 'Uprawnienia', '', '', NULL),
	(68, 34, 2, 'Permissions', '', '', NULL),
	(69, 35, 1, 'Nazwa uprawnienia', 'Unikalna nazwa', '', NULL),
	(70, 35, 2, 'Permission name', 'Unique name', '', NULL),
	(71, 36, 1, 'Uprawnienie nadrzędne', '', '', NULL),
	(72, 36, 2, 'Parent permission', '', '', NULL),
	(73, 37, 1, 'Tytuł uprawnienia', '', '', NULL),
	(74, 37, 2, 'Title permission', '', '', NULL),
	(75, 38, 1, 'Przypisz uprawnienie do grup', '', '', NULL),
	(76, 38, 2, 'Assign permission to groups', '', '', NULL),
	(77, 39, 1, 'Przypisz uprawnienie do użytkowników', '', '', NULL),
	(78, 39, 2, 'Assign permission to users', '', '', NULL),
	(91, 52, 1, 'Klucz tłumaczenia', '', '', NULL),
	(92, 52, 2, 'Key translation', '', '', NULL),
	(93, 53, 1, 'Tłumaczenie', '', '', NULL),
	(94, 53, 2, 'Translation', '', '', NULL),
	(95, 54, 1, 'Zapisz', '', '', NULL),
	(96, 54, 2, 'Save', '', '', NULL),
	(97, 55, 1, 'Temat', '', 'Brak tematu', NULL),
	(98, 55, 2, 'Subject', '', 'No subject', NULL),
	(99, 56, 1, 'Do', 'Wpisz 3 pierwsze znaki w celu wyszukania adresata.', '', NULL),
	(100, 56, 2, 'To', 'Entry 3 chars to search recipient', '', NULL),
	(101, 57, 1, 'Wiadomość', '', '', NULL),
	(102, 57, 2, 'Message', '', '', NULL),
	(103, 58, 1, 'Wyślij', '', '', NULL),
	(104, 58, 2, 'Send', '', '', NULL),
	(105, 59, 1, 'Załączniki', '', '', NULL),
	(106, 59, 2, 'Attachments', '', '', NULL),
	(107, 60, 1, 'Tytuł', '', '', NULL),
	(108, 60, 2, 'Title', '', '', NULL),
	(109, 61, 1, 'Nazwa dokumentu', '', '', NULL),
	(110, 61, 2, 'Name', '', '', NULL),
	(111, 62, 1, 'Treść', '', '', NULL),
	(112, 62, 2, 'Content', '', '', NULL),
	(113, 63, 1, 'Załączniki', '', '', NULL),
	(114, 63, 2, 'Attachments', '', '', NULL),
	(115, 64, 1, 'Zapisz', '', '', NULL),
	(116, 64, 2, 'Save', '', '', NULL),
	(117, 65, 1, 'Folder nadrzędny', '', '', NULL),
	(118, 65, 2, 'Parent directory', '', '', NULL),
	(119, 66, 1, 'Nazwa folderu', '', '', NULL),
	(120, 66, 2, 'Name', '', '', NULL),
	(121, 67, 1, 'Tytuł', '', '', NULL),
	(122, 67, 2, 'Title', '', '', NULL),
	(123, 68, 1, 'Folder nadrzędny', '', '', NULL),
	(124, 68, 2, 'Parent directory', '', '', NULL),
	(125, 69, 1, 'Zapisz', '', '', NULL),
	(126, 69, 2, 'Save', '', '', NULL),
	(127, 70, 1, 'Zdjęcie', '', '', NULL),
	(128, 70, 2, 'Photo', '', '', NULL),
	(131, 72, 1, 'Tytuł', '', '', NULL),
	(132, 72, 2, 'Title', '', '', NULL),
	(133, 73, 1, 'Wyróżniona', '', '', NULL),
	(134, 73, 2, 'Important', '', '', NULL),
	(135, 74, 1, 'Załączniki', '', '', NULL),
	(136, 74, 2, 'Attachments', '', '', NULL),
	(137, 75, 1, 'Treść', '', '', NULL),
	(138, 75, 2, 'Content', '', '', NULL),
	(139, 76, 1, 'Zapisz', '', '', NULL),
	(140, 76, 2, 'Save', '', '', NULL),
	(141, 77, 1, 'Wyróżnione', '', '', NULL),
	(142, 77, 2, 'Featured', '', '', NULL),
	(143, 78, 1, 'Tytuł', '', '', NULL),
	(144, 78, 2, 'Title', '', '', NULL),
	(145, 79, 1, 'Załączniki', '', '', NULL),
	(146, 79, 2, 'Attachments', '', '', NULL),
	(147, 80, 1, 'Opis', '', '', NULL),
	(148, 80, 2, 'Description', '', '', NULL),
	(149, 81, 1, 'Data rozpoczęcia', '', '', NULL),
	(150, 81, 2, 'Start date', '', '', NULL),
	(151, 82, 1, 'Data zakończenia', '', '', NULL),
	(152, 82, 2, 'Ending date', '', '', NULL),
	(153, 83, 1, 'Zapisz', '', '', NULL),
	(154, 83, 2, 'Save', '', '', NULL),
	(155, 84, 1, 'Imię', '', '', NULL),
	(156, 84, 2, 'Firstname', '', '', NULL),
	(157, 85, 1, 'Nazwisko', '', '', NULL),
	(158, 85, 2, 'Lastname', '', '', NULL),
	(159, 86, 1, 'Telefon', '', '', NULL),
	(160, 86, 2, 'Mobile', '', '', NULL),
	(161, 87, 1, 'Hasło', '', '', NULL),
	(162, 87, 2, 'Password', '', '', NULL),
	(167, 90, 1, 'Zapisz', '', '', NULL),
	(168, 90, 2, 'Save', '', '', NULL),
	(169, 91, 1, 'Zdjęcie profilowe', '', '', NULL),
	(170, 91, 2, 'Profile photo', '', '', NULL);
/*!40000 ALTER TABLE `forms_fields_translation` ENABLE KEYS */;


-- Dumping structure for table pi.forms_translation
CREATE TABLE IF NOT EXISTS `forms_translation` (
  `ftid` int(10) NOT NULL AUTO_INCREMENT,
  `fid` int(10) DEFAULT NULL,
  `lid` int(10) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ftid`),
  KEY `FK_forms_translation_forms` (`fid`),
  KEY `FK_forms_translation_languages` (`lid`),
  CONSTRAINT `FK_forms_translation_forms` FOREIGN KEY (`fid`) REFERENCES `forms` (`fid`) ON DELETE CASCADE,
  CONSTRAINT `FK_forms_translation_languages` FOREIGN KEY (`lid`) REFERENCES `languages` (`lid`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8;

-- Dumping data for table pi.forms_translation: ~50 rows (approximately)
/*!40000 ALTER TABLE `forms_translation` DISABLE KEYS */;
INSERT INTO `forms_translation` (`ftid`, `fid`, `lid`, `title`, `description`) VALUES
	(1, 1, 1, 'Intranet', 'Intranet'),
	(2, 1, 2, 'Intranet', 'Intranet'),
	(3, 2, 1, 'Logowanie', 'Formularz logowania'),
	(4, 2, 2, 'Login', 'Login Form'),
	(5, 3, 1, 'Formularze', 'Formularze'),
	(6, 3, 2, 'Forms', 'Forms'),
	(7, 4, 1, 'Pola formularzy', 'Pola formularzy'),
	(8, 4, 2, 'Form fields', 'Form fields'),
	(9, 5, 1, 'Ustawienia', 'Ustawienia'),
	(10, 5, 2, 'Settings', 'Settings'),
	(11, 6, 1, 'Podstawowe', 'Podstawowe'),
	(12, 6, 2, 'General', 'General'),
	(13, 7, 1, 'Użytkownicy', 'Użytkownicy'),
	(14, 7, 2, 'Users', 'Users'),
	(15, 8, 1, 'Podstawowe dane', 'Podstawowe dane'),
	(16, 8, 2, 'General data', 'General data'),
	(17, 9, 1, 'Dane dodatkowe', 'Dane dodatkowe'),
	(18, 9, 2, 'Additional data', 'Additional data'),
	(19, 10, 1, 'Uprawnienia', 'Uprawnienia'),
	(20, 10, 2, 'Permissions', 'Permissions'),
	(21, 11, 1, 'Grupy', 'Grupy'),
	(22, 11, 2, 'Groups', 'Groups'),
	(23, 12, 1, 'Podstawowe dane', 'Podstawowe dane'),
	(24, 12, 2, 'General data', 'General data'),
	(25, 13, 1, 'Uprawnienia', 'Uprawnienia'),
	(26, 13, 2, 'Permissions', 'Permissions'),
	(27, 14, 1, 'Uprawnienia', 'Uprawnienia'),
	(28, 14, 2, 'Permissions', 'Permissions'),
	(29, 15, 1, 'Podstawowe dane', 'Podstawowe dane'),
	(30, 15, 2, 'General data', 'General data'),
	(31, 16, 1, 'Grupy', 'Grupy'),
	(32, 16, 2, 'Groups', 'Groups'),
	(33, 17, 1, 'Użytkownicy', 'Użytkownicy'),
	(34, 17, 2, 'Users', 'Users'),
	(41, 21, 1, 'Słownik', ''),
	(42, 21, 2, 'Dictionary', ''),
	(43, 22, 1, 'Wiadomości prywatne', ''),
	(44, 22, 2, 'Private messages', ''),
	(45, 23, 1, 'Dokumenty', ''),
	(46, 23, 2, 'Documents', ''),
	(47, 24, 1, 'Dodawanie dokumentu', ''),
	(48, 24, 2, 'Adding document', ''),
	(49, 25, 1, 'Dodawanie folderu', ''),
	(50, 25, 2, 'Adding directory', ''),
	(51, 26, 1, 'Biuletyn', ''),
	(52, 26, 2, 'Bulletin', ''),
	(53, 27, 1, 'Kalendarz wydarzeń', ''),
	(54, 27, 2, 'Events', ''),
	(55, 28, 1, 'Profil', ''),
	(56, 28, 2, 'Profile', '');
/*!40000 ALTER TABLE `forms_translation` ENABLE KEYS */;


-- Dumping structure for table pi.groups
CREATE TABLE IF NOT EXISTS `groups` (
  `gid` int(10) NOT NULL AUTO_INCREMENT,
  `parent_id` int(3) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  `slug` text,
  `depth` int(3) DEFAULT NULL,
  `is_deleted` int(1) DEFAULT '0',
  PRIMARY KEY (`gid`),
  KEY `FK_groups_groups` (`parent_id`),
  CONSTRAINT `FK_groups_groups` FOREIGN KEY (`parent_id`) REFERENCES `groups` (`gid`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Dumping data for table pi.groups: ~2 rows (approximately)
/*!40000 ALTER TABLE `groups` DISABLE KEYS */;
INSERT INTO `groups` (`gid`, `parent_id`, `name`, `path`, `slug`, `depth`, `is_deleted`) VALUES
	(1, NULL, 'users', '1', 'users', 1, 0),
	(2, 1, 'test', '1.2', 'users-test', 2, 0);
/*!40000 ALTER TABLE `groups` ENABLE KEYS */;


-- Dumping structure for table pi.groups_permissions
CREATE TABLE IF NOT EXISTS `groups_permissions` (
  `gpid` int(10) NOT NULL AUTO_INCREMENT,
  `gid` int(10) DEFAULT NULL,
  `pid` int(10) DEFAULT NULL,
  `is_allowed` int(1) DEFAULT '0',
  PRIMARY KEY (`gpid`),
  KEY `FK_groups_permissions_groups` (`gid`),
  KEY `FK_groups_permissions_permissions` (`pid`),
  CONSTRAINT `FK_groups_permissions_groups` FOREIGN KEY (`gid`) REFERENCES `groups` (`gid`) ON DELETE CASCADE,
  CONSTRAINT `FK_groups_permissions_permissions` FOREIGN KEY (`pid`) REFERENCES `permissions` (`pid`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=328 DEFAULT CHARSET=utf8;

-- Dumping data for table pi.groups_permissions: ~2 rows (approximately)
/*!40000 ALTER TABLE `groups_permissions` DISABLE KEYS */;
INSERT INTO `groups_permissions` (`gpid`, `gid`, `pid`, `is_allowed`) VALUES
	(326, 1, 1, 1),
	(327, 2, 1, 1);
/*!40000 ALTER TABLE `groups_permissions` ENABLE KEYS */;


-- Dumping structure for table pi.groups_translation
CREATE TABLE IF NOT EXISTS `groups_translation` (
  `gtid` int(10) NOT NULL AUTO_INCREMENT,
  `gid` int(10) DEFAULT NULL,
  `lid` int(10) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`gtid`),
  KEY `FK_groups_translation_languages` (`lid`),
  KEY `FK_groups_translation_groups` (`gid`),
  CONSTRAINT `FK_groups_translation_groups` FOREIGN KEY (`gid`) REFERENCES `groups` (`gid`) ON DELETE CASCADE,
  CONSTRAINT `FK_groups_translation_languages` FOREIGN KEY (`lid`) REFERENCES `languages` (`lid`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- Dumping data for table pi.groups_translation: ~6 rows (approximately)
/*!40000 ALTER TABLE `groups_translation` DISABLE KEYS */;
INSERT INTO `groups_translation` (`gtid`, `gid`, `lid`, `title`) VALUES
	(1, 1, 1, 'Użytkownicy'),
	(2, 1, 2, 'Users'),
	(5, NULL, 1, 'thtr'),
	(6, NULL, 2, 'hrtth'),
	(7, 2, 1, 'test'),
	(8, 2, 2, 'test');
/*!40000 ALTER TABLE `groups_translation` ENABLE KEYS */;


-- Dumping structure for table pi.languages
CREATE TABLE IF NOT EXISTS `languages` (
  `lid` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `code` varchar(255) NOT NULL DEFAULT '',
  `encoding` enum('UTF-8') DEFAULT 'UTF-8',
  `is_default` int(1) DEFAULT '0',
  `is_active` int(1) DEFAULT '0',
  `sort` int(3) DEFAULT '999',
  PRIMARY KEY (`lid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Dumping data for table pi.languages: ~2 rows (approximately)
/*!40000 ALTER TABLE `languages` DISABLE KEYS */;
INSERT INTO `languages` (`lid`, `title`, `code`, `encoding`, `is_default`, `is_active`, `sort`) VALUES
	(1, 'polski', 'pl', 'UTF-8', 1, 1, 1),
	(2, 'English', 'en', 'UTF-8', 0, 1, 2);
/*!40000 ALTER TABLE `languages` ENABLE KEYS */;


-- Dumping structure for table pi.news
CREATE TABLE IF NOT EXISTS `news` (
  `nid` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) DEFAULT NULL,
  `type` int(1) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`nid`),
  KEY `FK_news_users` (`uid`),
  CONSTRAINT `FK_news_users` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- Dumping data for table pi.news: ~3 rows (approximately)
/*!40000 ALTER TABLE `news` DISABLE KEYS */;
INSERT INTO `news` (`nid`, `uid`, `type`, `created_at`) VALUES
	(12, 1, 1, '2013-01-08 20:58:39'),
	(13, 1, 0, '2013-01-08 21:15:42'),
	(14, 1, 1, '2013-01-08 21:16:24');
/*!40000 ALTER TABLE `news` ENABLE KEYS */;


-- Dumping structure for table pi.news_translation
CREATE TABLE IF NOT EXISTS `news_translation` (
  `ntid` int(10) NOT NULL AUTO_INCREMENT,
  `nid` int(10) DEFAULT NULL,
  `lid` int(10) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `attachments` text,
  `content` text,
  PRIMARY KEY (`ntid`),
  KEY `FK_news_translation_news` (`nid`),
  KEY `FK_news_translation_languages` (`lid`),
  CONSTRAINT `FK_news_translation_languages` FOREIGN KEY (`lid`) REFERENCES `languages` (`lid`) ON DELETE CASCADE,
  CONSTRAINT `FK_news_translation_news` FOREIGN KEY (`nid`) REFERENCES `news` (`nid`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;

-- Dumping data for table pi.news_translation: ~6 rows (approximately)
/*!40000 ALTER TABLE `news_translation` DISABLE KEYS */;
INSERT INTO `news_translation` (`ntid`, `nid`, `lid`, `title`, `attachments`, `content`) VALUES
	(23, 12, 1, 'Zmiany w firmie', NULL, '<p>Proszę o zapoznanie się ze zmianami w firmie.</p>'),
	(24, 12, 2, '', NULL, ''),
	(25, 13, 1, 'Hmm ciekawe', NULL, '<p>Lorem ipsum dolor sit amet enim. Etiam ullamcorper. Suspendisse a pellentesque dui, non felis. Maecenas malesuada elit lectus felis, malesuada ultricies. Curabitur et ligula. Ut molestie a, ultricies porta urna. Vestibulum commodo volutpat a, convallis ac, laoreet enim. Phasellus fermentum in, dolor. Pellentesque facilisis. Nulla imperdiet sit amet magna. Vestibulum dapibus, mauris nec malesuada fames ac turpis velit, rhoncus eu, luctus et interdum adipiscing wisi.</p>\r\n<p>Lorem ipsum dolor sit amet enim. Etiam ullamcorper. Suspendisse a pellentesque dui, non felis. Maecenas malesuada elit lectus felis, malesuada ultricies. Curabitur et ligula. Ut molestie a, ultricies porta urna. Vestibulum commodo volutpat a, convallis ac, laoreet enim. Phasellus fermentum in, dolor. Pellentesque facilisis. Nulla imperdiet sit amet magna. Vestibulum dapibus, mauris nec malesuada fames ac turpis velit, rhoncus eu, luctus et interdum adipiscing wisi.</p>'),
	(26, 13, 2, '', NULL, ''),
	(27, 14, 1, 'Bardzo przydługawy tytuł ciekawe czy się zmieści? ;D', NULL, '<p>Lorem ipsum dolor sit amet enim. Etiam ullamcorper. Suspendisse a pellentesque dui, non felis. Maecenas malesuada elit lectus felis, malesuada ultricies. Curabitur et ligula. Ut molestie a, ultricies porta urna. Vestibulum commodo volutpat a, convallis ac, laoreet enim. Phasellus fermentum in, dolor. Pellentesque facilisis. Nulla imperdiet sit amet magna. Vestibulum dapibus, mauris nec malesuada fames ac turpis velit, rhoncus eu, luctus et interdum adipiscing wisi.</p>'),
	(28, 14, 2, '', NULL, '');
/*!40000 ALTER TABLE `news_translation` ENABLE KEYS */;


-- Dumping structure for table pi.permissions
CREATE TABLE IF NOT EXISTS `permissions` (
  `pid` int(10) NOT NULL AUTO_INCREMENT,
  `did` int(10) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `slug` text,
  `path` varchar(255) DEFAULT NULL,
  `depth` int(3) DEFAULT NULL,
  `parent_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`pid`),
  KEY `did` (`did`),
  KEY `FK_permissions_permissions` (`parent_id`),
  CONSTRAINT `FK_permissions_domains` FOREIGN KEY (`did`) REFERENCES `domains` (`did`),
  CONSTRAINT `FK_permissions_permissions` FOREIGN KEY (`parent_id`) REFERENCES `permissions` (`pid`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8;

-- Dumping data for table pi.permissions: ~52 rows (approximately)
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` (`pid`, `did`, `name`, `slug`, `path`, `depth`, `parent_id`) VALUES
	(1, 1, 'intranet', 'intranet', '1', 1, NULL),
	(2, 1, 'dashboard', 'intranet-dashboard', '1.2', 2, 1),
	(4, 1, 'permissions', 'intranet-permissions', '1.4', 2, 1),
	(5, 1, 'groups', 'intranet-groups', '1.5', 2, 1),
	(6, 1, 'forms', 'intranet-forms', '1.6', 2, 1),
	(7, 1, 'settings', 'intranet-settings', '1.7', 2, 1),
	(8, 1, 'users', 'intranet-users', '1.8', 2, 1),
	(9, 1, 'dictionary', 'intranet-dictionary', '1.9', 2, 1),
	(23, 1, 'chat', 'intranet-chat', '1.23', 2, 1),
	(24, 1, 'pm', 'intranet-pm', '1.24', 2, 1),
	(25, 1, 'documents', 'intranet-documents', '1.25', 2, 1),
	(26, 1, 'employees', 'intranet-employees', '1.26', 2, 1),
	(27, 1, 'news', 'intranet-news', '1.27', 2, 1),
	(28, 1, 'events', 'intranet-events', '1.28', 2, 1),
	(29, 1, 'profile', 'intranet-profile', '1.29', 2, 1),
	(30, 1, 'create', 'intranet-events-create', '1.28.30', 3, 28),
	(31, 1, 'remove', 'intranet-events-remove', '1.28.31', 3, 28),
	(32, 1, 'edit', 'intranet-events-edit', '1.28.32', 3, 28),
	(33, 1, 'removeown', 'intranet-events-removeown', '1.28.33', 3, 28),
	(34, 1, 'editown', 'intranet-events-editown', '1.28.34', 3, 28),
	(35, 1, 'create', 'intranet-news-create', '1.27.35', 3, 27),
	(36, 1, 'edit', 'intranet-news-edit', '1.27.36', 3, 27),
	(37, 1, 'editown', 'intranet-news-editown', '1.27.37', 3, 27),
	(38, 1, 'remove', 'intranet-news-remove', '1.27.38', 3, 27),
	(39, 1, 'removeown', 'intranet-news-removeown', '1.27.39', 3, 27),
	(40, 1, 'createdirectory', 'intranet-documents-createdirectory', '1.25.40', 3, 25),
	(41, 1, 'createdocument', 'intranet-documents-createdocument', '1.25.41', 3, 25),
	(42, 1, 'editdirectory', 'intranet-documents-editdirectory', '1.25.42', 3, 25),
	(43, 1, 'editowndirectory', 'intranet-documents-editowndirectory', '1.25.43', 3, 25),
	(44, 1, 'editdocument', 'intranet-documents-editdocument', '1.25.44', 3, 25),
	(45, 1, 'editowndocument', 'intranet-documents-editowndocument', '1.25.45', 3, 25),
	(46, 1, 'removedirectory', 'intranet-documents-removedirectory', '1.25.46', 3, 25),
	(47, 1, 'removeowndirectory', 'intranet-documents-removeowndirectory', '1.25.47', 3, 25),
	(48, 1, 'removedocument', 'intranet-documents-removedocument', '1.25.48', 3, 25),
	(49, 1, 'removeowndocument', 'intranet-documents-removeowndocument', '1.25.49', 3, 25),
	(50, 1, 'create', 'intranet-pm-create', '1.24.50', 3, 24),
	(51, 1, 'remove', 'intranet-pm-remove', '1.24.51', 3, 24),
	(52, 1, 'reply', 'intranet-pm-reply', '1.24.52', 3, 24),
	(53, 1, 'create', 'intranet-chat-create', '1.23.53', 3, 23),
	(54, 1, 'create', 'intranet-dictionary-create', '1.9.54', 3, 9),
	(55, 1, 'edit', 'intranet-dictionary-edit', '1.9.55', 3, 9),
	(56, 1, 'remove', 'intranet-dictionary-remove', '1.9.56', 3, 9),
	(57, 1, 'create', 'intranet-users-create', '1.8.57', 3, 8),
	(58, 1, 'edit', 'intranet-users-edit', '1.8.58', 3, 8),
	(59, 1, 'remove', 'intranet-users-remove', '1.8.59', 3, 8),
	(60, 1, 'create', 'intranet-groups-create', '1.5.60', 3, 5),
	(61, 1, 'edit', 'intranet-groups-edit', '1.5.61', 3, 5),
	(62, 1, 'remove', 'intranet-groups-remove', '1.5.62', 3, 5),
	(63, 1, 'create', 'intranet-permissions-create', '1.4.63', 3, 4),
	(64, 1, 'edit', 'intranet-permissions-edit', '1.4.64', 3, 4),
	(65, 1, 'remove', 'intranet-permissions-remove', '1.4.65', 3, 4),
	(66, 1, 'files', 'intranet-files', '1.66', 2, 1);
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;


-- Dumping structure for table pi.permissions_translation
CREATE TABLE IF NOT EXISTS `permissions_translation` (
  `ptid` int(10) NOT NULL AUTO_INCREMENT,
  `pid` int(10) DEFAULT '0',
  `lid` int(10) DEFAULT '0',
  `title` text,
  PRIMARY KEY (`ptid`),
  KEY `FK_permissions_translation_permissions` (`pid`),
  KEY `FK_permissions_translation_languages` (`lid`),
  CONSTRAINT `FK_permissions_translation_languages` FOREIGN KEY (`lid`) REFERENCES `languages` (`lid`) ON DELETE CASCADE,
  CONSTRAINT `FK_permissions_translation_permissions` FOREIGN KEY (`pid`) REFERENCES `permissions` (`pid`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=139 DEFAULT CHARSET=utf8;

-- Dumping data for table pi.permissions_translation: ~104 rows (approximately)
/*!40000 ALTER TABLE `permissions_translation` DISABLE KEYS */;
INSERT INTO `permissions_translation` (`ptid`, `pid`, `lid`, `title`) VALUES
	(1, 1, 1, 'Intranet'),
	(2, 2, 1, 'Pulpit'),
	(3, 2, 2, 'Dashboard'),
	(4, 4, 1, 'Uprawnienia'),
	(5, 4, 2, 'Permissions'),
	(6, 5, 1, 'Grupy Użytkowników'),
	(7, 5, 2, 'User Groups'),
	(8, 6, 1, 'Formularze'),
	(9, 6, 2, 'Forms'),
	(10, 7, 1, 'Ustawienia'),
	(11, 7, 2, 'Settings'),
	(12, 8, 1, 'Użytkownicy'),
	(13, 8, 2, 'Users'),
	(14, 9, 1, 'Słownik'),
	(15, 9, 2, 'Dictionary'),
	(24, 1, 2, 'Intranet'),
	(51, 23, 1, 'Czat'),
	(52, 23, 2, 'Chat'),
	(53, 24, 1, 'Wiadomości prywatne'),
	(54, 24, 2, 'Private messages'),
	(55, 25, 1, 'Dokumenty'),
	(56, 25, 2, 'Documents'),
	(57, 26, 1, 'Struktura pracowników'),
	(58, 26, 2, 'Employees structure'),
	(59, 27, 1, 'Biuletyn'),
	(60, 27, 2, 'Bulletin'),
	(61, 28, 1, 'Kalendarz wydarzeń'),
	(62, 28, 2, 'Events'),
	(63, 29, 1, 'Edycja profilu'),
	(64, 29, 2, 'Edit profile'),
	(65, 30, 1, 'Tworzenie wydarzeń'),
	(66, 30, 2, 'Adding events'),
	(67, 31, 1, 'Usuwanie wszystkich wydarzeń'),
	(68, 31, 2, 'Remove all events'),
	(69, 32, 1, 'Edycja wszystkich wydarzeń'),
	(70, 32, 2, 'Edit all events'),
	(71, 33, 1, 'Usuwanie tylko własnych wydarzeń'),
	(72, 33, 2, 'Remove only own events'),
	(73, 34, 1, 'Edytowanie tylko własnych wydarzeń'),
	(74, 34, 2, 'Edit only own events'),
	(75, 35, 1, 'Tworzenie aktualności'),
	(76, 35, 2, 'Creating news'),
	(77, 36, 1, 'Edycja wszystkich aktualności'),
	(78, 36, 2, 'Edit all news'),
	(79, 37, 1, 'Edytowanie tylko własnych aktualności'),
	(80, 37, 2, 'Edit only own news'),
	(81, 38, 1, 'Usuwanie wszystkich aktualności'),
	(82, 38, 2, 'Remove all news'),
	(83, 39, 1, 'Usuwanie tylko własnych aktualności'),
	(84, 39, 2, 'Remove only own news'),
	(85, 40, 1, 'Tworzenie folderów'),
	(86, 40, 2, 'Creating directory'),
	(87, 41, 1, 'Tworzenie dokumentów'),
	(88, 41, 2, 'Creating documents'),
	(89, 42, 1, 'Edycja folderów'),
	(90, 42, 2, 'Edit directory'),
	(91, 43, 1, 'Edycja tylko własnych folderów'),
	(92, 43, 2, 'Edit only own directory'),
	(93, 44, 1, 'Edycja dokumentów'),
	(94, 44, 2, 'Edit documents'),
	(95, 45, 1, 'Edycja tylko własnych dokumentów'),
	(96, 45, 2, 'Edit only own documents'),
	(97, 46, 1, 'Usuwanie folderów'),
	(98, 46, 2, 'Remove directory'),
	(99, 47, 1, 'Usuwanie tylko własnych folderów'),
	(100, 47, 2, 'Remove only own directory'),
	(101, 48, 1, 'Usuwanie dokumentów'),
	(102, 48, 2, 'Remove documents'),
	(103, 49, 1, 'Usuwanie tylko własnych dokumentów'),
	(104, 49, 2, 'Remove only own documents'),
	(105, 50, 1, 'Tworzenie nowych wiadomości'),
	(106, 50, 2, 'Creating new messages'),
	(107, 51, 1, 'Usuwanie wiadomości'),
	(108, 51, 2, 'Remove messages'),
	(109, 52, 1, 'Wysyłanie odpowiedzi na wiadomości'),
	(110, 52, 2, 'Sending reply'),
	(111, 53, 1, 'Tworzenie nowych wiadomości'),
	(112, 53, 2, 'Creating new messages'),
	(113, 54, 1, 'Dodawanie definicji'),
	(114, 54, 2, 'Add definition'),
	(115, 55, 1, 'Edytowanie definicji'),
	(116, 55, 2, 'Edit definition'),
	(117, 56, 1, 'Usuwanie definicji'),
	(118, 56, 2, 'Remove definition'),
	(119, 57, 1, 'Dodawanie użytkowników'),
	(120, 57, 2, 'Add users'),
	(121, 58, 1, 'Edycja użytkowników'),
	(122, 58, 2, 'Edit users'),
	(123, 59, 1, 'Usuwanie użytkowników'),
	(124, 59, 2, 'Remove users'),
	(125, 60, 1, 'Dodawanie grup'),
	(126, 60, 2, 'Add groups'),
	(127, 61, 1, 'Edycja grup'),
	(128, 61, 2, 'Edit groups'),
	(129, 62, 1, 'Usuwanie grup'),
	(130, 62, 2, 'Edit groups'),
	(131, 63, 1, 'Dodawanie uprawnień'),
	(132, 63, 2, 'Creating permissions'),
	(133, 64, 1, 'Edycja uprawnień'),
	(134, 64, 2, 'Edit permissions'),
	(135, 65, 1, 'Usuwanie uprawnień'),
	(136, 65, 2, 'Remove permissions'),
	(137, 66, 1, 'Menadżer plików'),
	(138, 66, 2, 'File manager');
/*!40000 ALTER TABLE `permissions_translation` ENABLE KEYS */;


-- Dumping structure for table pi.private_messages
CREATE TABLE IF NOT EXISTS `private_messages` (
  `pmid` int(10) NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) DEFAULT NULL,
  `sender` int(10) DEFAULT NULL,
  `recipient` int(10) DEFAULT NULL,
  `attachments` text,
  `subject` varchar(255) DEFAULT NULL,
  `message` text,
  `sent_at` datetime DEFAULT NULL,
  `recipient_received_at` datetime DEFAULT NULL,
  `sender_received_at` datetime DEFAULT NULL,
  `deleted_by_sender` int(1) DEFAULT '0',
  `deleted_by_recipient` int(1) DEFAULT '0',
  PRIMARY KEY (`pmid`),
  KEY `FK_private_messages_users` (`sender`),
  KEY `FK_private_messages_users_2` (`recipient`),
  KEY `FK_private_messages_private_messages` (`parent_id`),
  CONSTRAINT `FK_private_messages_private_messages` FOREIGN KEY (`parent_id`) REFERENCES `private_messages` (`pmid`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- Dumping data for table pi.private_messages: ~3 rows (approximately)
/*!40000 ALTER TABLE `private_messages` DISABLE KEYS */;
INSERT INTO `private_messages` (`pmid`, `parent_id`, `sender`, `recipient`, `attachments`, `subject`, `message`, `sent_at`, `recipient_received_at`, `sender_received_at`, `deleted_by_sender`, `deleted_by_recipient`) VALUES
	(1, NULL, 1, 3, '[{"name":"\\u0105\\u015b\\u0107\\u0142\\u0119\\u00f3\\u0105\\u015b\\u0107\\u017c.txt","url":"http:\\/\\/deskcms.lh\\/documents\\/connector?cmd=file&target=l1_xIXFm8SHxYLEmcOzxIXFm8SHxbwudHh0"}]', 'Temat na jutro!!', '<p>Ważny temat na jutro, musi być gotowy do 15!</p>', '2012-12-23 13:54:04', '2012-12-23 14:22:17', '2012-12-23 13:54:04', 1, 0),
	(6, 1, 3, 1, NULL, 'Temat na jutro!!', '<p>test</p>', '2012-12-23 14:22:14', '2012-12-23 14:22:31', '2012-12-23 14:22:14', 0, 0),
	(7, NULL, 1, 1, '[{"name":"Snapshot_20120605_11.JPG","url":"http:\\/\\/deskcms.lh\\/documents\\/connector?cmd=file&target=l1_U25hcHNob3RfMjAxMjA2MDVfMTEuSlBH"}]', 'Brak tematu', '<p>fsdfsdf</p>', '2013-01-07 19:32:19', NULL, '2013-01-07 19:32:19', 0, 1);
/*!40000 ALTER TABLE `private_messages` ENABLE KEYS */;


-- Dumping structure for table pi.settings
CREATE TABLE IF NOT EXISTS `settings` (
  `sid` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`sid`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

-- Dumping data for table pi.settings: ~7 rows (approximately)
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` (`sid`, `name`, `value`) VALUES
	(1, 'general_language_method', 'auto'),
	(2, 'general_site_theme', 'Public'),
	(3, 'general_admin_theme', 'Admin'),
	(17, 'general_language_method', 'auto'),
	(18, 'general_site_theme', 'Public'),
	(19, 'general_admin_theme', 'Admin'),
	(20, 'test', 'aaa');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;


-- Dumping structure for table pi.users
CREATE TABLE IF NOT EXISTS `users` (
  `uid` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `salt` varchar(128) DEFAULT NULL,
  `password` varchar(128) DEFAULT NULL,
  `is_active` int(1) DEFAULT '0',
  `is_deleted` int(1) DEFAULT '0',
  `last_active_at` timestamp NULL DEFAULT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Dumping data for table pi.users: ~2 rows (approximately)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`uid`, `username`, `salt`, `password`, `is_active`, `is_deleted`, `last_active_at`, `last_login`, `created_at`) VALUES
	(1, 'luken.h@gmail.com', '60cdf31320cbbb7adce9c58bb2f7c594', '01e0b60397793dd2f6787f25ad990638', 1, 0, '2013-02-15 21:02:02', '2013-02-15 19:44:19', '2013-01-07 19:23:35'),
	(3, 'test@test.pl', '605ee6d50ca875711dc24aefb0a8068c', '9789ba38a5b4689763f55448b3d9d99f', 1, 0, '2013-03-26 10:14:14', '2013-03-26 10:04:07', '2013-02-04 16:43:34');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;


-- Dumping structure for table pi.users_data
CREATE TABLE IF NOT EXISTS `users_data` (
  `udid` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) DEFAULT NULL,
  `firstname` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `mobile` varchar(255) DEFAULT NULL,
  `photo` text,
  PRIMARY KEY (`udid`),
  KEY `FK_users_data_users` (`uid`),
  CONSTRAINT `FK_users_data_users` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Dumping data for table pi.users_data: ~2 rows (approximately)
/*!40000 ALTER TABLE `users_data` DISABLE KEYS */;
INSERT INTO `users_data` (`udid`, `uid`, `firstname`, `lastname`, `mobile`, `photo`) VALUES
	(1, 1, 'Łukasz', 'Hibner', 'test', '[{"name":"Snapshot_20120605_2.jpeg","url":"http:\\/\\/deskcms.lh\\/documents\\/connector?cmd=file&target=l1_U25hcHNob3RfMjAxMjA2MDVfMi5qcGVn"}]'),
	(3, 3, 'test', 'test', 'test', NULL);
/*!40000 ALTER TABLE `users_data` ENABLE KEYS */;


-- Dumping structure for table pi.users_groups
CREATE TABLE IF NOT EXISTS `users_groups` (
  `ugid` int(10) NOT NULL AUTO_INCREMENT,
  `gid` int(10) DEFAULT NULL,
  `uid` int(10) DEFAULT NULL,
  PRIMARY KEY (`ugid`),
  KEY `FK_users_groups_groups_permissions` (`gid`),
  KEY `FK_users_groups_users` (`uid`),
  CONSTRAINT `FK_users_groups_groups` FOREIGN KEY (`gid`) REFERENCES `groups` (`gid`) ON DELETE CASCADE,
  CONSTRAINT `FK_users_groups_users` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;

-- Dumping data for table pi.users_groups: ~2 rows (approximately)
/*!40000 ALTER TABLE `users_groups` DISABLE KEYS */;
INSERT INTO `users_groups` (`ugid`, `gid`, `uid`) VALUES
	(23, 1, 1),
	(29, 1, 3);
/*!40000 ALTER TABLE `users_groups` ENABLE KEYS */;


-- Dumping structure for table pi.users_permissions
CREATE TABLE IF NOT EXISTS `users_permissions` (
  `upid` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) DEFAULT NULL,
  `pid` int(10) DEFAULT NULL,
  `is_allowed` int(1) DEFAULT '0',
  PRIMARY KEY (`upid`),
  KEY `FK_users_permissions_users` (`uid`),
  KEY `FK_users_permissions_permissions` (`pid`),
  CONSTRAINT `FK_users_permissions_permissions` FOREIGN KEY (`pid`) REFERENCES `permissions` (`pid`) ON DELETE CASCADE,
  CONSTRAINT `FK_users_permissions_users` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8;

-- Dumping data for table pi.users_permissions: ~18 rows (approximately)
/*!40000 ALTER TABLE `users_permissions` DISABLE KEYS */;
INSERT INTO `users_permissions` (`upid`, `uid`, `pid`, `is_allowed`) VALUES
	(48, 3, 1, 1),
	(49, 3, 4, 0),
	(50, 3, 5, 0),
	(51, 3, 6, 0),
	(52, 3, 7, 0),
	(53, 3, 8, 0),
	(54, 3, 9, 0),
	(55, 3, 53, 0),
	(56, 3, 52, 0),
	(57, 3, 42, 0),
	(58, 3, 44, 0),
	(59, 3, 46, 0),
	(60, 3, 48, 0),
	(61, 3, 36, 0),
	(62, 3, 38, 0),
	(63, 3, 31, 0),
	(64, 3, 32, 0),
	(65, 3, 33, 0);
/*!40000 ALTER TABLE `users_permissions` ENABLE KEYS */;
/*!40014 SET FOREIGN_KEY_CHECKS=1 */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
