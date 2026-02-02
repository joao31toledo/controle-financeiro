<!doctype html>
<html lang="pt-BR" data-bs-theme="auto">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Controle de Despesas')</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @stack('styles')

    <style>
        body { padding-top: 70px; }
    </style>
  </head>
  <body>

    <nav class="navbar navbar-expand-lg fixed-top bg-body-tertiary border-bottom">
      <div class="container">
        <a class="navbar-brand" href="{{ route('despesas.index') }}">
            <i class="bi bi-wallet2"></i> Minhas Finanças
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
          <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav me-auto">
            <li class="nav-item">
              <a class="nav-link" href="{{ route('despesas.index') }}">Despesas</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('despesas.create') }}">Nova Despesa</a>
            </li>
          </ul>
          <button class="btn btn-outline-secondary btn-sm" id="btnTheme">
            <i class="bi bi-moon-stars-fill"></i> Tema
          </button>
        </div>
      </div>
    </nav>

    <main class="container mt-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    @stack('scripts')

    <script>
        const html = document.documentElement;
        const btnTheme = document.getElementById('btnTheme');
        const getPreferredTheme = () => {
            const storedTheme = localStorage.getItem('theme');
            if (storedTheme) return storedTheme;
            return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        };
        const setTheme = function (theme) {
            html.setAttribute('data-bs-theme', theme);
            localStorage.setItem('theme', theme);
        };
        setTheme(getPreferredTheme());
        btnTheme.addEventListener('click', () => {
            const currentTheme = html.getAttribute('data-bs-theme');
            setTheme(currentTheme === 'dark' ? 'light' : 'dark');
        });
    </script>
  </body>
</html>