<?php

class Google
{

	// O ideal é colocar aqui todas as integrações do google
	// Mas se tiver muitas e poderem ser agrupadas
	   // então o ideal é criar uma pasta e classes para agrupamentos de funlções

    private $Kms=0;
    private $TmpPrevisto=0;

    public function PrevisaoGoogle($latF, $lonF, $latC, $lonC) {
        $ori="&origins=".$latF.",".$lonF;
        $dest="&destinations=".$latC.",".$lonC;
        $request_url = "https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&".$ori.$dest;

        // echo $request_url; die;

        $data = file_get_contents($request_url);
        $data = json_decode($data);

        $time = 0;
        $distance = 0;
        foreach($data->rows[0]->elements as $road) {
            $time += $road->duration->value;
            $distance += $road->distance->value;
        }
        $kms=$distance/1000;

/*        $this->Kms=$kms;
        $this->TmpPrevisto=$time;*/
        $this->setKms($kms);
        $this->setTmpPrevisto($time);
    }

    public function setKms($Kms) {
        $this->Kms=$Kms;
    }

    public function getKms() {
        return $this->Kms;
    }

    public function setTmpPrevisto($TmpPrevisto) {
        $this->TmpPrevisto=$TmpPrevisto;
    }

    public function getTmpPrevisto() {
        return $this->TmpPrevisto;
    }

}