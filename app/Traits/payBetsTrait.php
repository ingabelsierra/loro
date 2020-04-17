<?php

namespace App\Traits;
use App\Bet;
use App\Notification;
use Carbon\Carbon;
use App\Wallet;
use App\Transaction;
use App\Tmk_has_campaign;
use App\Duel;
use App\Traits\transactionTrait;

trait payBetsTrait {


    //funcion para pagar las apuestas realizadas
    public function payBets($duel_id) {

        try{
            //Consulta la info del duelo
            $duel = Duel::find($duel_id);
            $winner_name = User::find($duel->user_ganador);
            $opp_1 = User::find($duel->user_retador);
            $opp_2 = User::find($duel->user_retado);
            if($duel){
                //Se consultan las apuestas de ese duelo (ganadores y perdedores)
                $winners_bets = Bet::where('duel_id',$duel->id)->where('user_duel', $duel->user_ganador)->get();
                $losers_bets = Bet::where('duel_id',$duel->id)->where('user_duel', '!=',$duel->user_ganador)->get();
                foreach($winners_bets as $winner){
                    $winner->winnered = '1';
                    $winner->save();
                }
                foreach($losers_bets as $loser ) {
                    $loser->winnered = '0';
                    $loser->save();
                }
                $tmk_campaign = Tmk_has_campaign::where('user_id', $winner->user_betting)->first();
                $campaign = $tmk_campaign->campaign_id;
                //Se consulta el monto total que se gano
                $amount = Bet::where('duel_id', $duel->id)->where('winnered', '0')->sum('betting');
                foreach($winners_bets as $winner){
                    //se calcula el porcentaje y ganancia de la apuesta
                    $porcent_gain = ( $winner->betting * 100 ) / $amount;
                    $gain = ( $amount * $porcent_gain ) / 100;
                    //asiganmos las monedas a la billetera
                    $wallet = Wallet::where('user_id', $winner->user_betting)->first();
                    $current_coins = $wallet->monedas;
                    // se verifica que la villetera exista
                    if($wallet){
                        $wallet->monedas = $current_coins + $gain;
                        $wallet->save();
                        //se crea la transacción
                        $dataTransaction = (object) array(
                            'user_id' => $winner->user_betting,
                            'saldo_anterior' => $current_coins,
                            'saldo_siguiente' => $wallet->monedas,
                            'tabla' => "bets",
                            'id_tabla' => $winner->id,
                        );

                        $transaction = $this->storeTransaction($dataTransaction);

                        \Log::info("Transaccion realizada" . $transaction);
                    }else{
                        return $this->sendError('El usuario no tiene billetera para pagar apuesta ', $winner);
                    }
                    //se envia notificación a todos los ganadores
                    
                    $notificacion = Notification::create([
                        'titulo' => "Ganaste la apuesta del duelo " . $opp_1->name . " VS " . $opp_2->name,
                        'descripcion' => "El ganador del duelo fue: " . $winner_name->name,
                        'estado' => "1",
                        'imagen' => "noimagen.png",
                        'tipo' => "bet",
                        'campania_id' => $campaign,
                    ]);

                }
                // se le envia la notificación a todos los perdedores
                foreach($losers_bets as $loser){
                    $notificacion = Notification::create([
                        'titulo' => "Perdiste la apuesta del duelo " . $opp_1->name . " VS " . $opp_2->name,
                        'descripcion' => "El ganador del duelo fue: " . $winner_name->name,
                        'estado' => "1",
                        'imagen' => "noimagen.png",
                        'tipo' => "bet",
                        'campania_id' => $campaign,
                    ]);
                }
                
            }
        }catch(Exception $e){

            return $this->sendError('Error al pagar apuestas'. $e->getMessage());
        }      
        return $winners_bets;
        

    }

}