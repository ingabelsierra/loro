<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\User_has_campaigns;
use Validator;
use App\Traits\paginationTrait;
use App\Traits\imagesTrait;
use Carbon\Carbon;
use App\Traits\responseTrait;
use App\Traits\registerUser;
use App\Tmk_has_campaign;
use App\Directores_has_campaigns;
use App\Coordinadores_has_campaigns;
use App\Campaign;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;
use App\Traits\getkactusTrait;
use App\Traits\transactionTrait;   
use App\User_has_levels;
use App\Wallet;
use App\Level;
use App\Traits\dateAllTrait;
use DateTime;
use App\BusinessUnitAdmin;

use Illuminate\Support\Facades\DB;


class AuthController extends Controller {

    use paginationTrait, registerUser, imagesTrait, getkactusTrait, transactionTrait,
        responseTrait,dateAllTrait;

    public $successStatus = 200;

    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return $this->sendResponse(true, 'Usuario validado');
        }else{
            return $this->sendResponse(false, 'Usuario o clave incorrecta');
        }
    }
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
                    'name' => 'required',
                    'email' => 'required|email|unique:users,email',
                    'password' => 'required',
                    //'campaigns' => 'required',
                    'c_password' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        $input = $request->all();
        //Creamos el usuario y le asignamos un rol
        $user = User::create([
                    'name' => $input['name'],
                    'email' => $input['email'],
                    'avatar' => "noimagen.png",
                    'frase' => "Sin frase",
                    'no_identificacion' => $input['no_identificacion'],
                    'password' => Hash::make($input['password']), 
        ]);


        //Asignamos el rol        
        $user->assignRole($input['role']);
     
        return $this->sendResponse($user->toArray(), 'Usuario Registrado.');
    }

    public function login() {

        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {

            $user = Auth::user();
                       
            $r = $user->createToken('AppName');
            $token['token'] = $r->accessToken;
            $token['expires_at'] = Carbon::parse($r->token['expires_at'])->toDateTimeString();
            $rol = $user->getRoleNames();
            // dd($rol);
            /*if(!$rol->isEmpty())    {
                switch($rol[0]) {
                    case "Tmk" : {$extraData = Tmk_has_campaign::where('user_id',$user->id)->get();}break;
                    case "Director" : { $extraData = Directores_has_campaigns::where('director_id',$user->id)->get();  }break;
                    case "Coordinador" : {$extraData = Coordinadores_has_campaigns::where('coordinador_id',$user->id)->get(); }break;
                    case "informativo" : {$extraData = User_has_campaigns::where('user_id',$user->id)->get(); }break;  
                    case "AdminUnidad" : {  $unitAdmin=BusinessUnitAdmin::where('user_id',$user->id)->first();
                        $extraData=Campaign::where('business_unit_id', $unitAdmin->business_unit_id)
                        ->get();  }break;  
                    default: $extraData=Campaign::all(); break;                   
                }
            }   else {
                $extraData = []; 
            }*/
            
            $extraData = []; 
            
            $NameCampaigns = [];
            /*foreach($extraData as $data ){
                $dataCampaign = Campaign::with('customer:id,nombre,imagen')->find($data->campaign_id ? $data->campaign_id : $data->id);

                // return var_dump($dataCampaign->nombre);
                $NameCampaigns[] = ["id" => $dataCampaign->id, "name" => $dataCampaign->nombre, "cliente_id" =>$dataCampaign->customer['id'], "cliente_nombre"=>$dataCampaign->customer['nombre'], "cliente_img" => $dataCampaign->customer['imagen']];
            }*/
            $extraData = $NameCampaigns;
            return response()->json(['success' => true, 'data' => $user, 'extra_data' => $extraData, 'message' => $token], $this->successStatus);
        } else {

            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }

    public function getAllUsers(Request $request) {

        if($request->rol == "Coordinador"){        
            $campania = Coordinadores_has_campaigns::where('coordinador_id',$request->user_id)->first();
            if ($request->pagination) {

                $users = User::join('tmk_has_campaigns', 'users.id', '=', 'tmk_has_campaigns.user_id')
                ->where('tmk_has_campaigns.campaign_id', $campania->campaign_id)
                ->paginate($this->pagination($request));
                
            } else {

                $users = User::join('tmk_has_campaigns', 'users.id', '=', 'tmk_has_campaigns.user_id')
                ->where('tmk_has_campaigns.campaign_id', $campania->campaign_id)->get();
            }
        }else{
            if ($request->pagination) {
                $users = User::paginate($this->pagination($request))->all();
            } else {
                $users = User::all();
            }
        }
        //return response()->json(['success' => $users], $this->successStatus); 
        return $this->sendResponse($users, 'Todos los Usuarios.');
    }

    public function show(Request $request, $id) {

        $user = User::find($id);

        if (is_null($user)) {
            return $this->sendError('Usuario no encontrado.');
        }

        $roles = $user->getRoleNames();

        //return response()->json(['success' => $user], $this->successStatus);
        return $this->sendResponse($user->toArray(), 'Detalle Usuario.');
    }

    //logout of the application
    public function logout(Request $request) {
        $request->user()->token()->revoke();
        return response()->json([
                    'message' => 'Successfully logged out'
        ]);
    }
    
    public function update(Request $request, $id) {

        $input = $request->all();
        $user = User::find($id);
        if(array_key_exists("password", $input)) {

            if(Hash::check($input['password'], $user->password)){
                return $this->sendResponse(0, 'No puedes registrar la contraseña antigua');
            }

            $input['password'] = Hash::make($input['password']);
        }
        if(array_key_exists("avatar", $input)){
           $input['avatar'] = $this->convertSaveImageB64($input['avatar'], "avatar");
        }

        $user->update($input);
        $file = Storage::url($user->avatar);

        //return new Response($file,200);
        return $this->sendResponse($user->toArray(), 'Usuario actualizado con exito.');
    }

    public function restorePassword($id) {

        $user = User::where('no_identificacion', $id)->first();
  
        $input['password'] = Hash::make($user->no_identificacion);
        
        $input['change_pw'] = 0;
        

        $user->update($input);
        //return new Response($file,200);
        return $this->sendResponse($user->toArray(), 'Contraseña restablecida con exito.');
    }

    public function getInfoMrChispa($user_id){

        $user = User::find($user_id);
        if(!is_null($user)){
            if($user->mrchispa == "0"){
                $processInfo = $this->getInfo($user->no_identificacion);
                if($processInfo){
                    $user->name = ucwords($processInfo[0]['empleado']['nombres'].' '.$processInfo[0]['empleado']['apellidos']);
                    $user->email = $processInfo[0]['empleado']['email_dos'];
                    $user->mrchispa = "1";
                    $user->save();
                    return $this->sendResponse($user->toArray(), 'Usuario actualizado con exito.');
                }else{
                    return $this->sendError('Mr Chispa no responde, tal vez el usuario no existe');
                }
            }else{
                return $this->sendResponse(true, "Este usuario ya se encuentra actualizado con MrChispa");
            }            
        }else{
            return $this->sendError("El usuario no existe");
        }

    }
    //Funcion para reiniciar niveles y monedas de usuarios de una campaña
    public function restoreCoinsLevels($campaign_id) {

        try{
            $listUserCampaign = Tmk_has_campaign::where('campaign_id', $campaign_id)->get();

            if($listUserCampaign){

                $level_cero = Level::where('nivel', 0)->where('campaign_id', $campaign_id)->first();

                foreach($listUserCampaign as $user){

                    $wallet_user  = Wallet::where('user_id', $user->user_id)->first();

                    $coins = $wallet_user->monedas;
                    $wallet_user->monedas = 0;
                    $wallet_user->save();

                    $level_user = User_has_levels::where('user_id', $user->user_id)->first(); 
                    
                    $level_user->level_id = $level_cero->id;
                    $level_user->save();

                    $dataTransaction = (object) array(
                        'user_id' => $user->user_id,
                        'saldo_anterior' => $coins,
                        'saldo_siguiente' => 0,
                        'tabla' => "Reset",
                        'id_tabla' => 0,
                    );

                    $transaction = $this->storeTransaction($dataTransaction);
                    \Log::info("Transaccion realizada" . $transaction);
                    
                }               
                //return new Response($file,200);
                return $this->sendResponse(true, 'Reset de monedas y niveles exitoso');
                
            }else{
                return $this->sendError('No se encontro la campaña');
            }

 

        }catch (Exception $e) {
            return $this->sendError('Error restablecer monedas y niveles' . $e->getMessage());
        }
    }
    
    public function filterLoginGeneral(Request $request){
        try {
         $rangeDate = strpos($request->date, 'to');
        $filterDate = '';
        $interval = '';
        $dates="";
        if ($rangeDate) {
            $dates = explode(" to ", $request->date);
            $datetime1 = new DateTime($dates[0]);
            $datetime2 = new DateTime($dates[1]);
            $interval = $datetime1->diff($datetime2);
            $filterDate = 'Date(created_at) as date';
        } else {
            $filterDate = 'Hour(created_at) as date';
        }
        $signInTotal=[];
        if (count($request->campaigns) == 0) {
            if(count($request->client) == 0){
                $campaigns = Campaign::all()->pluck('id')->toArray();
            }else{
                $campaigns = Campaign::whereIn('customer_id',$request->client)->pluck('id')->toArray();
            }
        } else {
            $campaigns = $request->campaigns;
        }

        
       
        foreach ($campaigns as $campaign) { 

            $key = Campaign::find($campaign);      
            if($rangeDate){
                $userSignIn = DB::table('oauth_access_tokens')
                ->whereDate('created_at','>=', Carbon::parse($dates[0])->format('Y-m-d'))
                ->whereDate('created_at','<=', Carbon::parse($dates[1])->format('Y-m-d'));
            }else{
                $userSignIn = DB::table('oauth_access_tokens')
                ->whereDate('created_at',Carbon::parse($request->date)->format('Y-m-d'));
            }
            $userSignIn = $userSignIn
            ->select(DB::raw($filterDate),DB::raw('count(*) as signin'))
            ->whereIn('user_id',$this->campaign($campaign))
            ->where('revoked',0)
            ->groupBy('date')
            ->get();
            
            $signInTotal[$key->nombre]=$this->dateAll($rangeDate,$interval,json_decode($userSignIn,true),$dates,'signin');
        }
        return $this->sendResponse($signInTotal, 'Ingresos totales');
    } catch (Exception $e) {
        return $this->sendError('Error al consultar ventas');
    }
    }
    public function campaign($campaignId){

        if($campaignId !='Todos'){

        $usersTkm=Tmk_has_campaign::where('campaign_id',$campaignId)
        ->pluck('user_id')
        ->toArray();
        $userCoor=Coordinadores_has_campaigns::where('campaign_id',$campaignId)
        ->pluck('coordinador_id')
        ->toArray(); 

        $users=array_merge($usersTkm,$userCoor);
            return $users;
        }
    }
  

}