<!DOCTYPE html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=euc-jp">
    ?>            
    <title>Pagamento</title>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">

    <script src="//cdnjs.cloudflare.com/ajax/libs/numeral.js/1.4.5/numeral.min.js"></script>

</head>
<?php

?>
<body>

<div class="container">

    <nav class="navbar navbar-inverse">
        <ul class="nav navbar-nav">
            <li><a href="{{ URL::to('produtos') }}">{{ Lang::get('menus.Produtos') }}</a></li>
            <li><a href="{{ URL::to('/servicos') }}">{{ Lang::get('menus.Servicos') }}</a></li>

            @if(Auth::check())
                {{--<li><a href="{{ URL::to('operacoes') }}">{{ Lang::get('menus.Operacoes') }}</a></li>--}}
                {{--<li><a href="{{ URL::to('confirma') }}">{{ Lang::get('menus.confirma') }}</a></li>--}}
                <li><a href="{{ URL::to('sair') }}">{{ Lang::get('menus.Deslogar') }}</a>
            @else
                <li><a href="{{ URL::to('login') }}">{{ Lang::get('menus.Login') }}</a>
            @endif

        </ul>
    </nav>
    <img src="http://cdn-img1.imagechef.com/w/150413/anmadf942b08f30c0fd.gif" />

    <h1>Pagamento</h1>
    
    <?php

    echo "aqui(pagamento)"; die;

    $idPed= Session::get('IDPED');
    $ConsTotal = DB::table('pedido')
	->select('Valor')       
	->where('idPed','=',$idPed)
	->first();          													              
    $sTotal = number_format($ConsTotal->Valor,2,',','.');
    ?>    
        <div id="divTotal"><input id="txTotal" readonly="readonly" type="text" value=" Valor Total R$ {{ $sTotal }} " /><br />
        <input id="txTotal0" readonly="readonly" type="text" width="261px" value=" Tempo previsto para a entrega: 30 Min " /></div>
        <div class="alert alert-danger">Em breve pagamentos por BitCoin</div>
	<img src="http://4.bp.blogspot.com/-1VkeUI8AVr0/Vl5FOE9_bNI/AAAAAAAAAEQ/5mdRUEXJax0/s640/InstantBitcoin.jpg" />
	<br />
</div>

<table class="table table-striped table-bordered">
    </div>
    <p>&nbsp;</p>
</body>

<!--Start of Tawk.to Script-->
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

    var QtdItens = document.getElementById("txQtdItens").value;
    if (QtdItens == 1) {
        AjustaTotal();
    }
</script>
<!--End of Tawk.to Script-->