<?php 
namespace App\Providers;

class WebsocketProvider
{
    /**  @return array[BeyondCode\LaravelWebSockets\AppProviders\App] */
    public function all(): array;

    public function findByAppId(int $appId): ?App;

    public function findByAppKey(string $appKey): ?App;
}