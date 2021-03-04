<?php

namespace App\Http\Controllers\Backend\Reports;

use App\Http\Controllers\Backend\Controller;
use App\Models\Articles\ArticleReport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use OsTheNeo\Toaster\BladeEngine;
use OsTheNeo\Toaster\FilesHelper;

class ArticleReportController extends Controller
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

        $indexTable = (object)[
            'title' => 'Consulta de documentos',
            'visualization' => 'table',
            'model'         => new ArticleReport(),
            'data'          => 'ajax',
            'schema'        => 'articleReport',
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



    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     * @throws ValidationException
     */

    public function show($id)
    {
        /**@var Article $article*/
        if(empty($article=Article::find($id))){
            Session::flash('danger', "Lo sentimos, no se encontró el documento  especificado");
            return redirect()->route('admin.article.index');
        }

        $data=[
            BladeEngine::Translate('title',$article)=>$article->title,
            BladeEngine::Translate('description',$article)=>$article->description,
            BladeEngine::Translate('category_id',$article)=>$article->category->name,
            BladeEngine::Translate('line_id',$article)=>$article->line->name,
            BladeEngine::Translate('editorial',$article)=>$article->editorial,
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
}
