<?php

class Location extends Eloquent
{

    private $idPais = '';
    private $Opc = '-';

    public function SetaLocal() {
	$locale = '';	 			
	$fazer = false;

	$forcado = false;
        
//     $idPais = 1;
//    $this->idPais = $idPais;

	if (Session::has('locale')) {
		$locale = Session::get('locale');

            if (Session::has('pais')) {
            	// echo 'Pegou o pais pela session</p>';
                $idPais = Session::get('pais');
            } else {
                // BUG .. Porque não ta pegando a Session do Pais ?
                $idPais = 1;
                // echo 'Forçou o pais como 1</p>';
            }
            // $idPais = 1;
            // Session::put('pais', $idPais);
            $this->idPais = $idPais;
	 		// echo 'Achou Local pela sessao ='.$locale.'</p>';
            // echo 'Pais ='.$idPais.'</p>';
	 	} else {
            $locale = Cookie::get('locale');
            if ($locale == '') {
                $fazer = true;
            }
        }

        if ($forcado==true) {
            $fazer = true;
        }

        if ($fazer==true) {

            // $IP = '207.46.1.1';
            $IP = $_SERVER['REMOTE_ADDR'];
            
            $ClsIPs = new IPs;
            $Siglapais = $ClsIPs->GetSiglapais($IP);
            $this->Opc = $ClsIPs->GetOpc();
            if ($Siglapais =='') {           
	            // $Varpais =  unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip=218.100.74.0')); //ID = Indonésia
	            $Varpais =  unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='.$IP));
	
	            $Siglapais = $Varpais['geoplugin_countryCode'];
	            
	            $ClsIPs->InsereIP($Siglapais );
	        }
	
            // echo 'Siglapais = '.$Siglapais.'</p>';

            $tbpais = DB::table('pais')
                ->select('ID','Lang')
                ->where('Sigla','=',$Siglapais)
                ->first();
                
            if ($tbpais==null) {
            	DB::insert('insert into pais (Nome, idLang, Sigla, qtd, Lang) values (?, ?, ?, ?, ?)', ['?', 2, $Siglapais, 1, 'en']);
            	$idPais = DB::table('pais')->max('ID');
            	$locale = 'en';
            } else {
	        $idPais = $tbpais->ID;        		        
	        $locale = $tbpais->Lang;
	        DB::update('update pais set qtd = qtd + 1 where Sigla = "' . $Siglapais . '"');
            }

            // echo 'Location.pais = '.$idPais.'</p>'; die;
	        $this->idPais = $idPais;

            Session::put('pais', $idPais);
            $cookie = Cookie::make('pais', $idPais);            
            Session::put('locale', $locale);
            $cookie = Cookie::make('locale', $locale);
            Session::put('SiglaPais', $Siglapais);

            // DB::insert('insert into IpCompleto (IP, Sigla, Obs) values (?, ?, ?)', [$IP, $Siglapais, $this->Opc]);

        }
 	    App::setLocale($locale);
    }

    public function getidPais() {
        return $this->idPais;
    }

    public function GetOpc() {
        if ($this->Opc == '-') {

            // $ip = '66.220.151.91';
            $ip = $_SERVER['REMOTE_ADDR'];
            // $ip = '66.220.145.243';

            $ClsIPs = new IPs;
            $IpPq = $ClsIPs->ConverteIP($ip);

            // echo 'Ip Convertido = '.$IpPq.'</Br>';

            $tbIp = DB::table('IPs')
                ->select('Opc')
                ->where('IP','=',$IpPq)
                ->first();
            if ($tbIp==null) {
                $this->Opc = '';
            } else {
                $this->Opc = $tbIp->Opc;
            }
        }
        // echo 'resultado = '.$this->Opc; die;
        return $this->Opc;
    }


}