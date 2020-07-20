@extends('_layouts.documentation')

@section('nav-menu')
    @include('_nav.menu', [
        'readMeUrl' => '/docs/core',
        'tutorials' => $page->navMenus->core->tutorials,
        'howTos' => $page->navMenus->core->howTos,
        'references' => $page->navMenus->core->references
    ])
@endsection
