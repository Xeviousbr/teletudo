<?php

class RenovarController extends BaseController
{
    public function show($id)
    {
       $pagar = Pagar::find($id);
       return View::make('renovar.show')
            ->with('pagar', $pagar);
    }
}
    
    