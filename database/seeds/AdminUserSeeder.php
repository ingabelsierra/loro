<?php

use App\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $user=User::create([
            'name' => 'Administrador',
            'email' => 'ingabelsierra@gmail.com',
            'avatar' => 'noimagen.jpg',
            'frase' => 'sin frase',
            'no_identificacion' => '123456789',
            'password' => bcrypt('sierra'),            
        ]);
        
        /*$user=User::create([
            'name' => 'Pedro Pablo',
            'email' => 'pedropablo@gmail.com',
            'avatar' => 'noimagen.jpg',
            'frase' => 'sin frase',
            'no_identificacion' => '123456789',
            'password' => bcrypt('sierra'),            
        ]);*/
          
    }
}
