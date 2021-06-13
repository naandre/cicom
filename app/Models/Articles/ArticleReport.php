<?php

namespace App\Models\Articles;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArticleReport extends Model
{
    use SoftDeletes;

    protected $table="ArticleReport";

    protected $fillable=["TITULO","ABSTRACT","PALABRAS CLAVE","CATEGORIA","LINEA INVESTIGACION","EDITORIAL","FECHA PUBLICACION","AUTORES"];

    public $fields=[
        "title"=>['options'=>['required'=>'required']],
        "description"=>[
            'kind'=>'textarea',
            'options'=>['required'=>'required']
        ],
        "keyWords"=>[
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
        'articleReport' => [
            'id',
            'TITULO',
            'ABSTRACT',
            'PALABRAS CLAVE',
            'CATEGORIA',
            'LINEA INVESTIGACION',
            'EDITORIAL',
            'FECHA PUBLICACION',
            'AUTORES',
            '_links'
        ]
    ];

    /**
     * @var array
     */
    public $links = [
        'articleReport' => [
            ['Ver', 'admin.search.show', 'id']
        ]
    ];

    public function category() : BelongsTo{
        return $this->belongsTo(Category::class,'category_id');
    }

    public function line() : BelongsTo{
        return $this->belongsTo(LinesInvestigation::class,'line_id');
    }

    public function authors() : HasMany {
        return  $this->hasMany(Author::class,'article_id');
    }
}
