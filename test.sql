-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.5.25 - MySQL Community Server (GPL)
-- Server OS:                    Win32
-- HeidiSQL Version:             11.2.0.6213
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table test.users
CREATE TABLE IF NOT EXISTS `users` (
  `idUser` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userName` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`idUser`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table test.users: ~4 rows (approximately)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`idUser`, `userName`) VALUES
	(1, 'Андрей О'),
	(2, 'Сергей Тест'),
	(3, 'Иван Тест'),
	(4, 'Василий В');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

-- Dumping structure for table test.userscity
CREATE TABLE IF NOT EXISTS `userscity` (
  `idUser` int(10) unsigned NOT NULL,
  `city` varchar(50) NOT NULL,
  PRIMARY KEY (`idUser`,`city`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table test.userscity: ~7 rows (approximately)
/*!40000 ALTER TABLE `userscity` DISABLE KEYS */;
INSERT INTO `userscity` (`idUser`, `city`) VALUES
	(1, 'Москва'),
	(1, 'Новосибирск'),
	(1, 'Самара'),
	(2, 'Москва'),
	(2, 'Новосибирск'),
	(3, 'Москва'),
	(4, 'Москва');
/*!40000 ALTER TABLE `userscity` ENABLE KEYS */;

-- Dumping structure for table test.usersinfo
CREATE TABLE IF NOT EXISTS `usersinfo` (
  `idUser` int(10) unsigned NOT NULL,
  `education` varchar(50) NOT NULL DEFAULT '',
  `email` varchar(150) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `comments` text,
  PRIMARY KEY (`idUser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table test.usersinfo: ~4 rows (approximately)
/*!40000 ALTER TABLE `usersinfo` DISABLE KEYS */;
INSERT INTO `usersinfo` (`idUser`, `education`, `email`, `phone`, `comments`) VALUES
	(1, 'высшее', 'andrey.okhotnikov@gmail.com', '(926) 123-4567', 'тестоый пользователь 1'),
	(2, 'среднее', NULL, NULL, 'тестоый пользователь 2'),
	(3, 'бакалавр', NULL, NULL, 'тестоый пользователь 3'),
	(4, 'среднее', NULL, NULL, 'тестоый пользователь 4');
/*!40000 ALTER TABLE `usersinfo` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
