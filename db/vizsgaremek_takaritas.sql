-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2024. Nov 11. 12:06
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
  `Id` int(10) NOT NULL,
  `nev` varchar(25) NOT NULL,
  `kiszereles` varchar(30) NOT NULL,
  `keszletenDB` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `eszkozszugseglet`
--

CREATE TABLE `eszkozszugseglet` (
  `eszkozId` int(10) NOT NULL,
  `lakasId` int(10) NOT NULL,
  `takaritoEmail` varchar(65) NOT NULL,
  `teljesitve` tinyint(1) NOT NULL,
  `igenyeltDarabszam` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `felhasznalo`
--

CREATE TABLE `felhasznalo` (
  `emailcim` varchar(65) NOT NULL,
  `jelszo` varchar(25) NOT NULL,
  `vezetekNev` text NOT NULL,
  `keresztNev` text NOT NULL,
  `elerhetoseg` varchar(30) NOT NULL,
  `megyeId` int(5) NOT NULL,
  `tulajdonos` tinyint(1) NOT NULL,
  `takarito` tinyint(1) NOT NULL,
  `admin` tinyint(1) NOT NULL,
  `szabadTakarito` tinyint(1) NOT NULL,
  `TakaritoSzabadsagKezd` date NOT NULL,
  `TakaritoSzabadsagVeg` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `foglaltsag`
--

CREATE TABLE `foglaltsag` (
  `Id` int(10) NOT NULL,
  `lakasId` int(10) NOT NULL,
  `vendegErkezes` date NOT NULL,
  `vendegTavozas` date NOT NULL,
  `vendegSzam` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `lakas`
--

CREATE TABLE `lakas` (
  `Id` int(10) NOT NULL,
  `lakcim` varchar(150) NOT NULL,
  `terulet` int(15) NOT NULL,
  `medence` tinyint(1) NOT NULL,
  `szauna` tinyint(1) NOT NULL,
  `tulajdonosEmail` varchar(65) NOT NULL,
  `belepesiAdatok` varchar(50) NOT NULL,
  `megyeId` int(5) NOT NULL,
  `kepek` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `megye`
--

CREATE TABLE `megye` (
  `Id` int(5) NOT NULL,
  `megyeNev` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

--
-- A tábla adatainak kiíratása `megye`
--

INSERT INTO `megye` (`Id`, `megyeNev`) VALUES
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
  `Id` int(10) NOT NULL,
  `lakasId` int(10) NOT NULL,
  `takaritoEmail` int(65) NOT NULL,
  `takaritoErkezes` date NOT NULL,
  `befejezve` tinyint(1) NOT NULL,
  `megjegyzes` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `eszkoz`
--
ALTER TABLE `eszkoz`
  ADD PRIMARY KEY (`Id`);

--
-- A tábla indexei `eszkozszugseglet`
--
ALTER TABLE `eszkozszugseglet`
  ADD UNIQUE KEY `takaritoEmail` (`takaritoEmail`),
  ADD KEY `eszkozId` (`eszkozId`),
  ADD KEY `lakasId` (`lakasId`);

--
-- A tábla indexei `felhasznalo`
--
ALTER TABLE `felhasznalo`
  ADD PRIMARY KEY (`emailcim`),
  ADD KEY `megyeId` (`megyeId`);

--
-- A tábla indexei `foglaltsag`
--
ALTER TABLE `foglaltsag`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `lakasId` (`lakasId`);

--
-- A tábla indexei `lakas`
--
ALTER TABLE `lakas`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `tulajdonosEmail` (`tulajdonosEmail`),
  ADD KEY `megyeId` (`megyeId`);

--
-- A tábla indexei `megye`
--
ALTER TABLE `megye`
  ADD PRIMARY KEY (`Id`);

--
-- A tábla indexei `takaritas`
--
ALTER TABLE `takaritas`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `lakasId` (`lakasId`,`takaritoEmail`);

--
-- A kiírt táblák AUTO_INCREMENT értéke
--

--
-- AUTO_INCREMENT a táblához `eszkoz`
--
ALTER TABLE `eszkoz`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `foglaltsag`
--
ALTER TABLE `foglaltsag`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `lakas`
--
ALTER TABLE `lakas`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `megye`
--
ALTER TABLE `megye`
  MODIFY `Id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT a táblához `takaritas`
--
ALTER TABLE `takaritas`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT;

--
-- Megkötések a kiírt táblákhoz
--

--
-- Megkötések a táblához `eszkozszugseglet`
--
ALTER TABLE `eszkozszugseglet`
  ADD CONSTRAINT `eszkozszugseglet_ibfk_1` FOREIGN KEY (`eszkozId`) REFERENCES `eszkoz` (`Id`),
  ADD CONSTRAINT `eszkozszugseglet_ibfk_2` FOREIGN KEY (`lakasId`) REFERENCES `lakas` (`Id`),
  ADD CONSTRAINT `eszkozszugseglet_ibfk_3` FOREIGN KEY (`takaritoEmail`) REFERENCES `felhasznalo` (`emailcim`);

--
-- Megkötések a táblához `felhasznalo`
--
ALTER TABLE `felhasznalo`
  ADD CONSTRAINT `felhasznalo_ibfk_1` FOREIGN KEY (`megyeId`) REFERENCES `megye` (`Id`);

--
-- Megkötések a táblához `foglaltsag`
--
ALTER TABLE `foglaltsag`
  ADD CONSTRAINT `foglaltsag_ibfk_1` FOREIGN KEY (`lakasId`) REFERENCES `lakas` (`Id`);

--
-- Megkötések a táblához `lakas`
--
ALTER TABLE `lakas`
  ADD CONSTRAINT `lakas_ibfk_1` FOREIGN KEY (`tulajdonosEmail`) REFERENCES `felhasznalo` (`emailcim`),
  ADD CONSTRAINT `lakas_ibfk_2` FOREIGN KEY (`megyeId`) REFERENCES `megye` (`Id`);

--
-- Megkötések a táblához `takaritas`
--
ALTER TABLE `takaritas`
  ADD CONSTRAINT `takaritas_ibfk_1` FOREIGN KEY (`lakasId`) REFERENCES `lakas` (`Id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
