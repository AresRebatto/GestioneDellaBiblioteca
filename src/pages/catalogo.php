<?php
$servername = "localhost";  // Cambia se necessario
$username = "root";         // Cambia se necessario
$password = "";             // Cambia se necessario
$dbname = "biblioteca";     // Nome del database

// Connessione al database
$conn = new mysqli($servername, $username, $password, $dbname);

// Controllo connessione
if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

if (isset($_GET['query'])) {
    $query = trim($_GET['query']);

    // Query di ricerca con sicurezza SQL (evita SQL Injection)
    $sql = "SELECT 
    l.LibroId,
    l.Titolo,
    GROUP_CONCAT(CONCAT(a.Nome, ' ', a.Cognome) SEPARATOR ', ') AS Autori,
    l.Genere,
    l.Sede,
    l.Stato
FROM libro l
JOIN libro_autore la ON l.LibroId = la.LibroId
JOIN autore a ON la.AutoreId = a.AutoreId
WHERE l.Titolo LIKE ?
GROUP BY l.LibroId, l.Titolo, l.Genere, l.Sede, l.Stato;";
    $stmt = $conn->prepare($sql);
    $search = "%$query%";
    $stmt->bind_param("s", $search);
    $stmt->execute();
    $result = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogo Libri</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 text-gray-900">

    <!-- Navbar -->
    <nav class="bg-blue-600 p-4 text-white">
        <div class="container mx-auto flex justify-between items-center">
            <div class="flex items-center">
                <h1 class="text-xl font-bold">📚 Biblioteca</h1>
            </div>
            <a href="loginform.php" class="hover:underline">Login</a>
        </div>
    </nav>

    <!-- Contenuto principale -->
    <div class="container mx-auto mt-6 p-6 bg-white shadow-lg rounded-lg">

        <!-- Titolo e barra di ricerca -->
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-semibold">Catalogo Libri</h2>
            <form action="catalogo.php" method="GET" class="flex">
                <input type="text" name="query" placeholder="Cerca un libro..."
                    class="w-full h-12 px-4 py-2 border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-600"
                    required>
                <button type="submit"
                    class="ml-2 h-15 bg-blue-600 text-white px-4 py-2 rounded-md text-sm hover:bg-blue-700">
                    🔍 Cerca
                </button>
            </form>
        </div>

        <!-- Tabella libri -->
        <div class="overflow-x-auto">
            <?php
            if (isset($result) && $result->num_rows > 0) {
                echo "<table class='w-full border-collapse border border-gray-300'>
                    <thead class='bg-gray-200'>
                        <tr>
                            <th class='p-3 border text-left'>Titolo</th>
                            <th class='p-3 border text-left'>Autore</th>
                            <th class='p-3 border text-left'>Sede</th>
                            <th class='p-3 border text-left'>Stato</th>
                            <th class='p-3 border text-left'>Azione</th>
                        </tr>
                    </thead>
                    <tbody class='text-gray-700'>";

                // Cicla attraverso i risultati e crea le righe della tabella
                while ($row = $result->fetch_assoc()) {
                    $statoClasse = ($row["Stato"] == "Disponibile") ? "text-green-600" : "text-red-600";
                    $button = ($row["Stato"] == "Disponibile")
                        ? "<a href='prenotazione_libro.php?id={$row["LibroId"]}' class='bg-green-500 text-white px-3 py-1 rounded'>📖 Prenota</a>"
                        : "<button class='bg-gray-400 text-white px-3 py-1 rounded cursor-not-allowed' disabled>⏳ In attesa</button>";
                    echo "<tr class='border-b hover:bg-gray-100'>
                            <td class='p-3'>" . htmlspecialchars($row["Titolo"]) . "</td>
                            <td class='p-3'>" . htmlspecialchars($row["Autori"]) . "</td>
                            <td class='p-3'>" . htmlspecialchars($row["Sede"]) . "</td>
                            <td class='p-3'>" . htmlspecialchars($row["Stato"]) . "</td>
                            <td class='p-2 border'>$button</td>
                          </tr>";
                }

                echo "</tbody></table>";
            } else {
                echo "<p class='mt-4 text-gray-600'>Nessun risultato trovato</p>";
            }
            ?>
        </div>

        <div class="flex justify-between items-center mt-4">
            <a href="inserimento_manuale.php"
                class="bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center hover:bg-blue-700">
                ➕ Aggiungi Libro
            </a>
            <button type="button" onclick="window.location.href='index.php'"
                class="px-4 py-2 bg-red-500 text-white font-medium rounded-md hover:bg-red-600 transition">
                🏠 Home
            </button>
        </div>
    </div>

</body>

</html>

<?php
$stmt->close();
$conn->close();
?>