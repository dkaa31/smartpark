@if ($paginator->hasPages())
<nav>
    <ul class="pagination pagination-sm mb-0" style="gap:2px;">
        {{-- Prev --}}
        @if ($paginator->onFirstPage())
            <li class="page-item disabled">
                <span class="page-link" style="font-size:.8rem;padding:4px 10px;">‹</span>
            </li>
        @else
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->previousPageUrl() }}" style="font-size:.8rem;padding:4px 10px;">‹</a>
            </li>
        @endif

        {{-- Pages --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <li class="page-item disabled">
                    <span class="page-link" style="font-size:.8rem;padding:4px 8px;">…</span>
                </li>
            @endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="page-item active">
                            <span class="page-link" style="font-size:.8rem;padding:4px 10px;background:#1e3a8a;border-color:#1e3a8a;">{{ $page }}</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $url }}" style="font-size:.8rem;padding:4px 10px;">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->nextPageUrl() }}" style="font-size:.8rem;padding:4px 10px;">›</a>
            </li>
        @else
            <li class="page-item disabled">
                <span class="page-link" style="font-size:.8rem;padding:4px 10px;">›</span>
            </li>
        @endif
    </ul>
</nav>
@endif
