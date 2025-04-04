-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2025. Ápr 04. 13:36
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
(9, 1, 3, 0, 5),
(10, 1, 0, 0, 5),
(11, 2, 10, 0, 4);

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
(1, 'csabaTeszt1@gmail.com', '$2y$10$f9yttAm/zLYDme6X2b4N/u0MGHxUC9zvZ9GwKaAEvXVHK5OYAubne', 'Kis', 'János', '+36201112222', 5, 0, 1, 0, '0000-00-00', '0000-00-00'),
(2, 'csabaTeszt2@gmail.com', '$2y$10$r7iRjGrHEwja6OzH7bXVxONW0xabLPxTDXnUPgk9W3wyJmGygJDCy', 'Csaba', 'Csaba', '+36201112223', 19, 1, 0, 0, '0000-00-00', '0000-00-00'),
(4, 'valaki@gmail.com', '$2y$10$fswEde/kAkhDqQrjnS4hW.8UftRAduN6Oz9mk3cU7zXQTEf/tIBtm', 'Valaki', 'János', '06301234567', 16, 1, 0, 0, '0000-00-00', '0000-00-00'),
(5, 'valaki1@gmail.com', '$2y$10$1nd16d8aNNIEsOtha1FT.u/aAmXfHfTw3WjixxCVLkXXgSlh4k5Aq', 'János', 'István', '06301234567', 19, 1, 0, 0, '2025-03-20', '2025-03-21'),
(6, 'nemtudom@gmail.com', '$2y$10$tVGQtzZ5ElLycWipefT1setRyhwUxbq8FgK6l0L76amxdLUZLLc5.', 'Árpád', 'János', '06301234567', 19, 1, 0, 0, '0000-00-00', '0000-00-00'),
(7, 'valakivagyok@gmail.com', '$2y$10$VU5dc9II0wr4aNV.zEV/9uDo7C2fHPPT4VQWVZwHGKTaUIkW6e.se', 'János', 'Pista', '06301234567', 19, 1, 0, 0, '0000-00-00', '0000-00-00'),
(8, 'tak1@gmail.com', '$2y$10$lx2dbFfHVJBk.26oHtt2g.dMlzosjvNSWCLqUEv7GM5u0Iv2yvSou', 'Margit', 'Gábor', '06402345678', 19, 0, 1, 0, '2025-03-19', '2025-03-20'),
(9, 'senki@gmail.com', '$2y$10$uzAaCdAXzgvyFWfSL2TkDuTc9OdMAHr1nctU2HgpfOTpv99Eqv/u2', 'Nagy', 'József', '06301234567', 19, 1, 0, 0, '0000-00-00', '0000-00-00'),
(10, 'envagyok1@gmail.com', '$2y$10$GRrOLmGbb0uZUXl7LY42AedHV21IA9kJRi10CO2Lqu0.nNz4mpnZ2', 'János', 'Kereszt', '06301234567', 20, 0, 0, 1, '2025-03-30', '2025-03-31'),
(12, 'tesztemail@gmail.com', '$2y$10$Nvy7ALxrUpFND2OBCkJMseUlA98v5rK2b8nM2uXVhcNpVmg33DYfi', 'Gipsz', 'János', '06301234567', 19, 1, 0, 0, '0000-00-00', '0000-00-00'),
(13, 'remekvizsga@gmail.com', '$2y$10$Bwnjt3.5pdm2ABZthxfyjOoxgsytScxzRg1hqvTHiu3THLUE.nqMK', 'Nagy', 'Gizike', '06301234567', 19, 0, 1, 0, '0000-00-00', '0000-00-00');

--
-- Eseményindítók `felhasznalo`
--
DELIMITER $$
CREATE TRIGGER `lakastorles` BEFORE DELETE ON `felhasznalo` FOR EACH ROW DELETE FROM lakas WHERE lakas.felhasznalo_id = old.ID
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `log_modosit_felhasznalo` AFTER UPDATE ON `felhasznalo` FOR EACH ROW INSERT INTO log(felhasznalo_id, tabla_nev, muvelet , datum) VALUES (old.id, "felhasznalo", "UPDATE", now())
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `log_torles_felhasznalo` BEFORE DELETE ON `felhasznalo` FOR EACH ROW INSERT INTO log(felhasznalo_id, tabla_nev, muvelet , datum) VALUES (old.id, "felhasznalo", "DELETE", now())
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `log_uj_felhasznalo` AFTER INSERT ON `felhasznalo` FOR EACH ROW INSERT INTO log(felhasznalo_id, tabla_nev, muvelet , datum) VALUES (new.id, "felhasznalo", "INSERT", now())
$$
DELIMITER ;

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
(20, 'Veszprémi csoda', 'Vársomajos utca 19', 50, 0, 0, 4, 19, 'Valami szép lakás', '../php/uploads/lakas_20/kepek/1740940458_gt4rs.jpg'),
(21, 'Veszprémi csoda', 'Vársomajos utca 19', 50, 0, 0, 4, 19, 'Valami szép lakás', '../php/uploads/lakas_21/kepek/1740940466_gt4rs.jpg'),
(23, 'Balatonfüred nagy', 'Avar utca 22', 400, 1, 0, 5, 19, 'Kulcsbox', '../php/uploads/lakas_23/kepek/1740941219_image.png'),
(24, 'Veszprémi nagy', 'Vársomajos utca 20', 60, 0, 0, 7, 19, 'Nincs', '../php/uploads/lakas_24/kepek/1742231168_Tejut.jpg'),
(25, 'Veszprém2', 'Nagy utca 12', 35, 0, 0, 9, 19, 'Valami szöveg', '../php/uploads/lakas_25/kepek/1742481755_kep.jpg'),
(30, 'Semmi', 'jhsfllkjjkle', 30, 0, 0, 5, 17, 'ssaf', 'képhelye'),
(39, 'Lakas', '8200 Veszprém, Fáskert utca 6/B', 120, 0, 1, 2, 17, 'sdfaf', '../php/uploads/lakas_39/kepek/1743751423_1743620653_dark-souls-3840x2160.jpg');

--
-- Eseményindítók `lakas`
--
DELIMITER $$
CREATE TRIGGER `log_modosit_lakas` AFTER UPDATE ON `lakas` FOR EACH ROW INSERT INTO log(felhasznalo_id, tabla_nev, muvelet , datum) VALUES (new.felhasznalo_id, "lakas", "UPDATE", now())
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `log_torles_lakas` BEFORE DELETE ON `lakas` FOR EACH ROW INSERT INTO log(felhasznalo_id, tabla_nev, muvelet , datum) VALUES (old.felhasznalo_id, "lakas", "DELETE", now())
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `log_uj_lakas` AFTER INSERT ON `lakas` FOR EACH ROW INSERT INTO log(felhasznalo_id, tabla_nev, muvelet , datum) VALUES (new.felhasznalo_id, "lakas", "INSERT", now())
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `log`
--

CREATE TABLE `log` (
  `id` int(11) NOT NULL,
  `felhasznalo_id` int(11) NOT NULL,
  `tabla_nev` varchar(25) NOT NULL,
  `muvelet` varchar(20) NOT NULL,
  `datum` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

--
-- A tábla adatainak kiíratása `log`
--

INSERT INTO `log` (`id`, `felhasznalo_id`, `tabla_nev`, `muvelet`, `datum`) VALUES
(1, 9, 'lakas', '', '2025-04-01 00:00:00'),
(2, 9, 'lakas', 'UPDATE', '2025-04-01 00:00:00'),
(3, 9, 'lakas', 'INSERT', '2025-04-01 00:00:00'),
(4, 9, 'lakas', 'INSERT', '2025-04-01 00:00:00'),
(5, 9, 'lakas', 'INSERT', '2025-04-01 00:00:00'),
(6, 9, 'lakas', 'DELETE', '2025-04-01 00:00:00'),
(7, 9, 'lakas', 'UPDATE', '2025-04-01 00:00:00'),
(8, 5, 'lakas', 'UPDATE', '2025-04-01 00:00:00'),
(9, 5, 'lakas', 'UPDATE', '2025-04-01 00:00:00'),
(10, 5, 'lakas', 'UPDATE', '2025-04-01 08:46:41'),
(11, 11, 'felhasznalo', 'INSERT', '2025-04-01 08:56:28'),
(12, 11, 'felhasznalo', 'UPDATE', '2025-04-01 08:56:59'),
(13, 11, 'felhasznalo', 'DELETE', '2025-04-01 08:57:36'),
(14, 12, 'felhasznalo', 'INSERT', '2025-04-01 09:12:06'),
(15, 12, 'lakas', 'INSERT', '2025-04-01 10:37:57'),
(16, 12, 'lakas', 'UPDATE', '2025-04-01 10:37:57'),
(17, 12, 'lakas', 'UPDATE', '2025-04-01 11:14:40'),
(18, 12, 'lakas', 'UPDATE', '2025-04-01 11:40:47'),
(19, 12, 'lakas', 'UPDATE', '2025-04-01 11:41:34'),
(20, 12, 'lakas', 'DELETE', '2025-04-01 11:50:52'),
(21, 12, 'lakas', 'INSERT', '2025-04-01 11:51:44'),
(22, 12, 'lakas', 'UPDATE', '2025-04-01 11:51:44'),
(23, 12, 'lakas', 'DELETE', '2025-04-01 11:51:48'),
(24, 13, 'felhasznalo', 'INSERT', '2025-04-02 16:58:34'),
(25, 2, 'lakas', 'DELETE', '2025-04-03 12:34:29'),
(26, 2, 'lakas', 'DELETE', '2025-04-03 12:34:32'),
(27, 2, 'lakas', 'INSERT', '2025-04-03 12:35:43'),
(28, 2, 'lakas', 'UPDATE', '2025-04-03 12:35:43'),
(29, 2, 'lakas', 'UPDATE', '2025-04-03 12:35:55'),
(30, 2, 'lakas', 'UPDATE', '2025-04-03 12:36:00'),
(31, 2, 'lakas', 'UPDATE', '2025-04-03 12:36:05'),
(32, 2, 'lakas', 'UPDATE', '2025-04-03 12:36:08'),
(33, 2, 'lakas', 'UPDATE', '2025-04-03 12:36:13'),
(34, 2, 'lakas', 'UPDATE', '2025-04-03 12:36:18'),
(35, 2, 'lakas', 'UPDATE', '2025-04-03 12:36:22'),
(36, 2, 'lakas', 'UPDATE', '2025-04-03 12:36:42'),
(37, 2, 'lakas', 'UPDATE', '2025-04-03 12:36:57'),
(38, 2, 'lakas', 'DELETE', '2025-04-03 12:38:42'),
(39, 2, 'lakas', 'INSERT', '2025-04-03 12:39:33'),
(40, 2, 'lakas', 'UPDATE', '2025-04-03 12:39:33'),
(41, 2, 'lakas', 'UPDATE', '2025-04-03 13:19:22'),
(42, 2, 'lakas', 'UPDATE', '2025-04-03 13:19:27'),
(43, 2, 'lakas', 'UPDATE', '2025-04-03 13:20:09'),
(44, 2, 'lakas', 'UPDATE', '2025-04-03 13:28:37'),
(45, 2, 'lakas', 'UPDATE', '2025-04-03 13:28:41'),
(46, 2, 'lakas', 'UPDATE', '2025-04-03 13:28:45'),
(47, 2, 'lakas', 'UPDATE', '2025-04-03 13:28:50'),
(48, 2, 'lakas', 'UPDATE', '2025-04-03 13:28:54'),
(49, 2, 'lakas', 'UPDATE', '2025-04-03 13:29:00'),
(50, 2, 'lakas', 'UPDATE', '2025-04-03 13:29:06'),
(51, 2, 'lakas', 'UPDATE', '2025-04-03 13:31:24'),
(52, 2, 'lakas', 'UPDATE', '2025-04-03 13:44:35'),
(53, 2, 'lakas', 'DELETE', '2025-04-03 13:46:00'),
(54, 2, 'lakas', 'INSERT', '2025-04-03 13:48:37'),
(55, 2, 'lakas', 'UPDATE', '2025-04-03 13:48:37'),
(56, 2, 'lakas', 'UPDATE', '2025-04-03 13:48:46'),
(57, 2, 'lakas', 'DELETE', '2025-04-04 08:29:09'),
(58, 2, 'lakas', 'INSERT', '2025-04-04 08:29:41'),
(59, 2, 'lakas', 'UPDATE', '2025-04-04 08:29:41'),
(60, 2, 'lakas', 'UPDATE', '2025-04-04 08:29:46'),
(61, 2, 'lakas', 'UPDATE', '2025-04-04 08:29:52'),
(62, 2, 'lakas', 'UPDATE', '2025-04-04 08:29:56'),
(63, 2, 'lakas', 'UPDATE', '2025-04-04 08:30:00'),
(64, 2, 'lakas', 'UPDATE', '2025-04-04 08:30:04'),
(65, 2, 'lakas', 'UPDATE', '2025-04-04 08:30:17'),
(66, 2, 'lakas', 'UPDATE', '2025-04-04 08:31:56'),
(67, 2, 'lakas', 'UPDATE', '2025-04-04 08:32:04'),
(68, 2, 'lakas', 'UPDATE', '2025-04-04 08:42:22'),
(69, 2, 'lakas', 'UPDATE', '2025-04-04 08:42:27'),
(70, 2, 'lakas', 'UPDATE', '2025-04-04 08:42:30'),
(71, 2, 'lakas', 'DELETE', '2025-04-04 08:45:33'),
(72, 2, 'lakas', 'INSERT', '2025-04-04 09:23:43'),
(73, 2, 'lakas', 'UPDATE', '2025-04-04 09:23:43');

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
(1, '1740941219_event.ics', 23, 5, '2025-03-02 18:46:59', 'BEGIN:VCALENDAR\r\nVERSION:2.0\r\nPRODID:-//ChatGPT//EN\r\nBEGIN:VEVENT\r\nUID:evt1@example.com\r\nDTSTAMP:20250227T120000Z\r\nDTSTART;VALUE=DATE:20250228\r\nDTEND;VALUE=DATE:20250302\r\nSUMMARY:Februári kétnapos esemény\r\nDESCRIPTION:Egy izgalmas februári esemény, amely két napig tart és tele van programokkal.\r\nEND:VEVENT\r\nBEGIN:VEVENT\r\nUID:evt2@example.com\r\nDTSTAMP:20250227T120000Z\r\nDTSTART;VALUE=DATE:20250303\r\nDTEND;VALUE=DATE:20250305\r\nSUMMARY:Márciusi kétnapos esemény\r\nDESCRIPTION:Egy fantasztikus márciusi esemény, amely két napig tart és számos érdekességet kínál.\r\nEND:VEVENT\r\nEND:VCALENDAR'),
(2, '1742231168_esemenyek.ics', 24, 7, '2025-03-17 17:06:08', 'BEGIN:VCALENDAR\nVERSION:2.0\nPRODID:-//My Calendar//EN\nBEGIN:VEVENT\nSUMMARY:Márciusi Meeting\nDTSTART:20250325T100000Z\nDTEND:20250325T110000Z\nDTSTAMP:20250317T170454Z\nEND:VEVENT\nBEGIN:VEVENT\nSUMMARY:Projekt Bemutató\nDTSTART:20250328T143000Z\nDTEND:20250328T153000Z\nDTSTAMP:20250317T170454Z\nEND:VEVENT\nBEGIN:VEVENT\nSUMMARY:Áprilisi Konferencia\nDTSTART:20250405T090000Z\nDTEND:20250405T100000Z\nDTSTAMP:20250317T170454Z\nEND:VEVENT\nBEGIN:VEVENT\nSUMMARY:Ügyféltalálkozó\nDTSTART:20250415T164500Z\nDTEND:20250415T174500Z\nDTSTAMP:20250317T170454Z\nEND:VEVENT\nEND:VCALENDAR'),
(3, 'eventek.ics', 25, 9, '2025-03-20 14:42:35', 'BEGIN:VCALENDAR\r\nVERSION:2.0\r\nPRODID:-//ChatGPT//HU\r\nBEGIN:VEVENT\r\nUID:evt1@example.com\r\nDTSTAMP:20250320T120000Z\r\nDTSTART:20250310T140000Z\r\nDTEND:20250310T160000Z\r\nSUMMARY:Márciusi Meeting\r\nDESCRIPTION:Egy fontos megbeszélés márciusban.\r\nLOCATION:Budapest\r\nEND:VEVENT\r\n\r\nBEGIN:VEVENT\r\nUID:evt2@example.com\r\nDTSTAMP:20250320T120000Z\r\nDTSTART:20250315T100000Z\r\nDTEND:20250315T120000Z\r\nSUMMARY:Március 15-i Ünnepség\r\nDESCRIPTION:Megemlékezés az 1848-as forradalomról.\r\nLOCATION:Kossuth tér, Budapest\r\nEND:VEVENT\r\n\r\nBEGIN:VEVENT\r\nUID:evt3@example.com\r\nDTSTAMP:20250320T120000Z\r\nDTSTART:20250325T180000Z\r\nDTEND:20250325T200000Z\r\nSUMMARY:Baráti Találkozó\r\nDESCRIPTION:Egy kellemes este barátokkal.\r\nLOCATION:Kávézó\r\nEND:VEVENT\r\n\r\nBEGIN:VEVENT\r\nUID:evt4@example.com\r\nDTSTAMP:20250320T120000Z\r\nDTSTART:20250405T090000Z\r\nDTEND:20250405T110000Z\r\nSUMMARY:Áprilisi Konferencia\r\nDESCRIPTION:Szakmai konferencia érdekes előadásokkal.\r\nLOCATION:Debrecen\r\nEND:VEVENT\r\n\r\nBEGIN:VEVENT\r\nUID:evt5@example.com\r\nDTSTAMP:20250320T120000Z\r\nDTSTART:20250420T150000Z\r\nDTEND:20250420T170000Z\r\nSUMMARY:Tavaszi Kirándulás\r\nDESCRIPTION:Egy kellemes túra a természetben.\r\nLOCATION:Börzsöny\r\nEND:VEVENT\r\n\r\nEND:VCALENDAR'),
(4, '1743751423_1743620653_myevents.ics', 39, 2, '2025-04-04 07:23:43', 'BEGIN:VCALENDAR\r\nVERSION:2.0\r\nPRODID:-//ical.marudot.com//iCal Event Maker\r\nCALSCALE:GREGORIAN\r\nBEGIN:VEVENT\r\nDTSTAMP:20250223T114217Z\r\nUID:1740310860923-97544@ical.marudot.com\r\nDTSTART;VALUE=DATE:20250225\r\nDTEND;VALUE=DATE:20250301\r\nSUMMARY:kugi\r\nEND:VEVENT\r\nBEGIN:VEVENT\r\nDTSTAMP:20250223T114217Z\r\nUID:1740310889740-67248@ical.marudot.com\r\nDTSTART;VALUE=DATE:20250311\r\nDTEND;VALUE=DATE:20250314\r\nSUMMARY:kugi2\r\nEND:VEVENT\r\nBEGIN:VEVENT\r\nDTSTAMP:20250223T114217Z\r\nUID:1740310917148-56043@ical.marudot.com\r\nDTSTART;VALUE=DATE:20250315\r\nDTEND;VALUE=DATE:20250318\r\nSUMMARY:kugi3\r\nEND:VEVENT\r\nEND:VCALENDAR');

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
(1, 24, 8, '2025-03-26 15:00:00', '2025-03-26 17:09:00', 0, ''),
(2, 24, 10, '2025-03-30 15:00:00', '2025-03-30 16:34:44', 1, 'Készen van');

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
-- A tábla indexei `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `megye`
--
ALTER TABLE `megye`
  ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `naptarak`
--
ALTER TABLE `naptarak`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT a táblához `felhasznalo`
--
ALTER TABLE `felhasznalo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT a táblához `foglaltsag`
--
ALTER TABLE `foglaltsag`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `lakas`
--
ALTER TABLE `lakas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT a táblához `log`
--
ALTER TABLE `log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT a táblához `megye`
--
ALTER TABLE `megye`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT a táblához `naptarak`
--
ALTER TABLE `naptarak`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT a táblához `takaritas`
--
ALTER TABLE `takaritas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
CREATE DEFINER=`root`@`localhost` EVENT `TakaritoVisszallit` ON SCHEDULE EVERY 4 DAY STARTS '2025-03-19 17:30:18' ON COMPLETION NOT PRESERVE ENABLE DO UPDATE `felhasznalo` SET `TakaritoSzabadsagKezd`= '0000-00-00', `TakaritoSzabadsagVeg` = '0000-00-00' WHERE `TakaritoSzabadsagVeg` = DATE(NOW())$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
