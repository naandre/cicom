<?php

namespace App\Http\Controllers\Backend\Articles;

use App\Http\Controllers\Backend\Controller;
use App\Models\Articles\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CategoryController extends Controller
{
    protected $rules=[
        'name' => ['required', 'string', 'max:50','unique:categories'],
        'state' => ['required', 'numeric'],
    ];
    public function index()
    {
        $indexTable = (object)[
            'title'     =>  'Listado de Categorías',
            'visualization' => 'table',
            'model'         => new Category(),
            'data'          => 'ajax',
            'schema'        => 'categoryTable',
            'filters'       =>'filter[isNull]=deleted_at',
            'buttons'       => [
                'top-right' => [
                    'kind'  => 'link',
                    'route' => 'admin.category.create',
                    'text'  => 'Crear Categoría']
            ]];
        $this->options = ['contents' => $indexTable];

        return parent::index();
    }

    function Forms($title) {
        $models = (object)['Category' => Category::class];

        $categoryForm = (object)[
            'title' =>  $title,
            'visualization' => 'form',
            'model' => 'Category',
            'buttons'       => [
                'top-right' => [
                    'kind'  => 'link',
                    'route' => 'admin.category.index',
                    'text'  => 'Cancelar']
            ]
        ];

        $forms = ['contents' => $categoryForm,
            'models'   => $models];

        return $forms;
    }

    public function create()
    {
        $this->options = $this->Forms('Nueva Categoría');
        return parent::create();
    }

    public function store(Request $request)
    {
        $this->validateUnserialize($request,$this->rules);
        $category=new Category($input=$this->unserializeForms($request->forms)[1]);
        $category->save();
        Session::flash('success', "Se creó la categoría");
        return redirect()->route('admin.category.index');
    }

    public function edit($id) {
        $this->Model=Category::find($id);
        $this->options = $this->Forms('Editar Categoría '.$this->Model->name);
        return parent::edit($id);
    }

    public function update(Request $request, $id)
    {
        $message="Lo sentimos, no se encontró la categoría especificada";
        $typeMessage='danger';

        /**@var Category $category*/
        if(!empty($category=Category::find($id))){
            $input=$this->unserializeForms($request->forms)[1];
            /**Si el nombre de la categoría aun no esta en uso, se continua con la actuaizacion*/
            if(($categoryAux=Category::where('name',$input['name'])->first()) and $categoryAux->id!=$id){
                Session::flash($typeMessage, "La categoría ".$input['name']." ya se encuentra creada");
                return redirect()->route('admin.category.edit',$category->id);
            }
            unset($this->rules['name'][3]);
            $this->validateUnserialize($request,$this->rules);
            $category->fill($input);
            $category->save();

            $message="Se editaron los datos";
            $typeMessage='success';
        }
        Session::flash($typeMessage, $message);
        return redirect()->route('admin.category.index');
    }

    public function destroy($id,Request $request){
        /**@var Category $category*/
        if(empty($category=Category::find($id))){
            Session::flash('danger','No se encontró la categoría especificada');
            return redirect()->route('admin.category.index');
        }
        Session::flash('success','Categoría Eliminada');
        $category->delete();
        return redirect()->route('admin.category.index');
    }
}
