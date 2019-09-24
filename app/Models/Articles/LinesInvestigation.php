<?php

namespace App\Models\Articles;

use Illuminate\Database\Eloquent\Model;

class LinesInvestigation extends Model
{
    protected $table="lines_investigation";
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
        'linesTable' => [
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
        'linesTable' => [
            ['Editar Categoría', 'admin.line.edit', 'id'],
            ['Eliminar Categoría', 'admin.line.destroy', 'id','destroy']
        ],
    ];
    /**
     * @var array
     */
    public $routes = [
        'edit'   => 'admin.line.update',
        'create' => 'admin.line.store'
    ];
}
