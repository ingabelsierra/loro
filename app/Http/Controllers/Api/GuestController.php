<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Guest;
use App\Winner;
use App\Traits\paginationTrait;
use App\Traits\responseTrait;
use Illuminate\Support\Facades\DB;

class GuestController extends Controller {

    use paginationTrait,// imagesTrait,
        responseTrait;

    public function index() {
        
        $guest = Guest::all();        

        return $this->sendResponse($guest, 'Todos los Usuarios Invitados.');
    }

    public function show($id) {

        $guest = Guest::find($id);

        if (is_null($guest)) {
            return $this->sendError('Invitado no encontrada.');
        }


        return $this->sendResponse($notification->toArray(), 'Noticia encontrada.');
    }

    public function store(Request $request) {
        
        //Obtenemos los enviados por POST
        $input = $request->all();  
        
        //Validammos que el email exista      
        $valida_email = DB::table('guests')->where('correo', $request->correo)->first();
        
        //sino existe lo creamos
        if (!$valida_email) {
            
        $guest = Guest::create($input);
        
        //dd($guest->correo);
            
        $mensaje="Lo siento sigue participando...";
        
        //si el usuario es ganador, 
        if($guest->id%5==0)
        {            
            $mensaje="Eres un feliz ganador, espera un correo con las instrucciones para reclamar el premio.";          
            
            $winner = new Winner;
            $winner->guest_id = $guest->id;
            $winner->email = $guest->correo;
            $winner->observaciones = "ganador";
            $winner->save();           
            
        }        

        return $this->sendResponse($mensaje, 'Usuario Invitado Registrado');
       
        } 
        
        else {
             return $this->sendResponse('El Usuario ya se encuentra registrado', '');
        
        }


        
    }
   

}
