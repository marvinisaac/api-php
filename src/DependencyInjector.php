<?php

    namespace Api;

    use \Illuminate\Database\Capsule\Manager as CapsuleManager;
    use \Slim\App;
    use Api\Shared\BaseClass\Input;
    use Api\Shared\BaseClass\Mysql;
    use User\User;
    use User\UserModel;

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
            $database = new Mysql();
            $database->setModel(new UserModel());
            $resource = new User();
            $resource->setDatabase($database);
            return new Input($resource);
        };

        return $api;
    }
}
