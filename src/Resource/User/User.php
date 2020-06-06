<?php

    namespace User;

    use \Slim\Http\Response;
    use Api\Shared\BaseClass\Resource;

final class User extends Resource
{
    public function create(array $input) : Response
    {
        $inputRequired = [
            'username',
            'password',
        ];
        $inputMissing = $this->checkRequired($inputRequired, $input);
        if (count($inputMissing) > 0) {
            return $this->handleInputMissing($inputMissing);
        }

        return $this->output->success(200, $input);
    }
}
