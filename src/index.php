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
            <a href="#" class="hover:underline">Login</a>
        </div>
    </nav>

    <!-- Contenuto principale -->
    <div class="container mx-auto mt-6 p-4 bg-white shadow-lg rounded-lg">

        <!-- Titolo e pulsante aggiunta -->
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-semibold">Catalogo Libri</h2>
        </div>

        <!-- Barra di ricerca -->
        <form action="catalogo.php" method="GET" class="flex mb-4">
            <input type="text" name="query" placeholder="Cerca un libro..."
                class="w-full h-15 px-2 py-1 border rounded-md text-sm" required>
            <button type="submit"
                class="ml-2 h-10 bg-blue-600 text-white px-3 py-1 rounded-md text-sm hover:bg-blue-700">
                🔍 Cerca
            </button>
        </form>



        <!-- Tabella libri -->
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
                <?php include 'get_libri.php'; ?> <!-- Inclusione dati -->
            </tbody>
        </table>

        <div class="flex justify-between items-center mb-4 mt-4">
            <a href="inserimento_manuale.php"
                class="bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center hover:bg-blue-700"">
                ➕ Aggiungi Libro
            </a>
        </div>

    </div>

</body>

</html>