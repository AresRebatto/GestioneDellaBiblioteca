<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "biblioteca"; // Sostituiscilo con il nome del tuo database

// Connessione al database
$conn = new mysqli($servername, $username, $password, $dbname);

// Controllo della connessione
if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

// Legge il body JSON inviato dal client
$data = json_decode(file_get_contents("php://input"), true);
$term = isset($data["term"]) ? $conn->real_escape_string($data["term"]) : '';

// Query per cercare i titoli dei libri che iniziano con il termine inserito
$sql = "SELECT titolo FROM libro WHERE Titolo LIKE '$term%' LIMIT 10";
$result = $conn->query($sql);

// Array per i suggerimenti
$suggestions = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $suggestions[] = $row["titolo"];
    }
}

// Imposta il tipo di contenuto e restituisce il JSON
header("Content-Type: application/json");
echo json_encode($suggestions);

$conn->close();
?>