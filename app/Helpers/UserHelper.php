<?php
/**
 * Created by PhpStorm.
 * User: jaime
 * Date: 18/01/19
 * Time: 01:27 PM
 */

namespace App\Helpers;


use App\Models\Users\User;
use Illuminate\Support\Facades\Hash;

class UserHelper
{
    /**
     * procesa y retorna los datos que se usaran para la creacion o actualizacion de un nuevo usuario
     * @param array $data - informacion de registro del usuario
     * @return array - datos prosesados listos para ser insertados
     */
    public static function dataCollection(array $data){
        $input=[
            'name' => $data['name'],
            'lastname' => $data['lastname']
        ];
        /* cerifica si el dato existe y se ingresa en el arreglo */
        if(isset($data['email']) and !empty($data['email'])) $input['email']=$data['email'];
        if(isset($data['user']) and !empty($data['user'])) $input['user']=$data['user'];
        if(isset($data['password']) and !empty($data['password'])) $input['password']=Hash::make($data['password']);

        return $input;
    }
    /**
     * genera las validaciones para crear o actualizar los usuarios
     * @param array|null $filters - filtros para excluir campos o para aplicar validaciones diferentes
     * @return array - validaciones
     */
    public static function generateValidations(array $filters=null){
        $validation= [
            'name' => ['required', 'string', 'max:250'],
            'lastname' => ['required', 'string', 'max:250'],
            'user' => ['required', 'string', 'max:200', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
        /**elimina campos de validacion en el array*/
        if(isset($filters['unset']))
            foreach ($filters['unset'] as $unset) unset($validation[$unset]);
        /**agrega campos de validacion en el array*/
        if(isset($filters['add']))
            foreach ($filters['add'] as $key=>$add) $validation[$key]=$add;
        return $validation;
    }
    /**
     * busca o registra a un usuario con los datos proporcionados,
     * la busqueda se hace usando el correo, de no estar registrado se registra el usuario con los datos
     * proporcionados en el array
     * @param array $data - datos del usuario
     * @return User modelo del usuario solicitado
     */
    public static function findOrRegister(array $data){
        $user=User::where('email',$data['email'])->first();
        if(empty($user)){
            $user=self::register($data);
            /**agrega por defecto el rol de cliente al usuario*/
            $user->addRoleClient();
        }
        return $user;
    }

    /**
     * se registra un usuario con los datos proporcionados en el arreglo
     * @param array $data - datos con los que se registran el usuario
     * @return User modelo del usuario creado
     */
    public static function register(array $data){
        $user=new User(self::dataCollection($data));
        $user->save();
        return $user;
    }

}
