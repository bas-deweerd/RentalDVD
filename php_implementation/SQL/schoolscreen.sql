-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Machine: 127.0.0.1
-- Gegenereerd op: 11 dec 2014 om 14:02
-- Serverversie: 5.6.21
-- PHP-versie: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Databank: `schoolscreen`
--
CREATE DATABASE IF NOT EXISTS `schoolscreen` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `schoolscreen`;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `lease`
--

DROP TABLE IF EXISTS `lease`;
CREATE TABLE IF NOT EXISTS `lease` (
  `Id` int(11) NOT NULL DEFAULT '0',
  `PCN` int(11) NOT NULL DEFAULT '0',
  `ProductId` int(11) NOT NULL DEFAULT '0',
  `End_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `product`
--

DROP TABLE IF EXISTS `product`;
CREATE TABLE IF NOT EXISTS `product` (
  `Id` int(11) NOT NULL,
  `Photo` varchar(255) NOT NULL,
  `Rent_price` int(11) NOT NULL,
  `Year` int(11) NOT NULL,
  `Title` varchar(255) NOT NULL,
  `Director` varchar(100) NOT NULL,
  `Genre` varchar(255) NOT NULL,
  `Duration` varchar(100) NOT NULL,
  `Description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `reservation`
--

DROP TABLE IF EXISTS `reservation`;
CREATE TABLE IF NOT EXISTS `reservation` (
  `PCN` int(11) NOT NULL DEFAULT '0',
  `ProductId` int(11) NOT NULL DEFAULT '0',
  `Note` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `PCN` int(11) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `User_type` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `lease`
--
ALTER TABLE `lease`
 ADD PRIMARY KEY (`Id`,`PCN`,`ProductId`), ADD KEY `PCN` (`PCN`), ADD KEY `ProductId` (`ProductId`);

--
-- Indexen voor tabel `product`
--
ALTER TABLE `product`
 ADD PRIMARY KEY (`Id`);

--
-- Indexen voor tabel `reservation`
--
ALTER TABLE `reservation`
 ADD PRIMARY KEY (`PCN`,`ProductId`), ADD KEY `ProductId` (`ProductId`);

--
-- Indexen voor tabel `user`
--
ALTER TABLE `user`
 ADD PRIMARY KEY (`PCN`);

--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `lease`
--
ALTER TABLE `lease`
ADD CONSTRAINT `lease_ibfk_1` FOREIGN KEY (`PCN`) REFERENCES `user` (`PCN`),
ADD CONSTRAINT `lease_ibfk_2` FOREIGN KEY (`ProductId`) REFERENCES `product` (`Id`);

--
-- Beperkingen voor tabel `reservation`
--
ALTER TABLE `reservation`
ADD CONSTRAINT `reservation_ibfk_1` FOREIGN KEY (`PCN`) REFERENCES `user` (`PCN`),
ADD CONSTRAINT `reservation_ibfk_2` FOREIGN KEY (`ProductId`) REFERENCES `product` (`Id`);
