@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex justify-center mt-6">
        <ul class="inline-flex items-center gap-2 bg-white p-2 rounded-2xl shadow-sm">
            {{-- Tombol Sebelumnya --}}
            @if ($paginator->onFirstPage())
                <li>
                    <span class="px-4 py-2 text-gray-400 bg-gray-100 rounded-xl cursor-not-allowed border shadow-sm">
                        Sebelumnya
                    </span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}"
                       class="px-4 py-2 rounded-xl border bg-[#f6d8e0] text-gray-800 hover:bg-[#e0b2c8] hover:text-white hover:shadow transition font-semibold">
                        Sebelumnya
                    </a>
                </li>
            @endif

            {{-- Nomor Halaman --}}
            @php
                $currentPage = $paginator->currentPage();
                $lastPage = $paginator->lastPage();
            @endphp

            {{-- Jika Laravel tidak kirim $elements (misal cuma 1 halaman), tampilkan manual --}}
            @if (empty($elements))
                <li>
                    <span class="px-4 py-2 rounded-xl bg-[#e0b2c8] text-white font-semibold border shadow-md">
                        1
                    </span>
                </li>
            @else
                @foreach ($elements as $element)
                    {{-- Titik-titik --}}
                    @if (is_string($element))
                        <li>
                            <span class="px-3 py-2 text-gray-700 border rounded-xl shadow-sm font-medium">{{ $element }}</span>
                        </li>
                    @endif

                    {{-- Nomor halaman --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $currentPage)
                                <li>
                                    <span class="px-4 py-2 rounded-xl bg-[#e0b2c8] text-white font-bold border shadow-md">
                                        {{ $page }}
                                    </span>
                                </li>
                            @else
                                <li>
                                    <a href="{{ $url }}"
                                       class="px-4 py-2 rounded-xl border bg-[#f8e5ed] text-gray-800 hover:bg-[#e0b2c8] hover:text-white hover:shadow transition font-semibold">
                                        {{ $page }}
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            @endif

            {{-- Tombol Berikutnya --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}"
                       class="px-4 py-2 rounded-xl border bg-[#f6d8e0] text-gray-800 hover:bg-[#e0b2c8] hover:text-white hover:shadow transition font-semibold">
                        Berikutnya
                    </a>
                </li>
            @else
                <li>
                    <span class="px-4 py-2 text-gray-400 bg-gray-100 rounded-xl cursor-not-allowed border shadow-sm">
                        Berikutnya
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif
