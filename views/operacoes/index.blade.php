<?php

/*header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: http://www.tele-tudo.com');
header('Access-Control-Max-Age: 1728000');
header("Content-Type: application/json");
$dados = ['id' => "1", 'nome' => 'aaa'];
echo json_encode($dados);
exit(0);*/

// Essa classe funciona como um WebService
// Retornando dados conforme a requisição

$op="";
if (isset($_REQUEST['op'])) {
    $op = $_REQUEST['op'];
}

/*$dados ="Erro=0";
$dados.="&DescErro=''";*/
$dados='';

header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: http://www.tele-tudo.com');
header('Access-Control-Max-Age: 1728000');
header("Content-Type: application/json");

switch ($op) {
    case 1: // Login
    {
        login($dados);
        break;
    }
    case 2: // Busca Produtos
    {
        produtos();
        break;
    }
    case 3: // Teste
    {
        teste();
        break;
    }
    case 4: // Avisa que o fornecedor esta ON
    {
        FornOn();
        break;
    }
    case 5: // Avisa que o fornecedor esta OFF
    {
        FornOff();
        break;
    }
    case 6: // Confirma venda
    {
        ConfirmaVenda();
        break;
    }
    case 7: // Itns do Pedido
    {
        ItensPedido();
        break;
    }

    case 8: // Retornar conteúdo de uma pesquisa, google, em formato JSON
    {
        PesquisaNoGoogle();
        break;
    }

    default:
    {
        RetornaPorErro(6);
        break;
    }
}

function login($dados) {
    $user = "";
    $pass = "";

    /* http://www.tele-tudo.com/operacoes?op=1&user=teste&pass=senhateste */

    if (isset($_REQUEST['user'])) {
        $user = $_REQUEST['user'];
    }
    if (isset($_REQUEST['pass'])) {
        $pass = $_REQUEST['pass'];
    }

    if (($user>"")&&($pass)) {
        // $passC = Hash::make();
        if (Auth::attempt(array(
                'user' => $user,
                'password' => $pass)
        )) {
            $pessoas = DB::table('pessoa')
                ->join('empresa', 'empresa.idPessoa', '=', 'pessoa.id')
                ->select('pessoa.id', 'pessoa.Nome',
                    'empresa.idEmpresa', 'empresa.Empresa')
                ->where('user','=',$user)
                ->first();
            $dados = ['Erro' => 0,
                'DescErro' => '',
                'id' => $pessoas->id,
                'nome' => $pessoas->Nome,
                'idEmpresa' => $pessoas->idEmpresa,
                'Empresa' => $pessoas->Empresa];
            echo json_encode($dados);
            Auth::logout();
        } else {
            RetornaPorErro(1);
        }
    } else {
        RetornaPorErro(2);
    }
}

function produtos() {
    /* http://www.tele-tudo.com/operacoes?op=2&idCli=21&busca=informatica&lat=-30.0277&lon=-51.2287&cep=91760570 */

    // echo "funcao desabitada";

    $idCli=0;
    $busca="";
    $lat=0;
    $lon=0;
    $cep=0;
    $Teste=0;

    if (isset($_REQUEST['idCli'])) { $idCli = $_REQUEST['idCli']; }
    if (isset($_REQUEST['busca'])) { $busca = $_REQUEST['busca']; }
    if (isset($_REQUEST['lat'])) { $lat = $_REQUEST['lat']; }
    if (isset($_REQUEST['lon'])) { $lat = $_REQUEST['lon']; }
    if (isset($_REQUEST['cep'])) { $cep = $_REQUEST['cep']; }
    if ($lat==0) {
        // Buscar a localização apartir do cadastro do usuario
        if ($idCli=="0") {
            RetornaPorErro(3);
        } else {
            $sql="SELECT cep.lat, cep.lon ";
            $sql.="FROM pessoa ";
            $sql.="Inner Join endereco on endereco.ID = pessoa.Endereco_ID ";
            $sql.="Inner Join cep on cep.id = endereco.idCep ";
            $sql.="WHERE pessoa.id = ".$idCli;
            $qry = DB::select( DB::raw($sql));
            foreach ($qry as $reg) {
                $lat=$reg->lat;
                $lon=$reg->lon;
            }
            if ($idCli==21) {
                $Teste=1;
            }
        }
    }
    $ClsProd = new Produtos;
    if ($ClsProd->Procura($busca, $cep, $lat, $lon, $Teste)==true) {
        $cid = $ClsProd->getCid();
        if ($cid>"") {
            $lstLojas = $ClsProd->getLojas();
            if ($lstLojas>"") {
                $res = $ClsProd->BuscaProd();
                echo json_encode($res);
            } else {
                RetornaPorErro(4);
            }
        }
    } else {
        RetornaPorErro(7);
    }
}

function teste() {
    /*header("Access-Control-Allow-Origin: *");
    header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
    header('Access-Control-Allow-Headers: http://www.tele-tudo.com');
    header('Access-Control-Max-Age: 1728000');
// header("Content-Length: 0");
    header("Content-Type: application/json");*/
    $dados = ['Param1' => "TEXTO", 'Param2' => 2];
    $enc = json_encode($dados);
    echo $enc;
    exit(0);
}

function RetornaPorErro($nr) {
    $desc="";
    switch ($nr) {
        case 1:
        {
            $desc="Usuário não localizado";
            break;
        }
        case 2:
        {
            $desc="Falta Login ou Senha";
            break;
        }
        case 3:
        {
            $desc="Nao foi informado o numero do cliente";
            break;
        }
        case 4:
        {
            $desc="Nao ha fornecedores disponiveis para sua localizacao";
            break;
        }
        /* erro retorno vem direto da classe produtos
        case 5:
        {
            $desc="Não houve produtos com essa pesquisa";
            break;
        } */
        case 6:
        {
            $desc="Operacao Invalida";
            break;
        }
        case 7:
        {
            $desc="Não foi possivel trazer os dados desta localidade";
            break;
        }

        default:
        {
            $desc="Erro desconhecido";
        }
    }
    $dados = ['Erro' => $nr,
        'DescErro' => $desc];
    echo json_encode($dados);
}

function FornOn() {
    // http://www.tele-tudo.com/operacoes?op=4&idForn=1

    $idForn = $_REQUEST['idForn'];

    /* if ($idForn==1) {
        // $idForn = 10; // Agapio
        $idForn = 22; // Big
    }*/

    $cForn = new Fornecedor();
    $qtd = $cForn->EsseFornOnLine($idForn);
    if ($qtd==0) {
        $dados = ['Erro' => 0,
            'DescErro' => '',
            'qtd' => $qtd];
    } else {
        $qryE = DB::table('empresa')
            ->select('tpEntrega')
            ->where('idEmpresa', '=', $idForn)
            ->first();
        if ($qryE->tpEntrega == 0 ) {
            $qry = DB::table('notificacao')
                ->select('notificacao.Hora','notificacao.valor','notificacao.idPedido','notificacao.idAviso',
                    'bancos.banco','bancos.apelido',
                    'pessoa.Nome',
                    'pedido.idPed')
                ->join('vlrtransf', 'vlrtransf.ID', '=', 'notificacao.idTransf')
                ->join('contasbancarias', 'contasbancarias.id', '=', 'vlrtransf.idConta')
                ->join('bancos', 'bancos.cod', '=', 'contasbancarias.idBanco')
                ->join('pedido', 'pedido.idPed', '=', 'notificacao.idPedido')
                ->join('pessoa', 'pessoa.id', '=', 'pedido.User')
                ->where('notificacao.idFornec', '=', $idForn)
                ->whereNull('vizualizado')
                ->whereNull('Confirmado')
                ->get();
            if ($qry[0]->apelido==null) {
                $Conta = $qry[0]->banco;
            } else {
                $Conta = $qry[0]->apelido;
            }
        } else {
            $qry = DB::table('notificacao')
                ->select('notificacao.Hora','notificacao.valor','notificacao.idPedido','notificacao.idAviso',
                    'pessoa.Nome',
                    'pedido.idPed')
                ->join('pedido', 'pedido.idPed', '=', 'notificacao.idPedido')
                ->join('pessoa', 'pessoa.id', '=', 'pedido.User')
                ->where('notificacao.idFornec', '=', $idForn)
                ->whereNull('vizualizado')
                ->whereNull('Confirmado')
                ->get();
            $Conta='';
        }
        $Hora = substr($qry[0]->Hora, 11, 5);
        $Valor = 'R$ '.number_format($qry[0]->valor, 2, ',', '.');
        $Nome = $qry[0]->Nome;
        $idPed = $qry[0]->idPed;
        $qryC = DB::table('config')
            ->select('Som')
            ->where('ID', '=', 1)
            ->first();
        $som = $qryC->Som;
        $dados = ['Erro' => 0,
            'DescErro' => '',
            'tpEntrega' => $qryE->tpEntrega,
            'qtd' => $qtd,
            'HoraVenda' => $Hora,
            'Conta' => $Conta,
            'Valor' => $Valor,
            'Nome' => $Nome,
            'idAviso' => $qry[0]->idAviso,
            'idPed' => $idPed,
            'Som' => $som];
    }
    echo json_encode($dados);
}

function FornOff() {
    $idForn = $_REQUEST['idForn'];
    $cForn = new Fornecedor();
    $cForn->FornOff($idForn);
}

function ConfirmaVenda() {
    $idAviso = $_GET['idAviso'];
    $qry = DB::table('notificacao')
        ->select('notificacao.idPedido','notificacao.idTransf')
        ->where('notificacao.idAviso', '=', $idAviso)
        ->first();
    $cFin = new Financeiro();
    if ($qry==null) {
        echo 'qry ta nulo'; die;
    }
    $cFin->Confirmou($idAviso, $qry->idPedido, $qry->idTransf);
    $dados = ['Erro' => 0, 'DescErro' => ''];
    echo json_encode($dados);
}

function ItensPedido() {
    $idPed = $_GET['idPed'];
    $cPed = new Pedido;
    $qry = $cPed->getRgItens($idPed);
    $Tarray[] = array( "qtd" => 1 );
    $qtd=0;
    foreach ($qry as $reg) {
        $qtd++;
        if ($qtd==1) {
            $Tarray[] = array( "qtde" => $reg->quant, "nome" => $reg->Nome );
        } else {
            array_push($Tarray, $reg->quant, $reg->Nome);
        }
    };
    $Comprador = $cPed->getCliente($idPed);
    $FoneCli = $cPed->getFoneCli();
    $Ender = $cPed->getEnderPedido();
    $Forma = $cPed->getFormaPagto();
    $Troco = $cPed->getTroco();
    $Coment = $cPed->getComentario();
    $dados = ['Erro' => 0,
        'DescErro' => '',
        'Comprador' => $Comprador,
        'FoneCli' => $FoneCli,
        'Ender' => $Ender,
        'Forma' => $Forma,
        'Troco' => $Troco,
        'qtd' => $qtd,
        'Coment' => $Coment,
        'Itens' => $Tarray];
    echo json_encode($dados);
}

function PesquisaNoGoogle() {
    // Alterado
    $texto = $_GET['texto'];
    $cBus = new Buscador();
    $resp = $cBus->Busca($texto);
    return $resp;
}

?>