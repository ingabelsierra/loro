<?php 
namespace App\Traits;

use Illuminate\Http\Request;
use App\User;
use App\Tmk_has_campaign;
use App\Coordinadores_has_campaigns;
use App\Directores_has_campaigns;
use App\Gerente_has_campaigns;
use App\Wallet;
use App\User_has_levels;


 trait registerUser
 {
     
    public function registerInfo($campanias, $user_id, $rol)
    {
        if (isset($rol) && $rol == "Gerente") {

            for($i = 0; $i < count($campanias); $i++){
                $gerenteCampania = Gerente_has_campaigns::create([
                'campaign_id' => $campanias[$i]['id'],
                'user_id' => $user_id
            ]);

                $data[$i] = $gerenteCampania;
            }
            
            
        }else{
            if (isset($rol) && $rol == "Director") {
                
                for($i = 0; $i < count($campanias); $i++){
                    $DirectorCampania = Directores_has_campaigns::create([
                    'campaign_id' => $campanias[$i]['id'],
                    'director_id' => $user_id
                ]);

                    $data[$i] = $DirectorCampania;
                }
                                
            }else{
                if (isset($rol) && $rol == "Coordinador") {

                    for($i = 0; $i < count($campanias); $i++){
                        $CoordinadorCampania = Coordinadores_has_campaigns::create([
                        'campaign_id' => $campanias[$i]['id'],
                        'coordinador_id' => $user_id
                    ]);

                        $data[$i] = $CoordinadorCampania;
                    }
            
                    
                }else{
                    if (isset($rol) && $rol == "Tmk") {

                        $tmkhcampaing = Tmk_has_campaign::create([
                                    'campaign_id' =>  $campanias[0]['id'],
                                    'user_id' => $user_id
                        ]);

                        //creamos el registro en la billetera
                        $wallet = Wallet::create([
                                    'user_id' => $user_id,
                                    'monedas' => 0
                        ]);
                        //Creamos el registro en la tabla User_has_levels
                        $userhlevel = User_has_levels::create([
                                    'user_id' => $user_id,
                                    'level_id' => 1
                        ]);

                        $data[0] = $tmkhcampaing;
                        
                    }
                }  
            }   
        }
        //count($campanias)
        if(count($data) == count($campanias)){
            return true;
        }else{

            if($rol == "Tmk"){
                $wallet->delete();
                $userhlevel->delete();
            }
            
            for($i = 0; $i < count($data); $i++){
                $data[$i]->delete();
            }

            return false;
        }
    }
 }  