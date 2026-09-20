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
        <body class="bg-[#511E1D] min-h-screen w-full flex items-center justify-center p-6" style="font-family: 'Poppins', sans-serif;">
            <div class="w-full max-w-[1294px] h-[879px] bg-white rounded-3xl shadow-xl overflow-hidden flex">
                {{-- Panel kiri --}}
                <div class="hidden md:flex md:w-1/2 relative">
                    <img src="{{ asset('images/login-bg.jpg') }}" alt="Kampus"
                         class="absolute inset-0 w-full h-full object-cover">
                    <div class="absolute inset-0 bg-[#511E1D] opacity-60"></div>

                    <div class="relative z-10 p-10 pt-16 flex flex-col justify-start text-white">
            <div class="w-32 h-32 bg-white rounded-full overflow-hidden flex items-center justify-center mb-6">
            <img src="{{ asset('images/logo-trans.png') }}" alt="Logo Loka" class="w-20 h-20 object-contain">
        </div>
            <h1 class="text-6xl font-bold mb-2" style="font-family: 'MuseoModerno', sans-serif;">LOKA</h1>
            <p class="text-xl">Reservasi Fasilitas jadi lebih mudah.</p>
        </div>
        </div>

        <div class="w-full md:w-1/2 p-10 sm:p-12 flex flex-col justify-center">
            <h2 class="text-3xl font-bold text-gray-900 mb-1" style="font-family: 'Plus Jakarta Sans', sans-serif;">Selamat Datang!</h2>
            <p class="text-gray-500 mb-8">Masuk untuk melanjutkan ke akun Anda.</p>

            @session('status')
                <div class="mb-4 text-sm font-medium text-green-600">
                    {{ $value }}
                </div>
            @endsession

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                        Email
                    </label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                           placeholder="Masukkan email"
                           class="w-full px-4 py-3 bg-[#F5F0EC] border-0 rounded-lg focus:ring-2 focus:ring-[#6B2737] text-gray-800">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                        Password
                    </label>
                    <div class="relative">
                        <input id="password" type="password" name="password" required
                               placeholder="Masukkan password"
                               class="w-full px-4 py-3 bg-[#F5F0EC] border-0 rounded-lg focus:ring-2 focus:ring-[#6B2737] text-gray-800">
                    </div>
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
                    <input type="checkbox" name="remember" id="remember" class="hidden">
                    <button type="submit"
                            class="w-full py-3 bg-[#491F1B] text-white font-semibold rounded-lg hover:bg-[#3a141c] transition"
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

                // Validasi format email sederhana
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailPattern.test(email.value)) {
                    e.preventDefault();
                    valid = false;
                    email.classList.add('ring-2', 'ring-red-500');
                } else {
                    email.classList.remove('ring-2', 'ring-red-500');
                }

                // Validasi password tidak kosong & minimal 6 karakter
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
</body>
</html>