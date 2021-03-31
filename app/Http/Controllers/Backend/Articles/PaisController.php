<?php

namespace App\Http\Controllers\Backend\Articles;

use App\Http\Controllers\Backend\Controller;
use App\Models\Articles\Pais;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PaisController extends Controller
{
    protected $rules=[
        'nombre' => ['required', 'string', 'max:100','unique:pais']
    ];
    public function index()
    {
        $indexTable = (object)[
            'title'     =>  'Listado de Paises',
            'visualization' => 'table',
            'model'         => new Pais(),
            'data'          => 'ajax',
            'schema'        => 'paisTable',
            'filters'       =>'filter[isNull]=deleted_at',
            'buttons'       => [
                'top-right' => [
                    'kind'  => 'link',
                    'route' => 'admin.pais.create',
                    'text'  => 'Registrar País']
            ]];
        $this->options = ['contents' => $indexTable];

        return parent::index();
    }

    function Forms($nombre) {
        $models = (object)['Pais' => Pais::class];

        $paisForm = (object)[
            'nombre' =>  $nombre,
            'visualization' => 'form',
            'model' => 'Pais',
            'buttons'       => [
                'top-right' => [
                    'kind'  => 'link',
                    'route' => 'admin.pais.index',
                    'text'  => 'Cancelar']
            ]
        ];

        $forms = ['contents' => $paisForm,
            'models'   => $models];

        return $forms;
    }

    public function create()
    {
        $this->options = $this->Forms('Registrar País');
        return parent::create();
    }

    public function store(Request $request)
    {
        $this->validateUnserialize($request,$this->rules);
        $pais=new Pais($input=$this->unserializeForms($request->forms)[1]);
        $pais->save();
        Session::flash('success', "Se registro el pais");
        return redirect()->route('admin.pais.index');
    }

    public function edit($id) {
        $this->Model=Pais::find($id);
        $this->options = $this->Forms('Editar Pais '.$this->Model->nombre);
        return parent::edit($id);
    }

    public function update(Request $request, $id)
    {
        $message="Lo sentimos, no se encontró el país especificado";
        $typeMessage='danger';

        /**@var Pais $pais*/
        if(!empty($pais=Pais::find($id))){
            $input=$this->unserializeForms($request->forms)[1];
            /**Si el nombre del pais aun no esta en uso, se continua con la actuaizacion*/
            if(($paisAux=Pais::where('nombre',$input['nombre'])->first()) and $paisAux->id!=$id){
                Session::flash($typeMessage, "El país ".$input['name']." ya se encuentra registrado");
                return redirect()->route('admin.pais.edit',$pais->id);
            }
            unset($this->rules['nombre'][3]);
            $this->validateUnserialize($request,$this->rules);
            $pais->fill($input);
            $pais->save();

            $message="Se editaron los datos";
            $typeMessage='success';
        }
        Session::flash($typeMessage, $message);
        return redirect()->route('admin.pais.index');
    }

    public function destroy($id,Request $request){
        /**@var País $pais*/
        if(empty($pais=Pais::find($id))){
            Session::flash('danger','No se encontró el país especificado');
            return redirect()->route('admin.pais.index');
        }
        Session::flash('success','País Eliminado');
        $pais->delete();
        return redirect()->route('admin.pais.index');
    }
}
