-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Mar 11, 2025 alle 09:11
-- Versione del server: 10.4.32-MariaDB
-- Versione PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `biblioteca`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `autore`
--

CREATE TABLE `autore` (
  `AutoreId` int(11) NOT NULL,
  `Nome` varchar(50) DEFAULT NULL,
  `Cognome` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `autore`
--



-- --------------------------------------------------------

--
-- Struttura della tabella `libro`
--

CREATE TABLE `libro` (
  `LibroId` int(11) NOT NULL,
  `ISBN` varchar(20) NOT NULL,
  `Titolo` varchar(255) NOT NULL,
  `URLImg` varchar(500) DEFAULT NULL,
  `Genere` varchar(50) DEFAULT NULL,
  `Sede` varchar(100) DEFAULT NULL,
  `Stato` enum('Disponibile','In prestito','Non disponibile') DEFAULT 'Disponibile',
  `AggiuntoDa` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `libro`
--



-- --------------------------------------------------------

--
-- Struttura della tabella `libro_autore`
--

CREATE TABLE `libro_autore` (
  `LibroId` int(11) DEFAULT NULL,
  `AutoreId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `libro_autore`
--



-- --------------------------------------------------------

--
-- Struttura della tabella `prestito`
--

CREATE TABLE `prestito` (
  `PrestitoId` int(11) NOT NULL,
  `DataRestituzione` date DEFAULT NULL,
  `NumeroProroghe` int(11) DEFAULT 0,
  `LibroId` int(11) NOT NULL,
  `UtenteId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `prestito`
--



-- --------------------------------------------------------

--
-- Struttura della tabella `sessioni`
--

CREATE TABLE `sessioni` (
  `SessioneId` int(11) NOT NULL,
  `UtenteId` int(11) DEFAULT NULL,
  `Token` varchar(255) DEFAULT NULL,
  `Scadenza` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `sessioni`
--


-- --------------------------------------------------------

--
-- Struttura della tabella `utente`
--

CREATE TABLE `utente` (
  `UtenteId` int(11) NOT NULL,
  `Nome` varchar(50) NOT NULL,
  `Cognome` varchar(50) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Classe` varchar(20) DEFAULT NULL,
  `Confermato` tinyint(1) DEFAULT 0,
  `Password` varchar(255) NOT NULL,
  `DataIscrizione` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `utente`
--


--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `autore`
--
ALTER TABLE `autore`
  ADD PRIMARY KEY (`AutoreId`);

--
-- Indici per le tabelle `libro`
--
ALTER TABLE `libro`
  ADD PRIMARY KEY (`LibroId`),
  ADD UNIQUE KEY `ISBN` (`ISBN`),
  ADD KEY `AggiuntoDa` (`AggiuntoDa`);

--
-- Indici per le tabelle `libro_autore`
--
ALTER TABLE `libro_autore`
  ADD KEY `LibroId` (`LibroId`),
  ADD KEY `AutoreId` (`AutoreId`);

--
-- Indici per le tabelle `prestito`
--
ALTER TABLE `prestito`
  ADD PRIMARY KEY (`PrestitoId`),
  ADD KEY `LibroId` (`LibroId`),
  ADD KEY `UtenteId` (`UtenteId`);

--
-- Indici per le tabelle `sessioni`
--
ALTER TABLE `sessioni`
  ADD PRIMARY KEY (`SessioneId`),
  ADD KEY `UtenteId` (`UtenteId`);

--
-- Indici per le tabelle `utente`
--
ALTER TABLE `utente`
  ADD PRIMARY KEY (`UtenteId`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `autore`
--
ALTER TABLE `autore`
  MODIFY `AutoreId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT per la tabella `libro`
--
ALTER TABLE `libro`
  MODIFY `LibroId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT per la tabella `prestito`
--
ALTER TABLE `prestito`
  MODIFY `PrestitoId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT per la tabella `sessioni`
--
ALTER TABLE `sessioni`
  MODIFY `SessioneId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT per la tabella `utente`
--
ALTER TABLE `utente`
  MODIFY `UtenteId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `libro`
--
ALTER TABLE `libro`
  ADD CONSTRAINT `libro_ibfk_1` FOREIGN KEY (`AggiuntoDa`) REFERENCES `utente` (`UtenteId`);

--
-- Limiti per la tabella `libro_autore`
--
ALTER TABLE `libro_autore`
  ADD CONSTRAINT `libro_autore_ibfk_1` FOREIGN KEY (`LibroId`) REFERENCES `libro` (`LibroId`),
  ADD CONSTRAINT `libro_autore_ibfk_2` FOREIGN KEY (`AutoreId`) REFERENCES `autore` (`AutoreId`);

--
-- Limiti per la tabella `prestito`
--
ALTER TABLE `prestito`
  ADD CONSTRAINT `prestito_ibfk_1` FOREIGN KEY (`LibroId`) REFERENCES `libro` (`LibroId`) ON DELETE CASCADE,
  ADD CONSTRAINT `prestito_ibfk_2` FOREIGN KEY (`UtenteId`) REFERENCES `utente` (`UtenteId`) ON DELETE CASCADE;

--
-- Limiti per la tabella `sessioni`
--
ALTER TABLE `sessioni`
  ADD CONSTRAINT `sessioni_ibfk_1` FOREIGN KEY (`UtenteId`) REFERENCES `utente` (`UtenteId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
