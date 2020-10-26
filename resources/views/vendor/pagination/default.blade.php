{{-- Vendor / pagination / default.php --}}

{{-- Bootsrap --}}
<div class="container-fluid">
  <div class="row">
    <div class="col-12">

      {{-- Pagination --}}
      <div class="wrapper_pagination">
        @if ($paginator->hasPages())
            <nav class="pagination_default d-flex justify-content-center align-items-center">
                <ul class="pagination rounded-0">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <li class="disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                            <span class="fas fa-chevron-left" aria-hidden="true"></span>
                        </li>
                    @else
                        <li>
                            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">
                              <span class="fas fa-chevron-left"></span>
                            </a>
                        </li>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <li class="disabled" aria-disabled="true"><span>{{ $element }}</span></li>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <li class="active" aria-current="page"><span>{{ $page }}</span></li>
                                @else
                                    <li><a href="{{ $url }}">{{ $page }}</a></li>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <li>
                            <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">
                              <span class="fas fa-chevron-right"></span>
                            </a>
                        </li>
                    @else
                        <li class="disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                            <span class="fas fa-hand-point-right" aria-hidden="true"></span>
                        </li>
                    @endif
                </ul>
            </nav>
        @endif
      </div>
      {{-- end Pagination --}}

    </div>
  </div>
</div>
{{-- end Bootsrap --}}
