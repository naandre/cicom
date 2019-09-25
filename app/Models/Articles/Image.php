<?php

namespace App\Models\Articles;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $table="images";
    protected $fillable=["name","image"];
    /**
     * @var array
     */
    public $fields = [
        'name'=>['options'=>['required'=>'required']],
        'image'=>[
            'type'=>'file',
            'options'=>['required'=>'required']
        ]
    ];
    /**
     * @var array
     */
    public $schemas = [
        'imageTable' => [
            'id',
            'name',
            'image',
            '_links'
        ]
    ];
    /**
     * @var array
     */
    public $links = [
        'imageTable' => [
            ['Editar', 'admin.image.edit', 'id'],
            ['Eliminar', 'admin.image.destroy', 'id','destroy']
        ],
    ];
    /**
     * @var array
     */
    public $routes = [
        'edit'   => 'admin.image.update',
        'create' => 'admin.image.store'
    ];
}
