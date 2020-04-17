<?php

namespace App\Traits;
use Carbon\Carbon;
use App\Mission;

trait fechasTrait {

    //funcion para ontener las fechas segun la recurrencia 
    public function getFecha($fecha_inicio, $fecha_fin, $recurrencia, $mission_id) {
        //crear migracion con los dos campos rengo_inicio y rango_fin y que la fecha_inicio y fin se actualicen cuando se cierre la mission
/*
        try{
            $fecha_actual = Carbon::now();
            switch ($recurrencia) {
                case 1:

                    $fecha_final = date('Y-m-d H:i:s',strtotime($fecha_inicio."+ 1 days"));


                    if ($fecha_final > $fecha_fin){

                        $mission = Mission::find($mission_id);
                        $mission->estado = 0;
                        $mission->save();

                    }

                    break;

                case 2:

                    break;

                case 3 :


                    break;

                case 4 :

                    $fecha_inicio = date('Y-m-d H:i:s',strtotime($fecha_inicio."+ 1 days"));

                    break;

                default:
                    return false;
                    break;
            }









        }catch(Exception $e){

            return $this->sendError('Error al subir nivel'. $e->getMessage());
        }      
        return $user_level;
        */

    }

}