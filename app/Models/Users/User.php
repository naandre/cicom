<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Authenticatable
{
    use Notifiable,SoftDeletes,EntrustUserTrait{
        SoftDeletes::restore insteadof EntrustUserTrait;
        EntrustUserTrait::restore insteadof SoftDeletes;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','lastname','user', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public $fields = [
        'name'=>['options'=>['required'=>'required']],
        'lastname'=>['options'=>['required'=>'required']],
        'user'=>['options'=>['required']],
        'email'=>['options'=>['required']],
        'password'=>['kind'=>'password','options'=>['required'=>'required']],
        'password_confirmation'=>['type'=>'password','options'=>['required'=>'required']]
    ];


    public $schemas = [
        'userTable' => [
            'id',
            'name',
            'lastname',
            'email',
            '_links'
        ]
    ];

    public $links = [
        'userTable' => [
            ['Ver', 'admin.users.show', 'id'],
            ['Editar', 'admin.users.edit', 'id'],
            ['Eliminar', 'admin.users.destroy', 'id','destroy']
        ],
    ];

    public $routes = [
        'edit'   => 'admin.users.update',
        'create' => 'admin.users.store'
    ];

    /**
     * agrega el rol indicado al usuario
     * @param $roleId - identificador del rol a agregar al usuario
     */
    public function addRole($roleId){
        $this->roles()->attach($roleId);
    }
}
