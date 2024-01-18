<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\AppWallpeper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StorageController extends Controller
{
    function storeImageInS3(Request $request){
        // dd(phpinfo());
        if($request->has('images')){
            foreach ($request->images as $image) {

                //get filename with extension
                $filenamewithextension = $image->getClientOriginalName();
                

                //get filename without extension
                $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);

                //get file extension
                $extension = $image->getClientOriginalExtension();

                //filename to store
                $filenametostore = $filename . '_' . time() . '.' . $extension;
                $path = Storage::disk('s3')->put('wallpapers/' . $filenametostore, file_get_contents($image), 'public');
            
                $url ='https://s3.' . env('AWS_DEFAULT_REGION') . '.amazonaws.com/' . env('AWS_BUCKET') . '/wallpapers/'.$filenametostore;
                 
                AppWallpeper::create([
                    'id'=> Str::uuid(),
                    'wallpaper_path'=>$url,
                    'created_at'=>now()
                ]);
                 
            }
            return response()->json(['message'=>"success","status"=>true]);
        }

    }
}
