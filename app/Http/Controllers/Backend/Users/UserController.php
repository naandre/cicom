<?php

namespace App\Http\Controllers\Backend\Users;

use App\Helpers\UserHelper;
use App\Http\Controllers\Backend\Controller;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use OsTheNeo\Toaster\BladeEngine;

class UserController extends Controller
{
    public function __construct()
    {
        $this->Model=new User();
    }

    /**
     * Lista todos los usuarios del sistema
     */
    public function index()
    {
        $buttons=[];
        if(Auth::user()->ability(Auth::user()->getRole(),'crear_usu'))
            $buttons=[
                'top-right' => [
                    'kind'  => 'link',
                    'route' => 'admin.users.create',
                    'text'  => 'Nuevo Usuario']
            ];

        $indexTable = (object)['visualization' => 'table',
            'model'         => new User(),
            'data'          => 'ajax',
            'schema'        => 'userTable',
            'filters'       =>'filter[isNull]=deleted_at',
            'buttons'       =>$buttons
        ];
        $this->options = ['contents' => $indexTable];

        return parent::index();
    }

    public function create()
    {
        $this->options = $this->Forms();
        return parent::create();
    }

    public function store(Request $request)
    {
        $this->validateUnserialize($request,UserHelper::generateValidations());
        $user=new User(UserHelper::dataCollection($input=$this->unserializeForms($request->forms)[1]));
        $user->save();
        /**agrega el rol espesificado*/
        $user->addRole($input['role_id']);
        Session::flash('success', "El usuario se creo correctamente");
        return redirect()->route('admin.users.index');
    }

    function Forms() {
        $models = (object)['User' => User::class];

        $userForm = (object)[
                'visualization' => 'form','model' => 'User','date'=>['date'=>[]],
                'buttons'       => [
                    'top-right' => [
                        'kind'  => 'link',
                        'route' => 'admin.users.index',
                        'text'  => 'Regresar']
                ]
            ];

        $forms = ['contents' => $userForm,
            'models'   => $models];

        return $forms;
    }

    public function edit($id) {
        /**@var User $user*/
        $user = User::find($id);
        /**@var Role $role*/
        if($role=$user->roles()->first()) $user->role_id=$role->id;
        $this->Model=$user;
        $this->options = $this->Forms();
        return parent::edit($id);
    }

    public function update(Request $request, $id)
    {
        $message="Lo sentimos, no se encontró el usuario especificado";
        $typeMessage='danger';
        /**@var User $user*/
        if(!empty($user=User::find($id))){
            $input=$this->unserializeForms($request->forms)[1];
            /**Si el correo aun no esta en uso, se continua con la actuaizacion*/
            if(($userAux=User::where('email',$input['email'])->first()) and $userAux->id!=$id){
                Session::flash($typeMessage, "Lo sentimos, el correo ya se encuentra en uso");
                return redirect()->route('admin.users.edit',$user->id);
            }
            $filters=['unset'=>['email','user']];
            if(empty($input['password'])) $filters['unset'][]='password';
            $this->validateUnserialize($request,UserHelper::generateValidations($filters));
            $user->fill(UserHelper::dataCollection($input));
            $user->save();
            /**Si es el caso, se hace el camo de rol*/
            $user->changeRole($input['role_id']);
            $message="Se actualizo el usuario de forma exitosa";
            $typeMessage='success';
        }
        Session::flash($typeMessage, $message);
        return redirect()->route('admin.users.show',$user->id);
    }

    public function show($id)
    {
        $user = User::find($id);
        $userDetail = (object)[
            'title'         => 'Detalles del usuario '.$user->name.' '.$user->lastname,
            'visualization' => 'list',
            'data'          =>
                [
                    BladeEngine::Translate('name',$user)=>$user->name,
                    BladeEngine::Translate('lastname',$user)=>$user->lastname,
                    BladeEngine::Translate('user',$user)=>$user->user,
                    BladeEngine::Translate('email',$user)=>$user->email,
                ],
            'buttons'       => [
                'top-left' => [
                    'kind'  => 'link',
                    'route' => 'admin.users.edit',
                    'parameters' => $user->id,
                    'text'  => 'Editar'],
                'top-right' => [
                    'kind'  => 'link',
                    'route' => 'admin.users.index',
                    'text'  => 'Regresar'],
            ]
        ];

        $this->options = ['contents' => [$userDetail]];
        return parent::show($id);
    }

    public function destroy($id,Request $request){
        $user=User::find($id);
        if(empty($user)){
            Session::flash('danger','No se encontró el usuario especificado');
            return redirect()->route('admin.users.index');
        }
        Session::flash('success','El usuario '.$user->name.' se elimino con éxito');
        $user->delete();
        return redirect()->route('admin.users.index');
    }
}
