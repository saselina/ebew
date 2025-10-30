<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-sans text-gray-900 antialiased bg-[#f5f7fa]">
    <div class="min-h-screen flex items-center justify-center">
        <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-lg">
            <h1 class="text-2xl font-semibold text-center text-gray-800 mb-2">
                Inventory Web
            </h1>
            <p class="text-center text-gray-500 mb-6">
                Silakan masuk untuk melanjutkan
            </p>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">
                        Email
                    </label>
                    <input id="email" type="email" name="email"
                        class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        Password
                    </label>
                    <input id="password" type="password" name="password"
                        class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
                </div>

                <div class="flex items-center justify-between mb-4 text-sm">
                    <label class="flex items-center text-gray-600">
                        <input type="checkbox" class="mr-2"> Remember me
                    </label>
                  
                </div>

                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-gray-700 text-white font-medium py-2 rounded-lg transition">
                    Log in
                </button>
            </form>
        </div>
    </div>
</body>

</html>
