<?php
// Avvia la sessione se non è già attiva
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Distrugge la sessione
session_unset();
session_destroy();

// Elimina il cookie del token di sessione
setcookie("session_token", "", time() - 3600, "/", "", true, true);

// Reindirizza alla pagina di login
header("Location: ../pages/index.php");
exit();
?>