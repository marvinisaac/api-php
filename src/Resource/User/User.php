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

        $user = [
            'username' => $input['username'],
            'password' => password_hash($input['password'], PASSWORD_DEFAULT),
        ];

        $result = $this->database->create($user);
        
        $errorMessage = $result['error_message'] ?? '';
        if (strrpos($errorMessage, 'Duplicate entry') !== false) {
            return $this->output->error(400, 'Username already exists.');
        }

        if (!$result['success'] ?? false) {
            return $this->output->error(500);
        }

        unset($input['password']);
        return $this->output->success(200, $input);
    }
}
