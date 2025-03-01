<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold text-center mb-6">Login</h2>
        
        <?php if (isset($_GET['errore'])): ?>
            <p class="text-red-500 text-sm mb-4"><?php echo htmlspecialchars($_GET['errore']); ?></p>
        <?php endif; ?>

        <form action="login.php" method="POST" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Email:</label>
                <input type="email" name="email" required class="w-full p-2 border border-gray-300 rounded-lg">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Password:</label>
                <input type="password" name="password" required class="w-full p-2 border border-gray-300 rounded-lg">
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded-lg hover:bg-blue-600">
                Accedi
            </button>
        </form>

        <p class="text-center text-sm text-gray-600 mt-4">
            Non hai un account? <a href="registerform.php" class="text-blue-500 hover:underline">Registrati</a>
        </p>
    </div>
</body>
</html>
