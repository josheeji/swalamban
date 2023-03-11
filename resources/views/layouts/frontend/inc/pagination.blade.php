@if ($paginator->hasPages())
    <nav aria-label="Page navigation example" style="margin-top:1rem;">
        <ul class="pagination justify-content-center">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                    <span aria-hidden="true"><i class="icon icon-arrow-left"></i></span>
                </li>
                <li class="page-item"> <a class="page-link" href="#" aria-label="Previous"> <span
                            aria-hidden="true">&laquo;</span> </a>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->url(1) }}" rel="prev"
                        aria-label="@lang('pagination.previous')">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled d-none d-md-block" aria-disabled="true">
                        <span>{{ $element }}</span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="active" aria-current="page"><a class="page-link" href="#">
                                    {{ $page }}</a></li>
                        @else
                            <li class="page-item d-none d-md-block"><a class="page-link"
                                    href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->url($paginator->lastPage()) }}" rel="next"
                        aria-label="@lang('pagination.last')" aria-label="Next"><span aria-hidden="true">&raquo;</span> </a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.last')">
                    <a class="page-link"> <span aria-hidden="true">&raquo;</span></a>
                </li>
            @endif
        </ul>
    </nav>
@endif
