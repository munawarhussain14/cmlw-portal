@if ($paginator->hasPages())
    
<nav class="paging" aria-label="Page navigation example">
    <ul class="pagination">
       
        @if ($paginator->onFirstPage())
            <li class="page-item disabled">
                <a class="page-link" >
                    <i class="fas fa-angle-double-left"></i>
                </a>
            </li>        
        @else
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->previousPageUrl() }}">
                    <i class="fas fa-angle-double-left"></i>
                </a>
            </li>
        @endif
      
        @foreach ($elements as $element)
           
            @if (is_string($element))
                <li class="disabled"><span>{{ $element }}</span></li>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="page-item active"><a class="page-link">{{$page}}</a></li>                        @else
                    <li class="page-item"><a class="page-link" href="{{ $url }}">{{$page}}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach
        
        @if ($paginator->hasMorePages())
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->nextPageUrl() }}">
                    <i class="fas fa-angle-double-right"></i>
                </a>
            </li>
        @else
            <li class="page-item disabled"><a class="page-link" href="#"><i class="fas fa-angle-double-right"></i></a></li>
        @endif
    </ul>
</nav>
@endif