<?php
session_start();

// Controllo se l'utente è autenticato
if (!isset($_SESSION["utente_id"])) {
    header("Location: loginform.php");
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
    die("Connessione fallita: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recupera i dati dal form
    $isbn = $conn->real_escape_string($_POST["isbn"]);
    $titolo = $conn->real_escape_string($_POST["title"]);
    $autore = $conn->real_escape_string($_POST["author"]); // Nome e Cognome dell'autore
    $genere = $conn->real_escape_string($_POST["genre"]);
    $anno = intval($_POST["year"]);
    $sede = $conn->real_escape_string($_POST["location"]);
    $stato = "Disponibile"; // Il libro è disponibile di default
    $aggiuntoDa = $_SESSION["utente_id"]; // ID utente che aggiunge il libro

    // Gestione caricamento immagine
    $target_dir = "uploads/";
    $copertina = null;

    if (!empty($_FILES["cover"]["name"])) {
        $file_name = basename($_FILES["cover"]["name"]);
        $target_file = $target_dir . uniqid() . "_" . $file_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Controllo tipo file
        $allowed_types = ["jpg", "jpeg", "png", "gif"];
        if (in_array($imageFileType, $allowed_types)) {
            if (move_uploaded_file($_FILES["cover"]["tmp_name"], $target_file)) {
                $copertina = $target_file;
            } else {
                echo "Errore nel caricamento del file.";
                exit;
            }
        } else {
            echo "Formato immagine non valido.";
            exit;
        }
    }

    // Separare il nome e il cognome dell'autore
    $autore_parts = explode(" ", trim($autore));
    $nome_autore = $conn->real_escape_string($autore_parts[0]);
    $cognome_autore = isset($autore_parts[1]) ? $conn->real_escape_string($autore_parts[1]) : '';

    // Controllare se l'autore esiste già
    $sql_autore = "SELECT AutoreId FROM Autore WHERE Nome = ? AND Cognome = ?";
    $stmt = $conn->prepare($sql_autore);
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

        header("Location: ../pages/index.php");
        exit();
    } else {
        echo "Errore: " . $sql_libro . "<br>" . $conn->error;
    }
}

$conn->close();
?>