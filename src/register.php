<?php
session_start();
$servername = "localhost";  // Cambia se necessario
$username = "root";         // Cambia se necessario
$password = "";             // Cambia se necessario
$dbname = "biblioteca";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST["nome"]);
    $cognome = trim($_POST["cognome"]);
    $email = trim($_POST["email"]);
    $classe = trim($_POST["classe"]);
    $password = trim($_POST["password"]);

    if ($conn->connect_error) {
        die("Connessione fallita: " . $conn->connect_error);
    }

    // Controlla se l'email è già registrata
    $check_sql = "SELECT Email FROM utente WHERE Email = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $errore = "Email già registrata!";
        header("Location: registerform.php?errore=" . urlencode($errore));
        exit();
    }

    $stmt->close();

    // Inserimento nel database senza hash della password
    $sql = "INSERT INTO utente (Nome, Cognome, Email, Classe, Confermato, Password, DataIscrizione) VALUES (?, ?, ?, ?, 1, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $nome, $cognome, $email, $classe, $password);

    if ($stmt->execute()) {
        header("Location: loginform.php?successo=Registrazione completata!");
        exit();
    } else {
        $errore = "Errore durante la registrazione.";
    }

    $stmt->close();
    $conn->close();
}

// Reindirizza con errore se qualcosa va storto
header("Location: register-form.php?errore=" . urlencode($errore));
exit();
?>