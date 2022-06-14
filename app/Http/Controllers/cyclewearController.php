<?php

namespace App\Http\Controllers;

use App\Entities\ModelGlobal;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use FunctionsCustoms;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Messaging;

use PDF;

use Illuminate\Support\Facades\DB;

class cyclewearController extends Controller
{
        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('api');

        //header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Origin: *");

        $this->cur_connect = 'mysql';
        $this->db = 'cyclewear_sys';

        // Datos para consultas de Api de Siigo
        $this->url_siigo_api = "https://api.siigo.com/v1/";
    }

    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function cwrGeneral(Request $request, $accion, $parametro=null)
    {
        switch ($accion) {
            case 1:
                $this->cwrCategorias($request);
                break;
            case 2:
                $this->cwrTiposCliente($request);
                break;
            case 3:
                $this->cwrMainMenu($request);
                break;
            case 4:
                $this->createUser($request);
                break;
             case 5:
                $this->login($request);
                break;
            case 6:
                $this->homepageDemos($request);
                break;
            case 7:
                $this->cwrTipoIdentificacion($request);
                break;
            case 8:
                $this->cwrCondicionProducto($request);
                break;
            case 9:
                $this->cwrListarSexo($request);
                break;
            case 10:
                $this->cwr2($request);
                break;
            case 11:
                $this->cwr3($request);
                break;
            case 12:
                $this->cwr4($request);
                break;
            case 13:
                $this->readUser($request);
                break;
            case 14:
                $this->cwrCreateProduct($request);
                break;
            case 15:
                $this->cwrGetProducts($request, $parametro);
                break;
            case 16:
                $this->cwrTiposProducto($request, $parametro);
                break;
            case 17:
                $this->cwrListarConsecutivos($request, $parametro);
                break;
            case 18:
                $this->cwrCrearConsecutivos($request, $parametro);
                break;
            case 19:
                $this->cwrActualizarConsecutivos($request, $parametro);
                break;
            case 20:
                $this->cwrListarVariantesProducto($request, $parametro);
                break;       
            case 21:
                $this->cwrcrearVarianteProductoDB($request, $parametro);
                break;
            case 22:
                $this->cwrCrearProductoDB($request);
                break;
            case 23:
                $this->cwrListarProductoDB($request);
                break;
            case 24:
                $this->cwrLeeUnProductoDB($request);
                break;
            case 25:
                $this->cwrListarUnaVarianteProducto($request, $parametro);
                break;
            case 26:
                $this->cwrListarResponsableIVA($request, $parametro);
                break;
            $this->readUserEmail($request);
                break;
            case 100:
                $this->listarInterlocutores($request);
                break;
            case 101:
                $this->crearCliente($request);
                break;
            case 102:
                $this->cwrleerUnCliente($request);
                break;
            case 110:
                $this->cwrCrearInterlocutor($request);
                break;
            case 111:
                $this->cwrlistarProveedores($request);
                break;
            case 112:
                $this->cwrTiposInterlocutores($request);
                break;
            case 113:
                $this->cwrCrearInterlocutorBE($request);
                break;
            case 114:
                $this->cwrLeerInterlocutor($request);
                break;    
            case 200:
                $this->cwrBikeExchange($request);
                break;
            case 201:
                $this->cwrReadBills($request);
                break;
            case 202:
                $this->cwrReadEnvoice($request);
                break;
            case 203:
                $this->cwrReadAdverts($request);
                break;
            case 204:
                $this->cwrReadAdvertsVariants($request);
                break;
            case 205:
                $this->cwrReadEnvoiceDate($request);
                break;
            case 710:
                $this->listaProductos($request);
                break;
            case 711:
                $this->crearProducto($request);
                break;
            case 712:
                $this->consultarProducto($request);
                break;
            case 713:
                $this->actualizarProducto($request);
                break;
            case 714:
                $this->borrarProducto($request);
                break;
            case 730:
                $this->listaFacturas($request);
                break;
            case 999:
                $this->cwrDatosEntorno($request);
                break;
            default:
                $response = array(
                    'type' => '0',
                    'message' => 'ERROR INESPERADO'
                );
                echo json_encode($response);
                exit;
        }
    }

    // Lee la condición del producto
    public function cwrDatosEntorno($rec)
    {
        $db_name = "cyclewear_sys";
    
        $condicionproducto = DB::connection($this->cur_connect)->select("select t0.id as value, t0.nombrecondicion as label 
                                                                             from ".$db_name.'.condicionproducto'." 
                                                                             t0 WHERE t0.estado = 1 ORDER BY nombrecondicion ASC");
        
        $tiposcliente = DB::connection($this->cur_connect)->select("select t0.*, t0.id as value, t0.nombretipocliente as label
                                                                    from ".$db_name.'.tipocliente'." t0 
                                                                    WHERE t0.estado = 1 ORDER BY tipocliente ASC");

        $listProveedores = DB::connection($this->cur_connect)->select("select t0.id as value, t0.razonsocial as label, t0.* 
                                                                       from ".$db_name.'.interlocutores'." t0
                                                                        WHERE t0.estado = 1 ORDER BY tipotercero ASC");

        $listSexo = DB::connection($this->cur_connect)->select("select t0.id as value, t0.nombresexo as label, t0.* 
                                                                from ".$db_name.'.sexo'." t0
                                                                WHERE t0.estado = 1 ORDER BY nombresexo ASC");

        $tiposinterlocutores = DB::connection($this->cur_connect)->select("select t0.id as value, 
                                                                t0.nombretipotercero as label, t0.* 
                                                                from ".$db_name.'.tipotercero'." t0
                                                                WHERE t0.estado = 1 ORDER BY nombretipotercero ASC");

        $tiposidentificacion = DB::connection($this->cur_connect)->select("select t0.id as value,
                                                                t0.descripcion as label, t0.* 
                                                                from ".$db_name.'.tipoidentificacion'." t0 
                                                                WHERE t0.estado = 1 ORDER BY tipoidentificacion ASC");
                                                                                              
        $ciudades = DB::connection($this->cur_connect)->select("select t0.id as value,
                                                                t0.nombre as label, t0.* 
                                                                from ".$db_name.'.ciudades'." t0 
                                                                ORDER BY nombre ASC");

        $tipoderegimen = DB::connection($this->cur_connect)->select("select t0.id as value, t0.descripcion as label, t0.* 
                                                                from ".$db_name.'.tiporegimen'." t0
                                                                WHERE t0.estado = 1 ORDER BY descripcion ASC");

        $responsabilidadfiscal = DB::connection($this->cur_connect)->select("select t0.id as value,
                                                                t0.descripcion as label, t0.* 
                                                                from ".$db_name.'.responsabilidadfiscal'." t0
                                                                WHERE t0.estado = 1 ORDER BY descripcion ASC");

        $listTiposProductos = DB::connection($this->cur_connect)->select("select t0.id as value, t0.nombretipoproducto as label, t0.* ,
                                                                                 t1.nombreestado
                                                                from ".$db_name.'.tipodeproducto'." t0
                                                                JOIN ".$db_name.'.estados'." t1 ON t0.estado = t1.id
                                                                WHERE t0.estado = 1 ORDER BY nombretipoproducto ASC");

        $listCategoriasUno = DB::connection($this->cur_connect)->select("select t0.id as value, t0.nombrecategoriauno as label, t0.*,
                                                                t1.nombreestado, t2.nombretipoproducto
                                                                from ".$db_name.'.categoriauno'." t0
                                                                JOIN ".$db_name.'.estados'." t1 ON t0.estado = t1.id
                                                                JOIN ".$db_name.'.tipodeproducto'." t2 ON t0.tipodeproducto = t2.id
                                                                WHERE t0.estado = 1 ORDER BY nombrecategoriauno ASC");

        $listCategoriasDos = DB::connection($this->cur_connect)->select("select t0.id as value, t0.nombrecategoriados as label, t0.*,
                                                                t1.nombreestado, t2.nombretipoproducto, t3.nombrecategoriauno
                                                                from ".$db_name.'.categoriados'." t0
                                                                JOIN ".$db_name.'.estados'." t1 ON t0.estado = t1.id
                                                                JOIN ".$db_name.'.tipodeproducto'." t2 ON t0.tipodeproducto = t2.id
                                                                JOIN ".$db_name.'.categoriauno'." t3 ON t0.categoriauno = t3.id
                                                                WHERE t0.estado = 1 ORDER BY nombrecategoriados ASC");

        $listCategoriasTres = DB::connection($this->cur_connect)->select("select t0.id as value, t0.nombrecategoriatres as label, t0.*,
                                                                t1.nombreestado, t2.nombretipoproducto, t3.nombrecategoriauno,
                                                                t4.nombrecategoriados
                                                                from ".$db_name.'.categoriatres'." t0
                                                                JOIN ".$db_name.'.estados'." t1 ON t0.estado = t1.id
                                                                JOIN ".$db_name.'.tipodeproducto'." t2 ON t0.tipodeproducto = t2.id
                                                                JOIN ".$db_name.'.categoriauno'." t3 ON t0.categoriauno = t3.id
                                                                JOIN ".$db_name.'.categoriados'." t4 ON t0.categoriados = t4.id
                                                                WHERE t0.estado = 1 ORDER BY nombrecategoriatres ASC");

        $listCategoriasCuatro = DB::connection($this->cur_connect)->select("select t0.id as value, t0.nombrecategoriacuatro as label, t0.*,
                                                                t1.nombreestado, t2.nombretipoproducto, t3.nombrecategoriauno,
                                                                t4.nombrecategoriados, t5.nombrecategoriatres
                                                                from ".$db_name.'.categoriacuatro'." t0
                                                                JOIN ".$db_name.'.estados'." t1 ON t0.estado = t1.id
                                                                JOIN ".$db_name.'.tipodeproducto'." t2 ON t0.tipodeproducto = t2.id
                                                                JOIN ".$db_name.'.categoriauno'." t3 ON t0.categoriauno = t3.id
                                                                JOIN ".$db_name.'.categoriados'." t4 ON t0.categoriados = t4.id
                                                                JOIN ".$db_name.'.categoriatres'." t5 ON t0.categoriatres = t5.id
                                                                WHERE t0.estado = 1 ORDER BY nombrecategoriatres ASC");
        
        $listColores = DB::connection($this->cur_connect)->select("select t0.id as value, t0.nombrecolor as label, t0.*
                                                                from ".$db_name.'.colores'." t0 
                                                                WHERE t0.estado = 1 ORDER BY  nombrecolor ASC");

        $listSabores = DB::connection($this->cur_connect)->select("select t0.id as value, t0.nombresabor label, t0.* 
                                                                from ".$db_name.'.sabor'." t0 
                                                                WHERE t0.estado = 1 ORDER BY  nombresabor ASC");
        
        $listTallas = DB::connection($this->cur_connect)->select("select t0.id as value, t0.nombretalla as label, t0.* 
                                                                from ".$db_name.'.talla'." t0 
                                                                WHERE t0.estado = 1 ORDER BY  nombretalla ASC");

        $listMarcoenPulgadas = DB::connection($this->cur_connect)->select("select t0.id as value, t0.nombremarcoenpulgadas as label, t0.*
                                                                from ".$db_name.'.marcoenpulgadas'." t0 
                                                                WHERE t0.estado = 1 ORDER BY nombremarcoenpulgadas ASC");
        
        $listTallaBandana = DB::connection($this->cur_connect)->select("select t0.id as value, t0.nombretallabandana as label, t0.*
                                                                from ".$db_name.'.tallabandana'." t0 
                                                                WHERE t0.estado = 1 ORDER BY nombretallabandana ASC");

        $listTallaCentimetros = DB::connection($this->cur_connect)->select("select t0.id as value, t0.nombretallacentimetros as label,  t0.*
                                                                    from ".$db_name.'.tallaencentimetros'." t0 
                                                                    WHERE t0.estado = 1 ORDER BY nombretallacentimetros ASC");

        $listTallaGuantes = DB::connection($this->cur_connect)->select("select t0.id as value, t0.nombreguantes as label, t0.* 
                                                                from ".$db_name.'.tallaguantes'." t0 
                                                                WHERE t0.estado = 1 ORDER BY nombreguantes ASC");

        $listTallaJersey = DB::connection($this->cur_connect)->select("select t0.id as value, t0.nombrejersey as label, t0.*
                                                                  from ".$db_name.'.tallajersey'." t0 
                                                                  WHERE t0.estado = 1 ORDER BY nombrejersey ASC");

        $listTallaMedias = DB::connection($this->cur_connect)->select("select t0.id as value, t0.nombremedias as label, t0.*
                                                                  from ".$db_name.'.tallamedias'." t0 
                                                                  WHERE t0.estado = 1 ORDER BY nombremedias ASC");

        $listTallaPantaloneta = DB::connection($this->cur_connect)->select("select t0.id as value, t0.nombretallapantaloneta as label,  t0.*
                                                                  from ".$db_name.'.tallapantaloneta'." t0 
                                                                  WHERE t0.estado = 1 ORDER BY nombretallapantaloneta ASC");

        $listTamanoAccesorio = DB::connection($this->cur_connect)->select("select t0.id as value, t0.nombretamanoaccesorio as label,  t0.*
                                                                  from ".$db_name.'.tamanoaccesorio'." t0 
                                                                  WHERE t0.estado = 1 ORDER BY nombretamanoaccesorio ASC");
                                                                  
        $listTamanoComponentes = DB::connection($this->cur_connect)->select("select t0.id as value, t0.nombretamanocomponentes as label, t0.*
                                                                  from ".$db_name.'.tamanocomponentes'." t0 
                                                                  WHERE t0.estado = 1 ORDER BY nombretamanocomponentes ASC");

        $listTamanoLlantasyNeumaticos = DB::connection($this->cur_connect)->select("select t0.id as value, t0.nombrellantasyneumaticos as label, t0.*
                                                                  from ".$db_name.'.tamanollantasyneumaticos'." t0 
                                                                  WHERE t0.estado = 1 ORDER BY nombrellantasyneumaticos ASC");

        $listTamanoRuedasyPartes = DB::connection($this->cur_connect)->select("select t0.id as value, t0.nombreruedasypartes as label, t0.* 
                                                                  from ".$db_name.'.tamanoruedasypartes'." t0 
                                                                  WHERE t0.estado = 1 ORDER BY nombreruedasypartes ASC");
        
        $listAcoplamiento = DB::connection($this->cur_connect)->select("select t0.id as value, t0.nombreacoplamiento as label, t0.*
                                                                  from ".$db_name.'.acoplamiento'." t0 
                                                                  WHERE t0.estado = 1 ORDER BY nombreacoplamiento ASC");
        
        $listDiametro = DB::connection($this->cur_connect)->select("select t0.id as value, t0.nombreadiametro as label, t0.* 
                                                                  from ".$db_name.'.diametro'." t0 
                                                                  WHERE t0.estado = 1 ORDER BY nombreadiametro ASC");
        
        $listRosca = DB::connection($this->cur_connect)->select("select t0.id as value, t0.nombrerosca as label, t0.*
                                                                  from ".$db_name.'.rosca'." t0 
                                                                  WHERE t0.estado = 1 ORDER BY  nombrerosca ASC");

        $listLongitud = DB::connection($this->cur_connect)->select("select t0.id as value, t0.nombrelongitud as label, t0.*
                                                                  from ".$db_name.'.longitud'." t0 
                                                                  WHERE t0.estado = 1 ORDER BY nombrelongitud ASC");

        $listAncho = DB::connection($this->cur_connect)->select("select t0.id as value, t0.nombreancho as label, t0.*
                                                                  from ".$db_name.'.ancho'." t0  
                                                                  WHERE t0.estado = 1 ORDER BY nombreancho ASC");

        $listMaterial = DB::connection($this->cur_connect)->select("select t0.id as value, t0.nombrematerial as label, t0.*
                                                                  from ".$db_name.'.material'." t0  
                                                                  WHERE t0.estado = 1 ORDER BY nombrematerial ASC");

        $listBrazodelaBiela = DB::connection($this->cur_connect)->select("select t0.id as value, t0.nombrebrazodelabiela as label, t0.*
                                                                  from ".$db_name.'.brazodelabiela'." t0    
                                                                  WHERE t0.estado = 1 ORDER BY nombrebrazodelabiela ASC");
                                                            
        $listVariablesProducto = DB::connection($this->cur_connect)->select("select t0.id as value, t0.id as label, t0.*,
                                                                  t1.nombretipoproducto, t2.nombrecategoriauno, t3.nombrecategoriados,
                                                                  t4.nombrecategoriatres, t5.nombrecategoriacuatro
                                                                  from ".$db_name.'.variablesproductos'." t0
                                                                  JOIN ".$db_name.'.tipodeproducto'." t1 ON t0.tipoproducto = t1.id
                                                                  JOIN ".$db_name.'.categoriauno'." t2 ON t0.categoriauno = t2.id  
                                                                  JOIN ".$db_name.'.categoriados'." t3 ON t0.categoriados = t3.id
                                                                  JOIN ".$db_name.'.categoriatres'." t4 ON t0.categoriatres = t4.id
                                                                  JOIN ".$db_name.'.categoriacuatro'." t5 ON t0.categoriacuatro = t5.id
                                                                  WHERE t0.estado = 1 ORDER BY id ASC");
           
        $listClientes = DB::connection($this->cur_connect)->select("select t0.id as value, t0.razonsocial as label, t0.* 
                                                            from ".$db_name.'.interlocutores'." t0
                                                            WHERE t0.estado = 1 && t0.tipotercero = 1 ORDER BY tipotercero ASC");
        
        $entorno = array(
            'vgl_condicionproducto' => $condicionproducto,
            'vgl_tiposcliente' => $tiposcliente,
            'vgl_proveedores' => $listProveedores,
            'vgl_sexo' => $listSexo,
            'vgl_tipointerlocutor' => $tiposinterlocutores,
            'vgl_tiposidentificacion' => $tiposidentificacion,
            'vgl_ciudades' => $ciudades,
            'vgl_tipoderegimen' => $tipoderegimen,
            'vgl_responsabilidadfiscal' => $responsabilidadfiscal,
            'vgl_tiposproductos' => $listTiposProductos,
            'vgl_categoriasUno' => $listCategoriasUno,
            'vgl_categoriasDos' => $listCategoriasDos,
            'vgl_categoriasTres' => $listCategoriasTres,
            'vgl_categoriasCuatro' => $listCategoriasCuatro,
            'vgl_colores' => $listColores,
            'vgl_sabores' => $listSabores,       
            'vgl_tallas' => $listTallas,
            'vgl_marcopulagadas' => $listMarcoenPulgadas,
            'vgl_tallabandana' => $listTallaBandana,
            'vgl_centimetros' => $listTallaCentimetros,
            'vgl_tallaguantes' => $listTallaGuantes,
            'vgl_jersey' => $listTallaJersey,
            'vgl_tallamedias' => $listTallaMedias,
            'vgl_tallapantaloneta' => $listTallaPantaloneta,
            'vgl_tamanoaccesorios' => $listTamanoAccesorio,
            'vgl_tamanocomponentes' => $listTamanoComponentes,
            'vgl_llantasyneumaticos' => $listTamanoLlantasyNeumaticos,
            'vgl_ruedasypartes' => $listTamanoRuedasyPartes,
            'vgl_acoplamiento' => $listAcoplamiento,
            'vgl_diametro' => $listDiametro, 
            'vgl_rosca' => $listRosca,
            'vgl_longitud' => $listLongitud,
            'vgl_ancho' => $listAncho,
            'vgl_material' => $listMaterial ,
            'vgl_brazobiela' => $listBrazodelaBiela,
            'vgl_variablesproducto' => $listVariablesProducto,
            'vgl_clientes' => $listClientes,
        );
    
        $condicionprod = array();
    
        $datoc = [
            'header_supplies' => $condicionproducto
        ];
        $condicionprod[] = $datoc;
    
        echo json_encode($entorno);
    }

     // Lee la condición del producto
    public function cwrBikeExchange($rec)
    {
        $curl = curl_init();
        $iniciar =  $rec->valor;

        curl_setopt_array($curl, array(
                CURLOPT_URL => "https://www.bikeexchange.com.co/api/v2/client/adverts?page%5Bnumber%5D=".$iniciar."&page%5Bsize%5D=100",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                'MARKETPLACER-API-KEY: 4a99fa8c297af70d8878f255f096642b',
                'Authorization: Basic e3t1c2VybmFtZX19Ont7cGFzc3dvcmR9fQ=='
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
    }

    // Lee la condición del producto
    public function cwrReadBills($rec)
    {
        $curl = curl_init();
        $iniciar =  $rec->pagina;

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://www.bikeexchange.com.co/api/v2/client/invoices?page%5Bnumber%5D=".$iniciar."page%5Bsize%5D=100",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
            'MARKETPLACER-API-KEY: 4a99fa8c297af70d8878f255f096642b',
            'Authorization: Basic e3t1c2VybmFtZX19Ont7cGFzc3dvcmR9fQ=='
            ),
        ));
            
        $response = curl_exec($curl);
            
        curl_close($curl);
        echo $response;
    }
    
    // Lee la condición del producto
    public function cwrReadEnvoice($rec)
    {
        $curl = curl_init();
        $envoice =  $rec->factura;

        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://www.bikeexchange.com.co/api/v2/client/invoices/".$envoice,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'MARKETPLACER-API-KEY: 4a99fa8c297af70d8878f255f096642b',
            'Authorization: Basic e3t1c2VybmFtZX19Ont7cGFzc3dvcmR9fQ=='
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        echo $response;
    }

    // Lee la condición del producto
    public function cwrReadEnvoiceDate($rec)
    {
            $curl = curl_init();
            $startdate =  $rec->fecha;

            curl_setopt_array($curl, array(
            CURLOPT_URL => "https://www.bikeexchange.com.co/api/v2/client/invoices?since=".$startdate,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'MARKETPLACER-API-KEY: 4a99fa8c297af70d8878f255f096642b',
                'Authorization: Basic e3t1c2VybmFtZX19Ont7cGFzc3dvcmR9fQ=='
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;

    }
    // Lee la condición del producto
    public function cwrReadAdverts($rec)
    {
        $curl = curl_init();
        $iniciar =  $rec->pagina;
    
        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://www.bikeexchange.com.co/api/v2/client/adverts?page%5Bnumber%5D=".$iniciar."page%5Bsize%5D=100",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'MARKETPLACER-API-KEY: 4a99fa8c297af70d8878f255f096642b',
            'Authorization: Basic e3t1c2VybmFtZX19Ont7cGFzc3dvcmR9fQ=='
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        echo $response;
    }

    public function cwrReadAdvertsVariants($rec)
    {
        $curl = curl_init();
        $adverts =  $rec->anuncio;
    
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://www.bikeexchange.com.co/api/v2/client/adverts/".$adverts."/variants",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
              'MARKETPLACER-API-KEY: 4a99fa8c297af70d8878f255f096642b',
              'Authorization: Basic e3t1c2VybmFtZX19Ont7cGFzc3dvcmR9fQ=='
            ),
          ));
          
          $response = curl_exec($curl);
          
          curl_close($curl);
          echo $response;          
    }
    
    // Lee la condición del producto
    public function cwrCondicionProducto($rec)
    {
        $db_name = "cyclewear_sys";

        $condicionproducto = DB::connection($this->cur_connect)->select("select t0.id as value, t0.nombrecondicion as label 
                                                                         from ".$db_name.'.condicionproducto'." 
                                                                         t0 WHERE t0.estado = 1 ORDER BY nombrecondicion ASC");

        $condicionprod = array();

        $datoc = [
                    'header_supplies' => $condicionproducto
                ];
                $condicionprod[] = $datoc;

        echo json_encode($condicionproducto);
    }

    // Lee la condición del producto
    public function cwrListarConsecutivos($rec)
    {
        $db_name = "cyclewear_sys";
    
        $consecutivoproducto = DB::connection($this->cur_connect)->select(
                                              "select t0.id as value, t0.descripcion as label,
                                               t0.* from ".$db_name.'.consecutivos'." 
                                               t0 WHERE estado = 1 && prefijo = '". $rec->prefijo."'"); 

    echo json_encode($consecutivoproducto);
    }

    //Crear conscutivo en Base de Datos
    public function cwrCrearConsecutivos($rec)
    {

        DB::beginTransaction();
        try {
                    $db_name = $this->db.".consecutivos";
                    $nuevoConsecutivo = new ModelGlobal();
                    $nuevoConsecutivo->setConnection($this->cur_connect);
                    $nuevoConsecutivo->setTable($db_name);

                    $nuevoConsecutivo->prefijo = $rec->prefijo;
                    $nuevoConsecutivo->descripcion = $rec->descripcion;
                    $nuevoConsecutivo->consecutivo = $rec->consecutivo;
                    $nuevoConsecutivo->empresa = $rec->empresa;
                    $nuevoConsecutivo->estado = $rec->estado;

                    $nuevoConsecutivo->save();

        } catch (\Exception $e){

            DB::rollBack();
            $response = array(
                'type' => '0',
                'message' => "ERROR ".$e
            );
            $rec->headers->set('Accept', 'application/json');
            echo json_encode($response);
            exit;
        }
        DB::commit();
        $response = array(
            'type' => 1,
            'message' => 'REGISTRO EXITOSO',
        );
        $rec->headers->set('Accept', 'application/json');
        echo json_encode($response);
        exit;
    }

    //Actualiazar Consecutivo
    public function cwrActualizarConsecutivos($rec)
    {
        //echo json_encode($rec->id);
        //exit;
        $db_name = $this->db.".consecutivos";
 
        DB::beginTransaction();
        try {
 
            DB::connection($this->cur_connect)->update("UPDATE ".$db_name." 
                SET consecutivo = '".$rec->consecutivo."'
                WHERE prefijo = '".$rec->prefijo."'");
 
        } catch (\Exception $e){
 
            DB::rollBack();
            $response = array(
                'type' => '0',
                'message' => "ERROR ".$e
            );
            echo json_encode($response);
            exit;
        }
        DB::commit();
 
        $response = array(
             'type' => 1,
             'message' => 'PROCESO EXITOSO'
        );
        $rec->headers->set('Accept', 'application/json');
        echo json_encode($response);
        exit;
    }
    
     //Crear conscutivo en Base de Datos
    public function cwrCreateProduct($rec)
    {
        DB::beginTransaction();
        try {
                $db_name = $this->db.".productos";
                $crearProducto = new ModelGlobal();
                $crearProducto->setConnection($this->cur_connect);
                $crearProducto->setTable($db_name);
 
                $crearProducto->idinterno = $rec->idinterno;
                $crearProducto->codigosiigo = $rec->codigosiigo;
                $crearProducto->codigoproveedor = $rec->codigoproveedor;
                $crearProducto->condicionproducto = $rec->condicionproducto;
                $crearProducto->sexo = $rec->sexo;
                $crearProducto->tipodeproducto = $rec->tipodeproducto;
                $crearProducto->categoriauno = $rec->categoriauno;
                $crearProducto->categoriados = $rec->categoriados;
                $crearProducto->categoriatres = $rec->categoriatres;
                $crearProducto->categoriacuatro = $rec->categoriacuatro;
                $crearProducto->fechaingreso = $rec->fechaingreso;
                $crearProducto->fechamodificacion = $rec->fechamodificacion;
                $crearProducto->estado = $rec->estado;
                $crearProducto->empresa = $rec->empresa;
 
                $crearProducto->save();
 
        } catch (\Exception $e){
 
            DB::rollBack();
            $response = array(
                'type' => '0',
                'message' => "ERROR ".$e
            );
            $rec->headers->set('Accept', 'application/json');
            echo json_encode($response);
            exit;
        }
        DB::commit();
        $response = array(
            'type' => 1,
            'message' => 'REGISTRO EXITOSO',
        );
        $rec->headers->set('Accept', 'application/json');
        echo json_encode($response);
        exit;
    }

    // Lee la condición del producto
    public function cwrTiposProducto($rec)
    {
        $db_name = "cyclewear_sys";
    
        $listTiposProductos = DB::connection($this->cur_connect)->select("select t0.id as value, t0.nombretipoproducto as label, t0.* ,
                                                        t1.nombreestado
                                                        from ".$db_name.'.tipodeproducto'." t0
                                                        JOIN ".$db_name.'.estados'." t1 ON t0.estado = t1.id
                                                        WHERE t0.estado = 1 ORDER BY nombretipoproducto ASC");
    
        //$condicionprod = array();
    
        //$datoc = [
        //           'header_supplies' => $condicionproducto
        //            ];
        //         $condicionprod[] = $datoc;
    
        echo json_encode($listTiposProductos);
    }

    //Crear usuario en Base de Datos
    public function cwrCrearProductoDB($rec)
    {
        //echo json_encode($rec->idinterno);
        //exit;
        DB::beginTransaction();
        try {
                $db_name = $this->db.".productos";
                $crearProducto = new ModelGlobal();
                $crearProducto->setConnection($this->cur_connect);
                $crearProducto->setTable($db_name);
 
                $crearProducto->idinterno = $rec->idinterno;
                $crearProducto->codigosiigo = $rec->codigosiigo;
                $crearProducto->codigoproveedor = $rec->codigoproveedor;
                $crearProducto->condicionproducto = $rec->condicionproducto;
                $crearProducto->sexo = $rec->sexo;
                $crearProducto->tipodeproducto = $rec->tipodeproducto;
                $crearProducto->categoriauno = $rec->categoriauno;
                $crearProducto->categoriados = $rec->categoriados;
                $crearProducto->categoriatres = $rec->categoriatres;
                $crearProducto->categoriacuatro = $rec->categoriacuatro;
                $crearProducto->fechaingreso = $rec->fechaingreso;
                $crearProducto->fechamodificacion = $rec->fechamodificacion;
                $crearProducto->estado = $rec->estado;
                $crearProducto->empresa = $rec->empresa;
 
                $crearProducto->save();
 
        } catch (\Exception $e){
 
            DB::rollBack();
            $response = array(
                'type' => '0',
                'message' => "ERROR ".$e
            );
            $rec->headers->set('Accept', 'application/json');
            echo json_encode($response);
            exit;
        }
        DB::commit();
        $response = array(
            'type' => 1,
            'message' => 'REGISTRO EXITOSO',
        );
        $rec->headers->set('Accept', 'application/json');
        echo json_encode($response);
        exit;
    }

    // Lee productos creados en la DB Local
    public function cwrListarProductoDB($rec)
    {
        //echo json_encode($rec->idinterno);
        //exit;
        $db_name = "cyclewear_sys";
          
        $listaproductos = DB::connection($this->cur_connect)->select(
                                               "select t0.idproductos as value, t0.descripcion as label, t0.*,
                                               t1.nombretipoproducto, t2.nombrecategoriauno, t3.nombrecategoriados,
                                               t4.nombrecategoriatres, t5.nombrecategoriacuatro
                                               from ".$db_name.'.productos'." t0
                                               JOIN ".$db_name.'.tipodeproducto'." t1 ON t0.tipodeproducto = t1.id
                                               JOIN ".$db_name.'.categoriauno'." t2 ON t0.categoriauno = t2.id  
                                               left join ".$db_name.'.categoriados'." t3 ON t0.categoriados = t3.id
                                               left join ".$db_name.'.categoriatres'." t4 ON t0.categoriatres = t4.id
                                               left join ".$db_name.'.categoriacuatro'." t5 ON t0.categoriacuatro = t5.id
                                               WHERE t0.estado = 1 ORDER BY idinterno DESC");

        echo json_encode($listaproductos);
    }

    // Lee un producto por el codigo en la DB Local
    public function cwrLeeUnProductoDB($rec)
    {
        //echo json_encode($rec->idinterno);
        //exit;
        $db_name = "cyclewear_sys";
            
        $leeproducto = DB::connection($this->cur_connect)->select(
                                            "select t0.idproductos as value, t0.descripcion as label, t0.*,
                                               t1.nombretipoproducto, t2.nombrecategoriauno, t3.nombrecategoriados,
                                               t4.nombrecategoriatres, t5.nombrecategoriacuatro
                                               from ".$db_name.'.productos'." t0
                                               JOIN ".$db_name.'.tipodeproducto'." t1 ON t0.tipodeproducto = t1.id
                                               JOIN ".$db_name.'.categoriauno'." t2 ON t0.categoriauno = t2.id  
                                               left join ".$db_name.'.categoriados'." t3 ON t0.categoriados = t3.id
                                               left join ".$db_name.'.categoriatres'." t4 ON t0.categoriatres = t4.id
                                               left join ".$db_name.'.categoriacuatro'." t5 ON t0.categoriacuatro = t5.id
                                               WHERE idinterno = '". $rec->idinterno."'"); 
         
        echo json_encode($leeproducto);
    }

    // Lee las variantes de los productos creados en la BD Local
    public function cwrListarVariantesProducto($rec)
    {
        //echo json_encode($rec->idinterno);
        //exit;
        $db_name = "cyclewear_sys";
      
        $variantesproducto = DB::connection($this->cur_connect)->select(
                                              "select t0.id as value, t0.idinterno as label, t0.*,
                                              t1.nombretipoproducto, t2.nombrecategoriauno, t3.nombrecategoriados,
                                              t4.nombrecategoriatres, t5.nombrecategoriacuatro
                                              from ".$db_name.'.variantesproductos'." t0 
                                              JOIN ".$db_name.'.productos'." t6 ON t0.idinterno = t6.idinterno
                                              JOIN ".$db_name.'.tipodeproducto'." t1 ON t6.tipodeproducto = t1.id
                                              JOIN ".$db_name.'.categoriauno'." t2 ON t6.categoriauno = t2.id  
                                              left join ".$db_name.'.categoriados'." t3 ON t6.categoriados = t3.id
                                              left join ".$db_name.'.categoriatres'." t4 ON t6.categoriatres = t4.id
                                              left join ".$db_name.'.categoriacuatro'." t5 ON t6.categoriacuatro = t5.id
                                              WHERE t0.estado = 1"); 
  
        echo json_encode($variantesproducto);
    }

    // Lee las variantes de los productos creados en la BD Local
    public function cwrListarUnaVarianteProducto($rec)
    {
        //echo json_encode($rec->idinterno);
        //exit;
        $db_name = "cyclewear_sys";
          
        $variantesproducto = DB::connection($this->cur_connect)->select(
                                              "select t0.id as value, t0.idinterno as label, t0.*,
                                              t1.nombretipoproducto, t2.nombrecategoriauno, t3.nombrecategoriados,
                                              t4.nombrecategoriatres, t5.nombrecategoriacuatro
                                              from ".$db_name.'.variantesproductos'." t0 
                                              JOIN ".$db_name.'.productos'." t6 ON t0.idinterno = t6.idinterno
                                              JOIN ".$db_name.'.tipodeproducto'." t1 ON t6.tipodeproducto = t1.id
                                              JOIN ".$db_name.'.categoriauno'." t2 ON t6.categoriauno = t2.id  
                                              left join ".$db_name.'.categoriados'." t3 ON t6.categoriados = t3.id
                                              left join ".$db_name.'.categoriatres'." t4 ON t6.categoriatres = t4.id
                                              left join ".$db_name.'.categoriacuatro'." t5 ON t6.categoriacuatro = t5.id 
                                              WHERE t0.estado = 1 && t0.idinterno = '". $rec->idinterno."'"); 
      
        echo json_encode($variantesproducto);
    }
    

    //Crear usuario en Base de Datos
    public function cwrCrearVarianteProductoDB($rec)
    {
        //echo json_encode($rec->idvariante);
        //exit;
        DB::beginTransaction();
        try {
                $db_name = $this->db.".variantesproductos";
                $crearVarianteProducto = new ModelGlobal();
                $crearVarianteProducto->setConnection($this->cur_connect);
                $crearVarianteProducto->setTable($db_name);
  
                $crearVarianteProducto->idvariante = $rec->idvariante;
                $crearVarianteProducto->idinterno = $rec->idinterno;
                $crearVarianteProducto->nombrevarianteuno = $rec->nombrevarianteuno;
                $crearVarianteProducto->nombrevariantedos = $rec->nombrevariantedos;
                $crearVarianteProducto->nombrevariantetres = $rec->nombrevariantetres;
                $crearVarianteProducto->nombrevariantecuatro = $rec->nombrevariantecuatro;
                $crearVarianteProducto->nombrevariantecinco = $rec->nombrevariantecinco;
                $crearVarianteProducto->preciobasevariante = $rec->preciobasevariante;
                $crearVarianteProducto->precioventavariante = $rec->precioventavariante;
                $crearVarianteProducto->cantidadvariante = $rec->cantidadvariante;
                $crearVarianteProducto->codigobarravariante = $rec->codigobarravariante;
                $crearVarianteProducto->skuvariante = $rec->skuvariante;
                $crearVarianteProducto->taxcodevariante = $rec->taxcodevariante;
                $crearVarianteProducto->fechaingreso = $rec->fechaingreso;
                $crearVarianteProducto->fechamodificacion = $rec->fechamodificacion;
                $crearVarianteProducto->estado = $rec->estado;
  
                $crearVarianteProducto->save();
  
        } catch (\Exception $e){
  
             DB::rollBack();
             $response = array(
                 'type' => '0',
                 'message' => "ERROR ".$e
             );
             $rec->headers->set('Accept', 'application/json');
             echo json_encode($response);
             exit;
         }
         DB::commit();
         $response = array(
             'type' => 1,
             'message' => 'REGISTRO EXITOSO',
         );
         $rec->headers->set('Accept', 'application/json');
         echo json_encode($response);
         exit;
    }

    // Lee clientes en la BD local
    public function cwrLeerInterlocutor($rec)
    {
        $db_name = "cyclewear_sys";
    
        $listarterceros = DB::connection($this->cur_connect)->select("select t0.*, t0.id as value, t0.razonsocial as label,
                                                                        concat(t0.nombres, ' ', t0.apellidos) as labeldos 
                                                                        from ".$db_name.'.interlocutores'." t0
                                                                        WHERE t0.tipotercero = '". $rec->tipotercero."'"); 
    
        echo json_encode($listarterceros);
    }

    //Crear Interlocutor en Base de Datos Local
    public function cwrCrearInterlocutor($rec)
    {
         DB::beginTransaction();
         try {
                     $db_name = $this->db.".interlocutores";
                     $nuevoInterlocutor = new ModelGlobal();
                     $nuevoInterlocutor->setConnection($this->cur_connect);
                     $nuevoInterlocutor->setTable($db_name);
 
                     $nuevoInterlocutor->tipotercero = $rec->tipotercero;
                     $nuevoInterlocutor->tipopersona = $rec->tipopersona;
                     $nuevoInterlocutor->tipoidentificacion = $rec->tipoidentificacion;
                     $nuevoInterlocutor->identificacion = $rec->identificacion;
                     $nuevoInterlocutor->digitodeverificacion = $rec->digitodeverificacion;
                     $nuevoInterlocutor->razonsocial = $rec->razonsocial;
                     $nuevoInterlocutor->nombres = $rec->nombres;
                     $nuevoInterlocutor->apellidos = $rec->apellidos;
                     $nuevoInterlocutor->nombrecomercial = $rec->nombrecomercial;
                     $nuevoInterlocutor->sucursal = $rec->sucursal;
                     $nuevoInterlocutor->estado = $rec->estado;
                     $nuevoInterlocutor->ciudad = $rec->ciudad;
                     $nuevoInterlocutor->direccion = $rec->direccion;
                     $nuevoInterlocutor->indicativo = $rec->indicativo;
                     $nuevoInterlocutor->telefono = $rec->telefono;
                     $nuevoInterlocutor->extension = $rec->extension;
                     $nuevoInterlocutor->nombrescontacto = $rec->nombrescontacto;
                     $nuevoInterlocutor->apellidoscontacto = $rec->apellidoscontacto;
                     $nuevoInterlocutor->correocontacto = $rec->correocontacto;
                     $nuevoInterlocutor->tipoderegimen = $rec->tipoderegimen;
                     $nuevoInterlocutor->codigoresponsabilidadfiscal = $rec->codigoresponsabilidadfiscal;
                     $nuevoInterlocutor->indicativofacturacion = $rec->indicativofacturacion;
                     $nuevoInterlocutor->telefonofacturacion = $rec->telefonofacturacion;
                     $nuevoInterlocutor->codigopostalfacturacion = $rec->codigopostalfacturacion;
                     $nuevoInterlocutor->usuarioasignado = $rec->usuarioasignado;
                     $nuevoInterlocutor->observacion = $rec->observacion;
                     $nuevoInterlocutor->fechacreacion = $rec->fechacreacion;
                     $nuevoInterlocutor->fechamodificacion = $rec->fechamodificacion;
          
                     $nuevoInterlocutor->save();
 
         } catch (\Exception $e){
 
             DB::rollBack();
             $response = array(
                 'type' => '0',
                 'message' => "ERROR ".$e
             );
             $rec->headers->set('Accept', 'application/json');
             echo json_encode($response);
             exit;
         }
         DB::commit();
         $response = array(
             'type' => 1,
             'message' => 'REGISTRO EXITOSO',
         );
         $rec->headers->set('Accept', 'application/json');
         echo json_encode($response);
         exit;
    }

     //Crear Interlocutor en Base de Datos Local tomados de BikeExchange
    public function cwrCrearInterlocutorBE($rec)
    {
         DB::beginTransaction();
         try {
                     $db_name = $this->db.".interlocutores";
                     $nuevoInterlocutor = new ModelGlobal();
                     $nuevoInterlocutor->setConnection($this->cur_connect);
                     $nuevoInterlocutor->setTable($db_name);

                     $nuevoInterlocutor->tipotercero = $rec->tipotercero;
                     $nuevoInterlocutor->tipopersona = $rec->tipotercero;
                     $nuevoInterlocutor->tipoidentificacion = $rec->id_type;
                     $nuevoInterlocutor->identificacion = $rec->identification;
                     $nuevoInterlocutor->digitodeverificacion = $rec->check_digit;
                     $nuevoInterlocutor->razonsocial = $rec->commercial_name;
                     $nuevoInterlocutor->nombres = $rec->nombre;
                     $nuevoInterlocutor->apellidos = $rec->apellido;
                     $nuevoInterlocutor->nombrecomercial = $rec->commercial_name;
                     $nuevoInterlocutor->sucursal = $rec->sucursal;
                     $nuevoInterlocutor->estado = $rec->estado;
                     $nuevoInterlocutor->ciudad = $rec->ciudad;
                     $nuevoInterlocutor->direccion = $rec->address;
                     $nuevoInterlocutor->indicativo = $rec->indicative;
                     $nuevoInterlocutor->telefono = $rec->number;
                     $nuevoInterlocutor->extension = $rec->extension;
                     $nuevoInterlocutor->nombrescontacto = $rec->nombre;
                     $nuevoInterlocutor->apellidoscontacto = $rec->apellido;
                     $nuevoInterlocutor->correocontacto = $rec->email;
                     $nuevoInterlocutor->tipoderegimen = $rec->tipoderegimen;
                     $nuevoInterlocutor->codigoresponsabilidadfiscal = $rec->code;
                     $nuevoInterlocutor->indicativofacturacion = $rec->indicativofacturacion;
                     $nuevoInterlocutor->telefonofacturacion = $rec->number;
                     $nuevoInterlocutor->codigopostalfacturacion = $rec->postal_code;
                     $nuevoInterlocutor->usuarioasignado = $rec->usuarioasignado;
                     $nuevoInterlocutor->observacion = $rec->comments;
                     $nuevoInterlocutor->fechacreacion = $rec->fecha;
                     $nuevoInterlocutor->fechamodificacion = $rec->fecha;
          
                     $nuevoInterlocutor->save();
 
         } catch (\Exception $e){
 
             DB::rollBack();
             $response = array(
                 'type' => '0',
                 'message' => "ERROR ".$e
             );
             $rec->headers->set('Accept', 'application/json');
             echo json_encode($response);
             exit;
         }
         DB::commit();
         $response = array(
             'type' => 1,
             'message' => 'REGISTRO EXITOSO',
         );
         $rec->headers->set('Accept', 'application/json');
         echo json_encode($response);
         exit;
    }
    
    public function listaProductos($rec)
    {
        //created_start=2021-02-17
        $url = $this->url_siigo_api."products?created_start".$rec->fecha;
        $response = FunctionsCustoms::SiigoGet($url,$this->db);
        $data = json_decode($response, true); // El objeto llega con String por eso no podias entrarle como un arreglo, con esta funcion la conviertes en un objecto accesible, y le agregas el segundo paramtro true para que seaun array manejable
        //var_dump($data); // luego de convertilo en un array porque te lo devolvia como un string puedes explorar sus llaves y sus array y objetos con esta funcion y asi puedes ver hasta donde quieres llegar en este caso a el result pero el result tiene 17 array y tiene sque decirle cual array quieres acceder
        //var_dump($data["results"][0]["code"]); // aqui acceso a el objecto results y luego al el array 0 y luego dentro de ese array cero es que encuentro en code
        //var_dump($data);
        //echo $data["results"][1]["code"];
        //echo $data["results"][11]["code"];
        $listaitems = array();
        foreach($data["results"] as $items){
            $itemunico = array("code:"=>$items["code"],
                               "id:"=>$items["id"],
                                "name:"=>$items["name"],
                                "namedos:"=>$items["reference"],
                                "adicional:"=>
                                    @$items["additional_fields"][0]["barcode"] ?
                                    $items["additional_fields"][0]["barcode"]
                                    :
                                    0,
                               "nombre:"=>
                                    @$items["prices"][0]["price_list"][0]["name"] ?
                                    $items["prices"][0]["price_list"][0]["name"]
                                    :
                                    0,
                                "valor:"=>
                                    @$items["prices"][0]["price_list"][0]["value"] ?
                                    $items["prices"][0]["price_list"][0]["value"]
                                    :
                                    0   
        );
            //    var_dump($items["prices"][0]["price_list"][0]["value"]); 
            $listaitems[] = $itemunico;
        };
        $rec->headers->set('Accept', 'application/json');
        echo json_encode($listaitems);
        //exit;
        //$rec->headers->set('Accept', 'application/json');
        //echo $response;
    }

    public function cwrTiposCliente($rec)
    {
        $db_name = "cyclewear_sys";

        $tiposcliente = DB::connection($this->cur_connect)->select("select t0.* from ".$db_name.'.tipocliente'." t0 WHERE t0.estado = 1 ORDER BY tipocliente ASC");

        $tiposcli = array();

        $datoc = [
                    'header_supplies' => $tiposcliente
                ];
                $tiposcli[] = $datoc;

        echo json_encode($tiposcli);
    }

    public function cwrListarProveedores($rec)
    {
        $db_name = "cyclewear_sys";

        $listProveedores = DB::connection($this->cur_connect)->select("select t0.id as value, t0.razonsocial as label, t0.* 
                            from ".$db_name.'.interlocutores'." t0
                            WHERE t0.estado = 1 ORDER BY tipotercero ASC");

        //$listprov = array();

        $datoc = [
                    //'header_supplies' => $listProveedores
                    $listProveedores
                ];
                //$listprov[] = $datoc;

        echo json_encode($listProveedores);
    }

    public function cwrTiposInterlocutores($rec)
    {
        $db_name = "cyclewear_sys";

        $tiposinterlocutores = DB::connection($this->cur_connect)->select("select t0.id as value, 
                                                                t0.nombretipotercero as label, t0.* 
                                                                from ".$db_name.'.tipotercero'." t0
                                                                WHERE t0.estado = 1 ORDER BY nombretipotercero ASC");

        //$listprov = array();

        $datoc = [
                    //'header_supplies' => $listProveedores
                    $tiposinterlocutores
                ];
                //$listprov[] = $datoc;

        echo json_encode($tiposinterlocutores);
    }

    public function cwrListarSexo($rec)
    {
        $db_name = "cyclewear_sys";

        $listSexo = DB::connection($this->cur_connect)->select("select t0.id as value, t0.nombresexo as label, t0.* 
                            from ".$db_name.'.sexo'." t0
                            WHERE t0.estado = 1 ORDER BY nombresexo ASC");

        //$listprov = array();

        $datoc = [
                    //'header_supplies' => $listProveedores
                    $listSexo
                ];
                //$listprov[] = $datoc;

        echo json_encode($listSexo);
    }

    public function cwrTipoIdentificacion($rec)
    {
        $db_name = "cyclewear_sys";

        $tiposidentificacion = DB::connection($this->cur_connect)->select("select t0.* 
                                                                   from ".$db_name.'.tipoidentificacion'." t0 
                                                                   WHERE t0.estado = 1 ORDER BY tipoidentificacion ASC");

        $tiposidentifi = array();

        $datoc = [
                    'header_supplies' => $tiposidentificacion
                ];
                $tiposidentifi[] = $datoc;

        echo json_encode($tiposidentifi);
    }

    public function crearProducto($rec)
    {
        $url = $this->url_siigo_api."products";
        $taxes_p = array();
        $priceslist_p = array();
        $prices_p = array();

        // Impuestos array
        $taxesa = array('id' => 13156);
        $taxes_p[] = $taxesa;

        // PriceList Array
        $priceslist_p[] = array('position' => 1, 'value' => $rec->precio1);
        $priceslist_p[] = array('position' => 2, 'value' => $rec->precio2);

        // Prices Array
        $pricesa = array('currency_code' => 'COP', 'price_list' => $priceslist_p);
        $prices_p[] = $pricesa;

        $array_post = array(
            "code" => $rec->code,
            "name" => $rec->name,
            "account_group" => 1253,
            "type" => "Product",
            "stock_control" => false,
            "active" => true,
            "tax_classification" => "Taxed",
            "tax_included" => false,
            "tax_consumption_value" => 0,
            "taxes" => $taxes_p,
            "prices" => $prices_p,
            "unit" => "15",
            "unit_label" => "unidad",
            "reference" => $rec->sku,
            "description" => $rec->description,
            "additional_fields" => array(
              "barcode" => $rec->barcode,
              "brand" => $rec->marca,
              "tariff" => $rec->tarifa,
              "model" => $rec->model
            )
          );
        $response = FunctionsCustoms::SiigoPost($url,$this->db,$array_post);
        $rec->headers->set('Accept', 'application/json');
        echo $response;
    }

    public function listarInterlocutores($rec)
    {
        $startdate =  $rec->fecha;
        //url = $this->url_siigo_api."customers?identification=".$rec->identification;
        //"https://api.siigo.com/v1/customers?created_start=2021-01-01&page=2&page_size=25"
        
        $url = $this->url_siigo_api."customers?created_start=".$startdate."&page=1&page_size=100";
        $response = FunctionsCustoms::SiigoGet($url,$this->db);
        $rec->headers->set('Accept', 'application/json');
        echo $response;
    }

     // Lee un cliente en la BD Local
     public function cwrleerUnCliente($rec)
     {
         $db_name = "cyclewear_sys";
     
         $consecutivoproducto = DB::connection($this->cur_connect)->select(
                                               "select t0.id as value, t0.razonsocial as label, t0.*
                                                from ".$db_name.'.interlocutores'." t0
                                                WHERE identificacion = ".$rec->identificacion." 
                                                   && tipotercero = '". $rec->tipotercero."'"); 
 
     echo json_encode($consecutivoproducto);
     }

    public function crearCliente($rec)
    {
        $url = $this->url_siigo_api."customers";
        $taxes_p = array();
        $priceslist_p = array();
        $prices_p = array();

        // Impuestos array
        //$taxesa = array('id' => 13156);
        //$taxes_p[] = $taxesa;

        // PriceList Array
        //$priceslist_p[] = array('position' => 1, 'value' => $rec->precio1);
        //$priceslist_p[] = array('position' => 2, 'value' => $rec->precio2);

        // Prices Array
        //$pricesa = array('currency_code' => 'COP', 'price_list' => $priceslist_p);
        //$prices_p[] = $pricesa;

        // "person_type" => '"'.$rec->person_type.'"',
         
        $array_post = array(
            "type" => $rec->type,
            "person_type" => $rec->person_type,
            "id_type" => $rec->id_type,
            "identification" => $rec->identification,
            "check_digit" => $rec->check_digit,
            "name" => [$rec->nombre, $rec->apellido],
            "commercial_name" => $rec->commercial_name,
            "branch_office" => 0,
            "active" => $rec->active,
            "vat_responsible" => $rec->vat_responsible,
            "fiscal_responsibilities" =>  array([
                "code" => $rec->code               
            ]),
            "address" => array(
                "address" => $rec->address,
                "city" => array(
                "country_code" => $rec->country_code,
                "state_code" => $rec->state_code,
                "city_code" => $rec->city_code
                ),
            "postal_code" => $rec->postal_code),
            "phones" => array([
                "indicative" => $rec->indicative,
                "number" => $rec->number,
                "extension" => $rec->extension
            ]),
            "contacts" => array([
                "first_name" => $rec->first_name,
                "last_name" => $rec->last_name,
                "email" => 'wcastro@gmail.com',
                "phone" => array(
                "indicative" => $rec->indicative,
                "number" => $rec->number,
                "extension" => $rec->extension
                )
            ]),
            "comments" => "Comentarios",
            "related_users" => array(
            "seller_id" => 629,
            "collector_id" => 629
            )
        );

        $response = FunctionsCustoms::SiigoPost($url,$this->db,$array_post);
        $rec->headers->set('Accept', 'application/json');

        $resp_crear = json_decode($response);

        if(isset($resp_crear->id)){
            $array_Resp = array("status" => 200, "id" => $resp_crear->id);
            $response = array(
                'type' => 1,
                'message' => 'REGISTRO EXITOSO',
                'id' => $resp_crear->id,
                'status' => 200,
            );
        }else{
            $array_Resp = array("status" => $resp_crear->Status, "id" => 0);
            $response = array(
                'type' => 0,
                'message' => 'ERROR EN REGISTRO',
                'id' => 0,
                'status' => 0,

            );
        }
        //cho json_encode($array_Resp);
        echo json_encode($response);
        //exit;
    }
}
