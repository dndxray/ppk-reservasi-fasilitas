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
    <script>
        // Mencegah FOUC / layout shift sidebar saat ganti halaman
        (function() {
            var saved = localStorage.getItem('sidebarOpen');
            if (saved === 'false') {
                document.documentElement.classList.add('sidebar-collapsed');
            }
        })();
    </script>
    <style>
        [x-cloak] { display: none !important; }

        /* Dimensi awal saat render pertama (sebelum Alpine siap) */
        #main-sidebar {
            width: 16rem; /* w-64 */
        }
        html.sidebar-collapsed #main-sidebar {
            width: 5rem !important; /* w-20 */
        }
        html.sidebar-collapsed #main-sidebar .sidebar-label {
            display: none !important;
        }
        html.sidebar-collapsed #main-sidebar .sidebar-toggle-box {
            justify-content: center !important;
        }

        /* Transisi konten halaman halus tanpa loncatan vertikal */
        .page-transition {
            animation: fadeIn 0.15s ease-out forwards;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
</head>
<body class="bg-[bg-white]" style="font-family: 'Poppins', sans-serif;">

<div x-data="{ 
        sidebarOpen: localStorage.getItem('sidebarOpen') === 'false' ? false : true,
        transitionsEnabled: false 
     }" 
     x-init="
        $watch('sidebarOpen', val => {
            localStorage.setItem('sidebarOpen', val);
            if (val) {
                document.documentElement.classList.remove('sidebar-collapsed');
            } else {
                document.documentElement.classList.add('sidebar-collapsed');
            }
        });
        // Aktifkan animasi transisi HANYA setelah halaman selesai dirender
        setTimeout(() => { transitionsEnabled = true; }, 100);
     "
     class="min-h-screen flex flex-col">

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

            <main class="p-6 flex-1 page-transition">
                {{ $slot }}
            </main>
        </div>
    </div>
</div>
</body>
</html>