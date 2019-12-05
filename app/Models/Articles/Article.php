<?php

namespace App\Models\Articles;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $table="articles";

    protected $fillable=["title","description","category_id","line_id","editorial","publication_date","file","user_id"];

    public $fields=[
        "title"=>['options'=>['required'=>'required']],
        "description"=>[
            'kind'=>'textarea',
            'options'=>['required'=>'required']
        ],
        "category_id"=>[
            'type'=>"select",
            'from'=>'categories',
            'take'=>['id','name'],
            'where'=>['state'=>1],
            'options'=>['placeholder'=>'Seleccione una opción...','required'=>'required']
        ],
        "line_id"=>[
            'type'=>"select",
            'from'=>'lines_investigation',
            'take'=>['id','name'],
            'where'=>['state'=>1],
            'options'=>['placeholder'=>'Seleccione una opción...','required'=>'required']
        ],
        "editorial",
        "publication_date",
        "file"=>['kind'=>'file','options'=>['accept'=>'.pdf','required'=>'required']],
    ];

    public $files=true;

    public $schemas = [
        'articleTable' => [
            'id',
            'title',
            'publication_date',
            '_links'
        ]
    ];
    /**
     * @var array
     */
    public $links = [
        'articleTable' => [
            ['Editar', 'admin.article.edit', 'id'],
            ['Ver', 'admin.article.show', 'id'],
            ['Autores', 'admin.author.index', 'id'],
            ['Eliminar', 'admin.article.destroy', 'id','destroy']
        ],
    ];
    /**
     * @var array
     */
    public $routes = [
        'edit'   => 'admin.article.update',
        'create' => 'admin.article.store'
    ];

    public function category(){
        return $this->belongsTo(Category::class,'category_id');
    }

    public function line(){
        return $this->belongsTo(LinesInvestigation::class,'line_id');
    }
}
