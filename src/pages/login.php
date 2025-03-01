<?php
session_start();
$servername = "localhost";  // Cambia se necessario
$username = "root";         // Cambia se necessario
$password = "";             // Cambia se necessario
$dbname = "biblioteca";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    if ($conn->connect_error) {
        die("Connessione fallita: " . $conn->connect_error);
    }

    // Query per verificare le credenziali
    $sql = "SELECT UtenteId, Nome, Cognome, Password FROM utente WHERE Email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // Verifica la password in chiaro
        if ($password == $user["Password"]) {
            $_SESSION["utente_id"] = $user["UtenteId"];
            $_SESSION["nome"] = $user["Nome"];
            $_SESSION["cognome"] = $user["Cognome"];
            header("Location: index.php"); // Reindirizza alla homepage
            exit();
        } else {
            $errore = "Password errata!";
        }
    } else {
        $errore = "Utente non trovato!";
    }

    $stmt->close();
    $conn->close();
}

// Reindirizza alla pagina del form con eventuali errori
header("Location: loginform.php?errore=" . urlencode($errore));
exit();
?>