<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Role;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function getLogin(Request $request) {
        
        $request->validate([
            'email' => "required"
        ]); 
       
        $search = DB::connection('personal')
                ->table('estructura_operativa')
                ->where(DB::raw('md5(Correo)') , $request->email)
                ->first();
        if($search){
//            dd($search);
            $user = User::updateOrCreate(['email' => $search->Correo], [
                'email' => $search->Correo , 
                'name'  => $search->Empleado, 
                'age'  => Carbon::parse($search->Nacimiento)->age, 
                'gender' => $search->Sexo, 
                'area' => $search->Direccion, 
                'position' => $search->Denominacion, 
                'boss_name' => $search->Jefe, 
                'boss_title' => $search->PuestoJefe
            ]);

            $token = getJwtToken($user);
            $response = array(
                'hash' => $token, 
                'token' => decodeJwtToken($token) 
            );
        }else{
            
            return response()->json([
                "message" => "Usuario no encontrado"
            ], 404);
            
        }

        
        return response()->json($response);
        
    }
    
    public function test() {
        phpinfo();
    }
}
