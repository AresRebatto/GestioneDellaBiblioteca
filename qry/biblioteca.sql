-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Mar 01, 2025 alle 11:13
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

INSERT INTO `autore` (`AutoreId`, `Nome`, `Cognome`) VALUES
(1, 'Umberto', 'Eco'),
(2, 'J.K.', 'Rowling'),
(3, 'George', 'Orwell'),
(4, 'Gabriel', 'Garcia Marquez'),
(5, 'Italo', 'Calvino'),
(6, 'Harper', 'Lee'),
(7, 'Fyodor', 'Dostoevsky'),
(8, 'Jane', 'Austen'),
(9, 'Terry', 'Pratchett'),
(10, 'Neil', 'Gaiman'),
(11, 'Stephen', 'King'),
(12, 'Peter', 'Straub'),
(13, 'Douglas', 'Preston'),
(14, 'Lincoln', 'Child');

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

INSERT INTO `libro` (`LibroId`, `ISBN`, `Titolo`, `URLImg`, `Genere`, `Sede`, `Stato`, `AggiuntoDa`) VALUES
(1, '978-88-04-46510-9', 'Il nome della rosa', 'https://m.media-amazon.com/images/I/61Aa9Yic8AL._AC_UL480_FMwebp_QL65_.jpg', 'Romanzo storico', 'Biblioteca Centrale', 'In prestito', 1),
(2, '978-0-7475-3269-6', 'Harry Potter e la Pietra Filosofale', 'https://m.media-amazon.com/images/I/514dFN+-M+L._SY445_SX342_.jpg', 'Fantasy', 'Biblioteca Centrale', 'In prestito', 2),
(3, '978-0-452-28423-4', '1984', 'https://m.media-amazon.com/images/I/71GuYZ9CinL._AC_UL480_FMwebp_QL65_.jpg', 'Distopico', 'Biblioteca Sud', 'In prestito', 3),
(4, '978-88-459-1725-0', 'Cent\'anni di solitudine', 'https://m.media-amazon.com/images/I/81nxsT-8NWS._AC_UL480_FMwebp_QL65_.jpg', 'Romanzo', 'Biblioteca Nord', 'In prestito', 4),
(5, '978-88-459-1892-0', 'Il barone rampante', 'https://m.media-amazon.com/images/I/81XSDpDOEML._AC_UL480_FMwebp_QL65_.jpg', 'Narrativa', 'Biblioteca Ovest', 'Disponibile', 1),
(6, '978-0-06-112008-4', 'Il buio oltre la siepe', 'https://m.media-amazon.com/images/I/713Q8cZg6KL._AC_UL480_FMwebp_QL65_.jpg', 'Romanzo', 'Biblioteca Est', 'Disponibile', 2),
(7, '978-0-14-044913-6', 'Delitto e castigo', 'https://m.media-amazon.com/images/I/41wZo3XpVcL._SY445_SX342_.jpg', 'Classico', 'Biblioteca Centrale', 'Disponibile', 3),
(8, '978-0-19-953556-9', 'Orgoglio e pregiudizio', 'https://m.media-amazon.com/images/I/81yX3cxZBhL._AC_UL480_FMwebp_QL65_.jpg', 'Romanzo', 'Biblioteca Nord', 'Disponibile', 4),
(9, '978-0-06-085398-3', 'Buona Apocalisse a tutti!', 'https://m.media-amazon.com/images/I/81ZUaFvfpnL._AC_UL480_FMwebp_QL65_.jpg', 'Fantasy', 'Biblioteca Centrale', 'Disponibile', 5),
(10, '978-1-5011-9752-7', 'Il Talismano', 'https://m.media-amazon.com/images/I/71xw9m8+uDL._AC_UL480_FMwebp_QL65_.jpg', 'Horror', 'Biblioteca Sud', 'Disponibile', 6),
(11, '978-1-4555-9928-4', 'Relic', 'https://m.media-amazon.com/images/I/71VBtn7bD-L._AC_UL480_FMwebp_QL65_.jpg', 'Thriller', 'Biblioteca Ovest', 'In prestito', 2);

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

INSERT INTO `libro_autore` (`LibroId`, `AutoreId`) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5),
(6, 6),
(7, 7),
(8, 8),
(9, 9),
(9, 10),
(10, 11),
(10, 12),
(11, 13),
(11, 14);

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

INSERT INTO `prestito` (`PrestitoId`, `DataRestituzione`, `NumeroProroghe`, `LibroId`, `UtenteId`) VALUES
(1, '2025-03-10', 0, 3, 1),
(2, '2025-04-05', 1, 6, 2),
(3, '2025-03-15', 0, 7, 3),
(4, '2025-03-22', 2, 5, 4);

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

INSERT INTO `utente` (`UtenteId`, `Nome`, `Cognome`, `Email`, `Classe`, `Confermato`, `Password`, `DataIscrizione`) VALUES
(1, 'Mario', 'Rossi', 'mario.rossi@example.com', '5A', 1, 'password123', '2025-02-26 11:31:12'),
(2, 'Luca', 'Bianchi', 'luca.bianchi@example.com', '4B', 1, 'securepass', '2025-02-26 11:31:12'),
(3, 'Anna', 'Verdi', 'anna.verdi@example.com', '3C', 1, 'mypassword', '2025-02-26 11:31:12'),
(4, 'Elena', 'Neri', 'elena.neri@example.com', '2D', 1, 'pass456', '2025-02-26 11:31:12'),
(5, 'Giulia', 'Ferrari', 'giulia.ferrari@example.com', '5B', 1, 'giuliapass', '2025-02-26 11:31:12'),
(6, 'Marco', 'De Luca', 'marco.deluca@example.com', '4C', 1, 'marcopass', '2025-02-26 11:31:12');

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
  MODIFY `AutoreId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT per la tabella `libro`
--
ALTER TABLE `libro`
  MODIFY `LibroId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT per la tabella `prestito`
--
ALTER TABLE `prestito`
  MODIFY `PrestitoId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT per la tabella `sessioni`
--
ALTER TABLE `sessioni`
  MODIFY `SessioneId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `utente`
--
ALTER TABLE `utente`
  MODIFY `UtenteId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
