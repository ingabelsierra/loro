<?php

namespace App\Traits;
use App\Transaction;


trait transactionTrait {

    //funcion para actualizar las transacciones
    public function updateTransaction($request, $id) {

        $input = $request->all();

        $transaccion = Transaction::find($id);

        if ($transaccion) {
            $transaccion->update($input);
            return $transaccion;
        }else{
            return false;
        }

    }
    //funcion para guardar las transacciones
    public function storeTransaction($request) {

        try{
            
            $transaccion = Transaction::create([                    
                'user_id' => $request->user_id,
                'saldo_anterior' => $request->saldo_anterior,
                'saldo_siguiente' => $request->saldo_siguiente,
                'tabla' => $request->tabla,
                'id_tabla' => $request->id_tabla,
            ]);    

        }catch(Exception $e){
            return $this->sendError('Error al registrar'. $e->getMessage());
        }      
        return $transaccion;

    }

}