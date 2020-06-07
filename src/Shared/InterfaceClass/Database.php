<?php

    namespace Api\Shared\InterfaceClass;

    use \Illuminate\Database\Eloquent\Model;

interface Database
{
    public function getModel() : Model;

    public function setModel(Model $model) : void;

    public function create(array $input) : array;

    public function readAll() : array;

    public function readBy(string $column, string $identifier) : array;
}
