<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('accesstokensiigo', function () {

    $this->cur_connect = 'mysql';
    $this->db = 'mercadorepuesto_sys';
    
    $curl = curl_init();
        
    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://api.siigo.com/auth",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => "{\n    \"username\": \"".env('USERNAME_API_SIIGO_MR')."\",\n    \"access_key\": \"".env('ACCESS_KEY_SIIGO_MR')."\"\n}",
      CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
        "content-type: application/json"
      ),
    ));
    
    $response = curl_exec($curl);
    $err = curl_error($curl);
    
    curl_close($curl);

    if ($err) {
      echo "cURL Error #:" . $err;
    } else {
      $items = json_decode($response, true);
      // Actualizo access Token en BDD
      DB::connection($this->cur_connect)->update("UPDATE ".$this->db.".siigo SET access_token = '".$items["access_token"]."', updatetime = now() WHERE id = 1");
    }

})->purpose('Actualizar Token de SIIGO');


Artisan::command('accesstokensiigocwr', function () {

    $this->cur_connect = 'mysql';
    $this->db = 'cyclewear_sys';
    
    $curl = curl_init();
        
    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://api.siigo.com/auth",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => "{\n    \"username\": \"".env('USERNAME_API_SIIGO_CWR')."\",\n    \"access_key\": \"".env('ACCESS_KEY_SIIGO_CWR')."\"\n}",
      CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
        "content-type: application/json"
      ),
    ));
    
    $response = curl_exec($curl);
    $err = curl_error($curl);
    
    curl_close($curl);
    
    if ($err) {
      echo "cURL Error #:" . $err;
    } else {
      $items = json_decode($response, true);
      // Actualizo access Token en BDD
      DB::connection($this->cur_connect)->update("UPDATE ".$this->db.".siigo SET access_token = '".$items["access_token"]."', updatetime = now() WHERE id = 1");
    }

})->purpose('Actualizar Token de SIIGO CYCLEWEAR');
