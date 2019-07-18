<?php

use Firebase\JWT\JWT;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

DEFINE('JWT_KEY', "my-secret-fucking-key");
DEFINE('REMITENT', "Sistema de Denuncia Ombudsperson");
DEFINE('LINK', "//intranet.strc.guanajuato.gob.mx/in4/index.php?r=custom_pages/view&id=52&a=4");


function getJwtToken($data, $days = 7) {
    $token = array(
        "id" => $data->id, 
        "email" => $data->email, 
        "name" => $data->name, 
        "role" => $data->role_id,
        "expires" => Carbon::now()->addDays($days)->toDateTimeString()
    );
    
    $jwt = JWT::encode($token, JWT_KEY);
    
    return $jwt;
}

function decodeJwtToken($token){
    try {
        $decode = JWT::decode($token, JWT_KEY, array('HS256'));
        return ( $decode );
    } catch (Exception $ex) {
        return null;
    }
}

function checkJwtToken($request){
    try {
        $token = trim(str_replace("Bearer", "", $request->header("authorization")));
        $decode = decodeJwtToken($token);

        if($decode && is_numeric($decode->id) && $decode->expires){
            $exp = new Carbon($decode->expires);
            if ($exp > Carbon::now()){
                return $decode;
            }
        }
        
        return false;
    } catch (Exception $ex) {
        return false;
    }
}

function insertMail($mail, $subject, $content, $name = null){
    DB::connection('intranet')->insert("
        insert into enviacorreos (Fecha_Creacion, Fecha_Programada, Remitente, Correo, Nombre, Asunto, Contenido) values(NOW(), NOW(), ?, ?, ?, ?, ?)", 
        [REMITENT, $mail, $name, $subject, $content]
    );
}


function insertIntranet($to, $msg, $task = 0, $ref = null) {
    DB::connection('intranet')->insert(
            "insert into notificaciones (sistema, enlace, para, mensaje, es_tarea, idref) values(?, ?, ?, ?, ?, ?)", 
            [REMITENT, LINK, $to, $msg, $task, $ref]
    );
}
