<!-- Arquivo app/views/layouts/padrao.blade.php -->
<!doctype html>
<html lang="pt-br">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />

    <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css">-->
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
</head>
<style type="text/css">
    .btn-facebook {
        color:#fff!important;
        background-color:#4863ae;
    }
    .Mouse {
        cursor:hand;
    }
</style>
<script Language="JavaScript">
    var Slogo = localStorage.getItem('SemLogo');
    var nav = navigator.appVersion;
    var A = nav.indexOf("Android");
    var Mobile = 0;
    if (A<1) {
        Mobile = 0;
        // document.write("<div class='container'>");
    } else {
        Mobile = 1;
    }
</script>
<?php
$UA = $_SERVER['HTTP_USER_AGENT'];
if (strrpos($UA, "Windows")) {
    $Img = "fundo.jpg";
} else {
    $Img = "fundocel.jpg";
}
?>
<body style="background-image: url('http://www.tele-tudo.com/download/{{$Img}}'); background-attachment: fixed">
<script Language="JavaScript">
    if (A<1) {
        document.write("<div class='container'>");
    }
</script>
<?php
$A=''; $AL='';
if (isset($_GET['A'])) {
    $AL='?A=1';
    $A='1';
} else {
    $UA = $_SERVER['HTTP_USER_AGENT'];
    if (!strrpos($UA, "Windows")) {
        $AL='?A=1';
    }
}
?>
<form name="entrar" action="http://www.tele-tudo.com/entrar" method="POST">
<table border="0" width="100%" cellspacing="0" cellpadding="0">
    <tbody>
        <tr>
            <td width="20%"><a href="index.htm"><img border="0" src="{{ URL::to('download/LOGOP.png') }}" width="200" height="90"></a></td>
            <td width="45%">
            <p align="center">
            <a href="aplicativo.htm">
            <img border="0" src="{{ URL::to('download/disponappeq.png') }}" width="200" height="68"></a>&nbsp;&nbsp;
            <a target="_blank" href="https://chat.whatsapp.com/FKgKLGaK648FLm8zkQG25B">
            <img border="0" src="{{ URL::to('download/Whatspeq.png') }}" width="275" height="61"></a></p></td>
            <td width="40%">
                <img border="0" src="{{ URL::to('download/usuario.png') }}" width="100" height="15">
                <input type="text" name="user" size="20"><br>
                <img border="0" src="{{ URL::to('download/senha.png') }}" width="100" height="15">
                <input type="password" name="senha" size="2">
                <br>
                {{ Form::checkbox('remember', 'remember', true) }}
                <img border="0" src="{{ URL::to('download/lembrar.png') }}" width="100" height="15">
                <br>
                <img border="0" src="{{ URL::to('download/fundo%20menu.png') }}" width="10" height="10">
                <img border="0" src="{{ URL::to('download/fundo%20menu.png') }}" width="10" height="10">
                <img border="0" src="{{ URL::to('download/fundo%20menu.png') }}" width="10" height="10">
                <img border="0" src="{{ URL::to('download/btcadastrar.png') }}" width="100" height="30" onclick="document.location.assign('http://www.tele-tudo.com/pessoa/create')" style="cursor:hand" >
                <img border="0" src="{{ URL::to('download/fundo%20menu.png') }}" width="10" height="10">
                <img border="0" src="{{ URL::to('download/fundo%20menu.png') }}" width="10" height="10">
                <img border="0" src="{{ URL::to('download/fundo%20menu.png') }}" width="10" height="10">
                <img border="0" src="{{ URL::to('download/btentrar.png') }}" width="100" height="30" onclick="document.entrar.submit()" style="cursor:hand" >
                <br>
            </td>
        </tr>
    </tbody>
</table>
</form>
{{ Form::open(array('url' => 'formulario')) }}
<script Language="JavaScript">
    if (Mobile==1) {
        document.write("<div class='container'>");
    }
</script>

<?php
$Img = Lang::get('menus.img');
?>
@yield('content')
<?php
$Chat=1;
if (Session::has('SemChat')) {
    $SemChat=Session::get('SemChat');
    if ($SemChat==1) {
        $Chat=0;
    }
}
?>
<script>
    function Logar() {
        document.location.assign("http://www.tele-tudo.com/login");
    }

    /* function Cadastrar() {
        document.location.assign("http://www.tele-tudo.com/pessoa/create");
    } */
<?php
if ($Chat==1) {
?>
    var $_Tawk_API = {}, $_Tawk_LoadStart = new Date();
    (function () {
        var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];
        s1.async = true;
        s1.src = 'https://embed.tawk.to/55a73bfb84d307454c01fcd3/default';
        s1.charset = 'UTF-8';
        s1.setAttribute('crossorigin', '*');
        s0.parentNode.insertBefore(s1, s0);
    })();
<?php
}
?>
</script>
</body>
</html>