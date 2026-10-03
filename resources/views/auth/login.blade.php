<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Loka</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=MuseoModerno:wght@700&family=Poppins:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#511E1D] min-h-screen w-full flex items-center justify-center p-4 sm:p-6" style="font-family: 'Poppins', sans-serif;">
    <div class="w-full max-w-[1294px] md:h-[879px] bg-white rounded-2xl md:rounded-3xl shadow-xl overflow-hidden flex flex-col md:flex-row">

        {{-- Panel kiri: disembunyikan total di HP, muncul mulai layar medium ke atas --}}
        <div class="hidden md:flex md:w-1/2 relative">
            <img src="{{ asset('images/login-bg.jpg') }}" alt="Kampus"
                 class="absolute inset-0 w-full h-full object-cover">
            <div class="absolute inset-0 bg-[#511E1D] opacity-60"></div>

            <div class="relative z-10 p-8 lg:p-10 flex flex-col justify-center text-white">
                <div class="w-16 h-16 lg:w-20 lg:h-20 bg-white rounded-full flex items-center justify-center mb-6">
                    <img src="{{ asset('images/logo-loka.png') }}" alt="Logo Loka" class="w-10 h-10 lg:w-12 lg:h-12">
                </div>
                <h1 class="text-3xl lg:text-4xl font-bold mb-2" style="font-family: 'MuseoModerno', sans-serif;">LOKA</h1>
                <p class="text-base lg:text-lg">Reservasi Fasilitas jadi lebih mudah.</p>
            </div>
        </div>

        {{-- Panel kanan: form login, full width di HP --}}
        <div class="w-full md:w-1/2 p-6 sm:p-8 md:p-10 lg:p-12 flex flex-col justify-center">

            {{-- Logo mini, cuma muncul di HP (karena panel kiri disembunyikan) --}}
            <div class="flex md:hidden items-center gap-3 mb-6">
                <img src="{{ asset('images/logo-trans.png') }}" alt="Logo Loka" class="w-10 h-10">
                <span class="text-2xl font-bold text-[#511E1D]" style="font-family: 'MuseoModerno', sans-serif;">LOKA</span>
            </div>

            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-1" style="font-family: 'Plus Jakarta Sans', sans-serif;">Selamat Datang!</h2>
            <p class="text-gray-500 mb-6 sm:mb-8 text-sm sm:text-base">Masuk untuk melanjutkan ke akun Anda.</p>

            @session('status')
                <div class="mb-4 text-sm font-medium text-green-600">
                    {{ $value }}
                </div>
            @endsession

            <form method="POST" action="{{ route('login') }}" class="space-y-4 sm:space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                        Email
                    </label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                           placeholder="Masukkan email"
                           class="w-full px-4 py-3 bg-[#F5F0EC] border-0 rounded-lg focus:ring-2 focus:ring-[#6B2737] text-gray-800 text-sm sm:text-base">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                        Password
                    </label>
                    <input id="password" type="password" name="password" required
                           placeholder="Masukkan password"
                           class="w-full px-4 py-3 bg-[#F5F0EC] border-0 rounded-lg focus:ring-2 focus:ring-[#6B2737] text-gray-800 text-sm sm:text-base">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:underline">
                            Lupa Password?
                        </a>
                    @endif
                </div>

                <div>
                    <button type="submit"
                            class="w-full py-3 bg-[#491F1B] text-white font-semibold rounded-lg hover:bg-[#3a141c] transition text-sm sm:text-base"
                            style="font-family: 'Plus Jakarta Sans', sans-serif;">
                        Masuk
                    </button>
                </div>

                <p class="text-center text-sm text-gray-600">
                    Belum memiliki akun?
                    <a href="{{ route('register') }}" class="text-blue-600 hover:underline">Daftar disini</a>
                </p>
            </form>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('form[action="{{ route('login') }}"]');
            const email = document.getElementById('email');
            const password = document.getElementById('password');

            form.addEventListener('submit', function (e) {
                let valid = true;

                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailPattern.test(email.value)) {
                    e.preventDefault();
                    valid = false;
                    email.classList.add('ring-2', 'ring-red-500');
                } else {
                    email.classList.remove('ring-2', 'ring-red-500');
                }

                if (password.value.length < 6) {
                    e.preventDefault();
                    valid = false;
                    password.classList.add('ring-2', 'ring-red-500');
                } else {
                    password.classList.remove('ring-2', 'ring-red-500');
                }

                if (!valid) {
                    alert('Periksa kembali email dan password Anda.');
                }
            });
        });
    </script>
</body>
</html>