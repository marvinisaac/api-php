<?php

    namespace Session;

    use \Carbon\Carbon;
    use \Slim\Http\Cookies;
    use \Slim\Http\Response;
    use Api\Shared\BaseClass\Input;
    use Session\SessionOutput;

final class SessionInput extends Input
{
    protected function setOutput(Response $response) : SessionOutput
    {
        $output = new SessionOutput($response);
        $this->resource->setOutput($output);

        return $output;
    }
}
