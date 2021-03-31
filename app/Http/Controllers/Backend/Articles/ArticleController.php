<?php

namespace App\Http\Controllers\Backend\Articles;

use App\Http\Controllers\Backend\Controller;
use App\Models\Articles\Article;
use App\Models\Articles\Pais;
use App\Models\Articles\Ciudad;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use OsTheNeo\Toaster\BladeEngine;
use OsTheNeo\Toaster\FilesHelper;

class ArticleController extends Controller
{
    protected $rules=[
        "title"=>['required','string','max:100'],
        "description"=>['required','string','max:500'],
        "category_id"=>['required','numeric'],
        "line_id"=>['required','numeric'],
        "editorial"=>['required','string','max:50'],
        "publication_date"=>['required'],
        "file"=>['required','file']
    ];

    public function index()
    {
        $buttons=[];
        if(Auth::user()->can(['cargar_arch']))
            $buttons=[
                'top-right' => [
                    'kind'  => 'link',
                    'route' => 'admin.article.create',
                    'text'  => 'Cargar Documento'],
                'top-left' => [
                    'kind'  => 'link',
                    'route' => 'admin.cargueMasivo.create',
                    'text'  => 'Cargar Documentos Masivamente'],
            ];
        $indexTable = (object)[
            'title' => 'Gestión de documentos',
            'visualization' => 'table',
            'model'         => new Article(),
            'data'          => 'ajax',
            'schema'        => 'articleTable',
            'filters'       =>'filter[isNull]=deleted_at',
            'buttons'       => $buttons];
        $this->options = ['contents' => $indexTable];
        return parent::index();
    }

    function Forms($title) {
        $models = (object)['Article' => Article::class];

        $articleForm = (object)[
            'title' => $title,
            'visualization' => 'form',
            'model' => 'Article',
            'buttons'       => [
            ],
            'date'=>['date'=>[]]
        ];

        $forms = ['contents' => $articleForm,
            'models'   => $models,'submitButton'=>true];

        return $forms;
    }

    public function create()
    {
        $this->options = $this->Forms('Crear documento');
        return parent::create();
    }

    public function store(Request $request)
    {
        $this->validate($request,$this->rules);
        $input=$request->all();
        $input['file']=FilesHelper::store($request,'file',$input['title'],'articles');
        $input["user_id"]=Auth::user()->id;
        $article=new Article($input);
        $article->save();
        Session::flash('success', "Documento Enviado, Ahora agrega los autores");
        return redirect()->route('admin.author.index',$article->id);
    }

    public function edit($id) {
        $this->Model = Article::find($id);
        unset($this->Model->fields['file']['options']['required']);
        $this->options = $this->Forms('Editar documento '.$this->Model->title);
        return parent::edit($id);
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function update(Request $request, $id)
    {
        $message="Lo sentimos, no se encontró el documento ";
        $typeMessage='danger';
        if(!empty($article=Article::find($id))){
            unset($this->rules['file']);
            $this->validate($request,$this->rules);
            $input=$request->all();
            if($request->file) $input['file']=FilesHelper::update($request,'file',$input['title'],'articles',$article->file);
            $article->fill($input);
            $article->save();
            $message="Se actualizo el documento  ".$article->name." de forma exitosa";
            $typeMessage='success';
        }
        Session::flash($typeMessage, $message);
        return redirect()->route('admin.article.index');
    }

    public function show($id)
    {
        /**@var Article $article*/
        if(empty($article=Article::find($id))){
            Session::flash('danger', "Lo sentimos, no se encontró el documento  especificado");
            return redirect()->route('admin.article.index');
        }
        if(empty($article->ciudad))
        {
            $ciudad = new Ciudad();
            $pais = new Pais();
        }
        else{
            $ciudad = $article->ciudad;
            $pais = $article->ciudad->pais;
        }
        
        $data=[
            BladeEngine::Translate('title',$article)=>$article->title,
            BladeEngine::Translate('description',$article)=>$article->description,
            BladeEngine::Translate('category_id',$article)=>$article->category->name,
            BladeEngine::Translate('line_id',$article)=>$article->line->name,
            BladeEngine::Translate('editorial',$article)=>$article->editorial,
            BladeEngine::Translate('publication_country',$article)=>$pais->nombre,
            BladeEngine::Translate('publication_city',$article)=>$ciudad->nombre,
            BladeEngine::Translate('publication_date',$article)=>$article->publication_date,
            BladeEngine::Translate('file',$article)=>'<a  target="_blank" href="'.asset(config('toaster.fileUpload.prefixUrl').'articles/'.$article->file).'">'.$article->file.'</a>',

        ];

        $raceDetail = (object)[
            'title'         => 'Detalles del documento '.$article->title,
            'visualization' => 'list',
            'data'          =>$data,
        ];

        $this->options = ['contents' => [$raceDetail]];
        return parent::show($id);
    }

    public function destroy($id,Request $request){
        /**@var Article $article*/
        if(empty($article=Article::find($id))){
            Session::flash('danger','No se encontró el documento  especificado');
            return redirect()->route('admin.article.index');
        }
        Session::flash('success','documento  eliminado');
        $article->delete();
        FilesHelper::destroy('articles/'.$article->file);
        return redirect()->route('admin.article.index');
    }

    public function findCityFromCountry($idCountry)
    {
        $findCityFromCountry = Ciudad::where('idPais', $idCountry)->get();
        return with(["findCityFromCountry" => $findCityFromCountry]);
    }
}
