@if ($paginator->hasPages())
  <nav class="sdb-pag-nav" role="navigation" aria-label="Pagination Navigation">
    {{-- Previous Page Link --}}
    @if ($paginator->onFirstPage())
      <span class="sdb-pag-btn disabled" aria-disabled="true">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
      </span>
    @else
      <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="sdb-pag-btn">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
      </a>
    @endif

    {{-- Pagination Elements --}}
    @foreach ($elements as $element)
      {{-- "Three Dots" Separator --}}
      @if (is_string($element))
        <span class="sdb-pag-dots">{{ $element }}</span>
      @endif

      {{-- Array Of Links --}}
      @if (is_array($element))
        @foreach ($element as $page => $url)
          @if ($page == $paginator->currentPage())
            <span class="sdb-pag-btn active" aria-current="page">{{ $page }}</span>
          @else
            <a href="{{ $url }}" class="sdb-pag-btn">{{ $page }}</a>
          @endif
        @endforeach
      @endif
    @endforeach

    {{-- Next Page Link --}}
    @if ($paginator->hasMorePages())
      <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="sdb-pag-btn">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
      </a>
    @else
      <span class="sdb-pag-btn disabled" aria-disabled="true">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
      </span>
    @endif
  </nav>
@endif

<style>
.sdb-pag-nav {
  display: inline-flex;
  align-items: center;
  gap: 4px;
}

.sdb-pag-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  min-width: 28px;
  height: 28px;
  padding: 0 6px;
  border-radius: 6px;
  font-size: 12px;
  font-weight: 600;
  color: #334155;
  background: #ffffff;
  border: 1px solid #e2e8f0;
  text-decoration: none;
  transition: all 0.15s;
}

.sdb-pag-btn:hover:not(.disabled):not(.active) {
  background: #f1f5f9;
  border-color: #cbd5e1;
  color: #047857;
}

.sdb-pag-btn.active {
  background: #047857;
  border-color: #047857;
  color: #ffffff;
}

.sdb-pag-btn.disabled {
  color: #cbd5e1;
  background: #f8fafc;
  cursor: not-allowed;
  border-color: #f1f5f9;
}

.sdb-pag-dots {
  padding: 0 4px;
  color: #94a3b8;
  font-size: 12px;
}
</style>
