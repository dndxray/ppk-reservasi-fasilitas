{{-- Footer tabel: keterangan jumlah data + nomor halaman (pagination asli), gaya sama dengan tabel laporan.
     Pemakaian: @include('partials.pagination-tabel', ['paginator' => $users]) --}}
@php
    $paginator->appends(request()->query());

    // Opsional: loncat ke bagian tertentu di halaman setelah pindah halaman,
    // misal @include('partials.pagination-tabel', ['paginator' => $x, 'fragment' => 'riwayat-reservasi'])
    if (!empty($fragment)) {
        $paginator->fragment($fragment);
    }

    $halamanSekarang = $paginator->currentPage();
    $halamanTerakhir = $paginator->lastPage();

    // Tampilkan maksimal 3 nomor halaman di sekitar halaman aktif
    $akhir = min($halamanTerakhir, max(1, $halamanSekarang - 1) + 2);
    $awal  = max(1, $akhir - 2);
@endphp

<div class="px-6 py-4 border-t border-gray-100 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between text-sm text-[#A94438]">
    <p>Menampilkan {{ $paginator->count() }} dari {{ $paginator->total() }} data</p>

    <nav class="flex items-center gap-2" aria-label="Navigasi halaman">
        @if ($paginator->onFirstPage())
            <span class="w-7 h-7 flex items-center justify-center text-gray-300 cursor-not-allowed select-none">&lt;</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" aria-label="Halaman sebelumnya"
               class="w-7 h-7 flex items-center justify-center text-gray-600 hover:bg-gray-100 rounded transition">&lt;</a>
        @endif

        @for ($i = $awal; $i <= $akhir; $i++)
            @if ($i === $halamanSekarang)
                <span aria-current="page"
                      class="w-7 h-7 flex items-center justify-center rounded bg-[#A94438] text-white font-medium cursor-default">{{ $i }}</span>
            @else
                <a href="{{ $paginator->url($i) }}"
                   class="w-7 h-7 flex items-center justify-center text-gray-600 hover:bg-gray-100 rounded transition">{{ $i }}</a>
            @endif
        @endfor

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" aria-label="Halaman berikutnya"
               class="w-7 h-7 flex items-center justify-center text-gray-600 hover:bg-gray-100 rounded transition">&gt;</a>
        @else
            <span class="w-7 h-7 flex items-center justify-center text-gray-300 cursor-not-allowed select-none">&gt;</span>
        @endif
    </nav>
</div>