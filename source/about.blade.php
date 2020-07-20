@extends('_layouts.default')

@section('body')
<section class="container">
    <h1>About Labrador</h1>
    <p>
        Ever since I got into PHP I have enjoyed writing my own framework... at least as much as one can enjoy this type
        of thing. I started writing my first custom framework, named SprayFire, nearly 10 years ago. The experience of that
        first framework taught me a <em>lot</em> about PHP and how common frameworks do the things that they do. This
        experience has even been helpful in languages other than PHP and I wouldn't change having went through it.
    </p>

    <p>
        However, the end result of SprayFire wasn't as modular as I wanted it to be and did not include some of the ideas
        and concepts I learned as I matured as a software engineer. So, I started rewriting SprayFire in a more modular
        architecture. Labrador 1.x and 2.x were the beginnings of this modularization effort. Though I was happy with where
        Labrador was it didn't feel like the framework was doing anything special or different enough to warrant its
        existence.
    </p>

    <p>
        Then I discovered <a href="https://amphp.org">Amp</a>, a PHP library that makes working with asynchronous code
        easy and fun. I immediately dived into the ecosystem and knew that I had to refactor Labrador to be an asynchronous
        framework written on top of Amp. After a long, winding road Labrador 3.x is my vision for how to architect asynchronous
        PHP applications with clean, well-tested code.
    </p>

    <p>
        Labrador gets its name from my dog, Nick, that I started caring for right around the time I started this project.
        Much like Nick, Labrador is meant to be friendly, easy-going, and a joy to be around!
    </p>
</section>
@endsection
