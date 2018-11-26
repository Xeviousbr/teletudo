@extends('layouts.padrao')
<title>Contas Bancárias</title>
@section('content')
<?php
$cContasBanc = new ContasBancarias();
if (isset($_GET['op'])) {
    $op = $_GET['op'];
    if ($op==1) {
        $cContasBanc->destroy($_GET['del']);
    } else {
        $a = $_GET['a'];
        $c = $_GET['c'];
        $i = $_GET['i'];
        $b = $_GET['b'];
        $banco = "' ".$b."'";
        $sql = "select cod from bancos where banco = ".$banco." or apelido = '".$b."'";
        $qry = DB::select( DB::raw($sql));
        $cContasBanc->Atualiza($i, $qry[0]->cod, $a, $c);
    }
    ?>
    <script>
        document.location.assign('http://www.tele-tudo.com/contasbancarias');
    </script>
    <?php
} else {
    $iduser = Auth::id();

     if ($iduser==3) {
        // $iduser=31;   // Coqueiro
        // $iduser=40;   // Roger
        $iduser=265; // TeleRefeições
    }

    $cForn = new Fornecedor();
    $cForn->SetIdPessoa($iduser);
    $idEmpresa = $cForn->getidEmpresa();
    $nmEmpresa = $cContasBanc->nmEmpresa($idEmpresa);
    ?>
<div class="alert alert-minimal alert-warning nomargin">
    <button class="close" data-dismiss="alert">×</button>
    <h1><i class="fa fa-info"></i>Contas Bancárias da empresa: {{$nmEmpresa}}</h1>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-striped"  id="tabelaContas">
        <thead>
        <tr>
            <th><i class="fa fa-building pull-right hidden-xs"></i> Banco</th>
            <th><i class="fa fa-building pull-right hidden-xs"></i> Agência</th>
            <th><i class="fa fa-building pull-right hidden-xs"></i> Conta</th>
            <th><i class="fa fa-building pull-right hidden-xs"></i> Operações</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $regs = $cContasBanc->Contas($idEmpresa);
        $nr=0;
        foreach ($regs as $reg) {
            echo "<tr>";
            echo "<td id='tdB".$nr."'>".trim($reg->banco)."</td>";
            echo "<td id='tdA".$nr."'>".$reg->Agencia."</td>";
            echo "<td id='tdC".$nr."'>".$reg->Conta."</td>";
            $dados="\"".$reg->banco.';'.$reg->Agencia.';'.$reg->Conta."\"";
            echo "<td align='center'><input name='btEditar' type='button' onclick='edita(".$reg->id.",".$dados.")' value='Editar'><input name='btExcluir' type='button' onclick='deletar(".$reg->id.")' value='excluir'></td>";
            echo "</tr>";
            $nr++;
        }
        ?>
        </tbody>
    </table>
</div>
<form action="http://tele-tudo.com/contasbancarias" method="post" id="frmConBan">
    {{--<input name="_method" type="hidden" value="PUT">--}}
    <table style="width: 100%">
        <tr>
            <td style="width: 58px"><label>Banco: </label></td>
            <td><label class="input">
                    <input type="text" list="list" id="cbBanco" name="cbBanco" style="width: 300px">
                    <datalist id="list">
                        <?php
                        $regs = $cContasBanc->Bancos();
                        foreach ($regs as $reg) {
                            echo "<option value='".trim($reg->banco)."'></option>";
                        }
                        ?>
                    </datalist>
                </label></td>
        </tr>
        <tr>
            <td style="width: 58px"><label>Agência:</label></td>
            <td><input name="txAgencia" id="txAgencia" type="text"></td>
        </tr>
        <tr>
            <<td style="width: 58px"><label>Conta:</label></td>
            <td><input name="txConta" id="txConta" type="text"></td>
        </tr>
    </table>
    <input name="txEmpresa" type="hidden" value="{{$idEmpresa}}">
    <input name="txId" id="txId" type="hidden" value="-1">
    <input name="btEnviar" id="btEnviar" type="submit" onclick="AcionaEdicao()" value="Adicionar">
</form>
<script>
    function AcionaEdicao() {
        if (document.getElementById("btEnviar").value=="Atualizar") {
            var op="a="+document.getElementById("txAgencia").value;
            op+="&c="+document.getElementById("txConta").value;
            op+="&i="+document.getElementById("txId").value;
            op+="&b="+document.getElementById("cbBanco").value;
            document.location.assign('http://tele-tudo.com/contasbancarias?op=2&'+op);
        }
    }

    function edita(nr, dados) {
        var itens = dados.split(";");
        document.getElementById("cbBanco").value=itens[0].trim();
        document.getElementById("txAgencia").value=itens[1];
        document.getElementById("txConta").value=itens[2];
        document.getElementById("txId").value=nr;
        document.getElementById("btEnviar").value="Atualizar";
        document.getElementById('btEnviar').type='button';
    }

    function deletar(nr) {
        if (confirm("Tem certeza que quer excluir esta conta bancária?")) {
            var op = 'del='+nr;
            document.location.assign('http://tele-tudo.com/contasbancarias?op=1&'+op);
        }

    }
</script>
<?php
}
?>
@stop