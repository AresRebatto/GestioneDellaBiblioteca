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
        <div class="container mx-auto flex justify-between">
            <h1 class="text-xl font-bold">📚 Biblioteca</h1>
            <a href="#" class="hover:underline">Login</a>
        </div>
    </nav>

    <!-- Contenuto principale -->
    <div class="container mx-auto mt-6 p-4 bg-white shadow-lg rounded-lg">
        
        <!-- Titolo -->
        <h2 class="text-2xl font-semibold mb-4">Catalogo Libri</h2>

        <!-- Barra di ricerca -->
        <div class="flex mb-4">
            <input type="text" placeholder="Cerca un libro..." class="w-full p-2 border rounded-lg">
            <button class="ml-2 bg-blue-600 text-white px-4 py-2 rounded-lg">🔍 Cerca</button>
        </div>

        <!-- Tabella libri -->
        <table class="w-full border-collapse border border-gray-300">
            <thead class="bg-gray-200">
                <tr>
                    <th class="p-2 border">Titolo</th>
                    <th class="p-2 border">Autore</th>
                    <th class="p-2 border">Genere</th>
                    <th class="p-2 border">Stato</th>
                    <th class="p-2 border">Azione</th>
                </tr>
            </thead>
            <tbody>
                <?php include 'get_libri.php'; ?> <!-- Inclusione dati -->
            </tbody>
        </table>

    </div>

</body>
</html>
