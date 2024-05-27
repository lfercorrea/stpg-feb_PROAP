<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>STPG-FEB</title>
    <link rel="stylesheet" href="{{ asset('css/materialize.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('storage/static/images/favicon.ico') }}">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
    <header>
        <nav>
            <div class="nav-wrapper black">
              <a href="{{ route('site.index') }}" class="brand-logo waves-effect waves-light">
                <img src="{{ asset('storage/static/images/logo.jpg') }}" class="responsive-img brand-logo">
              </a>
              <ul id="nav-mobile" class="right hide-on-med-and-down">
                <li><a href="/">PROAP</a></li>
              </ul>
            </div>
        </nav>
    </header>

    <main>
      <div class="section-margin-bottom side-margins">
        <p>@yield('content')</p>
      </div>
    </main>

    @if ( $msg = Session::get('fail') || $errors->any() )
        @include('messages.fail')
    @elseif ( $msg = Session::get('success') )
        @include('messages.success')
    @endif

    <footer class="page-footer blue lighten-2">
      <div class="container">
        <div class="row">
          <div class="col l6 s12">
            <p class="black-text">
              <i>
                "O melhor a se fazer, às vezes, é não fazer porra nenhuma."
              </i>
              <br>
              <b>Autor brilhante desconhecido</b>
            </p>
          </div>
        </div>
      </div>
      <div class="footer-copyright red black">
        <div class="container">
        <p>&copy; {{ date('Y') }} STPG-FEB. Nenhum direito reservado.</p>
        </div>
      </div>
    </footer>
    <script src="{{ asset('js/materialize.min.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
    <script src="{{ asset('js/jquery.js') }}"></script>
</body>
</html>