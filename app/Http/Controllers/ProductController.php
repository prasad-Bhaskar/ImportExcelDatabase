<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Variation;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\CategoryVariation;
use App\Models\Product;
use App\Models\ProductVariation;
use Carbon\Carbon;
use GuzzleHttp\Handler\Proxy;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class ProductController extends Controller
{
    public function uploadProduct (Request $request){
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
            $file->move(public_path().'/uploads/products', $newFileName );
            $reader =    new Xlsx();
            $path = public_path().'/uploads/products/'.$newFileName;
            $spreadsheets  = $reader->load($path, 0);
            $sheets = $spreadsheets->getActiveSheet();
            // dd($sheets);

            $worksheetInfo = $reader->listWorksheetInfo($path);

            $totelRows = $worksheetInfo[0]['totalRows'];
            
            for ($i=2; $i <= $totelRows; $i++) { 
                
                $sn = $sheets->getCell("A{$i}")->getValue();
                // dd($sn);
                $productName = $sheets->getCell("B{$i}")->getValue();
                $price = $sheets->getCell("C{$i}")->getValue();
                $min_price = $sheets->getCell("D{$i}")->getValue();
                $quantity = $sheets->getCell("E{$i}")->getValue();
                $min_quantity = $sheets->getCell("F{$i}")->getValue();
                $is_notify = $sheets->getCell("G{$i}")->getValue();
                $brandName = $sheets->getCell("H{$i}")->getValue();
                $unitOfMeasureName = $sheets->getCell("I{$i}")->getValue();
                $categoryName = $sheets->getCell("J{$i}")->getValue();
                $ampRating = $sheets->getCell("K{$i}")->getValue();
                $color = $sheets->getCell("L{$i}")->getValue();
                $holeSize = $sheets->getCell("M{$i}")->getValue();
                $model = $sheets->getCell("N{$i}")->getValue();
                $pole = $sheets->getCell("O{$i}")->getValue();
                $shape = $sheets->getCell("P{$i}")->getValue();
                $size = $sheets->getCell("Q{$i}")->getValue();
                $type = $sheets->getCell("R{$i}")->getValue();
                $watt = $sheets->getCell("S{$i}")->getValue();
                $categoryId = Category::where('title', $categoryName)
                ->value('id');
                $ampRatingVariationId = Variation::where('title', 'amp_rating')->value('id');
                $colorVariationId = Variation::where('title', 'color')->value('id');
                $holeSizeVariationId = Variation::where('title', 'hole_size')->value('id');
                $modelVariationId = Variation::where('title', 'model')->value('id');
                $poleVariationId = Variation::where('title', 'pole')->value('id');
                $shapeVariationId = Variation::where('title', 'shape')->value('id');
                $sizeVariationId = Variation::where('title', 'size')->value('id');
                $typeVariationId = Variation::where('title', 'type')->value('id');
                $wattVariationId = Variation::where('title', 'watt')->value('id');

                $isAmpRatingEnable =   (CategoryVariation::where(['category_id' => $categoryId, 'variation_id' => $ampRatingVariationId])->count()) ?  true : false;
                $isColorEnable = (CategoryVariation::where(['category_id' => $categoryId, 'variation_id' => $colorVariationId])->count())? true : false;              
                $isHoleSizeEnable = (CategoryVariation::where(['category_id' => $categoryId, 'variation_id' => $holeSizeVariationId])->count()) ?  true : false;
                $isModelEnable = (CategoryVariation::where(['category_id' => $categoryId, 'variation_id' => $modelVariationId])->count())? true : false;
                $isPoleEnable = (CategoryVariation::where(['category_id' => $categoryId, 'variation_id' => $poleVariationId])->count())? true : false;
                $isShapeEnable = (CategoryVariation::where(['category_id' => $categoryId, 'variation_id' => $shapeVariationId])->count())? true : false;
                $isSizeEnable = (CategoryVariation::where(['category_id' => $categoryId, 'variation_id' => $sizeVariationId])->count())? true : false;
                $isTypeEnable = (CategoryVariation::where(['category_id' => $categoryId, 'variation_id' => $typeVariationId])->count())? true : false;
                $isWattEnable = (CategoryVariation::where(['category_id' => $categoryId, 'variation_id' => $wattVariationId])->count())? true : false;               

                $brandId = Brand::where('value', $brandName)->value('id');
                if(! $brandId){
                    $brandId = Brand::create([
                        'value' => $brandName,
                        'created_at' => Carbon::now(),
                        'active' => 1
                    ])->id;
                }
                // dd($unitOfMeasureName);
                $unitOfMeasureId = DB::table('unit_measure')->where('title', $unitOfMeasureName)->value('id');
                // dd($unitOfMeasureId);

                $product = Product::create([
                    'title' =>  $productName,
                    'price' => $price,
                    'min_price' => $min_price,
                    'min_count' => $min_quantity,
                    'is_notify' => $is_notify,
                    'quantity' => $quantity,
                    'brand_id' => $brandId,
                    'category_id' => $categoryId,
                    'uom_id' => $unitOfMeasureId,
                    'created_at' => Carbon::now(),
                    'active' => 1
                ]);
                
                if($isAmpRatingEnable)
                {
                    $ampRatingValueId = DB::table('amp_rating')->where(['value' => $ampRating])->value('id');
                    if(!$ampRatingValueId){
                        DB::table('amp_rating')->insert([
                            'value' => $ampRating,
                            'created_at' => Carbon::now(),
                            'active' => 1
                        ]);
                        $ampRatingValueId = DB::getPdo()->lastInsertId();
                    }
                 
                    ProductVariation::create([
                        'variation_id' => $ampRatingVariationId,
                        'refrence_id' => $ampRatingValueId,
                        'created_at' => Carbon::now(),
                        'active' => 1
                    ]);
                }
                if($isColorEnable)
                {
                    $colorValueId =DB::table('color')->where(['value' => $color])->value('id');
                    if(! $colorValueId)
                    {
                        DB::table('color')->insert([
                            'value' => $color,
                            'created_at' => Carbon::now(),
                            'active' => 1
                        ]);
    
                        $colorValueId = DB::getPdo()->lastInsertId();
                    }
                    ProductVariation::create([
                        'product_id' => $product->id,
                        'variation_id' => $colorVariationId,
                        'refrence_id' => $colorValueId,
                        'created_at' => Carbon::now(),
                        'active' => 1
                    ]);

                }

                if($isHoleSizeEnable)
                {
                    $holeSizeValueId =DB::table('color')->where(['value' => $holeSize])->value('id');
                    if(! $holeSizeValueId)
                    {
                        DB::table('color')->insert([
                            'value' => $holeSize,
                            'created_at' => Carbon::now(),
                            'active' => 1
                        ]);
    
                        $holeSizeValueId = DB::getPdo()->lastInsertId();
                    }
                    ProductVariation::create([
                        'product_id' => $product->id,
                        'variation_id' => $holeSizeVariationId,
                        'refrence_id' => $holeSizeValueId,
                        'created_at' => Carbon::now(),
                        'active' => 1
                    ]);

                }

                if($isModelEnable)
                {
                    $modelValueId =DB::table('color')->where(['value' => $model])->value('id');
                    if(! $modelValueId)
                    {
                        DB::table('color')->insert([
                            'value' => $model,
                            'created_at' => Carbon::now(),
                            'active' => 1
                        ]);
    
                        $modelValueId = DB::getPdo()->lastInsertId();
                    }
                    ProductVariation::create([
                        'product_id' => $product->id,
                        'variation_id' => $modelVariationId,
                        'refrence_id' => $modelValueId,
                        'created_at' => Carbon::now(),
                        'active' => 1
                    ]);

                }

                if($isPoleEnable)
                {
                    $poleValueId =DB::table('color')->where(['value' => $pole])->value('id');
                    if(! $poleValueId)
                    {
                        DB::table('color')->insert([
                            'value' => $pole,
                            'created_at' => Carbon::now(),
                            'active' => 1
                        ]);
    
                        $poleValueId = DB::getPdo()->lastInsertId();
                    }
                    ProductVariation::create([
                        'product_id' => $product->id,
                        'variation_id' => $poleVariationId,
                        'refrence_id' => $poleValueId,
                        'created_at' => Carbon::now(),
                        'active' => 1
                    ]);

                }

                if($isShapeEnable)
                {
                    $shapeValueId =DB::table('color')->where(['value' => $shape])->value('id');
                    if(! $shapeValueId)
                    {
                        DB::table('color')->insert([
                            'value' => $shape,
                            'created_at' => Carbon::now(),
                            'active' => 1
                        ]);
    
                        $shapeValueId = DB::getPdo()->lastInsertId();
                    }
                    ProductVariation::create([
                        'product_id' => $product->id,
                        'variation_id' => $shapeVariationId,
                        'refrence_id' => $shapeValueId,
                        'created_at' => Carbon::now(),
                        'active' => 1
                    ]);

                }

                if($isSizeEnable)
                {
                    $sizeValueId =DB::table('color')->where(['value' => $size])->value('id');
                    if(! $sizeValueId)
                    {
                        DB::table('color')->insert([
                            'value' => $shape,
                            'created_at' => Carbon::now(),
                            'active' => 1
                        ]);
    
                        $sizeValueId = DB::getPdo()->lastInsertId();
                    }
                    ProductVariation::create([
                        'product_id' => $product->id,
                        'variation_id' => $sizeVariationId,
                        'refrence_id' => $sizeValueId,
                        'created_at' => Carbon::now(),
                        'active' => 1
                    ]);

                }

                if($isTypeEnable)
                {
                    $typeValueId =DB::table('color')->where(['value' => $type])->value('id');
                    if(! $typeValueId)
                    {
                        DB::table('color')->insert([
                            'value' => $type,
                            'created_at' => Carbon::now(),
                            'active' => 1
                        ]);
    
                        $typeValueId = DB::getPdo()->lastInsertId();
                    }
                    ProductVariation::create([
                        'product_id' => $product->id,
                        'variation_id' => $typeVariationId,
                        'refrence_id' => $typeValueId,
                        'created_at' => Carbon::now(),
                        'active' => 1
                    ]);

                }

                if($isWattEnable)
                {
                    $wattValueId =DB::table('color')->where(['value' => $watt])->value('id');
                    if(! $wattValueId)
                    {
                        DB::table('color')->insert([
                            'value' => $watt,
                            'created_at' => Carbon::now(),
                            'active' => 1
                        ]);
    
                        $wattValueId = DB::getPdo()->lastInsertId();
                    }
                    ProductVariation::create([
                        'product_id' => $product->id,
                        'variation_id' => $wattVariationId,
                        'refrence_id' => $wattValueId,
                        'created_at' => Carbon::now(),
                        'active' => 1
                    ]);

                }

                
                
                
            }

            return response()->json(['status' => 200, 'message' => 'success']);
        }
    }
}
