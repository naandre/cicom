<?php

namespace App\Http\Controllers\Backend\Users;

use App\Http\Controllers\Backend\Controller;
use App\Models\Users\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class RoleController extends Controller
{
    protected $rules=[
        'name' => ['required', 'string', 'max:255','unique:roles'],
        'display_name' => ['required', 'string', 'max:250'],
    ];
    public function index()
    {
        $indexTable = (object)['visualization' => 'table',
            'model'         => new Role(),
            'data'          => 'ajax',
            'schema'        => 'roleTable',
            'filters'       =>'filter[isNull]=deleted_at',
            'buttons'       => [
                'top-right' => [
                    'kind'  => 'link',
                    'route' => 'admin.roles.create',
                    'text'  => 'Nuevo Rol']
            ]];
        $this->options = ['contents' => $indexTable];

        return parent::index();
    }

    function Forms() {
        $models = (object)['Role' => Role::class];

        $roleForm = (object)[
            'visualization' => 'form',
            'model' => 'Role',
            'buttons'       => [
                'top-right' => [
                    'kind'  => 'link',
                    'route' => 'admin.roles.index',
                    'text'  => 'Regresar']
            ]
        ];

        $forms = ['contents' => $roleForm,
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
        $role=new Role($input=$this->unserializeForms($request->forms)[1]);
        $role->save();
        /**Se agregan los permisos*/
        $role->synPermissions(isset($input['permissions'])?$input['permissions']:[]);
        Session::flash('success', "El rol se creo correctamente");
        return redirect()->route('admin.roles.index');
    }

    public function edit($id) {
        /**@var Role $role*/
        $role=Role::find($id);
        /**Se cargan los permisos*/
        $role->permissions=implode(',',$role->getPermissionIds());
        $this->Model=$role;
        $this->options = $this->Forms();
        return parent::edit($id);
    }

    public function update(Request $request, $id)
    {
        $message="Lo sentimos, no se encontró el rol especificado";
        $typeMessage='danger';

        /**@var Role $role*/
        if(!empty($role=Role::find($id))){
            $input=$this->unserializeForms($request->forms)[1];
            /**Si el nombre del rol aun no esta en uso, se continua con la actuaizacion*/
            if(($roleAux=Role::where('name',$input['name'])->first()) and $roleAux->id!=$id){
                Session::flash($typeMessage, "Lo sentimos, ya existe un rol con el nombre ".$input['name']);
                return redirect()->route('admin.roles.edit',$role->id);
            }
            unset($this->rules['name'][3]);
            $this->validateUnserialize($request,$this->rules);
            $role->fill($input);
            $role->save();
            /**Se actualizan los permisos*/
            $role->synPermissions(isset($input['permissions'])?$input['permissions']:[]);
            $message="Se actualizo el rol de forma exitosa";
            $typeMessage='success';
        }
        Session::flash($typeMessage, $message);
        return redirect()->route('admin.roles.index');
    }

    public function destroy($id,Request $request){
        /**@var Role $role*/
        if(empty($role=Role::find($id))){
            Session::flash('danger','No se encontró el rol especificado');
            return redirect()->route('admin.roles.index');
        }
        Session::flash('success','El rol '.$role->name.' se elimino con éxito');
        if($role->perms()->count()>0) $role->synPermissions([]);
        $role->delete();
        return redirect()->route('admin.roles.index');
    }
}
