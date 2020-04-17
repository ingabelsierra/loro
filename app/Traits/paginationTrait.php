<?php

namespace App\Traits;
//use Illuminate\Support\Facades\DB;

trait paginationTrait {

    public function pagination($request) {

        if ($request->pagination == true) {
            $paginate = 10;
            if (isset($request->per_page)) {
                $paginate = $request->per_page;
            }
        } else {
            $request->has('per_page') ? ($paginate = $request->per_page) : $paginate;
        }


        return $paginate;
    }

}
