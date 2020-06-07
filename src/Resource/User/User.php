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

    public function readAll() : Response
    {
        $result = $this->database->readAll();
        
        if (!$result['success'] ?? false) {
            return $this->output->error(500, 'Database error.');
        }

        $resultAll = $result['details'];
        $fieldPublic = [
            'username',
            'created_at',
        ];
        $resultFiltered = [];
        foreach ($resultAll as $result) {
            $resultFiltered[] = $this->filterReadResults($fieldPublic, $result);
        }
        return $this->output->success(200, $resultFiltered);
    }

    public function readBy(string $identifier) : Response
    {
        $column = 'username';
        $result = $this->database->readBy($column, $identifier);
        
        if (!$result['success'] ?? false) {
            return $this->output->error(500, 'Database error.');
        }

        $result = $result['details'][0] ?? [];
        if (count($result) === 0) {
            return $this->output->error(400, 'Username not found.');
        }

        $fieldPublic = [
            'username',
            'created_at',
        ];
        $resultFiltered = $this->filterReadResults($fieldPublic, $result);
        return $this->output->success(200, $resultFiltered);
    }
}
