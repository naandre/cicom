<?php

namespace App\Models\Users;


use Zizaco\Entrust\EntrustRole;

/**
 * Class Role
 * @package App\Models\Users
 *
 * @property int $id
 * @property string $name
 * @property string $display_name
 * @property string $description
 */
class Role extends EntrustRole
{
    /**
     * @var array
     */
    protected $fillable=['name','display_name','description'];

    /**
     * @var array
     */
    public $fields = [
        'name'=>['options'=>['required'=>'required']],
        'display_name'=>['options'=>['required'=>'required']],
        'description'=>['kind'=>'textarea'],
        'permissions'=>[
            'type'    => 'checkbox',
            'group'   => [
                'from'=>'permissions',
                'take'=>['id','display_name'],
                'order'=>['display_name','ASC']
            ],
            'options' => ['required']
        ]
    ];


    /**
     * @var array
     */
    public $schemas = [
        'roleTable' => [
            'id',
            'name',
            'display_name',
            '_links'
        ]
    ];

    /**
     * @var array
     */
    public $links = [
        'roleTable' => [
            ['Editar', 'admin.roles.edit', 'id'],
            ['Eliminar', 'admin.roles.destroy', 'id','destroy']
        ],
    ];

    /**
     * @var array
     */
    public $routes = [
        'edit'   => 'admin.roles.update',
        'create' => 'admin.roles.store'
    ];

    /**
     * Sincroniza los permisos del rol
     * @param array $permissionIds
     */
    public function synPermissions(array $permissionIds){
        $this->perms()->sync($permissionIds);
    }

    /**
     * agrega permisos al rol
     * @param array $permissions
     */
    public function addPermisions(array $permissions){
        $this->perms()->attach($permissions);
    }

    public function getPermissionIds():array {
        return $this->perms()->get()->modelKeys();
    }
}
