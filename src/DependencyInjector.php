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

        $container['User'] = function () {
            $resource = new User();
            return new Input($resource);
        };

        return $api;
    }
}
