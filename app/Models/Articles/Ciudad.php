<?php

namespace App\Models\Articles;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ciudad extends Model
{
    protected $table="ciudad";

    protected $fillable=["nombre","idPais"];

    public $fields = [
        'nombre'=>['options'=>['required'=>'required']],
        "Pais"=>[
            'type'=>"select",
            'from'=>'pais',
            'take'=>['id','nombre'],
            'whereNotNull'=>['deleted_at'],
            'options'=>['placeholder'=>'Seleccione una opción...','required'=>'required']
        ],
    ];

    public $schemas = [
        'ciudadTable' => [
            'id',
            'nombre'
        ]
    ];

    public $links = [
        'ciudadTable' => [
            ['Editar País', 'admin.ciudad.edit', 'id'],
            ['Eliminar País', 'admin.ciudad.destroy', 'id','destroy']
        ],
    ];

    public $routes = [
        'edit'   => 'admin.ciudad.update',
        'create' => 'admin.ciudad.store'
    ];

    public function pais() : BelongsTo{
        return $this->belongsTo(Pais::class,'idPais');
    }
}