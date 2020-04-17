<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Winner;
use App\Traits\paginationTrait;
use App\Traits\responseTrait;
//use App\Traits\imagesTrait;
/*use App\User_has_notifications;

use App\Events\StoreNotificationEvent;

use Illuminate\Support\Facades\Log;*/

class WinnerController extends Controller {

    use paginationTrait,// imagesTrait,
        responseTrait;

    public function index() {
        
        $winner = Winner::all();        

        return $this->sendResponse($winner, 'Todos los Usuarios Ganadores.');
    }

    public function show($id) {

       
    }

    public function store(Request $request) {
        
        //Obtenemos los enviados por POST
        $input = $request->all();       
        $winner = Winner::create($input);        
        
        return $this->sendResponse($winner, 'Ganador Registrado');
    }
   

}
