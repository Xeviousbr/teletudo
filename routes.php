<?php

Route::get('/', function()
{
    return View::make('produtos/index');
});

Route::resource('servicos', 'ServicosController');

Route::resource('entrega', 'EntregaController');
Route::get('entregapropria', array('uses' => 'Entrega@Propria'));

// Route::resource('pagar', 'PagarController');

// Route::get('pagamento', array('uses' => 'PagamentoController@Aciona'));
Route::post('pagamento', array('uses' => 'PagamentoController@Aciona'));

Route::resource('renovar', 'RenovarController');

Route::resource('vlrtransf', 'VlrtransfController');

// Route::get('entrega', array('uses' => 'EntregaController@Aciona'));

Route::resource('produtos', 'ProdutosController');

Route::resource('home', 'HomeController');

// Route::post('cadastro', array('uses' => 'ProdutosController@Cadastro'));
Route::get('Cadastro', array('uses' => 'ProdutosController@Cadastro'));

// FAZER FUNCIONAR
// Route::get('perfil', array('uses' => 'PedidoController@Perfil'));
Route::get('perfil', array('uses' => 'HomeController@perfil'));
Route::post('perfil', array('uses' => 'HomeController@perfil'));
//

Route::get('contato', array('uses' => 'HomeController@contato'));

Route::get('operacoes', array('uses' => 'OperacoesController@Aciona'));
Route::get('processo', array('uses' => 'ProcessoController@Aciona'));

Route::get('doc', array('uses' => 'DocController@Aciona'));

Route::get('teste', array('uses' => 'OperacoesController@teste'));

Route::resource('pedido', 'PedidoController');
Route::get('confirma', array('uses' => 'PedidoController@Aciona'));

Route::get('resumo', array('uses' => 'PedidoController@Resumo'));

Route::get('captador', array('uses' => 'PedidoController@Captador'));
Route::get('convite', array('uses' => 'PedidoController@Convite'));

// Route::get('loginfb', array('uses' => 'PedidoController@loginfb'));
Route::get('loginfb', array('uses' => 'HomeController@loginfb'));
Route::get('fakerede', array('uses' => 'HomeController@fakerede'));
Route::get('logingm', array('uses' => 'HomeController@logingm'));
Route::get('privacidade', array('uses' => 'HomeController@privaci'));

Route::get('ender', array('uses' => 'HomeController@ender'));
Route::post('ender', array('uses' => 'HomeController@ender'));

Route::post('loginrede', array('uses' => 'HomeController@loginrede'));

// Chamado pelo Menu...
// Route::post('logarede', array('uses' => 'HomeController@logarede'));
Route::get('logarede', array('uses' => 'PedidoController@Logarede'));

Route::get('pagtodireto', array('uses' => 'PedidoController@Pagtodireto'));

Route::get('criapedido', array('uses' => 'PedidoController@Criapedido'));

Route::get('posface', array('uses' => 'PedidoController@PosFace'));
// Route::get('createsl', array('uses' => 'PedidoController@CreateSL'));

// Route::get('moip', array('uses' => 'PagamentoController@moip'));

Route::resource('adm', 'AdmController');

// Route::get('pagar', array('uses' => 'PagarController@index'));
// Route::get('pagar', array('uses' => 'PagarController@Aciona'));

Route::get('formas', array('uses' => 'FormasController@Aciona'));

Route::resource('contasbancarias', 'ContasbancariasController');
Route::get('contasbancarias', array('uses' => 'ContasbancariasController@Aciona'));

Route::get('form', array('uses' => 'ContatosController@form'));
Route::post('send', 'ContatosController@send');
Route::get('contatos', array('uses' => 'ContatosController@form'));

Route::get('login', array('uses' => 'HomeController@login'));

Route::get('entrar', 'HomeController@getEntrar');
Route::post('entrar', 'HomeController@postEntrar');

Route::get('sair', 'HomeController@getSair');

Route::resource('fornecedor', 'FornecedorController');

Route::resource('pessoa', 'PessoaController');
/*Route::get('/pessoa/edit/{pessoa}', 'PessoaController@edit');
Route::post('/pessoa/edit', 'PessoaController@handleEdit');*/

Route::resource('nerds', 'NerdController');

Route::get('confgentrega', array('uses' => 'PedidoController@confgentrega'));
// Route::get('confgentrega', array('uses' => 'TpEntregaEmpresaController@Configura'));
// Route::get('tpentregaempresa', array('uses' => 'TpEntregaEmpresaController@index'));

// Route::resource('tpentregaempresa', 'TpEntregaEmpresaController');
// Route::post('tpentregaempresa', 'TpEntregaEmpresaController@index');
// Route::get('tpentregaempresa', 'TpEntregaEmpresaController@index');

Route::resource('tpentregaempresa', 'TpEntregaEmpresaController');

// Route::get('geo', 'HomeController@geo');

// www.tele-tudo.com/renovar/89

//// Verifica se o usu�rio est� logado
//Route::group(array('before' => 'auth'), function()
//{
//    // Rota de artigos
//    Route::controller('artigos', 'ArtigosController');
//});

Route::get('imagens/{filename}',function( $filename )
{
    $fullpath = base_path('img') . '/' . $filename;
    if( File::exists($fullpath) )
    {
        $filetype = File::type( $fullpath );
        $response = Response::make( File::get( $fullpath ) , 200 );
        $response->header('Content-Type', $filetype);
        return $response;
    }
});

Route::get('som/{filename}',function( $filename )
{

    // Waveform Audio File Format (WAV)	audio/x-wav	.wav	Wikipedia: WAV

    // $fullpath = base_path('wav') . '/' . $filename;
    $fullpath = base_path('mp3') . '/' . $filename;
    if( File::exists($fullpath) )
    {
        $filetype = File::type( $fullpath );
        $response = Response::make( File::get( $fullpath ) , 200 );

        $response->header('audio/mpeg', $filetype);
        // $response->header('audio/x-wav', $filetype);

        return $response;
    }
});

// I use it like this {{ $data->thumbnail }} where $data came from the database and thumbnail comes as the string which used storage_path

/*
 Let us take a look at the default L4 application structure:

app // contains restricted server-side application data

app/storage // a writeable directory used by L4 and custom functions to store data ( i.e. log files, ... )

public // this directory is accessible for clients

If I were you, I would upload the file to the public directory directly:

Store image here: public_path() . 'img/filename.jpg'
Save the 'img/filename.jpg' in database
Generate the image URL with url('img/filename.jpg') => http://www.your-domain.com/img/filename.jpg
 */