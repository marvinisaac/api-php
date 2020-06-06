<?php

    namespace Api;

    use \Illuminate\Database\Capsule\Manager as CapsuleManager;
    use \Slim\App;

final class DependencyInjector
{
    public function inject(App $api) : App
    {
        $container = $api->getContainer();

        return $api;
    }
}
