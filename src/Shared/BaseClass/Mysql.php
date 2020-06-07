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

    public function readAll() : array
    {
        try {
            $detailsAll = $this->model::get()
                ->toArray();
            return [
                'success' => true,
                'details' => $detailsAll,
            ];
        } catch (QueryException $e) {
            error_log('>>> MySQL error: ' . $e->getMessage());
            return [
                'success' => false,
                'error_message' => 'Database error.',
            ];
        }
    }

    public function readBy(string $column, string $identifier) : array
    {
        try {
            $details = $this->model::where($column, $identifier)
                ->get()
                ->toArray();
            return [
                'success' => true,
                'details' => $details,
            ];
        } catch (QueryException $e) {
            error_log('>>> MySQL error: ' . $e->getMessage());
            return [
                'success' => false,
                'error_message' => 'Database error.',
            ];
        }
    }
}
