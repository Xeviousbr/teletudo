<?php
$idUser = 0;
?>
@extends('layouts.padrao')
<title>Tele-Tudo: Administração</title>

<?php
Session::put('SemChat',1);
Session::put('SemDownload',1);
?>
@section('content')

    <style type="text/css">
        .centro {
            text-align: center;
        }
        .cinza  {
            color: #C0C0C0;
        }
        .normal  {
            color: #060606;
        }
        .vermelho {
            color: #FF0000;
        }
        .verde {
            color: #056f2b;
        }
    </style>
    <?php
    $iduser=Session::get('iduser');
    $coringas=0;
    if ($iduser==1) {
    $buscado=''; // $dias=1;
    if (isset($_GET['op'])) {
    $op = $_GET['op'];
    $id = $_GET['id'];

    if (isset($_GET['op'])) {
    $op = $_GET['op'];
    $id = $_GET['id'];
    $atu=1;
    switch ($op) {
        case 1:
            // Financeiro: Visualizou
            $idPed = $_GET['idPed'];
            $cFin = new Financeiro();
            $cFin->Visualizou($id, $idPed);
            break;
        case 2:
            // Financeiro: Confirmou
            $idTrans = $_GET['idTrans'];
            $idPed = $_GET['idPed'];
            $cFin = new Financeiro();
            $cFin->Confirmou($id, $idPed, $idTrans);
            break;
        case 3:
            // Modo
            $cAdm = new Adm();
            $cAdm->SetaModo($id);
            break;
        case 4:
            // BuscaProdutos
            $buscado = $id;
            // $dias=$_GET['dias'];
            $atu=0;
            break;
        case 5:
            // Seta o modo de debug
            DB::update('update config set Debug = '.$id.' where id = 1');
            break;
        case 6:
            // Indica se vai ter som ou não
            DB::update('update config set Som = '.$id.' where id = 1');
            break;
        case 7:
            // Seta como logradouro aceito
            DB::update('update logradouro set adic  = 0 where ID = '.$id);
            break;
        case 8:
            // Listar os coringas
            $atu=0;
            $coringas=1;
            break;
        case 9:
            // Incluir Palavra Coringa
            $catE = $_GET['Cat'];
            DB::insert('insert into palavras (palavra, categoria) values (?, ?)', [$id, $catE]);
            echo "Palavra Inserida ".$id." categoria ".$catE;
            break;
    }
    if ($atu>0) {
    ?>
    <script language="javascript" type="text/javascript">
        document.location.assign("http://www.tele-tudo.com/adm");
    </script>
    <?php
    }
    ?>
    <?php
    }
    }
    $cAdm = new Adm();
    $cAdm->SetaON();
    $vLA=$cAdm->getLojasAbertas();
    $vEn=0;
    $Nu=0;
    $No=0;
    $Pe=0;
    $modo=$cAdm->VeModo();
    $cSessao = new Sessao;
    $TemSom =$cSessao->Som();
    $Debug=$cAdm->Debug();
    $OpSom=$cAdm->OpSom();
    ?>
    <script>
        function recarrega() {
            document.location.assign("http://www.tele-tudo.com/adm");
        }

        function SetaModo(modo) {
            document.location.assign("http://www.tele-tudo.com/adm?op=3&id="+modo);
        }

        function buscar() {
            var busca = document.getElementById('buscageral').value;
            document.location.assign("http://www.tele-tudo.com/adm?op=4&id="+busca+"&dias=1");
        }

        function HabEnder(id) {
            document.location.assign("http://www.tele-tudo.com/adm?op=7&id="+id);
        }

        function Coringa() {
            document.location.assign("http://www.tele-tudo.com/adm?op=8&id=0");
        }

        /*function insCor() {
            var Coringa = document.getElementById("txIncCoringa").value;
            var Cat = document.getElementById("cbIncCoringa").value;
            // document.location.assign("http://www.tele-tudo.com/adm?op=9&id=");
            alert("Coringa = " + Coringa);
            alert("Cat = "+Cat);
        }*/

    </script>
    <div class="panel-body">
        <h1>Site em modo: </h1>
        <div class="btn-group">
            <button type="button" onclick="SetaModo(1)" {{$modo[1][0]}} class="btn btn-default {{$modo[1][1]}}">Teste Simulado</button>
            <button type="button" onclick="SetaModo(2)" {{$modo[2][0]}} class="btn btn-default  {{$modo[2][1]}}">Teste</button>
            <button type="button" onclick="SetaModo(3)" {{$modo[3][0]}} class="btn btn-default active">Produção</button>
            &nbsp;&nbsp;&nbsp;
            <select name="opDebug" onchange="window.location.href='/adm?op=5&id='+(options[selectedIndex].value);">
                <option value="0" {{$Debug[0]}}>Normal</option>
                <option value="1" {{$Debug[1]}}>Debug</option>
            </select>
            &nbsp;
            <select name="opSom" onchange="window.location.href='/adm?op=6&id='+(options[selectedIndex].value);">
                <option value="0" {{$OpSom[0]}}>Sem Som</option>
                <option value="1" {{$OpSom[1]}}>Com Som</option>
            </select>


        </div>
    </div>
    <h1>Quantidade de Lojas Abertas: <label name='lbModo' style='vermelho' type='text'>{{$vLA}}</label></h1>
    <?php
    if ($vLA>0) {
        $Lojas = $cAdm->ListaLojasAbertas();
        echo '<table>';
        $cor='';
        foreach ($Lojas as $Loja) {
            if ($Loja->TpAcesso==0) {
                $cor='#0000FF';
            } else {
                $cor='15ff0a';
            }
            echo "<tr><td style='text-align: right; width: 429px; color: ".$cor."' >" .$Loja->Empresa."</td></tr>";
        }

        echo '</table>';
    }
    echo $cAdm->EnderNovo();
    $vPlay = $cAdm->vPlay();
    $qUser = $cAdm->qUser();
    $OrcHj = $cAdm->OrcHjr();
    $PedHj=$cAdm->PedHj();
    ?>
    <h1 class="normal">Valor devido a PlayDelivery: {{$vPlay}}</h1>
    <h2 class="normal">Usuários que acessaram o site hoje: {{$qUser}}</h2>
    <h2 class="normal">Orçamentos realizados hoje: {{$OrcHj}}</h2>
    <h2 class="cinza">Pedidos realizados hoje: {{$PedHj}}</h2>
    <?php
    echo $cAdm->ultBusca();
    ?>
    </table>
    <br>
    <table class="table table-striped table-bordered">
        <tr>
            <td class="centro">Comprador</td>
            <td class="centro">Vendedor</td>
            <td class="centro">Valor</td>
            <td class="centro">Hora</td>
            <td class='centro'>Banco</td><td class='centro'>Agência</td><td class='centro'>Conta</td>
            <td class="centro">Telefone</td>
            <td class="centro">Comentário</td>
            {{--<td class="centro">Visto</td>
            <td class="centro">Confirmação</td>--}}
        </tr>
        <tbody>
        <h2>Informação de recebimento de valores</h2>
        <?php
        Session::put('SemChat',1);
        $cFin = new Financeiro();
        $regs = $cFin->getTransferenciasADM();
        $Agora =  date("Y-m-d H:i:s");
        foreach ($regs as $reg) {
            echo '<tr>';
            echo '<td style="width: 50px">'.$reg->Nome.'</td>';
            echo '<td style="width: 50px">'.$reg->Empresa.'</td>';
            echo '<td style="width: 50px; text-align: right;">R$ '.number_format($reg->Valor, 2, ',', '.').'</td>';
            echo '<td style="width: 60px; text-align: center;">'.substr($reg->Hora, 11, 8).'</td>';
            echo '<td style="width: 60px; text-align: center;">'.$reg->BCO.'</td>';
            echo '<td style="width: 60px; text-align: center;">'.$reg->AGE.'</td>';
            echo '<td style="width: 50px; text-align: center;">'.$reg->CTA.'</td>';
            echo '<td style="width: 50px; text-align: center;">'.$reg->fone.'</td>';
            echo '<td style="width: 50px; text-align: center;">'.$reg->Comentario.'</td>';
            echo '</tr>';
        }
        ?>
    </table>
    <label>Busca Geral:<input id="buscageral" type="text" value="{{$buscado}}"></label>
    <input value="buscar" type="button" onclick="buscar()" >

    <script type="text/javascript">
        document.title = '('+'<?php echo $vLA; ?>'+')';
    </script>
    <?php
    if ($buscado>'') {
        echo $cAdm->BuscaGeral($buscado);
    } else {
    ?>
    <?php
    if ($coringas==1) {
    ?>
    <form name="formulario" action="http://www.tele-tudo.com/adm" method="get">
        <input name="op" type="hidden" value="9">
        <?php
        $cAdm->MontaCbCoringas();
        echo $cAdm->Coringas();
        echo "</form>";
        } else {
            echo "<Br><input value='Listar Palavras Coringa' onclick='Coringa()' type='button'>";
        }
        ?>
        <?php
        }
        ?>
        <script type="text/javascript">
            setTimeout(recarrega, 150000);
        </script>
    <?php
    } else {
        echo "<div class='alert alert-danger'><font size='5'>Esta área é apenas para administradores</font></div>";
        echo 'idUser = '.$iduser;
    }
    ?>
@stop