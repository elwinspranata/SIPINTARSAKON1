@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" style="padding: 1.5rem 0; border-top: 1px solid #e2e8f0;">
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 2rem;">
            {{-- Results Info --}}
            <div style="font-size: 0.8125rem; color: #64748b; font-weight: 600; flex: 0 0 auto;">
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
            <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; justify-content: center;">
                {{-- Previous Button --}}
                @if ($paginator->onFirstPage())
                    <span
                        style="display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 8px; background: #f1f5f9; color: #cbd5e1; cursor: not-allowed; border: 1px solid #e2e8f0; flex-shrink: 0;">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M15 19l-7-7 7-7" />
                        </svg>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                        style="display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 8px; background: white; color: #475569; border: 1px solid #d1d5db; text-decoration: none; transition: all 0.2s; flex-shrink: 0; cursor: pointer;"
                        onmouseover="this.style.background='var(--primary)';this.style.color='white';this.style.borderColor='var(--primary)';this.style.boxShadow='0 2px 8px rgba(43, 94, 167, 0.15)'"
                        onmouseout="this.style.background='white';this.style.color='#475569';this.style.borderColor='#d1d5db';this.style.boxShadow='none'">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                @endif

                {{-- Page Numbers --}}
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span
                            style="display: inline-flex; align-items: center; justify-content: center; min-width: 40px; height: 40px; padding: 0 0.25rem; font-size: 0.8125rem; color: #94a3b8; font-weight: 700;">{{ $element }}</span>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span
                                    style="display: inline-flex; align-items: center; justify-content: center; min-width: 40px; height: 40px; padding: 0 0.5rem; border-radius: 8px; background: #1B6B3A; color: white; font-size: 0.8125rem; font-weight: 800; box-shadow: 0 4px 12px rgba(27, 107, 58, 0.2); flex-shrink: 0; font-weight: 700;">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}"
                                    style="display: inline-flex; align-items: center; justify-content: center; min-width: 40px; height: 40px; padding: 0 0.5rem; border-radius: 8px; background: white; color: #475569; font-size: 0.8125rem; font-weight: 700; border: 1px solid #d1d5db; text-decoration: none; transition: all 0.2s; flex-shrink: 0; cursor: pointer;"
                                    onmouseover="this.style.background='#f3f4f6';this.style.borderColor='#9ca3af'"
                                    onmouseout="this.style.background='white';this.style.borderColor='#d1d5db'">{{ $page }}</a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Button --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                        style="display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 8px; background: white; color: #475569; border: 1px solid #d1d5db; text-decoration: none; transition: all 0.2s; flex-shrink: 0; cursor: pointer;"
                        onmouseover="this.style.background='var(--primary)';this.style.color='white';this.style.borderColor='var(--primary)';this.style.boxShadow='0 2px 8px rgba(43, 94, 167, 0.15)'"
                        onmouseout="this.style.background='white';this.style.color='#475569';this.style.borderColor='#d1d5db';this.style.boxShadow='none'">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                @else
                    <span
                        style="display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 8px; background: #f1f5f9; color: #cbd5e1; cursor: not-allowed; border: 1px solid #e2e8f0; flex-shrink: 0;">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M9 5l7 7-7 7" />
                        </svg>
                    </span>
                @endif
            </div>
        </div>
    </nav>
@endif