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

            // Genera un token casuale
            $token = bin2hex(random_bytes(32));

            // Connessione con PDO per la gestione delle sessioni
            try {
                $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                ]);

                // Debug: Verifica la connessione
                error_log("Connessione PDO riuscita");

                // Salva il token nel database con scadenza di 1 giorno e 1 ora (25 ore)
                $stmt = $pdo->prepare("INSERT INTO Sessioni (UtenteId, Token, Scadenza) VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 25 HOUR))");
                $stmt->execute([$user["UtenteId"], $token]);

                // Debug: Verifica se l'insert è andato a buon fine
                error_log("Token salvato per l'utente ID: " . $user["UtenteId"]);

                // Imposta il cookie con il token (HTTPOnly e Secure)
                setcookie("session_token", $token, time() + (3600 * 25), "/", "", true, true);

                // Reindirizza alla homepage
                header("Location: index.php");
                exit();
            } catch (PDOException $e) {
                error_log("Errore PDO: " . $e->getMessage()); // Log dell'errore
                die("Errore nella gestione della sessione: " . $e->getMessage()); // Debug
            }
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