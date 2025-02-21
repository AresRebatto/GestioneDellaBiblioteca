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

// Query per ottenere i libri
$sql = "SELECT * FROM Libro";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $statoClasse = ($row["Stato"] == "Disponibile") ? "text-green-600" : "text-red-600";
            $button = ($row["Stato"] == "Disponibile") 
                ? "<a href='prenotazione_libro.php?id={$row["LibroId"]}' class='bg-green-500 text-white px-3 py-1 rounded'>📖 Prenota</a>" 
                : "<button class='bg-gray-400 text-white px-3 py-1 rounded cursor-not-allowed' disabled>⏳ In attesa</button>";
            echo "
                <tr class='text-center'>
                    <td class='p-2 border'>{$row["Titolo"]}</td>
                    <td class='p-2 border'>{$row["Autore"]}</td>
                    <td class='p-2 border'>{$row["Genere"]}</td>
                    <td class='p-2 border $statoClasse'>{$row["Stato"]}</td>
                    <td class='p-2 border'>$button</td>
                </tr>
            ";
        }
    } else {
        echo "<tr><td colspan='5' class='text-center p-4'>Nessun libro disponibile</td></tr>";
    }
$conn->close(); // Chiude la connessione
?>