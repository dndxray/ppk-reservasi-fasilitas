<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOKA Reservation</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#F8F7F7]">

<div class="min-h-screen">

    <main class="p-6">

        {{ $slot }}

    </main>

</div>

</body>
</html>