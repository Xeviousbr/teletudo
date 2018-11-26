<!DOCTYPE html>
<html>
<head>
	<title>
        Tele Tudo - Documentação
	</title>
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
    <script>
        function Logar() {
            document.location.assign("http://www.tele-tudo.com/login");
        }

        function Cadastrar() {
            document.location.assign("http://www.tele-tudo.com/pessoa/create");
        }
    </script>
</head>
<div class="container">
<nav class="navbar navbar-inverse">
    <ul class="nav navbar-nav">
        <li><a href="{{ URL::to('produtos') }}">{{ Lang::get('menus.Produtos') }}</a></li>
        <li><a href="{{ URL::to('/servicos') }}">{{ Lang::get('menus.Servicos') }}</a></li>
        @if(Auth::check())
        <!--<li><a href="{{ URL::to('pessoa/edit') }}">Editar Cadastro</a></li>-->
        <li><a href="{{ route('pessoa.edit', Session::get('iduser')) }}">Editar Cadastro</a></li>
        @else
        <li><a href="{{ URL::to('login') }}">{{ Lang::get('menus.Login') }}</a></li>
        @endif
        <li><a href="{{ URL::to('contatos') }}">{{ Lang::get('menus.como') }}</a></li>
        @if(Auth::check())
        <li><a href="{{ URL::to('sair') }}">{{ Lang::get('menus.Deslogar') }}</a>
            @endif
    </ul>
</nav>
<?php
if(Auth::check()) {
    $ClsPessoa = new Pessoa;
    if ($ClsPessoa->EhDev(Session::get('iduser'))) {
    ?>
        <h1>Login</h1>
        Endereço: http://www.tele-tudo.com/operacoes<p>
        Parametros: op, user, pass
        <p style="margin-left: 40px;">op = 1</p><p>
    <p>
        Retornos: <p>
        <p style="margin-left: 40px;">Erro</p>
        <p style="margin-left: 40px;">DescErro</p>
        e caso erro seja zero<p>
        <p style="margin-left: 40px;">id</p>
        <p style="margin-left: 40px;">nome</p>
        <Br><Br><Br>Amanhã coloco o restante
    <?php
    } else {
        echo "<div class='alert alert-danger'><font size='5'>Esta área é apenas para usuários desenvolvedores</font></div>";
    }
} else {
?>
    <div class="alert alert-danger"><font size="5">Esta área é apenas para usuários registrados</font></div>
    <input type="button" id="btLogin" value="Login" onclick="Logar()" class="btn btn-primary " />
    <input type="button" id="btCadastrar" value="Cadastrar" onclick="Cadastrar()" class="btn btn-success " />
<?php
}
?>
</div>
</body>
<script type="text/javascript">
    var $_Tawk_API = {}, $_Tawk_LoadStart = new Date();
    (function () {
        var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];
        s1.async = true;
        s1.src = 'https://embed.tawk.to/55a73bfb84d307454c01fcd3/default';
        s1.charset = 'UTF-8';
        s1.setAttribute('crossorigin', '*');
        s0.parentNode.insertBefore(s1, s0);
    })();
</script>
</html>