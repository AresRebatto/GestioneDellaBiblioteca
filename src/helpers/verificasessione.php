<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "biblioteca";
$urlpagina = $_SERVER['REQUEST_URI'];
setcookie("urlpagina", $urlpagina, time() + 3600, "/");

// Connessione al database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

// Controlla se esiste il cookie del token
if (!isset($_COOKIE["session_token"])) {
    session_unset();
    session_destroy();
    header("Location: ../pages/loginform.php");
    exit();
}

$token = $_COOKIE["session_token"];

// Controlla se il token è ancora valido
$sql = "SELECT UtenteId, Scadenza FROM Sessioni WHERE Token = ? AND Scadenza > NOW()";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();
$sessione = $result->fetch_assoc();

if (!$sessione) {
    // 🔹 Token non valido o scaduto, distruggere la sessione
    session_unset();
    session_destroy();
    setcookie("session_token", "", time() - 3600, "/"); // Elimina il cookie

    header("Location: ../pages/loginform.php");
    exit();
}

// Aggiorna la scadenza del token ogni volta che l'utente visita una pagina
$sql = "UPDATE Sessioni SET Scadenza = DATE_ADD(NOW(), INTERVAL 25 HOUR) WHERE Token = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $token);
$stmt->execute();

?>
