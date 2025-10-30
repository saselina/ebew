@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex justify-center mt-6">
        <ul class="inline-flex items-center gap-2 bg-white p-2 rounded-2xl shadow-sm">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li>
                    <span class="px-4 py-2 text-gray-400 bg-gray-100 rounded-xl cursor-not-allowed">
                        Sebelumnya
                    </span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}"
                       class="px-4 py-2 rounded-xl bg-[#f6d8e0] text-gray-700 hover:bg-[#e0b2c8] transition font-medium">
                        Sebelumnya
                    </a>
                </li>
            @endif

            {{-- Pagination Numbers --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li>
                        <span class="px-3 py-2 text-gray-500">{{ $element }}</span>
                    </li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li>
                                <span class="px-4 py-2 rounded-xl bg-[#e0b2c8] text-white font-semibold shadow-sm">
                                    {{ $page }}
                                </span>
                            </li>
                        @else
                            <li>
                                <a href="{{ $url }}"
                                   class="px-4 py-2 rounded-xl bg-[#f8e5ed] text-gray-700 hover:bg-[#e0b2c8] hover:text-white transition font-medium">
                                    {{ $page }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}"
                       class="px-4 py-2 rounded-xl bg-[#f6d8e0] text-gray-700 hover:bg-[#e0b2c8] transition font-medium">
                        Berikutnya
                    </a>
                </li>
            @else
                <li>
                    <span class="px-4 py-2 text-gray-400 bg-gray-100 rounded-xl cursor-not-allowed">
                        Berikutnya
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif
