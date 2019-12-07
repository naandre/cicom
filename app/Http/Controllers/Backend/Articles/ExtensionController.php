<?php

namespace App\Http\Controllers\Backend\Articles;

use App\Http\Controllers\Backend\Controller;
use App\Models\Articles\Extension;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ExtensionController extends Controller
{
    protected $rules=[
        'name' => ['required', 'string', 'max:5','unique:categories'],
        'state' => ['required', 'numeric'],
    ];
    public function index()
    {
        $indexTable = (object)[
            'title' => 'Extensiones',
            'visualization' => 'table',
            'model'         => new Extension(),
            'data'          => 'ajax',
            'schema'        => 'extensionTable',
            'filters'       =>'filter[isNull]=deleted_at',
            'buttons'       => [
                'top-right' => [
                    'kind'  => 'link',
                    'route' => 'admin.extension.create',
                    'text'  => 'Crear extensión']
            ]];
        $this->options = ['contents' => $indexTable];

        return parent::index();
    }

    function Forms($title) {
        $models = (object)['Extension' => Extension::class];

        $extensionForm = (object)[
            'title' => $title,
            'visualization' => 'form',
            'model' => 'Extension',
            'buttons'       => [
                'top-right' => [
                    'kind'  => 'link',
                    'route' => 'admin.extension.index',
                    'text'  => 'Cancelar']
            ]
        ];

        $forms = ['contents' => $extensionForm,
            'models'   => $models];

        return $forms;
    }

    public function create()
    {
        $this->options = $this->Forms('Crear extensión');
        return parent::create();
    }

    public function store(Request $request)
    {
        $this->validateUnserialize($request,$this->rules);
        $extension=new Extension($input=$this->unserializeForms($request->forms)[1]);
        $extension->save();
        Session::flash('success', "Se creó la  Extensió");
        return redirect()->route('admin.extension.index');
    }

    public function edit($id) {
        $this->Model=Extension::find($id);
        $this->options = $this->Forms('Editar extensión '.$this->Model->name);
        return parent::edit($id);
    }

    public function update(Request $request, $id)
    {
        $message="Lo sentimos, no se encontró la Extensió especificada";
        $typeMessage='danger';

        /**@var Extension $extension*/
        if(!empty($extension=Extension::find($id))){
            $input=$this->unserializeForms($request->forms)[1];
            /**Si el nombre de la categoría aun no esta en uso, se continua con la actuaizacion*/
            if(($extensionAux=Extension::where('name',$input['name'])->first()) and $extensionAux->id!=$id){
                Session::flash($typeMessage, "La Extensió ".$input['name']." ya se encuentra creada");
                return redirect()->route('admin.extension.edit',$extension->id);
            }
            unset($this->rules['name'][3]);
            $this->validateUnserialize($request,$this->rules);
            $extension->fill($input);
            $extension->save();

            $message="Se editaron los datos";
            $typeMessage='success';
        }
        Session::flash($typeMessage, $message);
        return redirect()->route('admin.extension.index');
    }

    public function destroy($id,Request $request){
        /**@var Extension $extension*/
        if(empty($extension=Extension::find($id))){
            Session::flash('danger','No se encontró la Extensió especificada');
            return redirect()->route('admin.extension.index');
        }
        Session::flash('success','Extensión eliminada');
        $extension->delete();
        return redirect()->route('admin.extension.index');
    }
}
