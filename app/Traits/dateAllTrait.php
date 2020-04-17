<?php

namespace App\Traits;
use Carbon\Carbon;
use App\Mission;

trait dateAllTrait {

    public function dateAll($rangeDate,$interval="",$values,$dates="",$type){
        
        $valuesTemp = [];
        $dateValue=false;

        if($rangeDate){
            for ($i = 0; $i <= $interval->days; $i++) {

                foreach ($values as $value) {
                    if (Carbon::parse($dates[0])->addDays($i)->toDateString() === $value['date']) {
                        $valuesTemp[$i] = ["date" => Carbon::parse($value['date'])->isoFormat('YY/M/D'), $type => $value[$type]];
                        $dateValue=true;
                    }
                }
                if(!$dateValue){
                    $valuesTemp[$i] = ["date" => Carbon::parse($dates[0])->addDays($i)->isoFormat('YY/M/D'), $type => 0];
                }
                $dateValue=false;
            }
           }else{
            for ($i = 0; $i <= 24; $i++) {
                foreach ($values as $value) {
                    if ($i === $value['date']) {
                        $valuesTemp[$i] = ["date" => $value['date'], $type => $value[$type]];
                        $dateValue=true;
                    }
                }
                if(!$dateValue){
                    $valuesTemp[$i] = ["date" => $i, $type => 0];
                }
                $dateValue=false;
            }
           }
        return $valuesTemp;
    }

   
}