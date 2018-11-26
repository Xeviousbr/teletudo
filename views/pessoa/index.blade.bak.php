<!DOCTYPE html>
<html>
<head>
    <title>Tele Tudo</title>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
</head>
<body>
<div class="container">

    <nav class="navbar navbar-inverse">
        <ul class="nav navbar-nav">
            <li><a href="{{ URL::to('produtos') }}">{{ Lang::get('menus.Produtos') }}</a></li>
            <li><a href="{{ URL::to('/servicos') }}">{{ Lang::get('menus.Servicos') }}</a></li>
            <li><a href="{{ URL::to('login') }}">{{ Lang::get('menus.Login') }}</a>
            <li><a href="{{ URL::to('contatos') }}">{{ Lang::get('menus.como') }}</a></li>
        </ul>
    </nav>
    <img src="{{ Lang::get('menus.img') }}"/>

    <div class="alert alert-info">Seu cadastro foi realizado com sucesso</div>
    <div class="alert alert-success">Assim que existirem fornecedores na sua área de abrangência, será avisado por email</div>


</body>
</html>