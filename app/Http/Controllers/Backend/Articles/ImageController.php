<?php

namespace App\Http\Controllers\Backend\Articles;

use App\Http\Controllers\Backend\Controller;
use App\Models\Articles\Image;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    protected $rules=[
        'name' => ['required', 'string', 'max:50','unique:categories'],
        'state' => ['required', 'numeric'],
    ];
    public function index()
    {
        $indexTable = (object)['visualization' => 'table',
            'model'         => new Image(),
            'data'          => 'ajax',
            'schema'        => 'imageTable',
            'filters'       =>'filter[isNull]=deleted_at',
            'buttons'       => [
                'top-right' => [
                    'kind'  => 'link',
                    'route' => 'admin.image.create',
                    'text'  => 'Subir Imagen']
            ]];
        $this->options = ['contents' => $indexTable];

        return parent::index();
    }

    function Forms() {
        $models = (object)['Image' => Image::class];

        $imageForm = (object)[
            'visualization' => 'form',
            'model' => 'Image',
            'buttons'       => [
                'top-right' => [
                    'kind'  => 'link',
                    'route' => 'admin.image.index',
                    'text'  => 'Cancelar']
            ]
        ];

        $forms = ['contents' => $imageForm,
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
        $image=new Image($input=$this->unserializeForms($request->forms)[1]);
        $image->save();
        Session::flash('success', "Se subió la imagen");
        return redirect()->route('admin.image.index');
    }


    public function destroy($id,Request $request){
        /**@var Image $image*/
        if(empty($image=Image::find($id))){
            Session::flash('danger','No se encontró la Imagen especificada');
            return redirect()->route('admin.image.index');
        }
        Session::flash('success','Imagen eliminada');
        $image->delete();
        return redirect()->route('admin.image.index');
    }
}
