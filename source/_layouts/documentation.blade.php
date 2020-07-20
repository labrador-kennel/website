@extends('_layouts.default')

@section('body')
<section class="container">
    <div class="columns">
        <div class="column is-one-fifth">
            @yield('nav-menu')
        </div>

        <div class="column">
            <article class="lk-docs--article">
                @yield('content')
            </article>
        </div>
    </div>
</section>
@endsection
