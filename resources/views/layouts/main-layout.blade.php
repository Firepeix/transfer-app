<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <title>Transfer APP</title>
</head>
<body class="antialiased">
@stack('styles')
<nav class="grey darken-4" role="navigation">
    <div class="nav-wrapper container"><a id="logo-container" href="#" class="brand-logo">Transfer APP</a>
        <ul class="right hide-on-med-and-down">
            <li><a href="#">Cadastar Usuario: Lojista</a></li>
            <li><a href="#">Cadastar Usuario: Cliente</a></li>
            <li><a href="#">Realizar Transferência</a></li>
        </ul>
    </div>
</nav>
<div class="container">
    <div class="section">
        @yield('content')
    </div>
</div>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
<script src="{{asset('js/simple-mask-money.js')}}"></script>
<script src="{{asset('js/sweetalert2.js')}}"></script>
<script type="text/javascript">
  window.addEventListener('DOMContentLoaded', init);
  function init () {
    M.AutoInit(document.querySelector('body'));
  }

  function toReal(value) {
    return String(value / 100).replaceAll('.', ',');
  }
</script>
@stack('javascript')
</body>
</html>
