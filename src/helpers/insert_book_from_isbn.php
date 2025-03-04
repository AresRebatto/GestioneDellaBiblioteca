<?php
session_start();

// Controllo se l'utente è autenticato
if (!isset($_SESSION["utente_id"])) {
    header("Content-Type: application/json");
    echo json_encode(["error" => "Utente non autenticato"]);
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "biblioteca";

// Connessione al database
$conn = new mysqli($servername, $username, $password, $dbname);

// Controllo connessione
if ($conn->connect_error) {
    header("Content-Type: application/json");
    echo json_encode(["error" => "Connessione fallita: " . $conn->connect_error]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recupera i dati dal corpo della richiesta
    $data = json_decode(file_get_contents("php://input"), true);
    
    $isbn = $conn->real_escape_string($data["ISBN"]);
    $titolo = $conn->real_escape_string($data["titolo"]);
    $autore = $conn->real_escape_string($data["Autori"][0]["nome"] . " " . $conn->real_escape_string($data["Autori"][0]["cognome"]));
    $genere = $conn->real_escape_string($data["genere"]);
    $anno = intval($data["anno"]);
    $sede = $conn->real_escape_string($data["sede"]);
    $stato = "Disponibile"; // Il libro è disponibile di default
    $aggiuntoDa = $_SESSION["utente_id"]; // ID utente che aggiunge il libro
    $copertina = $conn->real_escape_string($data["copertina"]);

    // Separare il nome e il cognome dell'autore
    $autore_parts = explode(" ", trim($autore));
    $nome_autore = $conn->real_escape_string($autore_parts[0]);
    $cognome_autore = isset($autore_parts[1]) ? $conn->real_escape_string($autore_parts[1]) : '';

    // Controllare se l'autore esiste già
    $sql_check_autore = "SELECT AutoreId FROM Autore WHERE Nome = ? AND Cognome = ?";
    $stmt = $conn->prepare($sql_check_autore);
    $stmt->bind_param("ss", $nome_autore, $cognome_autore);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Autore già presente, recupera il suo ID
        $row = $result->fetch_assoc();
        $autore_id = $row["AutoreId"];
    } else {
        // Inserisci il nuovo autore
        $sql_insert_autore = "INSERT INTO Autore (Nome, Cognome) VALUES (?, ?)";
        $stmt = $conn->prepare($sql_insert_autore);
        $stmt->bind_param("ss", $nome_autore, $cognome_autore);
        if ($stmt->execute()) {
            $autore_id = $stmt->insert_id;
        } else {
            echo "Errore nell'inserimento dell'autore: " . $conn->error;
            exit();
        }
    }

    // Inserire il libro
    $sql_libro = "INSERT INTO Libro (ISBN, Titolo, Genere, Sede, Stato, AggiuntoDa, URLImg) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql_libro);
    $stmt->bind_param("sssssis", $isbn, $titolo, $genere, $sede, $stato, $aggiuntoDa, $copertina);

    if ($stmt->execute()) {
        $libro_id = $stmt->insert_id;

        // Associare il libro all'autore nella tabella libro_autore
        $sql_associazione = "INSERT INTO libro_autore (LibroId, AutoreId) VALUES (?, ?)";
        $stmt = $conn->prepare($sql_associazione);
        $stmt->bind_param("ii", $libro_id, $autore_id);
        $stmt->execute();

        echo "200";
    } else {
        echo "Errore: " . $conn->error;
    }
}

$conn->close();
?>