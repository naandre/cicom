<?php


namespace App\Models\Articles;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CargueMasivo extends Model
{
    protected $table="cargueMasivo";
    protected $fillable=["nombre","estado","fecha","archivoCargue","userId"];

    public $fields=[
        "nombre"=>['options'=>['required'=>'required']]
    ];

    public $files=true;

    public $schemas = [
        'cargueMasivoTable' => [
            'id',
            'nombre',
            'fecha',
            '_links'
        ]
    ];

    /**
     * @var array
     */
    public $links = [
        'cargueMasivoTable' => [
            ['Ver', 'admin.cargueMasivo.show', 'id'],
            ['Detalle', 'admin.detallecargue.show', 'id']
        ]
    ];

    /**
     * @var array
     */
    public $routes = [
        'create' => 'admin.cargueMasivo.store',
        'edit' => 'admin.cargueMasivo.update'
    ];


    public function detalleCargueMasivo() : HasMany {
        return  $this->hasMany(DetalleCargueMasivo::class,'idCargueMasivo');
    }

    public function estadoCargue() : BelongsTo{
        return $this->belongsTo(EstadoCargue::class,'estado');
    }

}
