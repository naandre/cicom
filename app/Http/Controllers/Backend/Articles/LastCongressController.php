<?php

namespace App\Http\Controllers\Backend\Articles;

use App\Http\Controllers\Backend\Controller;
use App\Models\Articles\LastCongress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use OsTheNeo\Toaster\FilesHelper;

class LastCongressController extends Controller
{
    protected $rules=[
        'name' => ['required', 'string', 'max:50']
    ];
    public function index()
    {
        $indexTable = (object)[
            'title' => 'Información de ultimo congresos',
            'visualization' => 'table',
            'model'         => new LastCongress(),
            'data'          => 'ajax',
            'schema'        => 'lastcongressTable',
            'filters'       =>'filter[isNull]=deleted_at',
            'buttons'       => [
                'top-right' => [
                    'kind'  => 'link',
                    'route' => 'admin.lastcongress.create',
                    'text'  => 'Agregar Información']
            ]];
        $this->options = ['contents' => $indexTable];

        return parent::index();
    }

    function Forms($title) {
        $models = (object)['LastCongress' => LastCongress::class];

        $lastcongressForm = (object)[
            'title' => $title,
            'visualization' => 'form',
            'model' => 'LastCongress',
            'buttons'       => [
                'top-right' => [
                    'kind'  => 'link',
                    'route' => 'admin.lastcongress.index',
                    'text'  => 'Cancelar']
            ]
        ];

        $forms = ['contents' => $lastcongressForm,
            'models'   => $models,'submitButton'=>true];

        return $forms;
    }

    public function create()
    {
        $this->options = $this->Forms('Agregar Información de ultimo congreso');
        return parent::create();
    }

    public function store(Request $request)
    {
        $this->validate($request,$this->rules);
        $input=$request->all();
        $input['file']=FilesHelper::store($request,'file',$input['name'],'lastcongress');
        $lastcongress=new LastCongress($input);
        $lastcongress->save();
        Session::flash('success', "Se agrego la Información");
        return redirect()->route('admin.lastcongress.index');
    }

    public function edit($id) {
        $this->Model=LastCongress::find($id);
        $this->options = $this->Forms('Editar información del congreso '.$this->Model->name);
        return parent::edit($id);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request,$this->rules);
        $message="Lo sentimos, no se encontró la Información especificada";
        $typeMessage='danger';
        /**@var LastCongress $lastcongress*/
        if(!empty($lastcongress=LastCongress::find($id))){
            $input=$request->all();
            if($request->file) $input['file']=FilesHelper::update($request,'file',$input['name'],'lastcongress/',$lastcongress->file);
            $lastcongress->fill($input);
            $lastcongress->save();

            $message="Se editaron los datos";
            $typeMessage='success';
        }
        Session::flash($typeMessage, $message);
        return redirect()->route('admin.lastcongress.index');
    }

    public function destroy($id,Request $request){
        /**@var LastCongress $lastcongress*/
        if(empty($lastcongress=LastCongress::find($id))){
            Session::flash('danger','No se encontró el registro');
            return redirect()->route('admin.lastcongress.index');
        }
        Session::flash('success','Registro eliminada');
        $lastcongress->delete();
        FilesHelper::destroy('lastcongress/'.$lastcongress->lastcongress);
        return redirect()->route('admin.lastcongress.index');
    }
}
