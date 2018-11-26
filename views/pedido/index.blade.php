<?php $idUser = 0; ?>
@extends('layouts.padrao')

@section('content')   

<script Language="JavaScript">
    var nav = navigator.appVersion;
    var A = nav.indexOf("Android");
    var nH = "h1";
    if (A<1) {
    } else {
        nH = "h4";
    }
    document.write("<"+nH+">Confirmação do acionamento da entrega</"+nH+">");
</script>
<?php
$cEntrega = new Entrega();
$idPedido = $_GET['IDPED'];
Session::put('IDPED', $idPedido);

$vValorTotal = $cEntrega->getValorTotal($idPedido);

Session::put('VLRTOTAL', $vValorTotal);
$ValorTotal = number_format($vValorTotal, 2, ',', '.');
?>
    <div class="alert alert-success">Compras R$ {{ number_format($cEntrega->getCompras($idPedido), 2, ',', '.') }}</div>
    <div class="alert alert-success">Tele-Entrega R$ {{ $cEntrega->getNossaCobranca() }}</div>
    <div class="alert alert-success">Valor Total R$ {{ $ValorTotal }}</div>
    <input id="hPd" type="text" hidden="hidden" value="{{ $idPedido }}" /></p>    
    <div>
    <table width="79%">
        <tr>
            <td><input type="submit" id="btAltera" value="Alterar a Compra" onclick="Alterar()" disabled class="btn btn-default" /></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td width="563px">&nbsp;</td>
            <td><input type="submit" id="btMaisItens" width="132px" value="Adicionar Mais Itens" onclick="Mais()" class="btn btn-warning" /></td>
        </tr>
        <tr>
            <form name="frmCanc" action="http://www.tele-tudo.com/produtos" method="get">
                <td height="30px"><input type="submit" id="btCancelar" width="125px" value="Cancelar" disabled class="btn btn-default" /></td>
            </form>
            <td height="30px"></td>
            <td height="30px"></td>
            <td height="30px"></td>            

            <td height="30px"><input type="submit" id="btPagamento" value="Finalização da compra" onclick="Pagar()" width="133px" text-align="center" class="btn btn-success" /></td>            
        </tr>        
    </table>
    <br/>
    <br />

        <?php
        $idPedido = $_GET['IDPED'];
        if (isset($_GET['tpEnt'])) {
            $tpEnt = $_GET['tpEnt'];
            /* echo 'tpEnt = '.$tpEnt.'<Br>';
            echo 'tpEnt pelo GET'; die; */
        } else {
            $cSessao = new Sessao;
            $tpEnt = $cSessao->tpEntrega($idPedido);
            /* echo 'tpEnt = '.$tpEnt.'<Br>';
            echo 'tpEnt pelo cSessao->tpEntrega'; die; */
        }
        ?>
        <script>
            function Pagar() {
                var tpEnt = <?php echo $tpEnt; ?>;
                var idPedido = <?php echo $idPedido; ?>;
                // alert(tpEnt);
                if (tpEnt==0) {
                    // PLAY DELIVERY
                    document.location.assign("http://www.tele-tudo.com/formas?ped="+idPedido);
                } else {
                    // TELE-ENTREGA PRÓPRIA
                    document.location.assign("http://www.tele-tudo.com/pagtodireto");
                    // document.location.assign("http://www.tele-tudo.com/resumo");
                }
            }

            function Mais() {
                var ped = document.getElementById("hPd").value;
                document.location.assign("http://www.tele-tudo.com/produtos?ped="+ped);
            }

        </script>


@stop