@if (isset($items) && $items->hasPages())
    <div class="mt-4 d-flex flex-column align-items-center gap-3">
        <!-- Texte de pagination -->
        <div class="text-muted small">
            Affichage de <span class="fw-bold">{{ $items->firstItem() }}</span> à <span class="fw-bold">{{ $items->lastItem() }}</span> sur <span class="fw-bold">{{ $items->total() }}</span> résultats
        </div>
        
        <!-- Boutons de pagination (max 3 pages visibles) -->
        <nav aria-label="Pagination">
            <ul class="pagination pagination-sm mb-0">
                {{-- Bouton Précédent --}}
                @if ($items->onFirstPage())
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link">&laquo;</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link text-primary" href="{{ $items->previousPageUrl() }}" rel="prev">&laquo;</a>
                    </li>
                @endif

                {{-- Éléments de pagination --}}
                @php
                    $currentPage = $items->currentPage();
                    $lastPage = $items->lastPage();
                    
                    $start = max(1, $currentPage - 1);
                    $end = min($lastPage, $start + 2);
                    if ($end - $start < 2) {
                        $start = max(1, $end - 2);
                    }
                @endphp

                @if($start > 1)
                    <li class="page-item"><a class="page-link text-primary" href="{{ $items->url(1) }}">1</a></li>
                    @if($start > 2)
                        <li class="page-item disabled" aria-disabled="true"><span class="page-link">...</span></li>
                    @endif
                @endif

                @for ($i = $start; $i <= $end; $i++)
                    @if ($i == $currentPage)
                        <li class="page-item active" aria-current="page"><span class="page-link bg-primary border-primary text-white">{{ $i }}</span></li>
                    @else
                        <li class="page-item"><a class="page-link text-primary" href="{{ $items->url($i) }}">{{ $i }}</a></li>
                    @endif
                @endfor

                @if($end < $lastPage)
                    @if($end < $lastPage - 1)
                        <li class="page-item disabled" aria-disabled="true"><span class="page-link">...</span></li>
                    @endif
                    <li class="page-item"><a class="page-link text-primary" href="{{ $items->url($lastPage) }}">{{ $lastPage }}</a></li>
                @endif

                {{-- Bouton Suivant --}}
                @if ($items->hasMorePages())
                    <li class="page-item">
                        <a class="page-link text-primary" href="{{ $items->nextPageUrl() }}" rel="next">&raquo;</a>
                    </li>
                @else
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link">&raquo;</span>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
@endif
