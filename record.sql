-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Окт 31 2016 г., 16:56
-- Версия сервера: 5.5.25
-- Версия PHP: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `record`
--

-- --------------------------------------------------------

--
-- Структура таблицы `reception`
--

CREATE TABLE IF NOT EXISTS `reception` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT 'ID пациента',
  `sid` int(11) NOT NULL DEFAULT '0' COMMENT 'ID специалиста',
  `schid` int(11) NOT NULL DEFAULT '0' COMMENT 'ID графика',
  `ptime` int(11) NOT NULL DEFAULT '0',
  `coment` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Дамп данных таблицы `reception`
--

INSERT INTO `reception` (`id`, `pid`, `sid`, `schid`, `ptime`, `coment`) VALUES
(1, 2, 1, 2, 1477807200, ''),
(8, 3, 1, 2, 1477814400, ''),
(10, 4, 1, 2, 1477816200, 'Ноет шея');

-- --------------------------------------------------------

--
-- Структура таблицы `schedule`
--

CREATE TABLE IF NOT EXISTS `schedule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sid` int(11) NOT NULL DEFAULT '0',
  `start_time` int(11) NOT NULL DEFAULT '0',
  `end_time` int(11) NOT NULL DEFAULT '0',
  `count_patient` int(11) NOT NULL DEFAULT '1',
  `time_patient` int(11) NOT NULL DEFAULT '300',
  `date` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `schedule`
--

INSERT INTO `schedule` (`id`, `sid`, `start_time`, `end_time`, `count_patient`, `time_patient`, `date`) VALUES
(1, 1, 1477728000, 1477742400, 15, 10, 1477688400),
(2, 1, 1477803600, 1477818000, 15, 15, 1477771200);

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `specialist` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `login` varchar(255) NOT NULL DEFAULT '',
  `fio` varchar(255) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL DEFAULT '',
  `admin` int(11) NOT NULL DEFAULT '0',
  `d_create` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `specialist`, `email`, `login`, `fio`, `password`, `admin`, `d_create`) VALUES
(1, 'Ортопед', 'admin@admin.com', 'admin', 'Васильев Влад Алексеевич', '21232f297a57a5a743894a0e4a801fc3', 1, 1477082602),
(2, '', 'user@user.ru', 'user', 'Иванов Иван Иванович', 'ee11cbb19052e40b07aac0ca060c23ee', 0, 1477086145),
(3, '', 'vasay@mail.ru', 'vasay', 'Колесников Василий Витальевич', 'a384b6463fc216a5f8ecb6670f86456a', 0, 1477257499),
(4, '', 'Tolik@mail.ru', 'Tolik', 'Антипов Анатолий Антонович', 'a384b6463fc216a5f8ecb6670f86456a', 0, 1477917074);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
