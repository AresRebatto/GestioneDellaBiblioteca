<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrazione</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold text-center mb-6">Registrati</h2>

        <form action="register.php" method="POST" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Nome:</label>
                <input type="text" name="nome" required class="w-full p-2 border border-gray-300 rounded-lg">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Cognome:</label>
                <input type="text" name="cognome" required class="w-full p-2 border border-gray-300 rounded-lg">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Email:</label>
                <input type="email" name="email" required class="w-full p-2 border border-gray-300 rounded-lg">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Classe:</label>
                <input type="text" name="classe" required class="w-full p-2 border border-gray-300 rounded-lg">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Password:</label>
                <input type="password" name="password" required class="w-full p-2 border border-gray-300 rounded-lg">
            </div>
            <button type="submit" class="w-full bg-green-500 text-white p-2 rounded-lg hover:bg-green-600">
                Registrati
            </button>
        </form>

        <p class="text-center text-sm text-gray-600 mt-4">
            Hai già un account? <a href="loginform.php" class="text-blue-500 hover:underline">Accedi</a>
        </p>
    </div>
</body>
</html>
