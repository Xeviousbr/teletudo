<?php  
class ContatosController extends BaseController {  
  public function form()  
  {   	    
    return View::make('contatos.index');  
  }  
  
  public function send()  
  {  
    $input = Input::all();  
    Mail::send('emails.contatos.index', $input, function($message) {  
      $message->to('xeviousbr@gmail.com')->replyTo(Input::get('email'))->subject('Contato do site');  
    });  
    return Redirect::to('form');  
  }  
} 
//
//
//<?php
//class ContatosController extends Controller {
//
////	protected function setupLayout()
////	{
////		if ( ! is_null($this->layout))
////		{
////			$this->layout = View::make($this->layout);
////		}
////	}
//	
//	public function Aciona() {
//		 return View::make('contatos.index');
//	}	
//
//}