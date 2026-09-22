<x-app-layout>

    <div class="min-h-screen bg-[#F8F7F7]">

        <div class="px-6 py-8">

            {{-- Breadcrumb --}}
            <div class="mb-6">
                <p class="text-sm text-[#996561]">
                    Dashboard
                    <span class="mx-2">/</span>
                    Pelaporan
                    <span class="mx-2">/</span>
                    Detail Laporan
                </p>
            </div>

            {{-- Header --}}
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-[#47201B]">
                    Detail Laporan
                </h1>

                <p class="mt-1 text-sm text-[#996561]">
                    Informasi lengkap mengenai laporan kerusakan fasilitas.
                </p>
            </div>

            {{-- Pesan sukses --}}
            @if (session('success'))
                <div class="mb-6 p-3 bg-green-100 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Detail Laporan --}}
            <div class="bg-white rounded-xl shadow-sm border border-[#996561]/20 overflow-hidden">

                {{-- Card Header --}}
                <div class="px-6 py-5 border-b border-[#996561]/10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

                    <div>
                        <p class="text-xs text-[#996561]">
                            Laporan #{{ $report->id }}
                        </p>

                        <h2 class="mt-1 text-lg font-semibold text-[#47201B]">
                            {{ $report->facility->nama_fasilitas ?? 'Fasilitas' }}
                        </h2>
                    </div>

                    {{-- Status --}}
                    <div>

                        @if($report->status === 'baru')

                            <span class="inline-flex px-3 py-1.5 text-xs font-medium rounded-full bg-blue-100 text-blue-700">
                                Baru
                            </span>

                        @elseif($report->status === 'diproses')

                            <span class="inline-flex px-3 py-1.5 text-xs font-medium rounded-full bg-yellow-100 text-yellow-700">
                                Diproses
                            </span>

                        @elseif($report->status === 'selesai')

                            <span class="inline-flex px-3 py-1.5 text-xs font-medium rounded-full bg-green-100 text-green-700">
                                Selesai
                            </span>

                        @elseif($report->status === 'ditolak')

                            <span class="inline-flex px-3 py-1.5 text-xs font-medium rounded-full bg-red-100 text-red-700">
                                Ditolak
                            </span>

                        @endif

                    </div>

                </div>

                {{-- Content --}}
                <div class="p-6">

                    {{-- Informasi Utama --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- Fasilitas --}}
                        <div>
                            <p class="text-xs font-medium text-[#996561] uppercase tracking-wide">
                                Nama Fasilitas
                            </p>

                            <p class="mt-1 text-sm font-medium text-[#47201B]">
                                {{ $report->facility->nama_fasilitas ?? '-' }}
                            </p>
                        </div>

                        {{-- Kategori --}}
                        <div>
                            <p class="text-xs font-medium text-[#996561] uppercase tracking-wide">
                                Kategori Kerusakan
                            </p>

                            <p class="mt-1 text-sm font-medium text-[#47201B]">
                                {{ $report->kategori }}
                            </p>
                        </div>

                        {{-- Tanggal --}}
                        <div>
                            <p class="text-xs font-medium text-[#996561] uppercase tracking-wide">
                                Tanggal Laporan
                            </p>

                            <p class="mt-1 text-sm font-medium text-[#47201B]">
                                {{ $report->created_at?->format('d F Y') }}
                            </p>
                        </div>

                        {{-- Pelapor --}}
                        <div>
                            <p class="text-xs font-medium text-[#996561] uppercase tracking-wide">
                                Pelapor
                            </p>

                            <p class="mt-1 text-sm font-medium text-[#47201B]">
                                {{ $report->user->name ?? '-' }}
                            </p>
                        </div>

                    </div>

                    {{-- Deskripsi --}}
                    <div class="mt-7">

                        <p class="text-xs font-medium text-[#996561] uppercase tracking-wide">
                            Deskripsi Kerusakan
                        </p>

                        <div class="mt-2 p-4 bg-[#F8F7F7] rounded-lg">

                            <p class="text-sm leading-relaxed text-[#47201B] whitespace-pre-line">
                                {{ $report->deskripsi }}
                            </p>

                        </div>

                    </div>

                    {{-- Foto --}}
                    @if($report->foto)

                        <div class="mt-7">

                            <p class="text-xs font-medium text-[#996561] uppercase tracking-wide">
                                Foto Kondisi Kerusakan
                            </p>

                            <div class="mt-3">

                                <img
                                    src="{{ asset('storage/' . $report->foto) }}"
                                    alt="Foto kondisi kerusakan"
                                    class="max-w-full md:max-w-md rounded-lg border border-[#996561]/20"
                                >

                            </div>

                        </div>

                    @endif

                    {{-- Catatan Resolusi --}}
                    @if($report->catatan_resolusi)

                        <div class="mt-7">

                            <p class="text-xs font-medium text-[#996561] uppercase tracking-wide">
                                Catatan Resolusi
                            </p>

                            <div class="mt-2 p-4 bg-[#F8F7F7] rounded-lg">

                                <p class="text-sm leading-relaxed text-[#47201B] whitespace-pre-line">
                                    {{ $report->catatan_resolusi }}
                                </p>

                            </div>

                        </div>

                    @endif

                    {{-- Tanggal Selesai --}}
                    @if($report->diselesaikan_pada)

                        <div class="mt-6">

                            <p class="text-xs font-medium text-[#996561] uppercase tracking-wide">
                                Diselesaikan Pada
                            </p>

                            <p class="mt-1 text-sm text-[#47201B]">
                                {{ $report->diselesaikan_pada->format('d F Y, H:i') }}
                            </p>

                        </div>

                    @endif


                    {{-- ===================================== --}}
                    {{-- PANEL KHUSUS PETUGAS --}}
                    {{-- ===================================== --}}

                    @if(auth()->user()->role === 'petugas')

                        <div class="mt-8 pt-8 border-t border-[#996561]/20">

                            <h2 class="text-lg font-semibold text-[#47201B]">
                                Proses Laporan
                            </h2>

                            <p class="mt-1 text-sm text-[#996561]">
                                Perbarui status laporan dan tambahkan catatan resolusi.
                            </p>

                            <form
                                action="{{ route('reports.updateStatus', $report) }}"
                                method="POST"
                                class="mt-6"
                            >

                                @csrf
                                @method('PATCH')

                                {{-- Status --}}
                                <div>

                                    <label
                                        for="status"
                                        class="block text-sm font-medium text-[#47201B] mb-2"
                                    >
                                        Status Laporan
                                    </label>

                                    <select
                                        name="status"
                                        id="status"
                                        required
                                        class="w-full rounded-lg border-[#996561]/30 focus:border-[#CA734D] focus:ring-[#CA734D]"
                                    >

                                        <option value="baru"
                                            {{ $report->status === 'baru' ? 'selected' : '' }}>
                                            Baru
                                        </option>

                                        <option value="diproses"
                                            {{ $report->status === 'diproses' ? 'selected' : '' }}>
                                            Diproses
                                        </option>

                                        <option value="selesai"
                                            {{ $report->status === 'selesai' ? 'selected' : '' }}>
                                            Selesai
                                        </option>

                                        <option value="ditolak"
                                            {{ $report->status === 'ditolak' ? 'selected' : '' }}>
                                            Ditolak
                                        </option>

                                    </select>

                                </div>

                                {{-- Status Fasilitas --}}
                                <div class="mt-5">
                                    <label
                                        for="status_fasilitas"
                                        class="block text-sm font-medium text-[#47201B] mb-2"
                                    >
                                        Status Fasilitas Terkait
                                    </label>

                                    <select
                                        name="status_fasilitas"
                                        id="status_fasilitas"
                                        class="w-full rounded-lg border-[#996561]/30 focus:border-[#CA734D] focus:ring-[#CA734D]"
                                    >
                                        <option value="aktif" {{ $report->facility->status === 'aktif' ? 'selected' : '' }}>
                                            Aktif (Tersedia)
                                        </option>
                                        <option value="dalam_perbaikan" {{ $report->facility->status === 'dalam_perbaikan' ? 'selected' : '' }}>
                                            Dalam Perbaikan
                                        </option>
                                        <option value="nonaktif" {{ $report->facility->status === 'nonaktif' ? 'selected' : '' }} disabled>
                                            Nonaktif
                                        </option>
                                    </select>
                                </div>

                                {{-- Catatan --}}
                                <div class="mt-5">

                                    <label
                                        for="catatan_resolusi"
                                        class="block text-sm font-medium text-[#47201B] mb-2"
                                    >
                                        Catatan Resolusi
                                    </label>

                                    <textarea
                                        name="catatan_resolusi"
                                        id="catatan_resolusi"
                                        rows="4"
                                        placeholder="Contoh: Kerusakan lampu sudah diperbaiki."
                                        class="w-full rounded-lg border-[#996561]/30 focus:border-[#CA734D] focus:ring-[#CA734D]"
                                    >{{ old('catatan_resolusi', $report->catatan_resolusi) }}</textarea>

                                </div>

                                {{-- Button --}}
                                <div class="mt-6 flex flex-col sm:flex-row gap-3">

                                    <button
                                        type="submit"
                                        class="inline-flex items-center justify-center px-5 py-2.5 bg-[#47201B] text-white text-sm font-semibold rounded-lg hover:bg-[#511E1D] transition"
                                    >
                                        Simpan Perubahan
                                    </button>

                                    <a
                                        href="{{ route('reports.antrian') }}"
                                        class="inline-flex items-center justify-center px-5 py-2.5 border border-[#996561]/30 text-[#47201B] text-sm font-semibold rounded-lg hover:bg-[#F8F7F7] transition"
                                    >
                                        Kembali ke Antrian
                                    </a>

                                </div>

                            </form>

                        </div>

                    @else

                        {{-- Tombol Pengguna --}}
                        <div class="mt-8 pt-6 border-t border-[#996561]/10">

                            <a
                                href="{{ route('reports.index') }}"
                                class="inline-flex items-center px-5 py-2.5 border border-[#996561]/30 text-sm font-semibold text-[#47201B] rounded-lg hover:bg-[#F8F7F7] transition"
                            >
                                ← Kembali ke Riwayat
                            </a>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</x-app-layout>