<?php

    namespace Api\Shared\BaseClass;

    use \Slim\App;
    use \Slim\Http\Environment;
    use \Slim\Http\Headers;
    use \Slim\Http\Request;
    use \Slim\Http\RequestBody;
    use \Slim\Http\Response;
    use \Slim\Http\Uri;
    use Api\Api;
    use Api\Shared\InterfaceClass\Database;
    use Api\Shared\InterfaceClass\Output;
    use Api\Shared\InterfaceClass\Resource as ResourceInterface;

class Resource implements ResourceInterface
{
    protected Database $database;
    protected Output $output;

    public function setDatabase(Database $database) : void
    {
        $this->database = $database;
    }

    public function setOutput(Output $output) : void
    {
        $this->output = $output;
    }
    
    public function create(array $input) : Response
    {
        return $this->output->success(200, $input);
    }

    protected function checkRequired(array $inputRequired, array $input) : array
    {
        $inputMissing = [];

        foreach ($inputRequired as $parent => $child) {
            if (is_array($child)) {
                if (!isset($input[$parent])) {
                    $inputMissing[] = $parent;
                    continue;
                }
                foreach ($child as $childField) {
                    if (!isset($input[$parent][$childField]) ||
                        trim($input[$parent][$childField]) === ''
                    ) {
                        $inputMissing[] = $parent . '->' . $childField;
                    }
                }
            } else {
                if (!isset($input[$child]) ||
                    trim($input[$child]) === ''
                ) {
                    $inputMissing[] = $child;
                    continue;
                }
            }
        }

        return $inputMissing;
    }

    protected function handleInputMissing(array $inputMissing) : Response
    {
        $errorMessage = 'Missing input: ' . implode(', ', $inputMissing);
        return $this->output->error(400, $errorMessage);
    }
}
