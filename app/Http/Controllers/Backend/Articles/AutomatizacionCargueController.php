<?php


namespace App\Http\Controllers\Backend\Articles;

use App\Http\Controllers\Backend\Controller;
use App\Models\Articles\Article;
use App\Models\Articles\CargueMasivo;
use App\Models\Articles\DetalleCargueMasivo;
use App\Models\Articles\EstadoCargue;
use App\Models\Articles\Category;
use App\Models\Articles\LinesInvestigation;
use App\Models\Articles\Ciudad;
use App\Models\Articles\Author;
use OsTheNeo\Toaster\FilesHelper;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AutomatizacionCargueController extends Controller
{
    public function procesarCargues()
    {
        $estadoCargueEnProceso=EstadoCargue::where('descripcionEstado','En Proceso')->first();
        $estadoCargueFinalizado=EstadoCargue::where('descripcionEstado','Finalizado')->first();
        $listaCargues=CargueMasivo::where('estado',$estadoCargueEnProceso->id)->get();
        $rutaBase = config('toaster.fileUpload.prefixUrl');
        $rutaBaseCargues = $rutaBase.'carguesMasivos/';
        $rutaBaseArticulos = $rutaBase.'articles/';
        foreach($listaCargues as $cargue)
        {
            $rutaArchivo = $rutaBaseCargues.$cargue->id.'/'.$cargue->archivoCargue;
            if(!file_exists($rutaArchivo))
            continue;

            $spreadsheet = new Spreadsheet();
            $inputFileType = 'Xlsx';
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
            $reader->setReadDataOnly(true);
            $nombreHoja = $reader->listWorksheetInfo($rutaArchivo)[1];
            $reader->setLoadSheetsOnly($nombreHoja);
            $archivo = $reader->load($rutaArchivo);
            $hoja = $archivo->getActiveSheet();
            $listaDetalles = $hoja->toArray();
            unset($listaDetalles[0]);
            $detallesCargueaProcesar = count($listaDetalles);
            $detallesCargueProcesados = 0;
            foreach($listaDetalles as $detalle)
            {
                if(!empty($detalleCargue=DetalleCargueMasivo::find($detalle[0])))
                {
                    if($detalleCargue->estado == $estadoCargueFinalizado->id){
                        $detallesCargueProcesados = $detallesCargueProcesados+1;
                        continue;
                    }
                    $rutaArticuloCargue = $rutaBaseCargues.$cargue->id.'/'.$detalleCargue->nombreDocumento;
                    $rutaNuevaArticulo = $rutaBaseArticulos.'/'.$detalleCargue->nombreDocumento;
                    if(!file_exists($rutaArticuloCargue))
                    continue;

                    $articulo = new Article();
                    $articulo->title = $detalle[2];
                    $articulo->description = $detalle[3];
                    $articulo->keyWords = $detalle[4];

                    $datoscategoria=$detalle[5];
                    if(empty($categoria=Category::find(explode("|",$datoscategoria)[0])))
                    continue;
                    $articulo->category_id = $categoria->id;

                    $datosLineaInv = $detalle[6];
                    if(empty($lineaInv=LinesInvestigation::find(explode("|",$datosLineaInv)[0])))
                    continue;
                    $articulo->line_id = $lineaInv->id;

                    $articulo->editorial = $detalle[7];

                    $fechaPublicacion = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($detalle[8])->format('Y-m-d');
                    $articulo->publication_date = $fechaPublicacion;

                    $datosLugarPub = $detalle[9];
                    if(empty($ciudad=Ciudad::find(explode("|",$datosLugarPub)[0])))
                    continue;
                    $articulo->publication_city = $ciudad->id;

                    $articulo->file = $detalleCargue->nombreDocumento;
                    $articulo->user_id = $cargue->userId;
                    $camposConsulta = ['title' => $articulo->title,'description'=>$articulo->description,'category_id'=>$articulo->category_id,
                    'line_id'=>$articulo->line_id,'editorial'=>$articulo->editorial,'publication_date'=>$articulo->publication_date,'file'=>$articulo->file];
                    if(empty($consultaArticulo = Article::where($camposConsulta)->first()))
                    {
                        $articulo->save();
                    }
                    else
                    $articulo->id = $consultaArticulo->id;
                    if(!rename($rutaArticuloCargue,$rutaNuevaArticulo))
                        continue;
                    
                    if(empty($articulo->id))
                    {
                        rename($rutaNuevaArticulo,$rutaArticuloCargue);
                        continue;
                    }
                    $listaDatosAutores = explode("|",$detalle[10]);
                    foreach($listaDatosAutores as $datoAutor){
                        $nombreAutor = explode(",",$datoAutor);

                        $autor = new Author();
                        $autor->name = $nombreAutor[0];

                        if(count($nombreAutor)>1)
                        $autor->lastname = $nombreAutor[1];
                        else
                        $autor->lastname = "";

                        $autor->article_id = $articulo->id;
                        $autor->save();
                    }
                    $detalleCargue->estado = $estadoCargueFinalizado->id;
                    $detalleCargue->save();
                }
                $detallesCargueProcesados = $detallesCargueProcesados + 1;
            }
            if($detallesCargueaProcesar == $detallesCargueProcesados){
                $cargue->estado = $estadoCargueFinalizado->id;
                $cargue->save();
                echo "Cargue Finalizado <br>";
            }
        }
    }
}
