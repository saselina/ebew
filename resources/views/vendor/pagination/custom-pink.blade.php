@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex justify-center mt-8">
        <ul class="flex items-center gap-3 text-[16px] font-semibold select-none">

            {{-- Tombol Sebelumnya --}}
            @if ($paginator->onFirstPage())
                <li>
                    <span class="px-5 py-3 bg-gray-100 text-gray-400 border border-gray-300 rounded-md cursor-not-allowed shadow-sm">
                        Sebelumnya
                    </span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}"
                       class="px-5 py-3 bg-white border border-gray-300 text-pink-600 rounded-md hover:bg-pink-50 hover:border-pink-400 transition duration-200 shadow-sm">
                        Sebelumnya
                    </a>
                </li>
            @endif

            {{-- Nomor Halaman --}}
            @php
                $current = $paginator->currentPage();
                $last = $paginator->lastPage();

                // Tampilkan maksimal 3 halaman
                $start = max(1, $current - 1);
                $end = min($last, $start + 2);

                // Pastikan selalu ada 3 item jika memungkinkan
                if (($end - $start) < 2 && $last > 2) {
                    $start = max(1, $end - 2);
                }
            @endphp

            @for ($page = $start; $page <= $end; $page++)
                @if ($page == $current)
                    <li>
                        <span class="px-4 py-2 bg-pink-500 text-white border border-pink-500 rounded-md font-bold shadow-sm">
                            {{ $page }}
                        </span>
                    </li>
                @else
                    <li>
                        <a href="{{ $paginator->url($page) }}"
                           class="px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-md hover:bg-pink-50 hover:border-pink-400 transition duration-200 shadow-sm">
                            {{ $page }}
                        </a>
                    </li>
                @endif
            @endfor

            {{-- Tombol Berikutnya --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}"
                       class="px-5 py-3 bg-white border border-gray-300 text-pink-600 rounded-md hover:bg-pink-50 hover:border-pink-400 transition duration-200 shadow-sm">
                        Berikutnya
                    </a>
                </li>
            @else
                <li>
                    <span class="px-5 py-3 bg-gray-100 text-gray-400 border border-gray-300 rounded-md cursor-not-allowed shadow-sm">
                        Berikutnya
                    </span>
                </li>
            @endif

        </ul>
    </nav>
@endif
