<?php 
namespace App\Traits;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Client;

 trait getkactusTrait
 {
     
     public function getInfo($cedula)
     {
        
        $client = new Client(['timeout' => 5.0]);
        if (isset($cedula)) {
            $promise = $client->getAsync('https://kactus.brm.com.co:8443/kactus/cat/' . $cedula)
                ->then(function (ResponseInterface $response) {
            //retorna la informacion
                return $response->getBody();
            }, function (RequestException $exepcion) {
            //retorna mensaje en caso de error
                return $exepcion->getMessage();
            });
            $response = $promise->wait();
            return $data = json_decode($response, true);
        }
        
    }
 }  