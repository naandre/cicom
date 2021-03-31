<?php

namespace App\Http\Controllers\Backend\Articles;

use App\Http\Controllers\Backend\Controller;
use App\Models\Articles\Ciudad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CiudadController extends Controller
{
    protected $rules=[
        'nombre' => ['required', 'string', 'max:100','unique:ciudad'],
        "idPais"=>['required','numeric']
    ];
    public function index()
    {
        $indexTable = (object)[
            'title'     =>  'Listado de Ciudades',
            'visualization' => 'table',
            'model'         => new Ciudad(),
            'data'          => 'ajax',
            'schema'        => 'ciudadTable',
            'filters'       =>'filter[isNull]=deleted_at',
            'buttons'       => [
                'top-right' => [
                    'kind'  => 'link',
                    'route' => 'admin.ciudad.create',
                    'text'  => 'Registrar Ciudad']
            ]];
        $this->options = ['contents' => $indexTable];

        return parent::index();
    }

    function Forms($nombre) {
        $models = (object)['Ciudad' => Ciudad::class];

        $ciudadForm = (object)[
            'nombre' =>  $nombre,
            'visualization' => 'form',
            'model' => 'Ciudad',
            'buttons'       => [
                'top-right' => [
                    'kind'  => 'link',
                    'route' => 'admin.ciudad.index',
                    'text'  => 'Cancelar']
            ]
        ];

        $forms = ['contents' => $ciudadForm,
            'models'   => $models];

        return $forms;
    }

    public function create()
    {
        $this->options = $this->Forms('Registrar Ciudad');
        return parent::create();
    }

    public function store(Request $request)
    {
        $this->validateUnserialize($request,$this->rules);
        $ciudad=new Ciudad($input=$this->unserializeForms($request->forms)[1]);
        $ciudad->save();
        Session::flash('success', "Se registro la ciudad");
        return redirect()->route('admin.ciudad.index');
    }

    public function edit($id) {
        $this->Model=Ciudad::find($id);
        $this->options = $this->Forms('Editar Ciudad '.$this->Model->nombre);
        return parent::edit($id);
    }

    public function update(Request $request, $id)
    {
        $message="Lo sentimos, no se encontró la ciudad especificado";
        $typeMessage='danger';

        /**@var Pais $pais*/
        if(!empty($ciudad=Ciudad::find($id))){
            $input=$this->unserializeForms($request->forms)[1];
            /**Si el nombre del pais aun no esta en uso, se continua con la actuaizacion*/
            if(($ciudadAux=Pais::where('nombre',$input['nombre'])->first()) and $ciudadAux->id!=$id){
                Session::flash($typeMessage, "La ciudad ".$input['name']." ya se encuentra registrada");
                return redirect()->route('admin.ciudad.edit',$pais->id);
            }
            unset($this->rules['nombre'][3]);
            $this->validateUnserialize($request,$this->rules);
            $ciudad->fill($input);
            $ciudad->save();

            $message="Se editaron los datos";
            $typeMessage='success';
        }
        Session::flash($typeMessage, $message);
        return redirect()->route('admin.ciudad.index');
    }

    public function destroy($id,Request $request){
        /**@var Ciudad $ciudad*/
        if(empty($ciudad=Ciudad::find($id))){
            Session::flash('danger','No se encontró la ciudad especificada');
            return redirect()->route('admin.ciudad.index');
        }
        Session::flash('success','Ciudad Eliminada');
        $ciudad->delete();
        return redirect()->route('admin.ciudad.index');
    }
}
