<?php

    namespace Session;

    use \Slim\Http\Response;
    use Api\Shared\BaseClass\Resource;
    use Session\SessionToken;
    use Session\SessionOutput;

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

        $username = $input['username'];
        $userSearch = $this->run('GET', '/user/' . $username);
        if ($userSearch['status'] !== 200) {
            return $this->output->error(400, 'Invalid username and password combination.');
        }

        $tokenService = new SessionToken();
        $token = [
            'token' => $tokenService->createFor($username)
        ];
        return $this->output->success(200, $token);
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
