<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\CaseFile;
use App\Complaint;

use Illuminate\Support\Facades\Storage;

class CaseFileController extends Controller
{
    public function getFiles($id) {
        if(auth()->user()->isAdmin()){
            $files = Complaint::findOrFail($id)->files;
        }else{
            $files = Complaint::where('created_by', auth()->user()->id)->findOrFail($id)->files;
        }
        
        return response()->json(['files' => $files]);
    }
    
    public function store(Request $request) {
        $request->validate([
            'complaint_id' => 'required', 
            'file' => 'required_if:source,LOCAL', 
            'url' => 'required_if:source,CORRES|url',
            'document' => 'required'
        ]); 
        
        $newFile = new CaseFile($request->all());
        
        $file = $request->file("file");
        
        if($file){
            if($path = $file->storePubliclyAs("documents", $file->hashName())){
                $newFile->path = $path;
            }else{
                return response()->json([
                    'message' => "Error cargando archivo"
                ], 400);
            }
            
        }else {
            
        }
        
        $newFile->save();
        
        return response()->json(['file' => $newFile]);
    }
    
    public function download($filename) {
        
        $file = CaseFile::where('path', "documents/$filename")->firstOrFail();
        
        $extension = explode(".", $file->path);
        
        $name = $file->document . "." . end($extension);

        return Storage::download($file->path, $name);
    }
    
    public function destroy($id) {
         if(auth()->user()->isAdmin()){
            $file = CaseFile::findOrFail($id);
            $file->delete();
        }
    }
}
