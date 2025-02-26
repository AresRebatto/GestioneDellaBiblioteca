<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inserisci Manualmente il Libro</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white shadow-lg rounded-lg p-6 w-full max-w-lg">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Inserisci Manualmente il Libro</h2>

        <form action="insert_book.php" method="POST" enctype="multipart/form-data" class="space-y-4">
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
                <label class="block text-gray-700 font-medium">Sede</label>
                <input type="text" name="location" required
                    class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Es. Biblioteca Centrale, Sala Lettura, etc.">
            </div>

            <!-- Copertina -->
            <div>
                <label class="block text-gray-700 font-medium">Carica Copertina</label>
                <input type="file" name="cover" accept="image/*" class="w-full px-3 py-2 border rounded-md">
            </div>

            <!-- Pulsante -->
            <div class="flex justify-center space-x-4 mt-4">
                <button type="button" onclick="window.location.href='index.php'"
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