<?php

    namespace Session;

    use \Slim\Http\Response;
    use Api\Shared\BaseClass\Resource;

final class Session extends Resource
{
    public function create(array $input) : Response
    {
        $inputRequired = [
            'username',
            'password',
        ];
        $inputMissing = $this->checkRequired($inputRequired, $input);
        if (count($inputMissing) > 0) {
            return $this->output->error(400, 'Invalid username and password combination.');
        }

        $userSearch = $this->run('GET', '/user/' . $input['username']);
        if ($userSearch['status'] !== 200) {
            return $this->output->error(400, 'Invalid username and password combination.');
        }

        return $this->output->success(200);
    }

    public function readAll() : Response
    {
        return $this->disabledOperation();
    }

    public function readBy(string $identifier) : Response
    {
        return $this->disabledOperation();
    }

    public function updateBy(string $identifier, array $input): Response
    {
        return $this->disabledOperation();
    }

    public function deleteBy(string $identifier) : Response
    {
        return $this->disabledOperation();
    }
}
