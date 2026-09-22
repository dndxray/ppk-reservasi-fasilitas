<x-app-layout>


    <div class="py-6">
        <div class="max-w-none space-y-6">

            {{-- Header profil --}}
            <div class="bg-[#F5EFE9] rounded-xl p-6 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-full bg-[#B23A2E] text-white flex items-center justify-center text-xl font-bold">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="font-bold text-gray-900 text-lg">{{ strtoupper($user->name) }}</div>
                        <div class="text-sm text-gray-500">
                            @if($user->isAdmin()) Admin
                            @elseif($user->isPetugas()) Petugas
                            @else Pengguna
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-[2fr_1fr] gap-8">
                {{-- Kolom kiri: Identitas Pribadi --}}
                <div class="bg-white rounded-xl shadow-sm p-8">
                    <h3 class="font-semibold text-gray-800 mb-4">Identitas Pribadi</h3>

                    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                        @csrf
                    </form>

                    <form method="post" action="{{ route('profile.update') }}" class="space-y-4">
                        @csrf
                        @method('patch')

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="name" value="Nama Lengkap" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full bg-[#F5EFE9] border-0"
                                              :value="old('name', $user->name)" required autofocus autocomplete="name" />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            <div>
                                <x-input-label for="nim_nip" value="NIM" />
                                <x-text-input id="nim_nip" name="nim_nip" type="text" class="mt-1 block w-full bg-[#F5EFE9] border-0"
                                              :value="old('nim_nip', $user->nim_nip)" />
                                <x-input-error class="mt-2" :messages="$errors->get('nim_nip')" />
                            </div>

                            <div>
                                <x-input-label for="email" value="Email" />
                                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full bg-[#F5EFE9] border-0"
                                              :value="old('email', $user->email)" required autocomplete="username" />
                                <x-input-error class="mt-2" :messages="$errors->get('email')" />
                            </div>

                            <div>
                                <x-input-label for="no_telepon" value="Nomor Kontak" />
                                <x-text-input id="no_telepon" name="no_telepon" type="text" class="mt-1 block w-full bg-[#F5EFE9] border-0"
                                              :value="old('no_telepon', $user->no_telepon)" />
                                <x-input-error class="mt-2" :messages="$errors->get('no_telepon')" />
                            </div>
                        </div>

                        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                            <div>
                                <p class="text-sm text-gray-800">
                                    {{ __('Your email address is unverified.') }}
                                    <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900">
                                        {{ __('Click here to re-send the verification email.') }}
                                    </button>
                                </p>
                                @if (session('status') === 'verification-link-sent')
                                    <p class="mt-2 font-medium text-sm text-green-600">
                                        {{ __('A new verification link has been sent to your email address.') }}
                                    </p>
                                @endif
                            </div>
                        @endif

                        <div class="flex items-center gap-4 pt-2">
                            <x-primary-button>{{ __('Simpan') }}</x-primary-button>

                            @if (session('status') === 'profile-updated')
                                <p x-data="{ show: true }" x-show="show" x-transition
                                   x-init="setTimeout(() => show = false, 2000)"
                                   class="text-sm text-gray-600">{{ __('Tersimpan.') }}</p>
                            @endif
                        </div>
                    </form>

                    <hr class="my-6">

                    <h3 class="font-semibold text-gray-800 mb-3">Informasi Akun</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="bg-[#F5EFE9] rounded-lg p-4">
                            <div class="text-xs text-gray-500">Bergabung Sejak</div>
                            <div class="font-medium text-gray-800">{{ $user->created_at->translatedFormat('d F Y') }}</div>
                        </div>
                        <div class="bg-[#F5EFE9] rounded-lg p-4">
                            <div class="text-xs text-gray-500">Status Akun</div>
                            <div class="font-medium
                                @if($user->status_verifikasi === 'terverifikasi') text-green-600
                                @elseif($user->status_verifikasi === 'ditolak') text-red-600
                                @else text-yellow-600
                                @endif">
                                {{ ucfirst($user->status_verifikasi) }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kolom kanan: Pengaturan --}}
                <div class="bg-white rounded-xl shadow-sm p-8 h-fit space-y-4">
                    <h3 class="font-semibold text-gray-800">Pengaturan</h3>

                    @include('profile.partials.update-password-form')

                    <hr>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="w-full py-2.5 bg-[#4a1a24] text-white text-sm font-medium rounded-lg hover:bg-[#3a141c] transition">
                            Keluar
                        </button>
                    </form>

                    @include('profile.partials.delete-user-form')
                </div>

            </div>
        </div>
    </div>
</x-app-layout>