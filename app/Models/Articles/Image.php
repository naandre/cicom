<?php

namespace App\Models\Articles;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Image
 * @package App\Models\Articles
 *
 * @property int $id
 * @property string $name
 * @property string $image
 */
class Image extends Model
{
    /**
     * @var string
     */
    protected $table="images";
    /**
     * @var array
     */
    protected $fillable=["name","image"];
    /**
     * @var array
     */
    public $fields = [
        'name'=>['options'=>['required'=>'required']],
        'image'=>[
            'type'=>'file',
            'options'=>['accept'=>'image/*','required'=>'required']
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
