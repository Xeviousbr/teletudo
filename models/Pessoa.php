<?php

use Illuminate\Auth\UserInterface;

class Pessoa extends Eloquent implements UserInterface
{

    // , RemindableInterface

	protected $table = 'pessoa';
	private $id = 0;
	
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}	
	
	public function getAuthPassword()
	{
		return $this->password;
	}

	public function getRememberToken()
	{
		return $this->remember_token;
	}	 	
	
	public function setRememberToken($value)
	{
		$this->remember_token = $value;
	}
	
	public function getRememberTokenName()
	{
		return 'remember_token';
	}  	
	
	public function getReminderEmail()
	{
		return $this->email;
	}	
	
    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }
	
    public function EhCliente() {
        $cliente= DB::table('pessoaperfil')->select(DB::raw('count(*) as Quant'))
            ->where('idPessoa','=',$this->id)
            ->where('idPerfil', '=', 5)
            ->first();
        return $cliente->Quant;
    }
    
    public function setIdCliente($IdCliente)
    {
        $this->IdCliente = $IdCliente;
    }

    public function EhDev($Id)
    {
        $dev= DB::table('pessoaperfil')->select(DB::raw('count(*) as Quant'))
            ->where('idPessoa','=',$Id)
            ->where('idPerfil', '=', 2)
            ->first();
        if ($dev->Quant>0) {
            return true;
        } else {
            return false;
        }
    }

    // CADASTRAMENTO

/*    public function getEstados() {
        $cons= DB::table('estado')
            ->select('Sigla')
            ->where('idPais', '=', 1)
            ->orderBy('Sigla')
            ->get();
        $ret="<option></option>";
        foreach ($cons as $reg) {
            $ret.="<option data-tokens='".$reg->Sigla."'>".$reg->Sigla."</option>";
        }
        return $ret;
    }*/

    // FACE
    public function IDpeloFace($idFace) {
        $Cons = DB::table('pessoa')
            ->select('id')
            ->where('FaceID', '=', $idFace)
            ->first();
        if ($Cons==null) {
            return 0;
        } else {
            return $Cons->id;
        }
    }

    public function VinculaFace($id, $idFace) {
        DB::update("update pessoa set FaceID = ".$idFace." where ID = ".$id);
    }

    public function getDadosUser($idPessoa) {
        $Cons = DB::table('pessoa')
            ->join('endereco', 'endereco.ID', '=', 'pessoa.Endereco_ID')
            ->join('cep', 'cep.id', '=', 'endereco.idCep')
            ->join('cidade', 'cidade.ID', '=', 'cep.idCidade')
            ->join('estado', 'estado.ID', '=', 'cidade.Estado_ID')
            ->select('pessoa.Nome','pessoa.email','pessoa.fone',
                'cidade.NomeCidade','estado.Sigla','cep.NrCep')
            ->where('pessoa.id','=',$idPessoa)
            ->first();
        $Nome = $Cons->Nome;
        $email=$Cons->email;
        $uf=$Cons->Sigla;
        $cidade=$Cons->NomeCidade;
        $UserTT="1";
        $telefone=$Cons->fone;
        $CEP=$Cons->Cep;
        $dados = array($Nome,$email,$uf, $cidade, $UserTT, $telefone, $CEP);
        return $dados;
    }


}