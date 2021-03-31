<?php

namespace App\Models\Articles;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pais extends Model
{
    protected $table="pais";

    protected $fillable=["nombre"];

    public $fields = [
        'nombre'=>['options'=>['required'=>'required']]
    ];

    public $schemas = [
        'paisTable' => [
            'id',
            'nombre',
            '_links'
        ]
    ];

    public $links = [
        'paisTable' => [
            ['Editar', 'admin.pais.edit', 'id'],
            ['Eliminar', 'admin.pais.destroy', 'id','destroy']
        ],
    ];

    public $routes = [
        'edit'   => 'admin.pais.update',
        'create' => 'admin.pais.store'
    ];
}