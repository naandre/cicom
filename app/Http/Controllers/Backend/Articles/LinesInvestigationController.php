<?php

namespace App\Http\Controllers\Backend\Articles;

use App\Http\Controllers\Backend\Controller;
use App\Models\Articles\LinesInvestigation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LinesInvestigationController extends Controller
{
    protected $rules=[
        'name' => ['required', 'string', 'max:50','unique:categories'],
        'state' => ['required', 'numeric'],
    ];
    public function index()
    {
        $indexTable = (object)['visualization' => 'table',
            'model'         => new LinesInvestigation(),
            'data'          => 'ajax',
            'schema'        => 'linesTable',
            'filters'       =>'filter[isNull]=deleted_at',
            'buttons'       => [
                'top-right' => [
                    'kind'  => 'link',
                    'route' => 'admin.line.create',
                    'text'  => 'Crear Linea de investigación']
            ]];
        $this->options = ['contents' => $indexTable];

        return parent::index();
    }

    function Forms() {
        $models = (object)['LinesInvestigation' => LinesInvestigation::class];

        $lineForm = (object)[
            'visualization' => 'form',
            'model' => 'LinesInvestigation',
            'buttons'       => [
                'top-right' => [
                    'kind'  => 'link',
                    'route' => 'admin.line.index',
                    'text'  => 'Cancelar']
            ]
        ];

        $forms = ['contents' => $lineForm,
            'models'   => $models];

        return $forms;
    }

    public function create()
    {
        $this->options = $this->Forms();
        return parent::create();
    }

    public function store(Request $request)
    {
        $this->validateUnserialize($request,$this->rules);
        $line=new LinesInvestigation($input=$this->unserializeForms($request->forms)[1]);
        $line->save();
        Session::flash('success', "Se creó la Linea de investigación");
        return redirect()->route('admin.line.index');
    }

    public function edit($id) {
        $this->Model=LinesInvestigation::find($id);
        $this->options = $this->Forms();
        return parent::edit($id);
    }

    public function update(Request $request, $id)
    {
        $message="Lo sentimos, no se encontró la Linea de investigación especificada";
        $typeMessage='danger';

        /**@var LinesInvestigation $line*/
        if(!empty($line=LinesInvestigation::find($id))){
            $input=$this->unserializeForms($request->forms)[1];
            /**Si el nombre de la categoría aun no esta en uso, se continua con la actuaizacion*/
            if(($lineAux=LinesInvestigation::where('name',$input['name'])->first()) and $lineAux->id!=$id){
                Session::flash($typeMessage, "La Linea de investigación ".$input['name']." ya se encuentra creada");
                return redirect()->route('admin.line.edit',$line->id);
            }
            unset($this->rules['name'][3]);
            $this->validateUnserialize($request,$this->rules);
            $line->fill($input);
            $line->save();

            $message="Se editaron los datos";
            $typeMessage='success';
        }
        Session::flash($typeMessage, $message);
        return redirect()->route('admin.line.index');
    }

    public function destroy($id,Request $request){
        /**@var LinesInvestigation $line*/
        if(empty($line=LinesInvestigation::find($id))){
            Session::flash('danger','No se encontró la Linea de investigación especificada');
            return redirect()->route('admin.line.index');
        }
        Session::flash('success','Linea de investigación Eliminada');
        $line->delete();
        return redirect()->route('admin.line.index');
    }
}
