<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Category;
use App\Models\Variation;
use Illuminate\Http\Request;
use App\Models\CategoryVariation;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class CategoryController extends Controller
{
    public function upload(Request $request) 
    {
       $validate =  Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx,xls'
        ]);
        if($validate->fails()){
            return response()->json(['error' => $validate->errors()]);
        }

       if($request->hasFile('file'))
       {
        
            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();
            $ext = $file->getClientOriginalExtension();
            $newFileName = time().'.'.$ext;
            $file->move(public_path().'/uploads', $newFileName );
            $reader =    new Xlsx();
            $path = public_path().'/uploads/'.$newFileName;
            $spreadsheets  = $reader->load($path, 0);
            $sheets = $spreadsheets->getActiveSheet();

            $worksheetInfo = $reader->listWorksheetInfo($path);

            $totelRows = $worksheetInfo[0]['totalRows'];
            
            for ($i=2; $i <= $totelRows; $i++) { 
                
                $title = $sheets->getCell("A{$i}")->getValue();
                $min_count = $sheets->getCell("B{$i}")->getValue();
                $is_notify = $sheets->getCell("C{$i}")->getValue();
                $amp_rating = $sheets->getCell("D{$i}")->getValue();
                $color = $sheets->getCell("E{$i}")->getValue();
                $hole_size = $sheets->getCell("F{$i}")->getValue();
                $model = $sheets->getCell("G{$i}")->getValue();
                $pole = $sheets->getCell("H{$i}")->getValue();
                $shape = $sheets->getCell("I{$i}")->getValue();
                $size = $sheets->getCell("J{$i}")->getValue();
                $type = $sheets->getCell("K{$i}")->getValue();
                $watt = $sheets->getCell("L{$i}")->getValue();

                $category = Category::create([
                    'title' =>$title,
                    'min_count' => $min_count,
                    'is_notify' => $is_notify,
                    'created_at' => Carbon::now(),
                    'active' => 1,
                ]);
                if($amp_rating == 1)
                {
                    CategoryVariation::create([
                        'category_id' => $category->id,
                        'variation_id' => Variation::where('title' , 'amp_rating')->value('id'),
                        'created_at' => Carbon::now(),
                        'active' => 1,
                    ]);
                }
                if($color == 1)
                {
                    CategoryVariation::create([
                        'category_id' => $category->id,
                        'variation_id' => Variation::where('title' , 'color')->value('id'),
                        'created_at' => Carbon::now(),
                        'active' => 1,
                    ]);
                }
                if($hole_size == 1)
                {
                    CategoryVariation::create([
                        'category_id' => $category->id,
                        'variation_id' => Variation::where('title' , 'hole_size')->value('id'),
                        'created_at' => Carbon::now(),
                        'active' => 1,
                    ]);
                }
                if($model == 1)
                {
                    CategoryVariation::create([
                        'category_id' => $category->id,
                        'variation_id' => Variation::where('title' , 'model')->value('id'),
                        'created_at' => Carbon::now(),
                        'active' => 1,
                    ]);
                }
                if($pole == 1)
                {
                    CategoryVariation::create([
                        'category_id' => $category->id,
                        'variation_id' => Variation::where('title' , 'pole')->value('id'),
                        'created_at' => Carbon::now(),
                        'active' => 1,
                    ]);
                }
                if($shape == 1)
                {
                    CategoryVariation::create([
                        'category_id' => $category->id,
                        'variation_id' => Variation::where('title' , 'shape')->value('id'),
                        'created_at' => Carbon::now(),
                        'active' => 1,
                    ]);
                }
                if($size == 1)
                {
                    CategoryVariation::create([
                        'category_id' => $category->id,
                        'variation_id' => Variation::where('title' , 'size')->value('id'),
                        'created_at' => Carbon::now(),
                        'active' => 1,
                    ]);
                }
                if($type == 1)
                {
                    CategoryVariation::create([
                        'category_id' => $category->id,
                        'variation_id' => Variation::where('title' , 'type')->value('id'),
                        'created_at' => Carbon::now(),
                        'active' => 1,
                    ]);
                }
                if($watt == 1)
                {
                    CategoryVariation::create([
                        'category_id' => $category->id,
                        'variation_id' => Variation::where('title' , 'watt')->value('id'),
                        'created_at' => Carbon::now(),
                        'active' => 1,
                    ]);
                }

                
            }
       }

        

    }
}
