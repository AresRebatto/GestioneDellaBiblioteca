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
    <title>Catalogo Libri</title>

    <script src="https://cdn.tailwindcss.com"></script>
     <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- jQuery UI -->
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
</head>

<body class="bg-gray-100 text-gray-900">
    <!-- Navbar -->
    <nav class="bg-blue-600 p-4 text-white">
        <div class="container mx-auto flex flex-wrap justify-between items-center">
            <h1 class="text-lg font-bold">📚 Biblioteca ITTS Rimini "O. Belluzzi - L. Da Vinci"</h1>

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
        <!-- Titolo -->
        <div class="flex flex-wrap justify-between items-center mb-4">
            <h2 class="text-2xl font-semibold">Catalogo Libri</h2>
        </div>

        <!-- Barra di ricerca -->
        <form action="catalogo.php" method="GET" class="flex flex-col md:flex-row mb-4 gap-2">
            <input id="search" type="text" name="query" placeholder="Cerca un libro..."
                class="w-full md:w-3/4 px-2 py-2 border rounded-md text-sm" required>
            <button type="submit"
                class="w-full md:w-auto bg-blue-600 text-white px-4 py-2 rounded-md text-sm hover:bg-blue-700">
                🔍 Cerca
            </button>
        </form>

        <!-- Tabella libri -->
        <div class="overflow-x-auto">
            <table class="w-full border-collapse border border-gray-300">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="p-2 border">Titolo</th>
                        <th class="p-2 border">Autore</th>
                        <th class="p-2 border">Genere</th>
                        <th class="p-2 border">Sede</th>
                        <th class="p-2 border">Stato</th>
                        <th class="p-2 border">Azione</th>
                    </tr>
                </thead>
                <tbody>
                    <?php include '../helpers/get_libri.php'; ?> <!-- Inclusione dati -->
                </tbody>
            </table>
        </div>

        <!-- Pulsante Aggiungi Libro -->
        <div class="flex justify-center md:justify-start mt-4">
            <a href="inserimento_libro.php"
                class="w-full md:w-auto bg-blue-600 text-white px-4 py-2 rounded-lg text-center hover:bg-blue-700">
                ➕ Aggiungi Libro
            </a>
        </div>
    </div>
    <script src="../js/autocomplete.js"></script>
</body>

</html>