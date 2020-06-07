<?php

    namespace User;

    use \Illuminate\Database\Eloquent\Model;
    
class UserModel extends Model
{
    protected $table = 'users';
    protected $fillable = [
        'username',
        'password',
    ];
}
