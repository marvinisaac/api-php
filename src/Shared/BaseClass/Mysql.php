<?php

    namespace Api\Shared\BaseClass;

    use \Illuminate\Database\Eloquent\Model;
    use \Illuminate\Database\QueryException;
    use Api\Shared\InterfaceClass\Database as DatabaseInterface;

class Mysql implements DatabaseInterface
{
    protected Model $model;

    public function getModel() : Model
    {
        return $this->model;
    }

    public function setModel(Model $model) : void
    {
        $this->model = $model;
    }

    public function create(array $input) : array
    {
        try {
            $details = $this->model::create($input);
            return [
                'success' => true,
                'details' => $details,
            ];
        } catch (QueryException $e) {
            error_log('>>> MySQL error: ' . $e->getMessage());
            return [
                'success' => false,
                'error_message' => $e->getMessage(),
            ];
        }
    }
}
