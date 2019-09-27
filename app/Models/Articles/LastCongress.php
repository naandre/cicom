<?php

namespace App\Models\Articles;

use Illuminate\Database\Eloquent\Model;

class LastCongress extends Model
{
    /**
     * @var string
     */
    protected $table="images";
    /**
     * @var array
     */
    protected $fillable=["name","url","file"];
    /**
     * @var array
     */
    public $fields = [
        'name'=>['options'=>['required'=>'required']],
        'url',
        'file'=>[
            'type'=>'file',
            'options'=>[]
        ]
    ];

    /**
     * @var bool
     */
    public $files=true;
    /**
     * @var array
     */
    public $schemas = [
        'lastcongressTable' => [
            'id',
            'name',
            'created_at',
            '_links'
        ]
    ];
    /**
     * @var array
     */
    public $links = [
        'lastcongressTable' => [
            ['Editar', 'admin.lastcongress.edit', 'id'],
            ['Eliminar', 'admin.lastcongress.destroy', 'id','destroy']
        ],
    ];
    /**
     * @var array
     */
    public $routes = [
        'edit'   => 'admin.lastcongress.update',
        'create' => 'admin.lastcongress.store'
    ];
}
