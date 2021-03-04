<?php


namespace App\Models\Articles;


use Illuminate\Database\Eloquent\Model;

class EstadoCargue extends Model
{
    protected $table="estadosCargue";
    protected $fillable=["descripcionEstado"];

    public $fields=[
        "descripcionEstado"=>['options'=>['required'=>'required']]
    ];

    public $schemas = [
        'estadoCargueTable' => [
            'id',
            'descripcionEstado',
            '_links'
        ]
    ];

    /**
     * @var array
     */
    public $links = [
        'estadoCargueTable' => [
            ['Ver', 'admin.estadoCargue.show', 'id']
        ]
    ];

    /**
     * @var array
     */
    public $routes = [
        'create' => 'admin.estadoCargue.store'
    ];

}
