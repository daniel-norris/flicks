<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flicks</title>
    <link href="/css/main.css" rel="stylesheet">

</head>

<body class="bg-gray-900 text-white">
    <header class="flex justify-between mx-6 p-6">
        <h1 class="text-gray-300 font-bold text-2xl">Flicks</h1>
        <nav>
            <ul class="text-sm flex space-x-4">
                <a href="#">
                    <li class="hover:text-gray-400">Top 50</li>
                </a>
                <a href="#" class="hover:text-gray-400">
                    <li>Movies</li>
                </a>
                <a href="#" class="hover:text-gray-400">
                    <li>TV Series</li>
                </a>
                <a href="#" class="hover:text-gray-400">
                    <li>Login</li>
                </a>
            </ul>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="mx-6 p-6 text-sm">
        Powered by the <a href="http://www.omdbapi.com/" class="underline">OMDb API</a>
    </footer>

</body>

</html>