<?php

namespace App;

use App\Models\Articles\Category;
use App\Models\Articles\LinesInvestigation;
use App\Models\Users\Permission;
use App\Models\Users\Role;
use App\Models\Users\User;
use OsTheNeo\Toaster\BladeEngine;

class Dictionary
{
    public static function alias($ask) {

        $dictionary = (object)[
            'userTable'  => User::class,
            'permissionTable'  => Permission::class,
            'roleTable'  => Role::class,
            'categoryTable'  => Category::class,
            'linesTable'  => LinesInvestigation::class,
        ];

        return $dictionary->$ask;
    }

    public static function replacemente($ask) {
        /**estructura de datos para las tablas de categories*/
        $categoryTables=[
            'state'          => ['kind' => 'group'],
            //'note'          => ['kind' => 'json','value' => 'datetime','splitData'=>'data:']
        ];
        $linesTable=[
            'state'          => ['kind' => 'group'],
            //'note'          => ['kind' => 'json','value' => 'datetime','splitData'=>'data:']
        ];

        $replacement = (object)[
            'categoryTable' =>$categoryTables,
            'linesTable' =>$linesTable,
        ];
        if (isset($replacement->$ask))
            return $replacement->$ask;
        return null;
    }

    public static function groupDefinitions($group){//'0'=>'Pendiente de pago'
        $groups = (object)([
            'state'=>['1'=>'Activo','0'=>'Desactivado'],
        ]);
        if (isset($groups->$group)) {
            return $groups->$group;
        }
        return null;
    }

    public static function groupCustomDefinitions($group,$parameters,$data){
        $item='Indefinido';
        switch ($parameters['from']){
            case 'DB':
                if(isset($parameters['data']['where'])){
                    $key=$parameters['data']['where'];
                    $parameters['data']['where']=[$key=>$data['row']->$group];
                }
                $item=BladeEngine::makeOptions($parameters['data'])->toArray()[$data['value']];
                break;
            case 'group':
                $item=self::groupDefinitions($parameters['table'])[$group][$data['value']];
                break;

        }
        return $item;
    }

    /**
     * extra un dato de un json
     */
    public static function jsonDefinitionValue($value,$key,$splitData=null){
        if($splitData!=null) $value=explode($splitData,$value)[1];
        $data=json_decode($value);
        return $data->$key;
    }
}
