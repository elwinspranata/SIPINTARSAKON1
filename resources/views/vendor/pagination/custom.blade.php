@if ($paginator->hasPages())
<nav role="navigation" aria-label="Pagination Navigation" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
    {{-- Results Info --}}
    <div style="font-size: 0.8125rem; color: #64748b; font-weight: 600;">
        Menampilkan
        @if ($paginator->firstItem())
            <span style="font-weight: 800; color: #1e293b;">{{ $paginator->firstItem() }}</span>
            s/d
            <span style="font-weight: 800; color: #1e293b;">{{ $paginator->lastItem() }}</span>
        @else
            {{ $paginator->count() }}
        @endif
        dari
        <span style="font-weight: 800; color: #1e293b;">{{ $paginator->total() }}</span>
        hasil
    </div>

    {{-- Pagination Buttons --}}
    <div style="display: flex; align-items: center; gap: 0.25rem;">
        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <span style="display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: 10px; background: #f1f5f9; color: #cbd5e1; cursor: not-allowed; border: 1px solid #e2e8f0;">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"/></svg>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" style="display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: 10px; background: white; color: #475569; border: 1px solid #e2e8f0; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.background='var(--primary)';this.style.color='white';this.style.borderColor='var(--primary)'" onmouseout="this.style.background='white';this.style.color='#475569';this.style.borderColor='#e2e8f0'">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"/></svg>
            </a>
        @endif

        {{-- Page Numbers --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span style="display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; font-size: 0.8125rem; color: #94a3b8; font-weight: 700;">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span style="display: inline-flex; align-items: center; justify-content: center; min-width: 36px; height: 36px; padding: 0 0.5rem; border-radius: 10px; background: var(--primary); color: white; font-size: 0.8125rem; font-weight: 800; box-shadow: 0 4px 12px rgba(43, 94, 167, 0.25);">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" style="display: inline-flex; align-items: center; justify-content: center; min-width: 36px; height: 36px; padding: 0 0.5rem; border-radius: 10px; background: white; color: #475569; font-size: 0.8125rem; font-weight: 700; border: 1px solid #e2e8f0; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.background='var(--primary-light)';this.style.color='var(--primary)';this.style.borderColor='var(--primary)'" onmouseout="this.style.background='white';this.style.color='#475569';this.style.borderColor='#e2e8f0'">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" style="display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: 10px; background: white; color: #475569; border: 1px solid #e2e8f0; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.background='var(--primary)';this.style.color='white';this.style.borderColor='var(--primary)'" onmouseout="this.style.background='white';this.style.color='#475569';this.style.borderColor='#e2e8f0'">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"/></svg>
            </a>
        @else
            <span style="display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: 10px; background: #f1f5f9; color: #cbd5e1; cursor: not-allowed; border: 1px solid #e2e8f0;">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"/></svg>
            </span>
        @endif
    </div>
</nav>
@endif
