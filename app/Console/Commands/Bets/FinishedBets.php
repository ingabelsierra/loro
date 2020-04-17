<?php

namespace App\Console\Commands\Bets;

use Illuminate\Console\Command;
use App\Duel;
use App\Wallet;
use App\Bet;
use App\Events\finishedBetUserEvent;
use App\Events\RefreshUserInfoEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\transactionTrait;
use App\Traits\responseTrait;


class FinishedBets extends Command
{
    use transactionTrait,
        responseTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'finished:bets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Realizar las transaciones para devolver el dinero sobrante a los usuarios';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        \Log::info("Ejecutando comando");
        DB::beginTransaction();
        try {
        $carbon = new \Carbon\Carbon();  
        $date = $carbon->now(); 
        $duels=Duel::where('fecha_inicio','<',$date)->where('state','Apostando')->get()->toArray();
        if(count($duels) > 0){
            foreach ($duels as $duel) {
                $duelBets=Duel::find($duel['id']);
                //Obtener las apuestas finales
                $totalBet=Bet::select(DB::raw('SUM(betting) as total_bets'))->where('duel_id',$duel['id'])->groupBy('user_duel')->get()->toArray();
                //Obtener la menor apuesta, para obtener apuesta base
                $totalBet=min($totalBet);
                $betsDuel=Bet::where('duel_id',$duel['id'])->get()->toArray();
                $userBetsArray=[];
                foreach ($betsDuel as $bet) {
                    //Se reparte proporcionalmente las apuestas
                     $totalBetUser=Bet::where('user_duel',$bet['user_duel'])->sum('betting');
                     $finalBetUser=(($bet['betting']*$totalBet['total_bets'])/$totalBetUser);
                     $returnMoney=$bet['betting']-$finalBetUser;
                     $returnMoney=round($returnMoney*100)/100;

                     array_push($userBetsArray,['bet_id'=>$bet['id'],'bet'=>round($finalBetUser*100)/100]);

                    \Log::info($totalBetUser);
                     if((int)$returnMoney!=0){
                        $wallet=Wallet::where('user_id',$bet['user_betting'])->first();
                        $newWallet = $wallet->monedas+$returnMoney;
                        $dataTransaction = (object) array(
                            'user_id' => $bet['user_betting'],
                            'saldo_anterior' => $wallet->monedas,
                            'saldo_siguiente' => $newWallet,
                            'tabla' => "bets",
                            'id_tabla' => $bet['id'],
                        );
                        $transaction = $this->storeTransaction($dataTransaction);
                        \Log::info("Transaccion realizada" . $transaction);
                        $wallet->monedas=$newWallet;
                        $wallet->save();
                     }
                }
                //Guardar cambios de apuestas
                foreach ($userBetsArray as $userBet) {
                    $betUser=Bet::find($userBet['bet_id']);  
                    $betUser->betting=$userBet['bet'];
                    broadcast(new finishedBetUserEvent($betUser->user_betting_user,$betUser));
                    $betUser->save();
                }
                $duelBets->state=Duel::COMPETING;
                $duelBets->save();
            }  
        }
        DB::commit(); 
        return 'Datos Actualizados';  
            } catch (\Exception $e) {
        DB::rollback();
        return $this->sendError($e);
    }
    }
}
