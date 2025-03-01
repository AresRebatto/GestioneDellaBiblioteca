<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carica Immagine</title>
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
    <div class="container mx-auto mt-6 p-6 bg-white shadow-lg rounded-lg flex flex-col items-center">
        
        <h2 class="text-2xl font-semibold mb-4">Carica un'Immagine</h2>
        
        <p class="mb-4 text-gray-700">Seleziona l'immagine o scatta una foto</p>
        
        <label class="w-full max-w-xs flex flex-col items-center px-4 py-6 bg-gray-50 text-blue-600 rounded-lg shadow-md tracking-wide uppercase border border-blue-400 cursor-pointer hover:bg-blue-100 hover:text-blue-700">
            <svg class="w-8 h-8 mb-2 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16V8a3 3 0 013-3h12a3 3 0 013 3v8m-4 4H7m4-12v6m0 0l-3-3m3 3l3-3"></path>
            </svg>
            <span class="text-sm">Scegli un file</span>
            <input type="file" accept="image/*" capture="environment" class="hidden">
        </label>
    </div>

</body>

</html>
