<?php include "../helpers/verificasessione.php"; ?>
<?php
// Inizia la sessione solo se non è già stata avviata
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Controlla se l'utente è loggato tramite il cookie session_token
$loggedIn = isset($_COOKIE["session_token"]) && isset($_SESSION["nome"]) && isset($_SESSION["cognome"]);
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aggiunta Libro</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 text-gray-900">

    <!-- Navbar -->
    <nav class="bg-blue-600 p-4 text-white">
        <div class="container mx-auto flex flex-wrap justify-between items-center">
                <a href="index.php">
                    <h1 class="text-xl font-bold">📚 Biblioteca ITTS Rimini "O. Belluzi - L. Da Vinci"</h1>
                </a>

            <?php if ($loggedIn): ?>
                <div class="flex items-center space-x-4">
                    <!-- Link al profilo con nome e cognome -->
                    <a href="profilo.php" class="hover:underline font-medium">
                        <?php echo $_SESSION["nome"] . " " . $_SESSION["cognome"]; ?>
                    </a>

                    <!-- Icona di logout -->
                    <form method="POST" action="../helpers/logout.php">
                        <button type="submit" class="text-red-400 hover:text-red-600">
                            🔴 Esci
                        </button>
                    </form>
                </div>
            <?php else: ?>
                <a href="loginform.php" class="hover:underline">Login</a>
            <?php endif; ?>
        </div>
    </nav>

    <!-- Contenuto principale -->
    <div class="container mx-auto mt-6 p-4 bg-white shadow-lg rounded-lg">
        
        <h2 class="text-2xl font-semibold mb-4">Aggiungi un Libro</h2>
        
        <div class="flex flex-col space-y-4">
            <a href="inserimento_libri/inserimento_manuale.php" 
                class="bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center justify-center hover:bg-blue-700">
                ➕ Aggiungi Manualmente
            </a>
            
            <a href="inserimento_libri/scannerizza_isbn.php" 
                class="bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center justify-center hover:bg-blue-700">
                📷 Scannerizza ISBN
            </a>
        </div>
    </div>

</body>

</html>
