@if ($paginator->hasPages())
    <nav class="navigation pagination">
        <div class="nav-links">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="page-numbers">&laquo;</span></span>
        @else
            <a class="page-numbers" href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo;</a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="page-numbers">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                      <span class="page-numbers current">{{ $page }}</span>
                    @else
                        <a class="page-numbers" href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
                <a class="next page-numbers" href="{{ $paginator->nextPageUrl() }}" rel="next">&raquo;</a>
        @endif
        </div>
    </nav>
@endif
