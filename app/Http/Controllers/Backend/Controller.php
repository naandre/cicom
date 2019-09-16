<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use OsTheNeo\Toaster\Controllers\ToasterController;

class Controller extends ToasterController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * metodo sobreescrito para adaptarlo a la serializacion del formulario
    */
    public function validateUnserialize(Request $request, array $rules, array $messages = [], array $customAttributes = [])
    {
        $request->request->add($this->unserializeForms($request->forms)[1]);
        return $this->validate($request,$rules,$messages,$customAttributes);
    }
}