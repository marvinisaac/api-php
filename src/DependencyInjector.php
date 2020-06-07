<?php

    namespace Api;

    use \Illuminate\Database\Capsule\Manager as CapsuleManager;
    use \Slim\App;
    use Api\Shared\BaseClass\Input;
    use User\User;

final class DependencyInjector
{
    public function inject(App $api) : App
    {
        $container = $api->getContainer();

        $databaseSettings = $container->get('settings')['database'];
        $capsule = new CapsuleManager();
        $capsule->addConnection($databaseSettings, 'default');
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
        $container['database'] = function ($container) use ($capsule) {
            return $capsule;
        };

        $container['User'] = function () {
            $resource = new User();
            return new Input($resource);
        };

        return $api;
    }
}
