<aside class="menu">
    <ul class="menu-list">
        <li><a href="{{$readMeUrl}}" class="{{ $page->isActive($readMeUrl) ? 'is-active' : '' }}">README</a></li>
    </ul>
    @if (isset($tutorials) && count($tutorials) > 0)
    <p class="menu-label">Tutorials</p>
    <ul class="menu-list">
        @foreach ($tutorials as $tutorial)
            <li><a href="{{$tutorial->path}}" class="{{ $page->isActive($tutorial->path) ? 'is-active' : '' }}">{{$tutorial->title}}</a></li>
        @endforeach
    </ul>
    @endif

    @if (isset($howTos) && count($howTos) > 0)
    <p class="menu-label">How Tos</p>
    <ul class="menu-list">
        @foreach ($howTos as $howTo)
            <li><a href="{{$howTo->path}}" class="{{ $page->isActive($howTo->path) ? 'is-active' : '' }}">{{$howTo->title}}</a></li>
        @endforeach
    </ul>
    @endif

    @if (isset($references) && count($references) > 0)
    <p class="menu-label">References</p>
    <ul class="menu-list">
        @foreach ($references as $reference)
            <li><a href="{{$reference->path}}" class="{{ $page->isActive($reference->path) ? 'is-active' : '' }}">{{$reference->title}}</a></li>
        @endforeach
    </ul>
    @endif
</aside>
