@php
if (! isset($scrollTo)) {
    $scrollTo = 'body';
}

$scrollIntoViewJsSnippet = ($scrollTo !== false)
    ? <<<JS
       (\$el.closest('{$scrollTo}') || document.querySelector('{$scrollTo}')).scrollIntoView()
    JS
    : '';
@endphp

<div>
    @if ($paginator->hasPages())
        <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
            {{-- Mobile --}}
            <div class="flex justify-between flex-1 sm:hidden">
                @if ($paginator->onFirstPage())
                    <span class="btn btn-sm btn-secondary opacity-50 cursor-not-allowed">{!! __('pagination.previous') !!}</span>
                @else
                    <button type="button" wire:click="previousPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled" class="btn btn-sm btn-secondary">
                        {!! __('pagination.previous') !!}
                    </button>
                @endif

                @if ($paginator->hasMorePages())
                    <button type="button" wire:click="nextPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled" class="btn btn-sm btn-secondary">
                        {!! __('pagination.next') !!}
                    </button>
                @else
                    <span class="btn btn-sm btn-secondary opacity-50 cursor-not-allowed">{!! __('pagination.next') !!}</span>
                @endif
            </div>

            {{-- Desktop --}}
            <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-500">
                        Menampilkan
                        <span class="font-semibold text-gray-700">{{ $paginator->firstItem() }}</span>
                        &ndash;
                        <span class="font-semibold text-gray-700">{{ $paginator->lastItem() }}</span>
                        dari
                        <span class="font-semibold text-gray-700">{{ $paginator->total() }}</span>
                        data
                    </p>
                </div>

                <div>
                    <span class="inline-flex items-center gap-1">
                        {{-- Prev --}}
                        @if ($paginator->onFirstPage())
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-300 bg-gray-50 border border-gray-100 cursor-not-allowed">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                            </span>
                        @else
                            <button type="button" wire:click="previousPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-500 bg-white border border-gray-200 hover:bg-gray-50 hover:border-teal-300 transition-all">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                            </button>
                        @endif

                        {{-- Page numbers --}}
                        @foreach ($elements as $element)
                            @if (is_string($element))
                                <span class="inline-flex items-center justify-center w-8 h-8 text-sm text-gray-400">&hellip;</span>
                            @endif
                            @if (is_array($element))
                                @foreach ($element as $page => $url)
                                    <span wire:key="paginator-{{ $paginator->getPageName() }}-page{{ $page }}">
                                        @if ($page == $paginator->currentPage())
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-sm font-semibold text-white" style="background: linear-gradient(135deg, #0d9488, #0f766e);">{{ $page }}</span>
                                        @else
                                            <button type="button" wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-sm text-gray-600 bg-white border border-gray-200 hover:bg-gray-50 hover:border-teal-300 transition-all">{{ $page }}</button>
                                        @endif
                                    </span>
                                @endforeach
                            @endif
                        @endforeach

                        {{-- Next --}}
                        @if ($paginator->hasMorePages())
                            <button type="button" wire:click="nextPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-500 bg-white border border-gray-200 hover:bg-gray-50 hover:border-teal-300 transition-all">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>
                            </button>
                        @else
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-300 bg-gray-50 border border-gray-100 cursor-not-allowed">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>
                            </span>
                        @endif
                    </span>
                </div>
            </div>
        </nav>
    @endif
</div>
