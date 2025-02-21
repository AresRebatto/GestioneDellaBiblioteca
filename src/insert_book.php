<?php
$servername = "localhost";  // Cambia se necessario
$username = "root";         // Cambia se necessario
$password = "";             // Cambia se necessario
$dbname = "biblioteca";     // Nome del database

// Connessione al database
$conn = new mysqli($servername, $username, $password, $dbname);

// Controllo connessione
if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recupera i dati dal form
    $titolo = $conn->real_escape_string($_POST["title"]);
    $autore = $conn->real_escape_string($_POST["author"]);
    $genere = $conn->real_escape_string($_POST["genre"]);
    $anno = intval($_POST["year"]);
    $sede = $conn->real_escape_string($_POST["location"]);
    $stato = "Disponibile"; // Di default il libro è disponibile
    $aggiuntoDa = 1; // Sostituisci con l'ID di un utente admin

    // Gestione caricamento immagine (se presente)
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

    // Query per inserire il libro
    $sql = "INSERT INTO Libro (Titolo, Autore, Genere, Sede, Stato, AggiuntoDa, URLImg) 
            VALUES ('$titolo', '$autore', '$genere', '$sede', '$stato', '$aggiuntoDa', '$copertina')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Libro aggiunto con successo!'); window.location.href='index.php';</script>";
    } else {
        echo "Errore: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>