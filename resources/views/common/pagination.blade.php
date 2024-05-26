<div class='container center'>
    @if ($paginator->hasPages())
        <ul class="pagination">
        {{-- Previous Page Link --}}
        </ul>
        <p class="small text-muted">
            {!! __('Mostrando de') !!}
            <b>{{ $paginator->firstItem() }}</b>
            {!! __('até') !!}
            <b>{{ $paginator->lastItem() }}</b>
            {!! __('de') !!}
            <b>{{ $paginator->total() }}</b>
            {!! __('resultados') !!}
        </p>

        <ul class="pagination">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="disabled">
                    <a href="#!">
                        <i class="material-icons">chevron_left</i>
                    </a>
                </li>
            @else
            <li class="waves-effect">
                    <a href="{{ $paginator->previousPageUrl() }}"><i class="material-icons">chevron_left</i></a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="disabled">{{ $element }}</li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="active red black">
                                <a>{{ $page }}</a>
                            </li>
                            {{-- <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li> --}}
                        @else
                            <li class="waves-effect"><a href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="waves-effect">
                    <a href="{{ $paginator->nextPageUrl() }}"><i class="material-icons">chevron_right</i></a>
                </li>
            @else
                <li class="disabled">
                    <a href="#!">
                        <i class="material-icons">chevron_right</i>
                    </a>
                </li>
            @endif
        </ul>
    @endif
</div>
