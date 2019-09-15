<?php

namespace App;

use OsTheNeo\Toaster\BladeEngine;

class Dictionary
{
    public static function alias($ask) {

        $dictionary = (object)[
            'productTable'  => Product::class,
            'variantTable'  => Variant::class,
        ];

        return $dictionary->$ask;
    }

    public static function replacemente($ask) {
        /**estructura de datos para las tablas de purchase*/
        $purchaseTables=[
            'delivery_state' => ['kind' => 'group'],
            'payment_state'  => ['kind' => 'group'],
            'state'          => ['kind' => 'group'],
            'note'          => ['kind' => 'json','value' => 'datetime','splitData'=>'data:']];

        $replacement = (object)[
            'purchaseTable' =>$purchaseTables,
            'purchaseTableBogota' => $purchaseTables,
            'purchaseTableBogotaNorte' => $purchaseTables
        ];
        if (isset($replacement->$ask))
            return $replacement->$ask;
        return null;
    }

    public static function groupDefinitions($group){//'0'=>'Pendiente de pago'
        $groups = (object)([
            'size'           => ['0s' => 'Pequeño', '1s' => 'Mediano', '2s' => 'Grande'],
            'delivery_state' => ['Pendiente de envío', 'Enviado', 'Recibido', 'Devuelto cliente', 'Devuelto despachadora'],
            'payment_state'  => ['0'=>'......','1'=>'Aprobado','2'=>'Rechazada','3'=>'En verificación','4'=>'Fallida',
                '5'=>'N/D','6'=>'Reversada','7'=>'Retenida','8'=>'Iniciada','9'=>'Exprirada',
                '10'=>'Abandonada','11'=>'Cancelada','12'=>'Antifraude'],
            'state'          => ['Pendiente', 'Aprobada', 'Cancelado usuario', 'Cancelado administrador'],
            'cities'=>config('store.cities'),
            'states'=>['1'=>'Activado','0'=>'Desactivado'],
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
