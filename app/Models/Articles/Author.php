<?php

namespace App\Models\Articles;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Author
 * @package App\Models\Articles
 */
class Author extends Model
{
    /**
     * @var string
     */
    protected $table="authors";

    /**
     * @var array
     */
    protected $fillable=["name","lastname","article_id"];

    /**
     * @var array
     */
    public $fields=[
        "name"=>['options'=>['required'=>'required']],
        "lastname"=>['options'=>['required'=>'required']],
    ];

    /**
     * @var array
     */
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


    /**
     * @return BelongsTo
     */
    public function article():BelongsTo{
        return $this->belongsTo(Article::class,'article_id');
    }
}
