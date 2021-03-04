<?php


namespace App\Http\Controllers\Backend\Articles;


use App\Http\Controllers\Backend\Controller;
use App\Models\Articles\Article;
use App\Models\Articles\CargueMasivo;
use App\Models\Articles\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Nuwave\Lighthouse\Schema\Types\Scalars\DateTime;
use OsTheNeo\Toaster\BladeEngine;
use OsTheNeo\Toaster\FilesHelper;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Protection;

class CargueMasivoController extends Controller
{
    protected $rules=[
        "nombre"=>['required','string','max:100']
    ];

    public function index()
    {
        $buttons = [];
        if (Auth::user()->can(['cargar_arch']))
            $buttons = [
                'top-right' => [
                    'kind' => 'link',
                    'route' => 'admin.cargueMasivo.create',
                    'text' => 'Cargar Documentos Masivamente']
            ];
        $indexTable = (object)[
            'title' => 'Cargue masivo de documentos',
            'visualization' => 'table',
            'model' => new CargueMasivo(),
            'data' => 'ajax',
            'schema' => 'cargueMasivoTable',
            'filters' => 'filter[isNull]=deleted_at',
            'buttons' => $buttons];
        $this->options = ['contents' => $indexTable];
        return parent::index();
    }

    function Forms($title, $agregarbtnDescarga = false, $idCargue = null) {
        $models = (object)['CargueMasivo' => CargueMasivo::class];
        $buttons = [];
        $submitButton = true;
        if($agregarbtnDescarga){
            $submitButton = false;
            $buttons = [
                'middle-center' => [
                    'kind'  => 'link',
                    'route' => 'admin.cargueMasivo.descargarExcel',
                    'parameters'=>$idCargue,
                    'text'  => 'Descargar Formato'],
                'middle-center' => [
                    'kind'  => 'link',
                    'route' => 'admin.cargueMasivo.edit',
                    'parameters'=>$idCargue,
                    'text'  => 'Subir Formato']
            ];
        }
        $cargueMasivoForm = (object)[
            'title' => $title,
            'visualization' => 'form',
            'model' => 'CargueMasivo',
            'buttons'       => $buttons,
            'date'=>['date'=>[]]
        ];

        $forms = ['contents' => $cargueMasivoForm,
            'models'   => $models,'submitButton'=>$submitButton];

        return $forms;
    }

    public function create()
    {
        $this->options = $this->Forms('Crear cargue masivo de documentos');
        return parent::create();
    }

    public function store(Request $request)
    {
        $this->validate($request,$this->rules);
        $input=$request->all();
        $input["userId"]=Auth::user()->id;
        $input["fecha"]=date("Y-m-d");
        $input["estado"]=1;
        $cargueMasivo=new CargueMasivo($input);
        $cargueMasivo->save();
        Session::flash('success', "Cargue creado, Ahora seleccione lo documentos que va cargar");
        return redirect()->route('admin.detallecargue.create',$cargueMasivo->id);
    }

    public function download($id) {

        $cargueMasivo=CargueMasivo::find($id);
        $data=[

            BladeEngine::Translate('Descargar',$cargueMasivo)=>'<a href="'.route('admin.cargueMasivo.descargarExcel',$id).'">Formato</a>',
            BladeEngine::Translate('Subir',$cargueMasivo)=>'<a href="'.route('admin.cargueMasivo.edit',$id).'">Formato</a>',

        ];

        $raceDetail = (object)[
            'title'         => 'Descargar Formato cargue masivo ',
            'visualization' => 'list',
            'data'          =>$data,
        ];

        $this->options = ['contents' => [$raceDetail]];
        return parent::show($id);
        /*$this->Model->fields = [];
        $this->options = $this->Forms('Descargar Formato cargue masivo ', true, $id);
        return parent::edit($id);*/
    }

    public function edit($id) {
        $this->Model = CargueMasivo::find($id);
        $this->Model->fields = [
            "archivoCargue"=>['kind'=>'file','options'=>['accept'=>'.xlsx','required'=>'required']]
        ];
        $this->options = $this->Forms('Adjuntar Formato cargue masivo ');
        return parent::edit($id);
    }

    public function descargarExcel($id)
    {
        if(empty($cargueMasivo=CargueMasivo::find($id))){
            Session::flash('danger', "Lo sentimos, no se encontró el cargue  especificado");
            return redirect()->route('admin.cargueMasivo.index');
        }
        $listaCategorias=current((array)DB::table('categories')->select(DB::raw('concat(id,"|",name) as name'))->pluck('name'));
        $textoCategorias = implode(',', $listaCategorias);
        $listaLineasInves=current((array)DB::table('lines_investigation')->select(DB::raw('concat(id,"|",name) as name'))->pluck('name'));
        $textoLineasInves=implode(',',$listaLineasInves);
        $listaDetalleCargue=current((array)DB::table('detallecarguemasivo')->select(DB::raw('id,nombreDocumento'))->where('idCargueMasivo','=',$id)->get());
        $categoria=Category::all();
        require __DIR__.'/../../../../../vendor/autoload.php';
        $excel = new Spreadsheet();
        $numCelda = 1;
        $hoja = $excel->getActiveSheet();
        $hoja->setTitle("Articulos");
        $hoja->setCellValue("A1", "Id Cargue");
        $hoja->setCellValue("B1", "Nombre Archivo");
        $hoja->setCellValue("C1", "Título");
        $hoja->setCellValue("D1", "Descripción");
        $hoja->setCellValue("E1", "Categoría");
        $hoja->setCellValue("F1", "Línea de Investigación");
        $hoja->setCellValue("G1", "Editorial");
        $hoja->setCellValue("H1", "Fecha de Publicación (YYYY/MM/DD)");
        $hoja->setCellValue("I1", "Autores (Separados por un pipe |)");
        foreach ($listaDetalleCargue as $detalleCargue) {
            $numCelda++;
            $this->armarCeldaExcel($hoja, 'A'.$numCelda, "texto", $detalleCargue->id);
            $this->armarCeldaExcel($hoja, 'B'.$numCelda, "texto", $detalleCargue->nombreDocumento);
            $this->armarCeldaExcel($hoja, 'E'.$numCelda, "lista", $textoCategorias);
            $this->armarCeldaExcel($hoja, 'F'.$numCelda, "lista", $textoLineasInves);
            $this->armarCeldaExcel($hoja, 'H'.$numCelda, "fecha");
            $this->armarCeldaExcel($hoja, 'I'.$numCelda, "texto",null,"Los Autores deben ir separados por un |");
        }
        $hoja->getProtection()->setSheet(true);
        $hoja->getStyle('C2:I'.$numCelda)->getProtection()->setLocked(Protection::PROTECTION_UNPROTECTED);
        $writer = new Xlsx($excel);
        $nombreDelDocumento = $cargueMasivo->nombre.".xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($nombreDelDocumento).'"');
        ob_end_clean();
        $writer->save('php://output');
        unset($this->Model->fields['file']['options']['required']);
        exit();
    }

    private function armarCeldaExcel($hoja, $celda, $tipo, $datos=null, $textoEsp = ""){
        $objValidation = $hoja->getCell($celda)->getDataValidation();
        switch ($tipo)
        {
            case "lista":
                $objValidation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
                $objValidation->setShowDropDown(true);
                $objValidation->setError('El valor no esta en la lista.');
                $objValidation->setPromptTitle('Seleccione');
                $objValidation->setPrompt('Por favor seleccione el valor de la lista.');
                if($datos != null)
                    $objValidation->setFormula1('"'.$datos.'"');
                break;
            case "fecha":
                $objValidation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_DATE );
                $hoja->getStyle($celda)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
                $objValidation->setPromptTitle('Fecha');
                $objValidation->setPrompt('La fecha debe ir en formato yyyy/mm/dd');
                $objValidation->setOperator('isValidDate');
                $objValidation->setError('Fecha invalida.');
                break;
            case "texto":
                $objValidation->setPromptTitle('Texto');
                if($textoEsp != "")
                    $objValidation->setPrompt($textoEsp);
                $hoja->setCellValue($celda, $datos);
                break;

        }
        $objValidation->setErrorTitle('Error');
        $objValidation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP );
        $objValidation->setAllowBlank(false);
        $objValidation->setShowInputMessage(true);
        $objValidation->setShowErrorMessage(true);


        $hoja->getCell($celda)->setDataValidation($objValidation);
    }

    public function update(Request $request, $id)
    {
        $message="Lo sentimos, no se encontró el cargue ";
        $typeMessage='danger';
        if(!empty($cargueMasivo=CargueMasivo::find($id))){
            $input=$request->all();
            /*dump($request->file);
            die();
            if($request->file) {
                $file = $request->file('archivoCargue');

                $input['archivoCargue']= FilesHelper::processFile($file, $file->getClientOriginalName(),'carguesMasivos/' . $request->cargueMasivoId, 'pdf');
            }*/
            $input['archivoCargue']=FilesHelper::store($request,'archivoCargue',$cargueMasivo->nombre,'carguesMasivos/' . $id);
            $cargueMasivo->fill($input);
            $cargueMasivo->estado = 2;
            $cargueMasivo->save();
            $message="Se subio el formato ".$cargueMasivo->nombre." de forma exitosa, se iniciara el proceso de cargue";
            $typeMessage='success';
        }
        Session::flash($typeMessage, $message);
        return redirect()->route('admin.article.index');
    }

    public function show($id)
    {
        /**@var CargueMasivo $cargueMasivo*/
        if(empty($cargueMasivo=CargueMasivo::find($id))){
            Session::flash('danger', "Lo sentimos, no se encontró el cargue  especificado");
            return redirect()->route('admin.cargueMasivo.index');
        }

        $data=[
            BladeEngine::Translate('nombre',$cargueMasivo)=>$cargueMasivo->nombre,
            BladeEngine::Translate('estado',$cargueMasivo)=>$cargueMasivo->estadoCargue->descripcionEstado,
            BladeEngine::Translate('fecha',$cargueMasivo)=>$cargueMasivo->fecha,
            BladeEngine::Translate('archivoCargue',$cargueMasivo)=>'<a  target="_blank" href="'.asset(config('toaster.fileUpload.prefixUrl').'carguesMasivos/'.$cargueMasivo->id.'/'.$cargueMasivo->archivoCargue).'">'.$cargueMasivo->archivoCargue.'</a>',

        ];

        $raceDetail = (object)[
            'title'         => 'Detalles del cargue masivo de documentos '.$cargueMasivo->nombre,
            'visualization' => 'list',
            'data'          =>$data,
        ];

        $this->options = ['contents' => [$raceDetail]];
        return parent::show($id);
    }


}
