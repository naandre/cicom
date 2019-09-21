<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Lineas de labels para los campos de los formularios
    |--------------------------------------------------------------------------
    |
    | Las siguientes lineas de idioma se usan para los labels mas generales usados para los campos
    | input, de los formularios en la aplicación
    |--------------------------------------------------------------------------
    | También puede crear diccionarios independientes para cada modelo, lo único que debe hacer es crear
    | un nuevo arreglo poniendo como llave el nombre de la tabla y cono contenido los campos con el respectivo
    | texto de label, similar al arreglo "dictionary" que encuentra a continuación.
    |
    */

    /*
     |------------------------------------------------------
     | Diccionario general
     |------------------------------------------------------
     | Diccionario con los label's mas comunes en los de la aplicacion
     |
     */
    'dictionary' => [
        'title'           => 'Titulo',
        'name'            => 'Nombres',
        'lastname'            => 'Apellidos',
        'user'            => 'Usuario',
        'email'            => 'Correo Electrónico',
        'password'            => 'Contraseña',
        'password_confirmation'   => 'Repetir contraseña',
        'category'        => 'Categorias',
        'vendor'          => 'Proveedor',
        'brief'           => 'Descripcion corta',
        'description'     => 'Descripcion',
        'variant_details' => 'Detalles',
        'role'            => 'Rol'
    ],
    /**
     * Diccionario para el modelo role
    */
    'roles'=>[
        'name'=>'Nombre',
        'display_name'=>'Nombre para mostrar',
        'description'=>'Descripción'
    ],
    /**
     * Diccionario para el modelo role
    */
    'permissions'=>[
        'name'=>'Nombre',
        'display_name'=>'Nombre para mostrar',
        'description'=>'Descripción'
    ],
    /**
     * Diccionario para el modelo category
    */
    'categories'=>[
        'name'=>'Nombre',
        'state'=>'Estado'
    ]

];
