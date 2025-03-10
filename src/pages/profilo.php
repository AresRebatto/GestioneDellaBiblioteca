<?php
// Avvia la sessione solo se non è già attiva
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Controlla se l'utente è loggato
if (!isset($_SESSION["utente_id"])) {
    header("Location: loginform.php");
    exit();
}

// Recupera le informazioni dell'utente dalla sessione
$utenteId = $_SESSION["utente_id"];
$nomeUtente = $_SESSION["nome"] ?? "Nome non disponibile";
$cognomeUtente = $_SESSION["cognome"] ?? "Cognome non disponibile";

// Connessione al database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "biblioteca";

$conn = new mysqli($servername, $username, $password, $dbname);

// Controllo della connessione
if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

// Query per ottenere i libri in prestito dell'utente
$sql = "
    SELECT l.LibroId, l.Titolo, l.URLImg, 
           GROUP_CONCAT(CONCAT(a.Nome, ' ', a.Cognome) SEPARATOR ', ') AS Autori, 
           p.DataRestituzione
    FROM Prestito p
    JOIN Libro l ON p.LibroId = l.LibroId
    JOIN libro_autore la ON l.LibroId = la.LibroId
    JOIN autore a ON la.AutoreId = a.AutoreId
    WHERE p.UtenteId = ? 
    GROUP BY l.LibroId, p.DataRestituzione
    ORDER BY p.DataRestituzione ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $utenteId);
$stmt->execute();
$result = $stmt->get_result();

// Array per i libri in prestito
$libri_prestito = [];
while ($row = $result->fetch_assoc()) {
    $libri_prestito[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profilo Utente</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <!-- Navbar -->
    <nav class="bg-blue-700 p-4 text-white flex justify-between items-center shadow-lg">
        <a href="index.php">
            <h1 class="text-xl font-bold">📚 Biblioteca ITTS Rimini "O. Belluzi - L. Da Vinci"</h1>
        </a>
        <div class="flex items-center space-x-4">
            <a href="index.php" class="font-medium hover:underline">🏠 Home</a>
            <form action="../helpers/logout.php" method="POST">
                <button type="submit" class="bg-red-500 px-4 py-2 rounded-lg hover:bg-red-600 transition ease-in-out duration-200">Logout</button>
            </form>
        </div>
    </nav>

    <!-- Contenuto della pagina -->
    <div class="container mx-auto mt-10 p-6 bg-white shadow-xl rounded-xl">

        <!-- Sezione delle informazioni dell'utente -->
        <div class="bg-gray-50 p-6 rounded-xl shadow-md mb-8">
            <h3 class="text-2xl font-semibold text-gray-800 mb-4">Informazioni dell'utente</h3>
            <p class="text-lg text-gray-700"><strong class="font-semibold">Nome:</strong> <?php echo $nomeUtente; ?></p>
            <p class="text-lg text-gray-700"><strong class="font-semibold">Cognome:</strong> <?php echo $cognomeUtente; ?></p>
        </div>

        <!-- Sezione dei libri presi in prestito -->
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">Libri presi in prestito</h2>

        <?php if (empty($libri_prestito)): ?>
            <p class="text-gray-600">Non hai libri in prestito.</p>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($libri_prestito as $libro): ?>
                    <a href="dettagli_prestito.php?libro_id=<?php echo $libro['LibroId']; ?>" class="block">
                        <div class="bg-gray-50 p-4 rounded-xl shadow-md hover:shadow-lg hover:bg-blue-50 transition ease-in-out duration-200">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <!-- Colonna immagine -->
                                <div class="flex justify-center">
                                    <img src="<?php echo !empty($libro['URLImg']) ? $libro['URLImg'] : 'https://via.placeholder.com/150'; ?>" 
                                         alt="Copertina libro" class="w-full h-48 object-contain rounded-lg">
                                </div>
                                <!-- Colonna informazioni libro -->
                                <div class="flex flex-col justify-between">
                                    <h3 class="text-lg font-semibold text-gray-800"><?php echo $libro['Titolo']; ?></h3>
                                    <p class="text-sm text-gray-600 mt-2">Autori: <?php echo $libro['Autori']; ?></p>
                                    <p class="text-sm text-gray-500">📅 Restituzione entro: <br><span class="font-semibold"><?php echo date("d M Y", strtotime($libro['DataRestituzione'])); ?></span></p>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>