<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flicks</title>
    <link href="/css/main.css" rel="stylesheet">

</head>

<body class="bg-gray-800 text-white">
    <header class="flex justify-between px-8 py-6 border-t-4 border-blue-500 bg-gray-900 shadow-md">
        <a href="/">
            <h1 class="text-gray-300 font-bold text-2xl uppercase leading-none border-l-4 border-blue-500 pl-4">Flicks</h1>
        </a>
        <nav>
            <ul class="text-sm flex space-x-8">
                <a href="#" class="hover:text-gray-400 flex items-center">
                    <svg class="w-6 h-6 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                      </svg>
                    <li>Top 50</li>
                </a>
                <a href="#" class="hover:text-gray-400 flex items-center">
                    <svg class="w-6 h-6 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" />
                      </svg>
                    <li>Movies</li>
                </a>
                <a href="#" class="hover:text-gray-400 flex items-center">
                    <svg class="w-6 h-6 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                      </svg>
                    <li>Login</li>
                </a>
            </ul>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="px-8 py-6 text-sm bg-gray-900 mt-8">
        Powered by the <a href="http://www.omdbapi.com/" class="underline">OMDb API</a>
    </footer>

</body>

</html>