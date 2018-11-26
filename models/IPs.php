<?php

class IPs extends Eloquent
{
	protected $table = 'IPs';
	private $IpPq = '';
	private $Opc = '';
	
	public function GetSiglapais($ip) {
		$this->IpPq = $this->ConverteIP($ip);
		
		$IPs = DB::table('IPs')
		          ->select('Sigla','Opc')       
		          ->where('IP','=',$this->IpPq)
		          ->first();          													          
		if ($IPs != null) {
			if ($IPs->Sigla!=null) {
				$this->Opc = $IPs->Opc;
			}
			return $IPs->Sigla;
		} else {
			return '';
		}
	}		
	
	public function ConverteIP($IP) {
		$pos = strpos($IP,'.');
		$ip2 = substr($IP,$pos+1);
		$pos = strpos($ip2,'.');
		$ip3 = substr($ip2,$pos);
		$tam3 = strlen($ip3);
		$tam = strlen($IP);
		return substr($IP,0,($tam-$tam3));
	}
	
	public function InsereIP($Sigla) {
		DB::insert('insert into IPs (IP, Sigla) values (?, ?)', [$this->IpPq, $Sigla]);
	}
	
	public function GetOpc() {
		return $this->Opc;	}
}