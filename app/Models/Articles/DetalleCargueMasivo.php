<?php


namespace App\Models\Articles;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetalleCargueMasivo extends Model
{
    protected $table="detalleCargueMasivo";
    protected $fillable=["nombreDocumento","estado","log","idCargueMasivo","user_id"];

    public $fields=[
        "nombreDocumento"=>['kind'=>'file','options'=>['accept'=>'.pdf','required'=>'required','multiple'=>'multiple','name'=>'nombreDocumento[]']]
    ];

    public $files=true;

    public $schemas = [
        'detalleCargueMasivoTable' => [
            'id',
            'nombreDocumento',
            'log',
            'estado',
            '_links'
        ]
    ];

    /**
     * @var array
     */
    public $links = [
        'detalleCargueMasivoTable' => [
            ['Ver', 'admin.cargueMasivo.show', 'idCargueMasivo']
        ]
    ];

    /**
     * @var array
     */
    public $routes = [
        'mixt' => 'admin.detallecargue.store'
    ];

    /**
     * @return BelongsTo
     */
    public function cargueMasivo():BelongsTo{
        return $this->belongsTo(CargueMasivo::class,'idCargueMasivo');
    }

    public function estadoCargue() : BelongsTo{
        return $this->belongsTo(EstadoCargue::class,'estado');
    }

}
