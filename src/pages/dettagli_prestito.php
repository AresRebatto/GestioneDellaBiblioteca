<?php
session_start();
$servername = "localhost";  
$username = "root";         
$password = "";             
$dbname = "biblioteca";

// Connessione al database con mysqli
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica autenticazione
if (!isset($_SESSION['utente_id'])) {
    die("Errore: utente non autenticato.");
}

$utenteId = $_SESSION['utente_id']; // Recupero ID utente dalla sessione

// Controllo se l'ID del libro è presente nella query string
if (!isset($_GET['libro_id'])) {
    die("Errore: ID libro non fornito.");
}

$libroId = $_GET['libro_id'];

// Query per ottenere i dettagli del libro
$query = "SELECT l.LibroId, l.Titolo, l.URLImg, 
                 GROUP_CONCAT(CONCAT(a.Nome, ' ', a.Cognome) SEPARATOR ', ') AS Autori, 
                 p.DataRestituzione
          FROM Prestito p
          JOIN Libro l ON p.LibroId = l.LibroId
          JOIN libro_autore la ON l.LibroId = la.LibroId
          JOIN autore a ON la.AutoreId = a.AutoreId
          WHERE p.LibroId = ? AND p.UtenteId = ?
          GROUP BY l.LibroId, p.DataRestituzione";

$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $libroId, $utenteId);
$stmt->execute();
$result = $stmt->get_result();
$libro = $result->fetch_assoc();

if (!$libro) {
    die("Errore: Libro non trovato o non in prestito.");
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dettagli Prestito</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <!-- Navbar -->
    <nav class="bg-blue-700 p-4 text-white flex justify-between items-center shadow-lg">
        <h1 class="text-2xl font-semibold">📚 Biblioteca ITTS Rimini</h1>
        <a href="profilo.php" class="font-medium hover:underline">🔙 Torna al profilo</a>
    </nav>

    <!-- Contenuto della pagina -->
    <div class="container mx-auto mt-10 p-6 bg-white shadow-xl rounded-xl text-center">
        <h2 class="text-2xl font-semibold text-gray-800">Dettagli del Prestito</h2>

        <div class="mt-6 flex flex-col items-center">
            <img src="<?php echo !empty($libro['URLImg']) ? $libro['URLImg'] : 'https://via.placeholder.com/150'; ?>" 
                 alt="Copertina libro" class="w-48 h-64 object-contain rounded-lg shadow-md">
            <h3 class="text-xl font-semibold mt-4"><?php echo $libro['Titolo']; ?></h3>
            <p class="text-lg text-gray-600">Autori: <?php echo $libro['Autori']; ?></p>
            <p class="text-lg text-gray-600 mt-2">📅 Restituzione entro: <span class="font-semibold"><?php echo date("d M Y", strtotime($libro['DataRestituzione'])); ?></span></p>
        </div>

        <!-- Azioni: Restituzione e Proroga -->
        <div class="mt-6 space-x-4">
            <form action="../helpers/gestisci_prestito.php" method="POST">
                <input type="hidden" name="libro_id" value="<?php echo $libroId; ?>">
                <button type="submit" name="azione" value="restituzione"
                        class="bg-red-500 text-white px-6 py-2 rounded-lg hover:bg-red-600 transition">
                    🔄 Restituisci Libro
                </button>
                <button type="submit" name="azione" value="proroga"
                        class="bg-green-500 text-white px-6 py-2 rounded-lg hover:bg-green-600 transition">
                    ⏳ Richiedi Proroga
                </button>
            </form>
        </div>
    </div>

</body>
</html>
