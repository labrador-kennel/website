<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="description" content="{{ $page->description ?? $page->siteDescription }}">

        <meta property="og:site_name" content="{{ $page->siteName }}"/>
        <meta property="og:title" content="{{ $page->title ?  $page->title . ' | ' : '' }}{{ $page->siteName }}"/>
        <meta property="og:description" content="{{ $page->description ?? $page->siteDescription }}"/>
        <meta property="og:url" content="{{ $page->getUrl() }}"/>
        <meta property="og:image" content="/assets/img/logo.png"/>
        <meta property="og:type" content="website"/>

        <meta name="twitter:image:alt" content="{{ $page->siteName }}">
        <meta name="twitter:card" content="summary_large_image">

        <title>{{ $page->siteName }}{{ $page->title ? ' | ' . $page->title : '' }}</title>

        <link rel="home" href="{{ $page->baseUrl }}">
        <link rel="icon" href="/assets/img/logo.png">

        @stack('meta')

        @if ($page->production)
            <!-- Insert analytics code here -->
        @endif

        <link rel="stylesheet" href="{{ mix('css/main.css', 'assets/build') }}">
    </head>

    <body>
        <header>
            <nav class="navbar is-primary" role="navigation" aria-label="main navigation">
                <div class="navbar-brand">
                    <a class="navbar-item navbar-item--title" href="/">
                        <img src="/assets/img/logo-white.png" />
                        <span class="navbar-item--title-site-name">
                            labrador-kennel.io
                        </span>
                    </a>
                </div>
            </nav>
        </header>

        <main role="main">
            @yield('body')
        </main>

        <script src="{{ mix('js/main.js', 'assets/build') }}"></script>
        <script src="https://unpkg.com/ionicons@5.1.2/dist/ionicons.js"></script>

        @stack('scripts')

        <footer class="footer is-primary">
            <div class="content has-text-centered">
                <div class="built-by-tagline">
                    Built with <ion-icon name="heart"></ion-icon> and <img src="/assets/img/amp-logo.png" alt="Amp logo" height="16px" width="16px" /> by <a href="https://cspray.io">Charles Sprayberry</a>
                </div>
                <div class="logo-disclaimer">
                    Organization logo by <a href="http://www.freepik.com">Freepik</a> from <a href="http://www.flaticon.com">www.flaticon.com</a> is licensed by <a href="http://creativecommons.org/licenses/by/3.0/">CC BY 3.0</a>
                </div>
            </div>
        </footer>
    </body>
</html>
