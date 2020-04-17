<?php

namespace App\Traits;
use Illuminate\Support\Facades\Storage;
use App\User_has_levels;
use App\Level;
use Carbon\Carbon;
use App\Wallet;
use App\Gift_history;

trait imagesTrait{

    //funcion para convertir b64 a archivo imagen
    public function convertSaveImageB64($image_avatar_b64, $name) {

        // Obtener los datos de la imagen
        $img = $this->getB64Image($image_avatar_b64);

        
        // Obtener la extensión de la Imagen
        $img_extension = $this->getB64Extension($image_avatar_b64);

        // Crear un nombre aleatorio para la imagen
        $img_name = $name."_". time() . '.' . $img_extension;   
        // Usando el Storage guardar en el disco creado anteriormente y pasandole a 
        // la función "put" el nombre de la imagen y los datos de la imagen como 
        // segundo parametro
        Storage::disk('images')->put($img_name, $img);
        
        return $img_name; 
        
    }

    public function getB64Image($base64_image){  
        // Obtener el String base-64 de los datos         
        $image_service_str = substr($base64_image, strpos($base64_image, ",")+1);
        // Decodificar ese string y devolver los datos de la imagen        
        $image = base64_decode($image_service_str);   
        // Retornamos el string decodificado
        return $image; 
    }

    public function getB64Extension($base64_image, $full=null){  
        // Obtener mediante una expresión regular la extensión imagen y guardarla
        // en la variable "img_extension"    
    
        preg_match("/^data:image\/(.*);base64/i",$base64_image, $img_extension);   

        // Dependiendo si se pide la extensión completa o no retornar el arreglo con
        // los datos de la extensión en la posición 0 - 1
        return ($full) ?  $img_extension[0] : $img_extension[1];  
    }

}