<?php

namespace App\Traits;
use App\User_has_levels;
use App\Level;
use Carbon\Carbon;
use App\Wallet;
use App\Gift_history;
use App\Tmk_has_campaign;

trait levelUpTrait {

    //funcion para actualizar las transacciones
    public function levelUpMissionCoins($user_id) {

        try{
            //se consulta la campaña del tmk
            $campaign = Tmk_has_campaign::where('user_id',$user_id)->first();
            if(!$campaign){
                return false;
            }
            //se verifica si con la mision y las monedas el usuario'puede subir de nivel                         
            $user_level = User_has_levels::where('user_id', $user_id)->with('level:id,nivel')->get();
            if(count($user_level) > 0){
                $next_level = Level::where('campaign_id', $campaign->campaign_id)->where('nivel', ($user_level[0]->level['nivel'] + 1))->first();
                if(!is_null($next_level)){

                    $date = Carbon::now();
                    $year = $date->format('Y');
                    $date = $date->format('Y-m-d H:i:s');
    
                    $user_missions = Gift_history::where('user_id', $user_id)
                        ->whereBetween('created_at', [$year.'-01-01 00:00:00', $date])
                        ->count();
    
                    $wallet = Wallet::where('user_id', $user_id)->first();
                    //se validan las monedas y misines cumplidas por el usuario
                    if($user_missions >= $next_level->misiones || $next_level->monedas <= $wallet->monedas){
                        //dd("entró: next_level->nivel: ".$next_level->nivel." user_level[0]->level_id: ".$user_level[0]->level_id);
                        $level = Level::where('nivel', $next_level->nivel)->first();
                        $user_level[0]->level_id=$level->id;
                        $user_level[0]->save();
                    }
                }else{
                    return false;
                }
                
            }else{
                return false;
            }
        }catch(Exception $e){

            return $this->sendError('Error al subir nivel'. $e->getMessage());
        }      
        return $user_level;
        

    }

}