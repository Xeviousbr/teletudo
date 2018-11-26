<?php

// class HomeController extends BaseController {
class HomeController extends Controller {

    /*
    |--------------------------------------------------------------------------
    | Default Home Controller
    |--------------------------------------------------------------------------
    |
    | You may wish to use controllers instead of, or in addition to, Closure
    | based routes. That's great! Here is an example controller method to
    | get you started. To route to this controller, just add the route:
    |
    |	Route::get('/', 'HomeController@showWelcome');
    |
    */

    public function showWelcome()
    {
        return View::make('hello');
    }

    public function contato() {
        return View::make('contatos.index');
        // return View::make('contatos/index');
        // return View::make('pages.contato');

    }

    public function login() {
        return View::make('login.index');
    }

    public function postContato() {
//		$rules = array( 'nome' => 'required', 'email' => 'required|email', 'texto' => 'required' );
//		$validation = Validator::make(Input::all(), $rules);
//		$data = array();
//		$data['nome'] = Input::get("nome");
//		$data['email'] = Input::get("email");
//		$data['texto'] = Input::get("texto");
//		if($validation->passes()) {
//			Mail::send('emails.contato', $data, function($message) {
//		 	$message->from(Input::get('email'), Input::get('nome'));
//		 	$message->to('xeviousbr@gmail.com') ->subject('Contato Tele-Tudo');
//		});
//
//		return Redirect::to('contato') ->with('message', 'Mensagem enviada com sucesso!');
//		}
//
//		return Redirect::to('contato')
//		 ->withInput()
//		 ->withErrors($validation)
//		 ->with('message', 'Erro! Preencha todos os campos corretamente.');
    }

    /*		public function Operacoes()
        {
            //$titulo = 'Entrar - Desenvolvendo com Laravel';
            return View::make('home/operacoes', compact('titulo'));
        }	*/

    public function getEntrar()
    {
        //$titulo = 'Entrar - Desenvolvendo com Laravel';
        return View::make('home/entrar', compact('titulo'));
    }

    public function postEntrar()
    {
        // Op??o de lembrar do usu?rio
        $remember = false;
        if(Input::get('remember'))
        {
            $remember = true;
        }

        $user = Input::get('user');
        $senha = Input::get('senha');

        /* $pass = Hash::make($senha);
        DB::update("update pessoa set password = '".$pass."', user = '".$user."' where id = 31");
        // echo 'pass: '.$pass.'<p>';
        echo 'Alteração realizada';
        die; */

        // Autenticão
        if (Auth::attempt(array(
                'user' => $user,
                'password' => $senha),
            $remember)) {

            $pessoas = DB::table('pessoa')
                ->select('id', 'Nome')
                ->where('user','=',$user)
                ->first();

            DB::update('update pessoa set contLogin = contLogin + 1 where id = '.$pessoas->id);

            Auth::loginUsingId($pessoas->id);

            $cookie = Cookie::make('Nome', $pessoas->Nome);
            $cookie = Cookie::make('iduser', $pessoas->id);

            Session::put('Nome', $pessoas->Nome);
            Session::put('iduser', $pessoas->id);

            $Nome = Session::get('Nome');

            $pag = '';
            if(isset($_COOKIE['pagina'])) {
                $pag = $_COOKIE['pagina'];
            }

            if ($pag > '') {
                $cookie = Cookie::make($pag, '');
                return Redirect::to($pag);

            } else {

                $EhAdm = DB::table('pessoaperfil')->select(DB::raw('count(*) as Quant'))
                    ->where('idPessoa','=',$pessoas->id)
                    ->where('idPerfil', '=', 1)
                    ->first();
                if ($EhAdm->Quant>0) {
                    return Redirect::to('adm');
                } else {

                    // PEDIDO SENDO FEITO, INICIALMENTE SEM LOGIN
                    if (Session::has('PEDIDO')) {
                        $idPedido = Session::get('PEDIDO');

                        if ($idPedido==0) {
                            echo 'Session::get(PEDIDO)='.Session::get('PEDIDO'); die;
                        }

                        DB::update("update pedido set User = '".$pessoas->id."' where idPed = ".$idPedido);
                        Session::forget('PEDIDO');
                        $tpEnt=Session::get('TpEntrega');
                        // echo 'aqui(Home)'; die;
                        return Redirect::to("confirma?IDPED=".$idPedido."&tpEnt=".$tpEnt);
                    } else {
                        $EhForn = DB::table('empresa')->select(DB::raw('count(*) as Quant'))
                            ->where('idPessoa','=',$pessoas->id)
                            ->first();

                        if ($EhForn->Quant>0) {
                            return Redirect::to('fornecedor');
                        } else {
                            $url="";
                            $url = Session::get('url');
                            $TamUrl=strlen($url);
                            if ($TamUrl==0) {
                                return Redirect::to('/');
                            } else {
                                return Redirect::to($url);
                            }
                        }
                    }
                }
            }

        } else {
            return Redirect::to('entrar')
                ->with('flash_error', 1)
                ->withInput();

        }
    }

    public function getSair()
    {
        Session::forget('Nome');
        Session::forget('iduser');
        Session::forget('Debug');
        Session::forget('PEDIDO');
        $cookie = Cookie::forget('Nome');
        $cookie = Cookie::forget('iduser');
        Auth::logout();
        return Redirect::to(Session::get('url'));
    }

    public function getGeo($cep, &$lat, &$long) {
        // public function Geo($cep, &$lat, &$long) {

        $address = "'".$cep."'".","."Brasil";
        $request_url = "http://maps.googleapis.com/maps/api/geocode/xml?address=".$address."&sensor=true"; // A URL que vc manda pro google para pegar o XML
        $xml = simplexml_load_file($request_url) or die("url not loading");// request do XML
        $status = $xml->status;// pega o status do request, j? qe a API da google pode retornar v?rios tipos de respostas
        if ($status=="OK") {
            //request returned completed time to get lat / lang for storage
            $lat = $xml->result->geometry->location->lat;
            $long = $xml->result->geometry->location->lng;
        }
        if ($status=="ZERO_RESULTS") {
            //indica que o geocode funcionou mas nao retornou resutados.
            echo "N?o Foi poss?vel encontrar o local";
        }
        if ($status=="OVER_QUERY_LIMIT") {
            //indica que sua cota di?ria de requests excedeu
            echo "A cota do GoogleMaps excedeu o limite di?rio";
        }
        if ($status=="REQUEST_DENIED") {
            //indica que seu request foi negado, geralmente por falta de um 'parametro de sensor?'
            echo "Acesso Negado";
        }
        if ($status=="INVALID_REQUEST") {
            // geralmente indica que a query (address or latlng) est? faltando.
            echo "Endere?o n?o est? preenchido corretamente";
        }

        if (($lat==-25.2912987) && ($long==-57.6265412)) {
            echo "CEP inv?liod ou Erro na API do Google";
        }
        // echo $lat.' '.$long.'</p>';
        $mensagem = "Caso deseje consultar outra regi?o altere o CEP";
    }

    public function loginfb()
    {
        return View::make('login.loginfb');
    }

    public function loginrede()
    {
        return View::make('login.loginrede');
    }

    public function logarede()
    {
        return View::make('login.logarede');
    }

    public function fakerede()
    {
        return View::make('login.fakerede');
    }

    public function logingm()
    {
        return View::make('login.logingm');
    }

    public function ender()
    {
        return View::make('login.ender');
    }

    public function privaci()
    {
        return View::make('login.privacidade');
    }

    public function index()
    {
        // PAGINA PARA NOVO TEMPLATE
        return View::make('home.index');
    }

    public function perfil()
    {
        return View::make('pessoa.perfil');
    }

}