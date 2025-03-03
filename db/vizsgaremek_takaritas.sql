-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2025. Már 04. 00:28
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
(2, 'csabaTeszt2@gmail.com', '$2y$10$r7iRjGrHEwja6OzH7bXVxONW0xabLPxTDXnUPgk9W3wyJmGygJDCy', 'Csaba', 'Csaba', '+36201112223', 19, 1, 0, 0, '0000-00-00', '0000-00-00');

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
(41, 'fsdfasxcv', 'ewfvser2', 42341, 1, 0, 2, 14, 'fcseafr32rsdcs', '../php/uploads/lakas_41/kepek/67c30860dfaa0_remnant2.jpg'),
(42, 'dvds', 'sdyfesf', 342, 1, 0, 2, 16, 'vasfs', '../php/uploads/lakas_42/kepek/1740826124_DS.jpg'),
(43, 'asdfs', '3fwsefw', 342, 1, 0, 2, 16, 'fasfasf', '../php/uploads/lakas_43/kepek/1740827031_orks.jpg'),
(44, 'dfs', 'sd34', 324, 1, 0, 2, 15, 'fraserfawe', '../php/uploads/lakas_44/kepek/1740827725_remnant2.jpg'),
(45, 'gsdf', 'lakas utca 345', 2342, 0, 1, 2, 15, 'fgsdrfes', '../php/uploads/lakas_45/kepek/1740828229_remnant2.jpg'),
(46, 'GSDFG', 'gsdfgsd4', 352, 1, 0, 2, 17, 'vdfgvert34', '../php/uploads/lakas_46/kepek/1740830863_remnant2.jpg');

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
(16, '1740825986_myevents.ics', 41, 2, '2025-03-01 10:46:26', 'BEGIN:VCALENDAR\r\nVERSION:2.0\r\nPRODID:-//ical.marudot.com//iCal Event Maker\r\nCALSCALE:GREGORIAN\r\nBEGIN:VEVENT\r\nDTSTAMP:20250223T114217Z\r\nUID:1740310860923-97544@ical.marudot.com\r\nDTSTART;VALUE=DATE:20250225\r\nDTEND;VALUE=DATE:20250301\r\nSUMMARY:kugi\r\nEND:VEVENT\r\nBEGIN:VEVENT\r\nDTSTAMP:20250223T114217Z\r\nUID:1740310889740-67248@ical.marudot.com\r\nDTSTART;VALUE=DATE:20250311\r\nDTEND;VALUE=DATE:20250314\r\nSUMMARY:kugi2\r\nEND:VEVENT\r\nBEGIN:VEVENT\r\nDTSTAMP:20250223T114217Z\r\nUID:1740310917148-56043@ical.marudot.com\r\nDTSTART;VALUE=DATE:20250315\r\nDTEND;VALUE=DATE:20250318\r\nSUMMARY:kugi3\r\nEND:VEVENT\r\nEND:VCALENDAR'),
(17, '1740826124_ical_jan_feb_events.ics', 42, 2, '2025-03-01 10:48:44', '\nBEGIN:VCALENDAR\nVERSION:2.0\nPRODID:-//YourOrganization//YourApp//EN\nCALSCALE:GREGORIAN\nMETHOD:PUBLISH\n\nBEGIN:VEVENT\nUID:uid1@example.com\nDTSTAMP:20250123T120000Z\nDTSTART:20250105T090000Z\nDTEND:20250107T170000Z\nSUMMARY:Kétnapos esemény januárban\nDESCRIPTION:Egy érdekes esemény 2 napos időtartammal.\nEND:VEVENT\n\nBEGIN:VEVENT\nUID:uid2@example.com\nDTSTAMP:20250123T120000Z\nDTSTART:20250110T090000Z\nDTEND:20250113T170000Z\nSUMMARY:Háromnapos esemény januárban\nDESCRIPTION:Egy érdekes esemény 3 napos időtartammal.\nEND:VEVENT\n\nBEGIN:VEVENT\nUID:uid3@example.com\nDTSTAMP:20250123T120000Z\nDTSTART:20250115T090000Z\nDTEND:20250117T170000Z\nSUMMARY:Kétnapos esemény januárban\nDESCRIPTION:Egy érdekes esemény 2 napos időtartammal.\nEND:VEVENT\n\nBEGIN:VEVENT\nUID:uid4@example.com\nDTSTAMP:20250123T120000Z\nDTSTART:20250120T090000Z\nDTEND:20250123T170000Z\nSUMMARY:Háromnapos esemény januárban\nDESCRIPTION:Egy érdekes esemény 3 napos időtartammal.\nEND:VEVENT\n\nBEGIN:VEVENT\nUID:uid5@example.com\nDTSTAMP:20250123T120000Z\nDTSTART:20250127T090000Z\nDTEND:20250129T170000Z\nSUMMARY:Kétnapos esemény januárban\nDESCRIPTION:Egy érdekes esemény 2 napos időtartammal.\nEND:VEVENT\n\nBEGIN:VEVENT\nUID:uid6@example.com\nDTSTAMP:20250123T120000Z\nDTSTART:20250203T090000Z\nDTEND:20250205T170000Z\nSUMMARY:Kétnapos esemény februárban\nDESCRIPTION:Egy érdekes esemény 2 napos időtartammal.\nEND:VEVENT\n\nBEGIN:VEVENT\nUID:uid7@example.com\nDTSTAMP:20250123T120000Z\nDTSTART:20250208T090000Z\nDTEND:20250211T170000Z\nSUMMARY:Háromnapos esemény februárban\nDESCRIPTION:Egy érdekes esemény 3 napos időtartammal.\nEND:VEVENT\n\nBEGIN:VEVENT\nUID:uid8@example.com\nDTSTAMP:20250123T120000Z\nDTSTART:20250213T090000Z\nDTEND:20250215T170000Z\nSUMMARY:Kétnapos esemény februárban\nDESCRIPTION:Egy érdekes esemény 2 napos időtartammal.\nEND:VEVENT\n\nBEGIN:VEVENT\nUID:uid9@example.com\nDTSTAMP:20250123T120000Z\nDTSTART:20250218T090000Z\nDTEND:20250221T170000Z\nSUMMARY:Háromnapos esemény februárban\nDESCRIPTION:Egy érdekes esemény 3 napos időtartammal.\nEND:VEVENT\n\nBEGIN:VEVENT\nUID:uid10@example.com\nDTSTAMP:20250123T120000Z\nDTSTART:20250225T090000Z\nDTEND:20250227T170000Z\nSUMMARY:Kétnapos esemény februárban\nDESCRIPTION:Egy érdekes esemény 2 napos időtartammal.\nEND:VEVENT\n\nEND:VCALENDAR\n'),
(18, '1740827031_ical_jan_feb_events.ics', 43, 2, '2025-03-01 11:03:51', '\nBEGIN:VCALENDAR\nVERSION:2.0\nPRODID:-//YourOrganization//YourApp//EN\nCALSCALE:GREGORIAN\nMETHOD:PUBLISH\n\nBEGIN:VEVENT\nUID:uid1@example.com\nDTSTAMP:20250123T120000Z\nDTSTART:20250105T090000Z\nDTEND:20250107T170000Z\nSUMMARY:Kétnapos esemény januárban\nDESCRIPTION:Egy érdekes esemény 2 napos időtartammal.\nEND:VEVENT\n\nBEGIN:VEVENT\nUID:uid2@example.com\nDTSTAMP:20250123T120000Z\nDTSTART:20250110T090000Z\nDTEND:20250113T170000Z\nSUMMARY:Háromnapos esemény januárban\nDESCRIPTION:Egy érdekes esemény 3 napos időtartammal.\nEND:VEVENT\n\nBEGIN:VEVENT\nUID:uid3@example.com\nDTSTAMP:20250123T120000Z\nDTSTART:20250115T090000Z\nDTEND:20250117T170000Z\nSUMMARY:Kétnapos esemény januárban\nDESCRIPTION:Egy érdekes esemény 2 napos időtartammal.\nEND:VEVENT\n\nBEGIN:VEVENT\nUID:uid4@example.com\nDTSTAMP:20250123T120000Z\nDTSTART:20250120T090000Z\nDTEND:20250123T170000Z\nSUMMARY:Háromnapos esemény januárban\nDESCRIPTION:Egy érdekes esemény 3 napos időtartammal.\nEND:VEVENT\n\nBEGIN:VEVENT\nUID:uid5@example.com\nDTSTAMP:20250123T120000Z\nDTSTART:20250127T090000Z\nDTEND:20250129T170000Z\nSUMMARY:Kétnapos esemény januárban\nDESCRIPTION:Egy érdekes esemény 2 napos időtartammal.\nEND:VEVENT\n\nBEGIN:VEVENT\nUID:uid6@example.com\nDTSTAMP:20250123T120000Z\nDTSTART:20250203T090000Z\nDTEND:20250205T170000Z\nSUMMARY:Kétnapos esemény februárban\nDESCRIPTION:Egy érdekes esemény 2 napos időtartammal.\nEND:VEVENT\n\nBEGIN:VEVENT\nUID:uid7@example.com\nDTSTAMP:20250123T120000Z\nDTSTART:20250208T090000Z\nDTEND:20250211T170000Z\nSUMMARY:Háromnapos esemény februárban\nDESCRIPTION:Egy érdekes esemény 3 napos időtartammal.\nEND:VEVENT\n\nBEGIN:VEVENT\nUID:uid8@example.com\nDTSTAMP:20250123T120000Z\nDTSTART:20250213T090000Z\nDTEND:20250215T170000Z\nSUMMARY:Kétnapos esemény februárban\nDESCRIPTION:Egy érdekes esemény 2 napos időtartammal.\nEND:VEVENT\n\nBEGIN:VEVENT\nUID:uid9@example.com\nDTSTAMP:20250123T120000Z\nDTSTART:20250218T090000Z\nDTEND:20250221T170000Z\nSUMMARY:Háromnapos esemény februárban\nDESCRIPTION:Egy érdekes esemény 3 napos időtartammal.\nEND:VEVENT\n\nBEGIN:VEVENT\nUID:uid10@example.com\nDTSTAMP:20250123T120000Z\nDTSTART:20250225T090000Z\nDTEND:20250227T170000Z\nSUMMARY:Kétnapos esemény februárban\nDESCRIPTION:Egy érdekes esemény 2 napos időtartammal.\nEND:VEVENT\n\nEND:VCALENDAR\n'),
(19, '1740827725_myevents.ics', 44, 2, '2025-03-01 11:15:25', 'BEGIN:VCALENDAR\r\nVERSION:2.0\r\nPRODID:-//ical.marudot.com//iCal Event Maker\r\nCALSCALE:GREGORIAN\r\nBEGIN:VEVENT\r\nDTSTAMP:20250223T114217Z\r\nUID:1740310860923-97544@ical.marudot.com\r\nDTSTART;VALUE=DATE:20250225\r\nDTEND;VALUE=DATE:20250301\r\nSUMMARY:kugi\r\nEND:VEVENT\r\nBEGIN:VEVENT\r\nDTSTAMP:20250223T114217Z\r\nUID:1740310889740-67248@ical.marudot.com\r\nDTSTART;VALUE=DATE:20250311\r\nDTEND;VALUE=DATE:20250314\r\nSUMMARY:kugi2\r\nEND:VEVENT\r\nBEGIN:VEVENT\r\nDTSTAMP:20250223T114217Z\r\nUID:1740310917148-56043@ical.marudot.com\r\nDTSTART;VALUE=DATE:20250315\r\nDTEND;VALUE=DATE:20250318\r\nSUMMARY:kugi3\r\nEND:VEVENT\r\nEND:VCALENDAR'),
(20, '1740828229_ical_jan_feb_events.ics', 45, 2, '2025-03-01 11:23:49', '\nBEGIN:VCALENDAR\nVERSION:2.0\nPRODID:-//YourOrganization//YourApp//EN\nCALSCALE:GREGORIAN\nMETHOD:PUBLISH\n\nBEGIN:VEVENT\nUID:uid1@example.com\nDTSTAMP:20250123T120000Z\nDTSTART:20250105T090000Z\nDTEND:20250107T170000Z\nSUMMARY:Kétnapos esemény januárban\nDESCRIPTION:Egy érdekes esemény 2 napos időtartammal.\nEND:VEVENT\n\nBEGIN:VEVENT\nUID:uid2@example.com\nDTSTAMP:20250123T120000Z\nDTSTART:20250110T090000Z\nDTEND:20250113T170000Z\nSUMMARY:Háromnapos esemény januárban\nDESCRIPTION:Egy érdekes esemény 3 napos időtartammal.\nEND:VEVENT\n\nBEGIN:VEVENT\nUID:uid3@example.com\nDTSTAMP:20250123T120000Z\nDTSTART:20250115T090000Z\nDTEND:20250117T170000Z\nSUMMARY:Kétnapos esemény januárban\nDESCRIPTION:Egy érdekes esemény 2 napos időtartammal.\nEND:VEVENT\n\nBEGIN:VEVENT\nUID:uid4@example.com\nDTSTAMP:20250123T120000Z\nDTSTART:20250120T090000Z\nDTEND:20250123T170000Z\nSUMMARY:Háromnapos esemény januárban\nDESCRIPTION:Egy érdekes esemény 3 napos időtartammal.\nEND:VEVENT\n\nBEGIN:VEVENT\nUID:uid5@example.com\nDTSTAMP:20250123T120000Z\nDTSTART:20250127T090000Z\nDTEND:20250129T170000Z\nSUMMARY:Kétnapos esemény januárban\nDESCRIPTION:Egy érdekes esemény 2 napos időtartammal.\nEND:VEVENT\n\nBEGIN:VEVENT\nUID:uid6@example.com\nDTSTAMP:20250123T120000Z\nDTSTART:20250203T090000Z\nDTEND:20250205T170000Z\nSUMMARY:Kétnapos esemény februárban\nDESCRIPTION:Egy érdekes esemény 2 napos időtartammal.\nEND:VEVENT\n\nBEGIN:VEVENT\nUID:uid7@example.com\nDTSTAMP:20250123T120000Z\nDTSTART:20250208T090000Z\nDTEND:20250211T170000Z\nSUMMARY:Háromnapos esemény februárban\nDESCRIPTION:Egy érdekes esemény 3 napos időtartammal.\nEND:VEVENT\n\nBEGIN:VEVENT\nUID:uid8@example.com\nDTSTAMP:20250123T120000Z\nDTSTART:20250213T090000Z\nDTEND:20250215T170000Z\nSUMMARY:Kétnapos esemény februárban\nDESCRIPTION:Egy érdekes esemény 2 napos időtartammal.\nEND:VEVENT\n\nBEGIN:VEVENT\nUID:uid9@example.com\nDTSTAMP:20250123T120000Z\nDTSTART:20250218T090000Z\nDTEND:20250221T170000Z\nSUMMARY:Háromnapos esemény februárban\nDESCRIPTION:Egy érdekes esemény 3 napos időtartammal.\nEND:VEVENT\n\nBEGIN:VEVENT\nUID:uid10@example.com\nDTSTAMP:20250123T120000Z\nDTSTART:20250225T090000Z\nDTEND:20250227T170000Z\nSUMMARY:Kétnapos esemény februárban\nDESCRIPTION:Egy érdekes esemény 2 napos időtartammal.\nEND:VEVENT\n\nEND:VCALENDAR\n'),
(21, '1740830863_ical_jan_feb_events.ics', 46, 2, '2025-03-01 12:07:43', '\nBEGIN:VCALENDAR\nVERSION:2.0\nPRODID:-//YourOrganization//YourApp//EN\nCALSCALE:GREGORIAN\nMETHOD:PUBLISH\n\nBEGIN:VEVENT\nUID:uid1@example.com\nDTSTAMP:20250123T120000Z\nDTSTART:20250105T090000Z\nDTEND:20250107T170000Z\nSUMMARY:Kétnapos esemény januárban\nDESCRIPTION:Egy érdekes esemény 2 napos időtartammal.\nEND:VEVENT\n\nBEGIN:VEVENT\nUID:uid2@example.com\nDTSTAMP:20250123T120000Z\nDTSTART:20250110T090000Z\nDTEND:20250113T170000Z\nSUMMARY:Háromnapos esemény januárban\nDESCRIPTION:Egy érdekes esemény 3 napos időtartammal.\nEND:VEVENT\n\nBEGIN:VEVENT\nUID:uid3@example.com\nDTSTAMP:20250123T120000Z\nDTSTART:20250115T090000Z\nDTEND:20250117T170000Z\nSUMMARY:Kétnapos esemény januárban\nDESCRIPTION:Egy érdekes esemény 2 napos időtartammal.\nEND:VEVENT\n\nBEGIN:VEVENT\nUID:uid4@example.com\nDTSTAMP:20250123T120000Z\nDTSTART:20250120T090000Z\nDTEND:20250123T170000Z\nSUMMARY:Háromnapos esemény januárban\nDESCRIPTION:Egy érdekes esemény 3 napos időtartammal.\nEND:VEVENT\n\nBEGIN:VEVENT\nUID:uid5@example.com\nDTSTAMP:20250123T120000Z\nDTSTART:20250127T090000Z\nDTEND:20250129T170000Z\nSUMMARY:Kétnapos esemény januárban\nDESCRIPTION:Egy érdekes esemény 2 napos időtartammal.\nEND:VEVENT\n\nBEGIN:VEVENT\nUID:uid6@example.com\nDTSTAMP:20250123T120000Z\nDTSTART:20250203T090000Z\nDTEND:20250205T170000Z\nSUMMARY:Kétnapos esemény februárban\nDESCRIPTION:Egy érdekes esemény 2 napos időtartammal.\nEND:VEVENT\n\nBEGIN:VEVENT\nUID:uid7@example.com\nDTSTAMP:20250123T120000Z\nDTSTART:20250208T090000Z\nDTEND:20250211T170000Z\nSUMMARY:Háromnapos esemény februárban\nDESCRIPTION:Egy érdekes esemény 3 napos időtartammal.\nEND:VEVENT\n\nBEGIN:VEVENT\nUID:uid8@example.com\nDTSTAMP:20250123T120000Z\nDTSTART:20250213T090000Z\nDTEND:20250215T170000Z\nSUMMARY:Kétnapos esemény februárban\nDESCRIPTION:Egy érdekes esemény 2 napos időtartammal.\nEND:VEVENT\n\nBEGIN:VEVENT\nUID:uid9@example.com\nDTSTAMP:20250123T120000Z\nDTSTART:20250218T090000Z\nDTEND:20250221T170000Z\nSUMMARY:Háromnapos esemény februárban\nDESCRIPTION:Egy érdekes esemény 3 napos időtartammal.\nEND:VEVENT\n\nBEGIN:VEVENT\nUID:uid10@example.com\nDTSTAMP:20250123T120000Z\nDTSTART:20250225T090000Z\nDTEND:20250227T170000Z\nSUMMARY:Kétnapos esemény februárban\nDESCRIPTION:Egy érdekes esemény 2 napos időtartammal.\nEND:VEVENT\n\nEND:VCALENDAR\n');

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
-- A tábla indexei `naptarak`
--
ALTER TABLE `naptarak`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lakas_id` (`lakas_id`),
  ADD KEY `felhasznalo_id` (`felhasznalo_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `eszkozszukseglet`
--
ALTER TABLE `eszkozszukseglet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `felhasznalo`
--
ALTER TABLE `felhasznalo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT a táblához `foglaltsag`
--
ALTER TABLE `foglaltsag`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `lakas`
--
ALTER TABLE `lakas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT a táblához `megye`
--
ALTER TABLE `megye`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT a táblához `naptarak`
--
ALTER TABLE `naptarak`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT a táblához `takaritas`
--
ALTER TABLE `takaritas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
-- Megkötések a táblához `naptarak`
--
ALTER TABLE `naptarak`
  ADD CONSTRAINT `naptarak_ibfk_1` FOREIGN KEY (`lakas_id`) REFERENCES `lakas` (`id`),
  ADD CONSTRAINT `naptarak_ibfk_2` FOREIGN KEY (`felhasznalo_id`) REFERENCES `felhasznalo` (`id`);

--
-- Megkötések a táblához `takaritas`
--
ALTER TABLE `takaritas`
  ADD CONSTRAINT `lakasid` FOREIGN KEY (`lakasId`) REFERENCES `lakas` (`id`),
  ADD CONSTRAINT `takaritoid` FOREIGN KEY (`felhasznalo_id`) REFERENCES `felhasznalo` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
