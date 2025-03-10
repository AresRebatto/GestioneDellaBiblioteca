<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inserisci Manualmente il Libro</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex flex-col items-center justify-center">
    <!-- Navbar -->
    <nav class="bg-blue-600 p-4 text-white fixed top-0 w-full shadow-md">
        <div class="container mx-auto flex justify-between items-center">
            <div class="flex items-center">
                <a href="../index.php">
                    <h1 class="text-xl font-bold">📚 Biblioteca ITTS Rimini "O. Belluzi - L. Da Vinci"</h1>
                </a>
            </div>
        </div>
    </nav>

    <!-- Contenuto principale -->
    <div class="bg-white shadow-lg rounded-lg p-6 w-full max-w-lg mt-16">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4 text-center">Inserisci Manualmente il Libro</h2>

        <form action="../../helpers/insert_book.php" method="POST" enctype="multipart/form-data" class="space-y-4">
            <!-- ISBN -->
            <div>
                <label class="block text-gray-700 font-medium">ISBN</label>
                <input type="text" name="isbn" required
                    class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Titolo -->
            <div>
                <label class="block text-gray-700 font-medium">Titolo</label>
                <input type="text" name="title" required
                    class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Autore -->
            <div>
                <label class="block text-gray-700 font-medium">Autore</label>
                <input type="text" name="author" required
                    class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Genere -->
            <div>
                <label class="block text-gray-700 font-medium">Genere</label>
                <input type="text" name="genre" required
                    class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Anno di pubblicazione -->
            <div>
                <label class="block text-gray-700 font-medium">Anno di pubblicazione</label>
                <input type="number" name="year" required min="1000" max="9999"
                    class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Sede -->
            <div>
                <label for="sede" class="block text-sm font-medium text-gray-700 mb-2">Sede</label>
                <select id="sede" name="sede"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="BZ" selected>BZ</option>
                    <option value="DV">DV</option>
                </select>
            </div>

            <!-- Copertina -->
            <div>
                <label class="block text-gray-700 font-medium">Carica Copertina</label>
                <input type="file" name="cover" accept="image/*" class="w-full px-3 py-2 border rounded-md">
            </div>

            <!-- Pulsanti -->
            <div class="flex justify-center space-x-4 mt-4">
                <button type="button" onclick="window.location.href='../index.php'"
                    class="px-4 py-2 bg-red-500 text-white font-medium rounded-md hover:bg-red-600 transition">
                    Annulla
                </button>
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 transition">
                    Salva Libro
                </button>
            </div>
        </form>
    </div>
</body>


</html>