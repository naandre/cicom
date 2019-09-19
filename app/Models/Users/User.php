<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;

/**
 * Class User
 * @package App\Models\Users
 *
 * @property int $id
 * @property string $name
 * @property string $lastname
 * @property string $email
 *
 * @property Role $roles
 */
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

    /**
     * @var array
     */
    public $fields = [
        'name'=>['options'=>['required'=>'required']],
        'lastname'=>['options'=>['required'=>'required']],
        'user'=>['options'=>['required']],
        'email'=>['options'=>['required']],
        'role_id'=>[
            'type'=>'select',
            'take'=>['id','display_name'],
            'from'=>'roles',
            'order'=>['display_name'],
            'options'=>['placeholder'=>'Seleccione un rol...','required']
        ],
        'password'=>['kind'=>'password','options'=>['required'=>'required']],
        'password_confirmation'=>['type'=>'password','options'=>['required'=>'required']]
    ];


    /**
     * @var array
     */
    public $schemas = [
        'userTable' => [
            'id',
            'name',
            'lastname',
            'email',
            '_links'
        ]
    ];

    /**
     * @var array
     */
    public $links = [
        'userTable' => [
            ['Ver', 'admin.users.show', 'id'],
            ['Editar', 'admin.users.edit', 'id'],
            ['Eliminar', 'admin.users.destroy', 'id','destroy']
        ],
    ];

    /**
     * @var array
     */
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
    /**
     * remueve el rol indicado al usuario
     * @param $roleId - identificador del rol a remover
     */
    public function removeRole($roleId){
        $this->roles()->detach($roleId);
    }

    /**
     * obtiene y retorna el id del rol, asignado al usuario
     * @return int|null
     */
    public function getRoleId(){
        /**@var Role $role*/
        if($role=$this->roles()->first())
            return $role->id;
        return null;
    }

    public function changeRole(int $newRoleId){
        if(($oldRoleId=$this->getRoleId()) and $oldRoleId!=$newRoleId){
            $this->removeRole($oldRoleId);
            $this->addRole($newRoleId);
        }elseif (!$oldRoleId) $this->addRole($newRoleId);
    }
}
