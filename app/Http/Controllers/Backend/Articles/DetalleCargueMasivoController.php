<?php


namespace App\Http\Controllers\Backend\Articles;


use App\Http\Controllers\Backend\Controller;
use App\Models\Articles\CargueMasivo;
use App\Models\Articles\DetalleCargueMasivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Nuwave\Lighthouse\Schema\Types\Scalars\DateTime;
use OsTheNeo\Toaster\BladeEngine;
use OsTheNeo\Toaster\FilesHelper;

class DetalleCargueMasivoController extends Controller
{
    protected $rules=[
        "nombreDocumento"=>['required']
    ];

    public function indexDetail($cargueMasivoId)
    {
        $buttons = [];
        if (Auth::user()->can(['cargar_arch']))
            $buttons = [
                'top-right' => [
                    'kind'  => 'link',
                    'route' => 'admin.detallecargue.create',
                    'parameters'=>$cargueMasivoId,
                    'text' => 'Cargar Documentos Masivamente']
            ];
        $indexTable = (object)[
            'title' => 'Cargue masivo de documentos',
            'visualization' => 'table',
            'model' => new DetalleCargueMasivo(),
            'data' => 'ajax',
            'schema' => 'detalleCargueMasivoTable',
            'filters' => 'filter[isNull]=deleted_at&filter[idCargueMasivo]='.$cargueMasivoId,
            'buttons' => $buttons];
        $this->options = ['contents' => $indexTable];
        return parent::index();
    }

    function Forms($cargueMasivoId,$title) {
        $models = (object)['DetalleCargueMasivo' => DetalleCargueMasivo::class];

        $detalleCargueMasivoForm = (object)[
            'title' => $title,
            'visualization' => 'form',
            'model' => 'DetalleCargueMasivo',
            'buttons'       => [
                'top-right' => [
                    'kind'  => 'link',
                    'route' => 'admin.detallecargue.index',
                    'parameters'=>$cargueMasivoId,
                    'text'  => 'Cancelar']
            ]
        ];

        $forms = ['contents' => $detalleCargueMasivoForm,
            'models'   => $models,'submitButton' => true,'mixt'=>['parameters'=>["cargueMasivoId"=>$cargueMasivoId]]];

        return $forms;
    }

    public function createDetalle($cargueMasivoId)
    {
        $this->options = $this->Forms($cargueMasivoId,"Cargar documentos");
        return parent::create()->with(['access'=>'mixt']);
    }

    public function store(Request $request)
    {
        $this->validate($request,$this->rules);
        $input=$request->all();
        $input['idCargueMasivo'] = $request->cargueMasivoId;
        $input["estado"]=1;
        if ($request->hasfile('nombreDocumento')) {
            foreach ($request->file('nombreDocumento') as $file) {
                $input['nombreDocumento'] = FilesHelper::processFile($file, $file->getClientOriginalName(),'carguesMasivos/' . $request->cargueMasivoId, 'pdf');
                $detalleCargueMasivo = new DetalleCargueMasivo($input);
                $detalleCargueMasivo->save();
            }
        }
        Session::flash('success', "Documentos cargados, Ahora descargue el formato para diligenciar la información faltante, una vez diligenciado proceda a adjuntarlo");
        return redirect()->route('admin.cargueMasivo.download',$request->cargueMasivoId);
    }


    public function show($id)
    {
        //return redirect()->route('admin.cargueMasivo.index',$id);
        /**@var DetalleCargueMasivo $cargueMasivo*/
        if(empty($cargueMasivo=CargueMasivo::find($id))){
            Session::flash('danger', "Lo sentimos, no se encontró el detalle del cargue  especificado");
            return redirect()->route('admin.cargueMasivo.index');
        }
        $listData = [];

        foreach ($cargueMasivo->detalleCargueMasivo as $detalleCargue) {
            $data=[
                BladeEngine::Translate('nombre',$detalleCargue)=>$detalleCargue->nombreDocumento,
                BladeEngine::Translate('estado',$detalleCargue)=>$detalleCargue->estadoCargue->descripcionEstado,
                BladeEngine::Translate('log',$detalleCargue)=>$detalleCargue->log,
                BladeEngine::Translate('documento',$detalleCargue)=>'<a  target="_blank" href="'.asset(config('toaster.fileUpload.prefixUrl').'carguesMasivos/'.$cargueMasivo->id.'/'.$detalleCargue->nombreDocumento).'">'.$detalleCargue->nombreDocumento.'</a>',
    
            ];
            //var_dump($data);
        //break;
            $listData[] = $data;
        }

        //var_dump($listData);
       // die();

        $raceDetail = (object)[
            'title'         => 'Detalles del cargue masivo de documentos '.$cargueMasivo->nombre,
            'visualization' => 'listInList',
            'data'          =>$listData,
        ];

        $this->options = ['contents' => [$raceDetail]];
        return parent::show($id);
    }

}
