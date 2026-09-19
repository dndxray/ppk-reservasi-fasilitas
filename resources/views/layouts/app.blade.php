<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOKA Reservation</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=MuseoModerno:wght@700&family=Poppins:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[bg-white]" style="font-family: 'Poppins', sans-serif;">

<div x-data="{ sidebarOpen: true }" class="min-h-screen flex flex-col">

    @include('layouts.navigation')

    <div class="flex flex-1">
        
            @include('layouts.sidebar')
       
        <div class="flex-1 flex flex-col min-w-0">
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main class="p-6 flex-1">
                {{ $slot }}
            </main>
        </div>
    </div>
</div>
</body>
</html>