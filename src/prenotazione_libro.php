<?php
// Connessione al database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "biblioteca";

// Crea la connessione
$conn = new mysqli($servername, $username, $password, $dbname);

// Controlla se la connessione ha avuto successo
if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

// Recupera l'ID del libro dalla query string
$bookId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Recupera i dettagli del libro dal database
$sql = "SELECT * FROM Libro WHERE LibroId = $bookId";
$result = $conn->query($sql);

// Se il libro esiste, carica i dettagli
if ($result->num_rows > 0) {
    $book = $result->fetch_assoc();
} else {
    echo "Libro non trovato.";
    exit;
}

// Gestione della conferma della prenotazione
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Aggiorna lo stato del libro a "Non disponibile"
    $updateSql = "UPDATE Libro SET Stato = 'Non disponibile' WHERE LibroId = $bookId";
    if ($conn->query($updateSql) === TRUE) {
        echo "<script>alert('Prenotazione confermata!'); window.location.href='index.php';</script>";
    } else {
        echo "Errore nell'aggiornamento dello stato: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conferma Prenotazione</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white shadow-lg rounded-lg p-6 w-full max-w-md">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Conferma la tua prenotazione</h2>

        <!-- Info del libro -->
        <div class="flex items-center space-x-4 border-b pb-4">
            <img src="<?php echo $book['URLImg'] ?: 'https://via.placeholder.com/80'; ?>" alt="Copertina libro" class="w-20 h-28 object-cover rounded-md shadow-md">
            <div>
                <h3 class="text-lg font-medium text-gray-900"><?php echo $book['Titolo']; ?></h3>
                <p class="text-gray-600">di <span class="font-medium"><?php echo $book['Autore']; ?></span></p>
                <p class="text-sm text-gray-500 mt-1">📅 Disponibile dal: <span class="font-semibold"><?php echo date("d M Y"); ?></span></p>
            </div>
        </div>

        <!-- Dettagli prenotazione -->
        <div class="mt-4 text-gray-700">
            <p>⚠️ Hai <span class="font-semibold">48 ore</span> per ritirare il libro.</p>
            <p>📍 Biblioteca: <span class="font-semibold">Biblioteca Centrale</span></p>
            <p>🔑 Codice prenotazione: <span class="font-mono text-blue-600">#<?php echo strtoupper(uniqid()); ?></span></p>
        </div>

        <!-- Bottoni di azione -->
        <form method="POST">
            <div class="mt-6 flex justify-between">
                <button type="button" onclick="window.location.href='index.php';" class="px-4 py-2 bg-red-500 text-white font-medium rounded-md hover:bg-red-600 transition">Annulla</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 transition">Conferma</button>
            </div>
        </form>
    </div>

</body>
</html>