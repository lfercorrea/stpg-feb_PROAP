<div class='container center'>
    @if ($paginator->hasPages())
        <p class="small text-muted">
            {!! __('Mostrando de') !!}
            <b>{{ $paginator->firstItem() }}</b>
            {!! __('até') !!}
            <b>{{ $paginator->lastItem() }},</b>
            {!! __('de') !!}
            <b>{{ $paginator->total() }}</b>
            {!! __('itens') !!}
        </p>

        <ul class="pagination">
            {{-- mostra o chevron < --}}
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

            {{-- paginação de fato --}}
            @foreach ($elements as $element)
                {{-- divisor '...' entre seções --}}
                @if (is_string($element))
                    <li class="disabled">{{ $element }}</li>
                @endif

                {{-- páginas --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="active black">
                                <a>{{ $page }}</a>
                            </li>
                        @else
                            <li class="waves-effect"><a href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- mostra o chevron > --}}
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
