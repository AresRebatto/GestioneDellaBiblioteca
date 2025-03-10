<?php
session_start();
$servername = "localhost";  
$username = "root";         
$password = "";             
$dbname = "biblioteca";

// Connessione al database con mysqli
$conn = new mysqli($servername, $username, $password, $dbname);

// Controllo della connessione
if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = hash('sha256', trim($_POST["password"]));

    // Query per verificare le credenziali
    $sql = "SELECT UtenteId, Nome, Cognome, Password FROM utente WHERE Email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        if ($password == $user["Password"]) { // Verifica la password in chiaro (da sostituire con password_hash in futuro)
            $_SESSION["utente_id"] = $user["UtenteId"];
            $_SESSION["nome"] = $user["Nome"];
            $_SESSION["cognome"] = $user["Cognome"];

            // **Verifica se esiste già una sessione attiva**
            $sql = "SELECT Token FROM Sessioni WHERE UtenteId = ? AND Scadenza > NOW()";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $user["UtenteId"]);
            $stmt->execute();
            $result = $stmt->get_result();
            $sessione_attiva = $result->fetch_assoc();

            if ($sessione_attiva) {
                // **Se esiste una sessione valida, usa lo stesso token**
                $token = $sessione_attiva["Token"];
            } else {
                // **Altrimenti genera un nuovo token**
                $token = bin2hex(random_bytes(32));

                // Salva il nuovo token nel database con scadenza di 1 giorno e 1 ora (25 ore)
                $sql = "INSERT INTO Sessioni (UtenteId, Token, Scadenza) VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 25 HOUR))";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("is", $user["UtenteId"], $token);
                $stmt->execute();
            }

            // Imposta il cookie con il token (HTTPOnly e Secure)
            setcookie("session_token", $token, time() + (3600 * 25), "/", "", true, true);
            $urlLocation = isset($_COOKIE["urlpagina"]) ? urldecode($_COOKIE["urlpagina"]) : "/GestioneDellaBiblioteca/src/pages/index.php";
            // Reindirizza alla homepage
            header("Location: " . $urlLocation);
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
header("Location: ../pages/loginform.php?errore=" . urlencode($errore));
exit();
?>
