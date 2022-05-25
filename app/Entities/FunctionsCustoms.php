<?php

namespace App\Entities;

use Image;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Mail;
use App\Mail\EnvioTokenMRP;

use Illuminate\Support\Facades\DB;

class FunctionsCustoms extends Model
{
    //

    public static function UploadImage5($base64String,$nombre,$path_server_img,$extension)
    {

        /* Subido de Imagenes a Directorio de Carpeta Publica para App Movil en Base 64*/
        $temp = strtolower(trim($base64String));
        if ($temp != "img/imageplaceholder.png" && $temp != "img/cardplaceholder.png") {
            // Obtener el String base-64 de los datos
            $image_service_str = substr($base64String, strpos($base64String, ",")+1);
            // Decodificar ese string y devolver los datos de la imagen
            $image = base64_decode($image_service_str);

            // Crear un nombre aleatorio para la imagen
            $img_name = $nombre.'.'.$extension;
            // Usando el Storage guardar en el disco creado anteriormente y pasandole a
            // la función "put" el nombre de la imagen y los datos de la imagen como
            // segundo parametro
            //Storage::disk('brokerhood')->put($img_name, $image);

            return true;
        } else {
            return false;
        }
    }

    public static function UploadImage4($base64String,$nombre,$path_server_img)
    {

        /* Subido de Imagenes a Directorio de Carpeta Publica para App Movil en Base 64*/
        $temp = strtolower(trim($base64String));
        if ($temp != "img/imageplaceholder.png" && $temp != "img/cardplaceholder.png") {

            $data = base64_decode($base64String);
            $url = public_path() . "/files/" . $path_server_img . "/" . $nombre;
            $ifp = fopen($url, "w");
            fwrite($ifp, $data);
            fclose($ifp);

            /////////////////////////////////////
            // Resize de Imagen
            /*$image = public_path("/files/" . $path_server_img . "/" . $nombre);
            $img = Image::make($image);
            $img->resize(1024, 1024, function ($constraint) {
                $constraint->aspectRatio();
            })->save($image);*/
            //////////////
            return true;
        } else {
            return false;
        }
    }


    public static function UploadImage($base64String,$nombre,$path_server_img,$extension)
    {

        /* Subido de Imagenes a Directorio de Carpeta Publica para App Movil en Base 64*/
        $temp = strtolower(trim($base64String));
        if ($temp != "img/imageplaceholder.png" && $temp != "img/cardplaceholder.png") {

            $imagen = str_replace('data:image/png;base64,', '', $base64String);
            $imagen = str_replace('data:image/gif;base64,', '', $imagen);
            $imagen = str_replace('data:image/jpg;base64,', '', $imagen);
            $imagen = str_replace('data:image/jpeg;base64,', '', $imagen);
            $imagen = str_replace(' ', '+', $imagen);
            $data = base64_decode($imagen);
            //$data = base64_decode($base64String);
            $url = public_path() . "/files/" . $path_server_img . "/" . $nombre . '.' . $extension;
            $ifp = fopen($url, "w");
            fwrite($ifp, $data);
            fclose($ifp);

            /////////////////////////////////////
            // Resize de Imagen
            /*$image = public_path("/files/" . $path_server_img . "/" . $nombre);
            $img = Image::make($image);
            $img->resize(1024, 1024, function ($constraint) {
                $constraint->aspectRatio();
            })->save($image);*/
            //////////////
            return true;
        } else {
            return false;
        }

    }

    // Funcion para subir imagenes con la ruta absoluta
    public static function UploadImageAbs($base64String,$nombre,$path_server_img,$extension)
    {

        /* Subido de Imagenes a Directorio de Carpeta Publica para App Movil en Base 64*/
        $temp = strtolower(trim($base64String));
        if ($temp != "img/imageplaceholder.png" && $temp != "img/cardplaceholder.png") {

            $imagen = str_replace('data:image/png;base64,', '', $base64String);
            $imagen = str_replace('data:image/gif;base64,', '', $imagen);
            $imagen = str_replace('data:image/jpg;base64,', '', $imagen);
            $imagen = str_replace('data:image/jpeg;base64,', '', $imagen);
            $imagen = str_replace(' ', '+', $imagen);
            $data = base64_decode($imagen);
            //$data = base64_decode($base64String);
            $url = $path_server_img . $nombre . '.' . $extension;
            $ifp = fopen($url, "w");
            fwrite($ifp, $data);
            fclose($ifp);

            /////////////////////////////////////
            // Resize de Imagen
            /*$image = public_path("/files/" . $path_server_img . "/" . $nombre);
            $img = Image::make($image);
            $img->resize(1024, 1024, function ($constraint) {
                $constraint->aspectRatio();
            })->save($image);*/
            //////////////
            return true;
        } else {
            return false;
        }

    }

    public static function UploadExcel($nombre_tmp,$nombre,$path_server)
    {


            if ($nombre) {

                $url = public_path() . "/files/" . $path_server . "/" . $nombre;

            if(move_uploaded_file($nombre_tmp, $url)){
                $data['status'] = true;
                 return true;
            }else{
                $data['message'] = "Error on uploading image";
                return false;
            }


            }

    }

    public static function SendPush($payload){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://exp.host/--/api/v2/push/send",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => array(
                "Accept: application/json",
                "Accept-Encoding: gzip, deflate",
                "Content-Type: application/json",
                "cache-control: no-cache",
                "host: exp.host"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        return $response;
        /*if ($err) {
            return 0;
        } else {
            return 1;
        }*/
    }

    public static function PushReceipts($payload){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://exp.host/--/api/v2/push/getReceipts",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => array(
                "Accept: application/json",
                "Accept-Encoding: gzip, deflate",
                "Content-Type: application/json",
                "cache-control: no-cache",
                "host: exp.host"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        return $response;
        /*if ($err) {
            return 0;
        } else {
            return 1;
        }*/
    }

    public static function ProcesarExcelJson($data_json,$nombre,$path_server)
    {


                $url = public_path() . "/files/brokerhood/tmp/excel.json";
                $ifp = fopen($url, "w");
                fwrite($ifp, $data_json);
                fclose($ifp);


    }

    public static function MoveFilePDF($name_tmp,$path_server_tmp,$path_server_end,$name_new)
    {


            if ($name_new) {

                $url_tmp = public_path() . "/files/" . $path_server_tmp . $name_tmp;
                $url_new = public_path() . "/files/" . $path_server_end . $name_new;

                if (file_exists($url_tmp)) {

                    if(copy($url_tmp, $url_new)){

                        unlink($url_tmp);

                        $data['status'] = true;
                         return true;
                    }else{
                        $data['message'] = "Error en Copia de Archivo";
                        return false;
                    }

                }else{
                        $data['message'] = "Error Archivo No Existe";
                        return false;
                }

            }

    }

    public static function UploadPDF($file,$path_server)
    {


            if ($path_server) {


            $pdf_archivo = $file->getClientOriginalName();
            $nombre_tmp = $file->path();

            $url = public_path() . "/files/" . $path_server . $pdf_archivo;

            if(move_uploaded_file($nombre_tmp, $url)){
                $data['name'] = $pdf_archivo;
                $data['status'] = "uploading";
                 return $data;
            }else{
                $data['status'] = "aborted";
                return $data;
            }


            }


    }

    public static function ConsultarImg($url)
    {

            if( empty( $url ) ){
                return false;
            }

            $ch = curl_init( $url );

            // Establecer un tiempo de espera
            curl_setopt( $ch, CURLOPT_TIMEOUT, 5 );
            curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 5 );

            // Establecer NOBODY en true para hacer una solicitud tipo HEAD
            curl_setopt( $ch, CURLOPT_NOBODY, true );
            // Permitir seguir redireccionamientos
            curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
            // Recibir la respuesta como string, no output
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

            // Descomentar si tu servidor requiere un user-agent, referrer u otra configuración específica
            // $agent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safari/537.36';
            // curl_setopt($ch, CURLOPT_USERAGENT, $agent)

            $data = curl_exec( $ch );

            // Obtener el código de respuesta
            $httpcode = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
            //cerrar conexión
            curl_close( $ch );

            // Aceptar solo respuesta 200 (Ok), 301 (redirección permanente) o 302 (redirección temporal)
            $accepted_response = array( 200, 301, 302 );
            if( in_array( $httpcode, $accepted_response ) ) {
                return true;
            } else {
                return false;
            }

    }

    public static function EnvioCodigoTokenMRP($rec)
    {
        // Funcion Para envio de Email
        Mail::to($rec->destinatario)->send(new EnvioTokenMRP($rec));

    }

    public static function NotificarWS($numerows,$mensaje)
    {

        $data = [
            'phone' => $numerows, // Receivers phone
            'body' => $mensaje, // Message
        ];
        $json = json_encode($data); // Encode data to JSON
        // URL for request POST /message
        $url = 'https://eu120.chat-api.com/instance125141/message?token=dh9nkmfy9tq3s2j6';
        // Make a POST request
        $options = stream_context_create(['http' => [
                'method'  => 'POST',
                'header'  => 'Content-type: application/json',
                'content' => $json
            ]
        ]);
        // Send a request
        $result = file_get_contents($url, false, $options);
        echo $result;
/*
            $curl = curl_init();

            curl_setopt_array($curl, array(
            CURLOPT_URL => "https://ms.justintime.com.co/enviowsp",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "numero=".$numerows."&mensaje=".$mensaje,
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: application/x-www-form-urlencoded"
            ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
            echo "cURL Error #:" . $err;
            } else {
            echo $response;
            }*/

    }


    public static function TokenSMS($numerows,$mensaje)
    {

            $curl = curl_init();

            curl_setopt_array($curl, array(
            CURLOPT_URL => "https://co.sopranodesign.com/cgpapi/messages/sms",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "messageType=sms&destination=".$numerows."&text=".$mensaje,
            CURLOPT_HTTPHEADER => array(
                "authorization: Basic YWRtaW5pc3RyYWNpb25AbWVyY2Fkb3JlcHVlc3RvLmNvbS5jbzoxRGU5WXNqOQ==",
                "cache-control: no-cache",
                "content-type: application/x-www-form-urlencoded"
            ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
            echo "cURL Error #:" . $err;
            } else {
            echo $response;
            }

    }

    public static function UploadImageMrp($base64String,$nombre,$path_server_img)
    {

        /* Subido de Imagenes a Directorio de Carpeta Publica para App Movil en Base 64*/
        $temp = strtolower(trim($base64String));
        if ($temp != "img/imageplaceholder.png" && $temp != "img/cardplaceholder.png") {

            $imagen = str_replace('data:image/png;base64,', '', $base64String);
            $imagen = str_replace('data:image/gif;base64,', '', $imagen);
            $imagen = str_replace('data:image/jpg;base64,', '', $imagen);
            $imagen = str_replace('data:image/jpeg;base64,', '', $imagen);
            $imagen = str_replace(' ', '+', $imagen);
            $data = base64_decode($imagen);
            //$data = base64_decode($base64String);
            $url = public_path() . "/files/" . $path_server_img . "/" . $nombre;
            $ifp = fopen($url, "w");
            fwrite($ifp, $data);
            fclose($ifp);

            /////////////////////////////////////
            // Resize de Imagen
            /*$image = public_path("/files/" . $path_server_img . "/" . $nombre);
            $img = Image::make($image);
            $img->resize(1024, 1024, function ($constraint) {
                $constraint->aspectRatio();
            })->save($image);*/
            //////////////
            return true;
        } else {
            return false;
        }

    }

    public static function UploadPDFMrp($file,$path_server)
    {
        if ($path_server) {
            $pdf_archivo = $file->getClientOriginalName();
            $nombre_tmp = $file->path();

            $url = public_path() . "/files/" . $path_server . $pdf_archivo;

            if(move_uploaded_file($nombre_tmp, $url)){
                $data['name'] = $pdf_archivo;
                $data['status'] = "uploading";
                 return $data;
            }else{
                $data['status'] = "aborted";
                return $data;
            }
        }
    }

    // Funciones Curl Para Consultas de Array JSON
    public static function SiigoGet($url,$db)
    {
        $access_token = current(DB::connection('mysql')->select("select access_token from ".$db.".siigo where id = 1"));
        $token = $access_token->access_token;
        $headers = array(
            "authorization: Bearer ".$token,
            "token: ".$token,
            "cache-control: no-cache"
        );

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_ENCODING, "");
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        $err = curl_error($ch);

        curl_close($ch);

        if ($err) {
        return "cURL Error #:" . $err;
        } else {
        return $response;
        }

    }

    // Funciones Curl para Solicitudes POST hacia ML
    public static function SiigoPost($url,$db,$array_post)
    {
        $access_token = current(DB::connection('mysql')->select("select access_token from ".$db.".siigo where id = 1"));
        $token = $access_token->access_token;
        $headers = array(
            "authorization: Bearer ".$token,
            "token: ".$token,
            "cache-control: no-cache",
            "Content-Type: application/json"
        );

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_ENCODING, "");
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POST, count($array_post));
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($array_post));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        $err = curl_error($ch);

        curl_close($ch);

        if ($err) {
        return "cURL Error #:" . $err;
        } else {
        return $response;
        }

    }

    // Funciones Curl para Solicitudes PUT hacia ML
    public static function SiigoPut($url,$db,$array_post)
    {
        $access_token = current(DB::connection('mysql')->select("select access_token from ".$db.".siigo where id = 1"));
        $token = $access_token->access_token;
        $headers = array(
            "authorization: Bearer ".$token,
            "token: ".$token,
            "cache-control: no-cache",
            "Content-Type: application/json"
        );

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_ENCODING, "");
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POST, count($array_post));
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($array_post));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        $err = curl_error($ch);

        curl_close($ch);

        if ($err) {
        return "cURL Error #:" . $err;
        } else {
        return $response;
        }

    }

    // Funciones Curl para Solicitudes DELETE hacia ML
    public static function SiigoDelete($url,$db,$array_post)
    {
        $access_token = current(DB::connection('mysql')->select("select access_token from ".$db.".siigo where id = 1"));
        $token = $access_token->access_token;
        $headers = array(
            "authorization: Bearer ".$token,
            "token: ".$token,
            "cache-control: no-cache",
            "Content-Type: application/json"
        );

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_ENCODING, "");
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_POST, count($array_post));
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($array_post));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        $err = curl_error($ch);

        curl_close($ch);

        if ($err) {
        return "cURL Error #:" . $err;
        } else {
        return $response;
        }

    }
}
