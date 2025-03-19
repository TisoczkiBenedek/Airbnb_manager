-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2025. Már 19. 17:40
-- Kiszolgáló verziója: 10.4.32-MariaDB
-- PHP verzió: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Adatbázis: `vizsgaremek_takaritas`
--

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `eszkoz`
--

CREATE TABLE `eszkoz` (
  `id` int(11) NOT NULL,
  `nev` varchar(50) NOT NULL,
  `kiszereles` varchar(20) NOT NULL,
  `keszletenDB` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

--
-- A tábla adatainak kiíratása `eszkoz`
--

INSERT INTO `eszkoz` (`id`, `nev`, `kiszereles`, `keszletenDB`) VALUES
(1, 'Seprű', '', 10),
(2, 'Domestos', '500 ml', 10);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `eszkozszukseglet`
--

CREATE TABLE `eszkozszukseglet` (
  `id` int(11) NOT NULL,
  `eszkozId` int(11) NOT NULL,
  `felhasznalo_id` int(11) NOT NULL,
  `teljesitve` tinyint(4) NOT NULL,
  `igenyeltDarab` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

--
-- A tábla adatainak kiíratása `eszkozszukseglet`
--

INSERT INTO `eszkozszukseglet` (`id`, `eszkozId`, `felhasznalo_id`, `teljesitve`, `igenyeltDarab`) VALUES
(1, 1, 3, 0, 5),
(2, 2, 3, 0, 10),
(3, 1, 3, 0, 5),
(4, 1, 3, 0, 0),
(5, 1, 3, 0, 0),
(6, 1, 3, 0, 0),
(7, 2, 3, 0, 3),
(8, 1, 3, 0, 5),
(9, 1, 3, 0, 5);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `felhasznalo`
--

CREATE TABLE `felhasznalo` (
  `id` int(11) NOT NULL,
  `emailcim` varchar(65) NOT NULL,
  `jelszo` varchar(255) NOT NULL,
  `Vezeteknev` varchar(50) NOT NULL,
  `Keresztnev` varchar(50) NOT NULL,
  `elerhetoseg` varchar(12) NOT NULL,
  `megyeid` int(11) NOT NULL,
  `tulajdonos` tinyint(4) NOT NULL,
  `takarito` tinyint(4) NOT NULL,
  `admin` tinyint(4) NOT NULL,
  `TakaritoSzabadsagKezd` date NOT NULL,
  `TakaritoSzabadsagVeg` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

--
-- A tábla adatainak kiíratása `felhasznalo`
--

INSERT INTO `felhasznalo` (`id`, `emailcim`, `jelszo`, `Vezeteknev`, `Keresztnev`, `elerhetoseg`, `megyeid`, `tulajdonos`, `takarito`, `admin`, `TakaritoSzabadsagKezd`, `TakaritoSzabadsagVeg`) VALUES
(1, 'csabaTeszt1@gmail.com', '$2y$10$f9yttAm/zLYDme6X2b4N/u0MGHxUC9zvZ9GwKaAEvXVHK5OYAubne', 'Kis', 'Pista', '+36201112222', 5, 0, 1, 0, '0000-00-00', '0000-00-00'),
(2, 'csabaTeszt2@gmail.com', '$2y$10$r7iRjGrHEwja6OzH7bXVxONW0xabLPxTDXnUPgk9W3wyJmGygJDCy', 'Csaba', 'Csaba', '+36201112223', 19, 1, 0, 0, '0000-00-00', '0000-00-00'),
(3, 'morzsi@gmail.com', '$2y$10$dLMAJlgmgHMa.FJECri.F.LgN8L7QRL4/yH8uRzibWbqA4c2GWaZ2', 'Morzsi', 'János', '36501234567', 12, 1, 0, 0, '0000-00-00', '0000-00-00'),
(4, 'valaki@gmail.com', '$2y$10$fswEde/kAkhDqQrjnS4hW.8UftRAduN6Oz9mk3cU7zXQTEf/tIBtm', 'Valaki', 'János', '06301234567', 16, 1, 0, 0, '0000-00-00', '0000-00-00'),
(5, 'valaki1@gmail.com', '$2y$10$1nd16d8aNNIEsOtha1FT.u/aAmXfHfTw3WjixxCVLkXXgSlh4k5Aq', 'János', 'István', '06301234567', 19, 1, 0, 0, '0000-00-00', '0000-00-00'),
(6, 'nemtudom@gmail.com', '$2y$10$tVGQtzZ5ElLycWipefT1setRyhwUxbq8FgK6l0L76amxdLUZLLc5.', 'Árpád', 'János', '06301234567', 19, 1, 0, 0, '0000-00-00', '0000-00-00'),
(7, 'valakivagyok@gmail.com', '$2y$10$VU5dc9II0wr4aNV.zEV/9uDo7C2fHPPT4VQWVZwHGKTaUIkW6e.se', 'János', 'Pista', '06301234567', 19, 1, 0, 0, '0000-00-00', '0000-00-00'),
(8, 'tak1@gmail.com', '$2y$10$lx2dbFfHVJBk.26oHtt2g.dMlzosjvNSWCLqUEv7GM5u0Iv2yvSou', 'Margit', 'Gábor', '06402345678', 19, 0, 1, 0, '2025-03-19', '2025-03-20');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `foglaltsag`
--

CREATE TABLE `foglaltsag` (
  `id` int(11) NOT NULL,
  `lakas_id` int(11) NOT NULL,
  `vendegErkezes` date NOT NULL,
  `vendegTavozas` date NOT NULL,
  `vendegSzam` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `lakas`
--

CREATE TABLE `lakas` (
  `id` int(11) NOT NULL,
  `nev` varchar(50) NOT NULL,
  `cim` varchar(70) NOT NULL,
  `terulet` int(11) NOT NULL,
  `medence` tinyint(4) NOT NULL,
  `szauna` tinyint(4) NOT NULL,
  `felhasznalo_id` int(11) NOT NULL,
  `megye_id` int(11) NOT NULL,
  `belepesi_adatok` varchar(100) NOT NULL,
  `kepek` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

--
-- A tábla adatainak kiíratása `lakas`
--

INSERT INTO `lakas` (`id`, `nev`, `cim`, `terulet`, `medence`, `szauna`, `felhasznalo_id`, `megye_id`, `belepesi_adatok`, `kepek`) VALUES
(11, 'asf', 'afsd3', 23131, 1, 0, 2, 18, '4141', 'uploads/lakas_11/kepek/1738617955_remnant.jpg'),
(12, 'asf', 'afsd3', 3424, 0, 0, 2, 3, '324123', 'uploads/lakas_12/kepek/1738618119_remnant.jpg'),
(13, 'Veszprémi csoda', 'Vársomajos utca 12', 50, 0, 0, 3, 19, 'Valami', '../php/uploads/lakas_13/kepek/1740671847_gt4rs.jpg'),
(14, 'Veszprémi csoda', 'Vársomajos utca 12', 50, 0, 0, 3, 19, 'Valami', '../php/uploads/lakas_14/kepek/1740671854_gt4rs.jpg'),
(15, 'Veszprémi csoda', 'Vársomajos utca 12', 50, 0, 0, 3, 19, 'Valami', '../php/uploads/lakas_15/kepek/1740671861_gt4rs.jpg'),
(16, 'Veszprémi csoda', 'Vársomajos utca 12', 50, 0, 0, 3, 19, 'Valami', '../php/uploads/lakas_16/kepek/1740671895_gt4rs.jpg'),
(17, 'Veszprémi csoda', 'Vársomajos utca 12', 50, 0, 0, 3, 19, 'Valami', '../php/uploads/lakas_17/kepek/1740671924_gt4rs.jpg'),
(18, 'Veszprém nagy', 'Vársomajos utca 19', 100, 0, 0, 3, 19, 'Valami', '../php/uploads/lakas_18/kepek/1740672246_image_modositott.png'),
(19, 'Veszprém nagymegy', 'Vársomajos utca 19', 100, 0, 0, 3, 6, 'hjhjdjkhdas', '../php/uploads/lakas_19/kepek/1740672659_737max_elsa_beskow_1920-960.jpg'),
(20, 'Veszprémi csoda', 'Vársomajos utca 19', 50, 0, 0, 4, 19, 'Valami szép lakás', '../php/uploads/lakas_20/kepek/1740940458_gt4rs.jpg'),
(21, 'Veszprémi csoda', 'Vársomajos utca 19', 50, 0, 0, 4, 19, 'Valami szép lakás', '../php/uploads/lakas_21/kepek/1740940466_gt4rs.jpg'),
(23, 'Balatonfüred nagy', 'Avar utca 22', 400, 1, 0, 5, 19, 'Kulcsbox', '../php/uploads/lakas_23/kepek/1740941219_image.png'),
(24, 'Veszprémi nagy', 'Vársomajos utca 20', 60, 0, 0, 7, 19, 'Nincs', '../php/uploads/lakas_24/kepek/1742231168_Tejut.jpg');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `megye`
--

CREATE TABLE `megye` (
  `id` int(11) NOT NULL,
  `megyeNev` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

--
-- A tábla adatainak kiíratása `megye`
--

INSERT INTO `megye` (`id`, `megyeNev`) VALUES
(1, 'Budapest'),
(2, 'Pest vármegye'),
(3, 'Bács-Kiskun vármegye'),
(4, 'Baranya vármegye'),
(5, 'Békés vármegye'),
(6, 'Borsod-Abaúj-Zemplén vármegye'),
(7, 'Csongrád-Csanád vármegye'),
(8, 'Fejér vármegye'),
(9, 'Győr-Moson-Sopron vármegye'),
(10, 'Hajdú-Bihar vármegye'),
(11, 'Heves vármegye'),
(12, 'Jász-Nagykun-Szolnok vármegye'),
(13, 'Komárom-Esztergom vármegye'),
(14, 'Nógrád vármegye'),
(15, 'Somogy vármegye'),
(16, 'Szabolcs-Szatmár-Bereg vármegye'),
(17, 'Tolna vármegye'),
(18, 'Vas vármegye'),
(19, 'Veszprém vármegye'),
(20, 'Zala vármegye');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `naptarak`
--

CREATE TABLE `naptarak` (
  `id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `lakas_id` int(11) NOT NULL,
  `felhasznalo_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `file_content` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- A tábla adatainak kiíratása `naptarak`
--

INSERT INTO `naptarak` (`id`, `file_name`, `lakas_id`, `felhasznalo_id`, `created_at`, `file_content`) VALUES
(0, '1740941219_event.ics', 23, 5, '2025-03-02 18:46:59', 'BEGIN:VCALENDAR\r\nVERSION:2.0\r\nPRODID:-//ChatGPT//EN\r\nBEGIN:VEVENT\r\nUID:evt1@example.com\r\nDTSTAMP:20250227T120000Z\r\nDTSTART;VALUE=DATE:20250228\r\nDTEND;VALUE=DATE:20250302\r\nSUMMARY:Februári kétnapos esemény\r\nDESCRIPTION:Egy izgalmas februári esemény, amely két napig tart és tele van programokkal.\r\nEND:VEVENT\r\nBEGIN:VEVENT\r\nUID:evt2@example.com\r\nDTSTAMP:20250227T120000Z\r\nDTSTART;VALUE=DATE:20250303\r\nDTEND;VALUE=DATE:20250305\r\nSUMMARY:Márciusi kétnapos esemény\r\nDESCRIPTION:Egy fantasztikus márciusi esemény, amely két napig tart és számos érdekességet kínál.\r\nEND:VEVENT\r\nEND:VCALENDAR'),
(0, '1742231168_esemenyek.ics', 24, 7, '2025-03-17 17:06:08', 'BEGIN:VCALENDAR\nVERSION:2.0\nPRODID:-//My Calendar//EN\nBEGIN:VEVENT\nSUMMARY:Márciusi Meeting\nDTSTART:20250325T100000Z\nDTEND:20250325T110000Z\nDTSTAMP:20250317T170454Z\nEND:VEVENT\nBEGIN:VEVENT\nSUMMARY:Projekt Bemutató\nDTSTART:20250328T143000Z\nDTEND:20250328T153000Z\nDTSTAMP:20250317T170454Z\nEND:VEVENT\nBEGIN:VEVENT\nSUMMARY:Áprilisi Konferencia\nDTSTART:20250405T090000Z\nDTEND:20250405T100000Z\nDTSTAMP:20250317T170454Z\nEND:VEVENT\nBEGIN:VEVENT\nSUMMARY:Ügyféltalálkozó\nDTSTART:20250415T164500Z\nDTEND:20250415T174500Z\nDTSTAMP:20250317T170454Z\nEND:VEVENT\nEND:VCALENDAR');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `takaritas`
--

CREATE TABLE `takaritas` (
  `id` int(11) NOT NULL,
  `lakasId` int(11) NOT NULL,
  `felhasznalo_id` int(11) NOT NULL,
  `takaritoErkezes` datetime NOT NULL,
  `takaritoTavozas` datetime NOT NULL,
  `befejezve` tinyint(4) NOT NULL,
  `megjegyzes` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

--
-- A tábla adatainak kiíratása `takaritas`
--

INSERT INTO `takaritas` (`id`, `lakasId`, `felhasznalo_id`, `takaritoErkezes`, `takaritoTavozas`, `befejezve`, `megjegyzes`) VALUES
(1, 24, 8, '2025-03-26 15:00:00', '2025-03-26 17:09:00', 0, '');

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `eszkoz`
--
ALTER TABLE `eszkoz`
  ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `eszkozszukseglet`
--
ALTER TABLE `eszkozszukseglet`
  ADD PRIMARY KEY (`id`),
  ADD KEY `eszkozId` (`eszkozId`) USING BTREE;

--
-- A tábla indexei `felhasznalo`
--
ALTER TABLE `felhasznalo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `megye_ID` (`megyeid`);

--
-- A tábla indexei `foglaltsag`
--
ALTER TABLE `foglaltsag`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lakas_id` (`lakas_id`);

--
-- A tábla indexei `lakas`
--
ALTER TABLE `lakas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `felhasznalo_id` (`felhasznalo_id`) USING BTREE,
  ADD KEY `megyeid` (`megye_id`);

--
-- A tábla indexei `megye`
--
ALTER TABLE `megye`
  ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `takaritas`
--
ALTER TABLE `takaritas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `takaritoid` (`felhasznalo_id`),
  ADD KEY `lakasid` (`lakasId`);

--
-- A kiírt táblák AUTO_INCREMENT értéke
--

--
-- AUTO_INCREMENT a táblához `eszkoz`
--
ALTER TABLE `eszkoz`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT a táblához `eszkozszukseglet`
--
ALTER TABLE `eszkozszukseglet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT a táblához `felhasznalo`
--
ALTER TABLE `felhasznalo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT a táblához `foglaltsag`
--
ALTER TABLE `foglaltsag`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `lakas`
--
ALTER TABLE `lakas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT a táblához `megye`
--
ALTER TABLE `megye`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT a táblához `takaritas`
--
ALTER TABLE `takaritas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Megkötések a kiírt táblákhoz
--

--
-- Megkötések a táblához `eszkozszukseglet`
--
ALTER TABLE `eszkozszukseglet`
  ADD CONSTRAINT `eszkozid` FOREIGN KEY (`eszkozId`) REFERENCES `eszkoz` (`id`);

--
-- Megkötések a táblához `felhasznalo`
--
ALTER TABLE `felhasznalo`
  ADD CONSTRAINT `megye_ID` FOREIGN KEY (`megyeid`) REFERENCES `megye` (`id`);

--
-- Megkötések a táblához `foglaltsag`
--
ALTER TABLE `foglaltsag`
  ADD CONSTRAINT `lakas_id` FOREIGN KEY (`lakas_id`) REFERENCES `lakas` (`id`);

--
-- Megkötések a táblához `lakas`
--
ALTER TABLE `lakas`
  ADD CONSTRAINT `felhasznaloid` FOREIGN KEY (`felhasznalo_id`) REFERENCES `felhasznalo` (`id`),
  ADD CONSTRAINT `megyeid` FOREIGN KEY (`megye_id`) REFERENCES `megye` (`id`);

--
-- Megkötések a táblához `takaritas`
--
ALTER TABLE `takaritas`
  ADD CONSTRAINT `lakasid` FOREIGN KEY (`lakasId`) REFERENCES `lakas` (`id`),
  ADD CONSTRAINT `takaritoid` FOREIGN KEY (`felhasznalo_id`) REFERENCES `felhasznalo` (`id`);

DELIMITER $$
--
-- Események
--
CREATE DEFINER=`root`@`localhost` EVENT `TakaritoVisszallait` ON SCHEDULE EVERY 1 DAY STARTS '2025-03-19 17:30:18' ON COMPLETION NOT PRESERVE ENABLE DO UPDATE `felhasznalo` SET `TakaritoSzabadsagKezd`= '0000-00-00', `TakaritoSzabadsagVeg` = '0000-00-00' WHERE `TakaritoSzabadsagVeg` = DATE(NOW())$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
