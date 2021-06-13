<?php

namespace App\Models\Articles;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use SoftDeletes;

    protected $table="articles";

    protected $fillable=["title","description","category_id","line_id","editorial","publication_date","file","user_id","publication_city","publication_country","keyWords"];

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
        "publication_country"=>[
            'type'=>"select",
            'from'=>'pais',
            'take'=>['id','nombre'],
            'options'=>['placeholder'=>'Seleccione una opción...','required'=>'required']
        ],
        "publication_city"=>[
            'type'=>"select",
            'from'=>'ciudad',
            'take'=>['id','nombre'],
            'where'=>['idPais'=>"pais"],
            'options'=>['placeholder'=>'Seleccione una opción...','required'=>'required']
        ],
        "file"=>['kind'=>'file','options'=>['accept'=>'.pdf','required'=>'required']],
    ];

    public $files=true;

    public $schemas = [
        'articleTable' => [
            'id',
            'title',
            'description',
            'keyWords',
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
        'articleReport' => [
            ['Ver', 'admin.article.show', 'id']
        ]
    ];
    /**
     * @var array
     */
    public $routes = [
        'edit'   => 'admin.article.update',
        'create' => 'admin.article.store'
    ];

    public function category() : BelongsTo{
        return $this->belongsTo(Category::class,'category_id');
    }

    public function line() : BelongsTo{
        return $this->belongsTo(LinesInvestigation::class,'line_id');
    }

    public function ciudad() : BelongsTo{
        return $this->belongsTo(Ciudad::class,'publication_city');
    }

    public function authors() : HasMany {
        return  $this->hasMany(Author::class,'article_id');
    }
}
