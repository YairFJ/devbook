<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <title>Home</title>
</head>
<body>
   <header>
        <h1 class="title-nav">DEVBOOK</h1>
        <nav>
            @if (Route::has('login'))
                <nav class="-mx-3 flex flex-1 justify-end">
                    @auth
                        <a
                            href="{{ url('/dashboard') }}"
                            class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                        >
                            Dashboard
                        </a>
                    @else
                        <a
                            href="{{ route('login') }}"
                            class="rounded-md px-3 py-2 text-white ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white" id="login-btn"
                        >
                            Log in
                        </a>

                        @if (Route::has('register'))
                            <a
                                href="{{ route('register') }}"
                                class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white" id="register-btn"
                            >
                                Register
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </nav>
    </header>
    <main>
        <h1 class="title-main">
            <span>W</span><span>E</span><span>L</span><span>C</span><span>O</span><span>M</span><span>E</span>
            <span> </span><span>T</span><span>O</span><br>
            <span>D</span><span>E</span><span>V</span><span>B</span><span>O</span><span>O</span><span>K</span>
        </h1>

        <ul class="titles-list">
            <div class="list-t">
                <li><h2 class="subtitle-main">This is a special social network for developers</h2></li>
                <li><h2 class="subtitle2-main">FEEL FREE TO USE OUR APP</h2></li>
            </div>
            <li><img class="logo" src="{{ asset('DevBook 1.png') }}" alt=""></li>
        </ul>
    </main>

    <footer>
        <p class="title-footer">SINCE 2024</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>