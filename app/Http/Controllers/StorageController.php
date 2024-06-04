<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\AppWallpeper;
use Illuminate\Http\Request;
use Intervention\Image\Image;
// use Intervention\Image\ImageManagerStatic as Image;

use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;

class StorageController extends Controller
{
    function storeImageInS3(Request $request){
        
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
                $thumbnail = $filename . '_thumbnail' . time() . '.' . $extension;
                $path = Storage::disk('s3')->put('wallpapers/' . $filenametostore, file_get_contents($image), 'public');

                $url = 'https://s3.' . env('AWS_DEFAULT_REGION') . '.amazonaws.com/' . env('AWS_BUCKET') . '/wallpapers/'; 

                // $newImage = Image::make($request->file('file'))->resize(160, 160);

                $manager = new ImageManager(
                    new Driver()
                );
                // dd($image->getClientOriginalName());
                $newImage = $manager->read($image->getRealPath())->resize(500, 500);;
                // $newImage->resize(200, 150);
                // $newImage = Image::make($image)->resize();
                // $newImage = Image;
                // dd($newImage);

                Storage::disk('s3')->put('wallpapers/' . $thumbnail, (string)$newImage->encode(), 'public');
                
                $imageUrl =$url.$filenametostore;
                $thumbnailUrl = $url.$thumbnail;
                 
                AppWallpeper::create([
                    'id'=> Str::uuid(),
                    'name'=> $filename,
                    'wallpaper_path'=>$imageUrl,
                    'thumbnail'=>$thumbnailUrl,
                    'created_at'=>now()
                ]);
                 
            }
            return response()->json(['message'=>"success","status"=>true]);
        }

    }
}
