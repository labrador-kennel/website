@extends('_layouts.default')

@section('body')
<section>
    <div class="hero">
        <div class="hero-body">
            <div class="container">
                <div class="hero-titles">
                    <h1 class="title">
                        Labrador Kennel
                    </h1>
                    <h2 class="subtitle">
                        A suite of well-tested, cleanly-separated asynchronous packages built on top of <a href="https://www.amphp.org">Amp</a> and PHP 7+.
                    </h2>
                    <div class="actions">
                        <a href="https://github.com/labrador-kennel" class="button is-primary">
                            GitHub
                        </a>
                        <a href="/about" class="button is-text">
                            About
                        </a>
                    </div>
                </div>
                <figure class="image hero-image">
                    <img src="/assets/img/logo.png"  />
                </figure>
            </div>
        </div>
    </div>
</section>

<section class="container">
    <div class="lk-home--package-list">
        @foreach($page->packages as $package)
            <div class="lk-home--package-list-package">
                <div class="card">
                    <div class="card-header">
                        <div class="card-header-title">
                            {{$package->name}}
                        </div>
                    </div>
                    <div class="card-content">
                        {!! $package->description !!}
                    </div>
                    <div class="card-footer">
                        @if ($package->docsUrl)
                        <a href="{{$package->docsUrl}}" class="card-footer-item">
                            <ion-icon name="book-outline"></ion-icon>
                            Docs
                        </a>
                        @endif
                        <a href="{{$package->githubUrl}}" class="card-footer-item">
                            <ion-icon name="logo-github"></ion-icon>
                            GitHub
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>
@endsection
