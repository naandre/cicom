<?php

namespace App\Models\Users;


use Zizaco\Entrust\EntrustPermission;

/**
 * Class Permission
 * @package App\Models\Users
 *
 * @property int $id
 * @property string $name
 * @property string $display_name
 * @property string $description
 */
class Permission extends EntrustPermission
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
        'description'
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
            ['Editar', 'admin.permissions.edit', 'id'],
            ['Eliminar', 'admin.permissions.destroy', 'id','destroy']
        ],
    ];

    /**
     * @var array
     */
    public $routes = [
        'edit'   => 'admin.permissions.update',
        'create' => 'admin.permissions.store'
    ];
}
