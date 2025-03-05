<?php include "../helpers/verificasessione.php"; ?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aggiunta Libro</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 text-gray-900">

    <!-- Navbar -->
    <nav class="bg-blue-600 p-4 text-white">
        <div class="container mx-auto flex justify-between items-center">
            <div class="flex items-center">
                <h1 class="text-xl font-bold">📚 Biblioteca ITTS Rimini "O. Belluzi - L. Da Vinci"</h1>
            </div>
            <a href="loginform.php" class="hover:underline">Login</a>
        </div>
    </nav>

    <!-- Contenuto principale -->
    <div class="container mx-auto mt-6 p-4 bg-white shadow-lg rounded-lg">
        
        <h2 class="text-2xl font-semibold mb-4">Aggiungi un Libro</h2>
        
        <div class="flex flex-col space-y-4">
            <a href="inserimento_libri/inserimento_manuale.php" 
                class="bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center justify-center hover:bg-blue-700">
                ➕ Aggiungi Manualmente
            </a>
            
            <a href="inserimento_libri/scannerizza_isbn.php" 
                class="bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center justify-center hover:bg-blue-700">
                📷 Scannerizza ISBN
            </a>
        </div>
    </div>

</body>

</html>
