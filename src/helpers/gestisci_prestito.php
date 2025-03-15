<?php
session_start();
$servername = "localhost";  // Cambia se necessario
$username = "root";         // Cambia se necessario
$password = "";             // Cambia se necessario
$dbname = "biblioteca";     // Nome del database

// Connessione al database
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica autenticazione
include "verificasessione.php";

$utenteId = $_SESSION['utente_id']; // ID utente dalla sessione

// Controllo se i dati sono stati inviati correttamente
if (!isset($_POST['libro_id']) || !isset($_POST['azione'])) {
    die("Errore: dati mancanti.");
}

$libroId = $_POST['libro_id'];
$azione = $_POST['azione'];

// Controllo se il libro è in prestito all'utente e ottiene i dati
$query = "SELECT DataRestituzione, NumeroProroghe FROM Prestito WHERE LibroId = ? AND UtenteId = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $libroId, $utenteId);
$stmt->execute();
$result = $stmt->get_result();
$prestito = $result->fetch_assoc();

if (!$prestito) {
    die("Errore: Libro non trovato o non in prestito.");
}

// Azione: Restituzione del libro
if ($azione === "restituzione") {
    // Elimina il prestito
    $query = "DELETE FROM Prestito WHERE LibroId = ? AND UtenteId = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $libroId, $utenteId);

    if ($stmt->execute()) {
        // Aggiorna lo stato del libro a "Disponibile"
        $query = "UPDATE Libro SET Stato = 'Disponibile' WHERE LibroId = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $libroId);
        $stmt->execute();

        header("Location: ../pages/profilo.php");
        exit;
    } else {
        die("Errore durante la restituzione del libro.");
    }

// Azione: Proroga del prestito
} elseif ($azione === "proroga") {
    $numProroghe = $prestito['NumeroProroghe'];

    if ($numProroghe >= 3) {
        header("Location: ../pages/profilo.php?msg=Hai raggiunto il limite massimo di 3 proroghe.");
        exit;
    }

    $query = "UPDATE Prestito 
              SET DataRestituzione = DATE_ADD(DataRestituzione, INTERVAL 7 DAY), 
                  NumeroProroghe = NumeroProroghe + 1
              WHERE LibroId = ? AND UtenteId = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $libroId, $utenteId);

    if ($stmt->execute()) {
        header("Location: ../pages/profilo.php");
        exit;
    } else {
        die("Errore durante la richiesta di proroga.");
    }
} else {
    die("Errore: azione non valida.");
}
?>
