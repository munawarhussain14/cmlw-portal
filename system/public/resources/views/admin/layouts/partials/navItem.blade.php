<li class="nav-item">
    <a href="{{$route}}" class="nav-link {{ (request()->segment(2) == $segment) ? 'active' : '' }}">
        <i class="{{$icon}}"></i>
        <p>
        {{$page}}
        </p>
    </a>
</li>