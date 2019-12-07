<?php

namespace App\Http\Controllers\Backend\Articles;

use App\Http\Controllers\Backend\Controller;
use App\Models\Articles\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AuthorController extends Controller
{
    protected $rules=[
        "name"=>['required','string','max:100'],
        "lastname"=>['required','string','max:100']
    ];

    public function indexCustom($articleId)
    {
        $indexTable = (object)[
            'title' => 'Autores del documento',
            'visualization' => 'table',
            'model'         => new Author(),
            'data'          => 'ajax',
            'schema'        => 'authorTable',
            'filters'       =>'filter[isNull]=deleted_at&filter[article_id]='.$articleId,
            'buttons'       => [
                'top-right' => [
                    'kind'  => 'link',
                    'route' => 'admin.author.create',
                    'parameters'=>$articleId,
                    'text'  => 'Agregar Autor']
            ]];
        $this->options = ['contents' => $indexTable];
        return parent::index();
    }

    function Forms($articleId,$title) {
        $models = (object)['Author' => Author::class];

        $authorForm = (object)[
            'title' => $title,
            'visualization' => 'form',
            'model' => 'Author',
            'buttons'       => [
                'top-right' => [
                    'kind'  => 'link',
                    'route' => 'admin.author.index',
                    'parameters'=>$articleId,
                    'text'  => 'Cancelar']
            ]
        ];

        $forms = ['contents' => $authorForm,
            'models'   => $models,'submitButton' => true,'mixt'=>['parameters'=>["articleId"=>$articleId]]];

        return $forms;
    }

    public function createCustomer($articleId)
    {
        $this->options = $this->Forms($articleId,"Agregar Autores del documento");
        return parent::create()->with(['access'=>'mixt']);
    }

    public function store(Request $request)
    {
        $this->validate($request,$this->rules);
        $input=$request->all();
        $input['article_id']=$request->articleId;
        $author=new Author($input);
        $author->save();
        Session::flash('success', "Autor agregado exitosamente");
        return redirect()->route('admin.author.index',$request->articleId);
    }

    public function edit($id) {
        $this->Model=Author::find($id);
        $this->options = $this->Forms($this->Model->article_id,'Editar Autores del documento');
        return parent::edit($id);
    }

    public function update(Request $request, $id)
    {
        $message="Lo sentimos, no se encontró el Autor";
        $typeMessage='danger';

        /**@var Author $author*/
        if(!empty($author=Author::find($id))){
            $input=$request->all();
            $this->validate($request,$this->rules);
            $author->fill($input);
            $author->save();
            $message="Se editaron los datos";
            $typeMessage='success';
        }
        Session::flash($typeMessage, $message);
        return redirect()->route('admin.author.index',$author->article_id);
    }

    public function destroy($id,Request $request){
        /**@var Author $author*/
        if(empty($author=Author::find($id))){
            Session::flash('danger','No se encontró el Autor especificado');
            return redirect()->route('admin.author.index');
        }
        Session::flash('success','Autor Eliminado');
        $author->delete();
        return redirect()->route('admin.author.index',$author->article_id);
    }
}
