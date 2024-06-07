<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>STPG-FEB - @yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/materialize.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('storage/static/images/favicon.ico') }}">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
    <header class="print-hidden">
        <nav>
            <div class="nav-wrapper black">
              <a href="{{ route('site.index') }}" class="brand-logo waves-effect waves-light">
                <img src="{{ asset('storage/static/images/logo.jpg') }}" class="responsive-img brand-logo">
              </a>
              <ul id="nav-mobile" class="right hide-on-med-and-down">
                <li class="waves-effect waves-light"><a href="{{ route('site.solicitacoes') }}">Solicitações</a></li>
                <li class="waves-effect waves-light"><a href="{{ route('import_menu') }}">Importação</a></li>
                <li class="waves-effect waves-light"><a href="{{ route('site.relatorio.index') }}">Relatório por programa</a></li>
                <li class="waves-effect waves-light"><a href="{{ route('site.programas.show') }}">Programas</a></li>
                <li class="waves-effect waves-light"><a href="{{ route('site.solicitantes') }}">Solicitantes</a></li>
              </ul>
            </div>
        </nav>
    </header>

    <main>
      <div class="print-only">
        <img src="{{ asset('storage/static/images/logo_doc_header.jpg') }}" class="logo-doc-header">
      </div>
      <div class="section-margin-bottom side-margins">
        <p>@yield('content')</p>
      </div>
    </main>

    @if ( $msg = Session::get('fail') || $errors->any() )
        @include('messages.fail')
    @elseif ( $msg = Session::get('success') )
        @include('messages.success')
    @endif
    
    <footer class="page-footer blue lighten-2 print-hidden">
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
    {{-- <div class="print-only footer-container">
      <div class="container">
        <div class="row">
          <div class="col s6 left">
            <img src="{{ asset('storage/static/images/logo_doc_footer.jpg') }}" class="logo-doc-footer">
          </div>
          <div class="col s6">
            <div class="text-footer">
              Faculdade de Engenharia de Bauru – Pós-graduação<br>
              Av. Eng. Luiz Edmundo Carrijo Coube, 14-01  17033-360  Bauru - SP<br>
              tel. (14) 3103-6108  spg@feb.unesp.br   www.feb.unesp.br
            </div>
          </div>
        </div>
      </div>
    </div> --}}
    <script src="{{ asset('js/materialize.min.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
    <script src="{{ asset('js/jquery.js') }}"></script>
</body>
</html>