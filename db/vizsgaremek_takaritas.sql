-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2025. Jan 21. 10:22
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
CREATE DATABASE IF NOT EXISTS `vizsgaremek_takaritas` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_hungarian_ci;
USE `vizsgaremek_takaritas`;

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
  `kepek` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `foglaltsag`
--
ALTER TABLE `foglaltsag`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `lakas`
--
ALTER TABLE `lakas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `megye`
--
ALTER TABLE `megye`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

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
-- Megkötések a táblához `takaritas`
--
ALTER TABLE `takaritas`
  ADD CONSTRAINT `lakasid` FOREIGN KEY (`lakasId`) REFERENCES `lakas` (`id`),
  ADD CONSTRAINT `takaritoid` FOREIGN KEY (`felhasznalo_id`) REFERENCES `felhasznalo` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
