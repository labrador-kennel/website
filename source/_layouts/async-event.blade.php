@extends('_layouts.documentation')

@section('nav-menu')
    @include('_nav.menu', [
        'readMeUrl' => '/docs/async-event',
        'tutorials' => $page->navMenus->asyncEvent->tutorials,
        'howTos' => $page->navMenus->asyncEvent->howTos,
        'references' => $page->navMenus->asyncEvent->references
    ])
@endsection
