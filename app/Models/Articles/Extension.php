<?php

namespace App\Models\Articles;

use Illuminate\Database\Eloquent\Model;

class Extension extends Model
{
    protected $table="extensions";
    protected $fillable=["name","state"];
    /**
     * @var array
     */
    public $fields = [
        'name'=>['options'=>['required'=>'required']],
        'state'=>[
            'type'=>'select',
            'group'=>'state',
            'options'=>['placeholder'=>'Seleccione una opción...','required'=>'required']
        ]
    ];
    /**
     * @var array
     */
    public $schemas = [
        'extensionTable' => [
            'id',
            'name',
            'state',
            '_links'
        ]
    ];
    /**
     * @var array
     */
    public $links = [
        'extensionTable' => [
            ['Editar', 'admin.extension.edit', 'id'],
            ['Eliminar', 'admin.extension.destroy', 'id','destroy']
        ],
    ];
    /**
     * @var array
     */
    public $routes = [
        'edit'   => 'admin.extension.update',
        'create' => 'admin.extension.store'
    ];
}
