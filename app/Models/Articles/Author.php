<?php

namespace App\Models\Articles;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    protected $table="authors";

    protected $fillable=["name","lastname","article_id"];

    public $fields=[
        "name"=>['options'=>['required'=>'required']],
        "lastname"=>['options'=>['required'=>'required']],
    ];

    public $schemas = [
        'authorTable' => [
            'id',
            'name',
            'lastname',
            '_links'
        ]
    ];
    /**
     * @var array
     */
    public $links = [
        'authorTable' => [
            ['Editar', 'admin.author.edit', 'id'],
            ['Eliminar', 'admin.author.destroy', 'id','destroy']
        ],
    ];
    /**
     * @var array
     */
    public $routes = [
        'edit'   => 'admin.author.update',
        'mixt' => 'admin.author.store'
    ];
}
