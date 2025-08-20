<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    @vite('resources/css/app.css') {{-- Tailwind build --}}
</head>
<body class="bg-gray-900 flex items-center justify-center min-h-screen text-gray-100">

<div class="bg-gray-800 p-8 rounded-lg shadow-lg w-full max-w-sm border border-gray-700">
    <h2 class="text-2xl font-bold mb-6 text-center text-white">Login To Prism Ads</h2>

    @if($errors->any())
        <div class="bg-red-900 text-red-200 p-3 rounded mb-4 border border-red-700">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
        @csrf
        <input type="email"
               name="email"
               placeholder="Email"
               autocomplete="email"
               required
               class="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2 text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">

        <input type="password"
               name="password"
               placeholder="Password"
               autocomplete="current-password"
               required
               class="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2 text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">

        <button type="submit"
                class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-500 transition duration-200">
            Login
        </button>
    </form>
</div>

</body>
</html>
