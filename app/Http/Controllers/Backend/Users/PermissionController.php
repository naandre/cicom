<?php

namespace App\Http\Controllers\Backend\Users;

use App\Http\Controllers\Backend\Controller;
use App\Models\Users\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class PermissionController extends Controller
{
    protected $rules=[
        'name' => ['required', 'string', 'max:255','unique:permissions'],
        'display_name' => ['required', 'string', 'max:250'],
    ];
    public function index()
    {
        $buttons=[];
        if(Auth::user()->can(['developer'])){
            $buttons=[
                'top-right' => [
                    'kind'  => 'link',
                    'route' => 'admin.permissions.create',
                    'text'  => 'Nuevo Permiso']
            ];
        }

        $indexTable = (object)[
            'title' => 'Listado de permisos',
            'visualization' => 'table',
            'model'         => new Permission(),
            'data'          => 'ajax',
            'schema'        => 'permissionTable',
            'filters'       =>'filter[isNull]=deleted_at',
            'buttons'       => $buttons];
        $this->options = ['contents' => $indexTable];

        return parent::index();
    }

    function Forms($title) {
        $models = (object)['Permission' => Permission::class];

        $permissionForm = (object)[
            'title' => $title,
            'visualization' => 'form',
            'model' => 'Permission',
            'buttons'       => [
                'top-right' => [
                    'kind'  => 'link',
                    'route' => 'admin.permissions.index',
                    'text'  => 'Regresar']
            ]
        ];

        $forms = ['contents' => $permissionForm,
            'models'   => $models];

        return $forms;
    }

    public function create()
    {
        $this->options = $this->Forms('Nuevo permiso');
        return parent::create();
    }

    public function store(Request $request)
    {
        $this->validateUnserialize($request,$this->rules);
        $permission=new Permission($input=$this->unserializeForms($request->forms)[1]);
        $permission->save();
        Session::flash('success', "El permiso se creo correctamente");
        return redirect()->route('admin.permissions.index');
    }

    public function edit($id) {
        $this->Model=Permission::find($id);
        $this->options = $this->Forms('Editar permiso '.$this->Model->display_name);
        return parent::edit($id);
    }

    public function update(Request $request, $id)
    {
        $message="Lo sentimos, no se encontró el permiso especificado";
        $typeMessage='danger';

        /**@var Permission $permission*/
        if(!empty($permission=Permission::find($id))){
            $input=$this->unserializeForms($request->forms)[1];
            /**Si el nombre del permiso aun no esta en uso, se continua con la actuaizacion*/
            if(($permissionAux=Permission::where('name',$input['name'])->first()) and $permissionAux->id!=$id){
                Session::flash($typeMessage, "Lo sentimos, ya existe un permiso con el nombre ".$input['name']);
                return redirect()->route('admin.permissions.edit',$permission->id);
            }
            unset($this->rules['name'][3]);
            $this->validateUnserialize($request,$this->rules);
            $permission->fill($input);
            $permission->save();
            $message="Se actualizo el permiso de forma exitosa";
            $typeMessage='success';
        }
        Session::flash($typeMessage, $message);
        return redirect()->route('admin.permissions.index');
    }
}
