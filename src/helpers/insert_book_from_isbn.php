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
    // Recupera i dati dal form
    $data = json_decode(file_get_contents("php://input"), true);    
    $isbn = $data["ISBN"];
    $anno = intval($data["anno"]);
    $autori = $data["autori"];
    $copertina = $data["copertina"];
    $genere = $data["genere"];
    $titolo = $data["titolo"];
    $sede = $data["sede"];

    $stato = "Disponibile"; // Il libro è disponibile di default
    
    $aggiuntoDa = $_SESSION["utente_id"]; // ID utente che aggiunge il libro
    
    $id_autori = [];

    foreach ($autori as $autore) {
        // Controllare se l'autore esiste già
        $sql_autore = "SELECT AutoreId FROM Autore WHERE Nome = ? AND Cognome = ?";
        $stmt = $conn->prepare($sql_autore);
        $stmt->bind_param("ss", $autore["nome"], $autore["cognome"]);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Autore già presente, recupera il suo ID
            $row = $result->fetch_assoc();
            $id_autori[] = $row["AutoreId"];
        } else {
            // Inserisci il nuovo autore
            $sql_insert_autore = "INSERT INTO Autore (Nome, Cognome) VALUES (?, ?)";
            $stmt = $conn->prepare($sql_insert_autore);
            $stmt->bind_param("ss", $autore["nome"], $autore["cognome"]);
            
            if ($stmt->execute()) {
                $id_autori[] = $stmt->insert_id;
            } else {
                echo "Errore nell'inserimento dell'autore: " . $conn->error;
                exit();
            }
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
        foreach($id_autori as $autore_id) {
            $sql_associazione = "INSERT INTO libro_autore (LibroId, AutoreId) VALUES (?, ?)";
            $stmt = $conn->prepare($sql_associazione);
            $stmt->bind_param("ii", $libro_id, $autore_id);
            $stmt->execute();
        }
        echo json_encode(["message" => "success"]);
        
        exit();
    } else {
        echo "Errore: " . $sql_libro . "<br>" . $conn->error;
    }
}

$conn->close();
?>