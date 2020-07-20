@extends('_layouts.documentation')

@section('nav-menu')
    @include('_nav.menu', [
        'readMeUrl' => '/docs/http-cors',
        'tutorials' => $page->navMenus->httpCors->tutorials,
        'howTos' => $page->navMenus->httpCors->howTos,
        'references' => $page->navMenus->httpCors->references
    ])
@endsection
