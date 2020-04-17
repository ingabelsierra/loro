<?php

Route::group(['prefix' => 'v1', 'middleware' => ['cors']], function() {


    //Rutas si autenticar   
    Route::post('login', 'Api\AuthController@login');   
    Route::post('guest', 'Api\GuestController@store');
   
    //Rutas que requiren autenticación
    Route::group(['middleware' => 'auth:api'], function() {
        
        Route::get('logout', 'Api\AuthController@logout');       
        Route::get('winners', 'Api\WinnerController@index');    
        
        
    }); 
});