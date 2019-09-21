<?php

namespace App\Models\Articles;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table="categories";
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
        'categoryTable' => [
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
        'categoryTable' => [
            ['Editar Categoría', 'admin.category.edit', 'id'],
            ['Eliminar Categoría', 'admin.category.destroy', 'id','destroy']
        ],
    ];
    /**
     * @var array
     */
    public $routes = [
        'edit'   => 'admin.category.update',
        'create' => 'admin.category.store'
    ];
}
