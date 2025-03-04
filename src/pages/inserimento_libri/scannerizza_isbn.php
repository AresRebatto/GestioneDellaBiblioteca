<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carica Immagine</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="bg-gray-100 text-gray-900">

    <!-- Navbar -->
    <nav class="bg-blue-600 p-4 text-white">
        <div class="container mx-auto flex justify-between items-center">
            <div class="flex items-center">
                <a href="../index.php"><h1 class="text-xl font-bold">📚 Biblioteca ITTS Rimini "O. Belluzi - L. Da Vinci"</h1></a>
            </div>
        </div>
    </nav>

    <!-- Contenuto principale -->
    <div class="container mx-auto mt-6 p-6 bg-white shadow-lg rounded-lg flex flex-col items-center max-w-lg">
        <div class="mb-6 w-full">
            <label for="sede" class="block text-sm font-medium text-gray-700 mb-2">Sede</label>
            <select id="sede" name="sede" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option value="BZ" selected>BZ</option>
                <option value="DV">DV</option>
            </select>
        </div>

        <h2 class="text-2xl font-semibold mb-4 text-center text-gray-800">Carica un'Immagine</h2>
        
        <p class="mb-4 text-gray-600 text-center">Seleziona l'immagine o scatta una foto</p>
        
        <label class="w-full max-w-xs flex flex-col items-center px-4 py-6 bg-gray-50 text-blue-600 rounded-lg shadow-lg tracking-wide uppercase border border-blue-400 cursor-pointer hover:bg-blue-100 hover:text-blue-700 transition-colors duration-200 ease-in-out">
            <svg class="w-8 h-8 mb-2 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16V8a3 3 0 013-3h12a3 3 0 013 3v8m-4 4H7m4-12v6m0 0l-3-3m3 3l3-3"></path>
            </svg>
            <span class="text-sm">Scegli un file</span>
            <input type="file" accept="image/*" capture="environment" class="hidden" id="fileInput">
        </label>

        <p id="fileName" class="mt-4 text-gray-700 text-center font-medium"></p>
    </div>

    <script src="../../js/api_call.js"></script>

</body>

</html>
