<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\Category;
use App\Models\Variation;
use App\Models\CategoryVariation;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CategoryImport implements ToCollection , WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return Illuminate\Support\Collection|null
    */
    public function collection(Collection $collection)
    {    
        foreach($collection as  $row)
        {
            // dd($row['title']);
          try {
            $category = Category::create([
                'title' => $row['title'],
                'min_count' => $row['min_count'],
                'is_notify' => $row['is_notify'],
                'created_at' => Carbon::now(),
                'active' => 1,
               ]);
               if($row['amp_rating'] == 1)
               {
                CategoryVariation::create([
                    'category_id' => $category->id,
                    'variation_id' => Variation::where('title' , 'amp_rating')->value('id'),
                    'created_at' => Carbon::now(),
                    'active' => 1,
                ]);
               }
               if($row['color'] == 1)
               {
                CategoryVariation::create([
                    'category_id' => $category->id,
                    'variation_id' => Variation::where('title' , 'color')->value('id'),
                    'created_at' => Carbon::now(),
                    'active' => 1,
                ]);
               }
               if($row['hole_size'] == 1)
               {
                CategoryVariation::create([
                    'category_id' => $category->id,
                    'variation_id' => Variation::where('title' , 'hole_size')->value('id'),
                    'created_at' => Carbon::now(),
                    'active' => 1,
                ]);
               }
               if($row['model'] == 1)
               {
                CategoryVariation::create([
                    'category_id' => $category->id,
                    'variation_id' => Variation::where('title' , 'model')->value('id'),
                    'created_at' => Carbon::now(),
                    'active' => 1,
                ]);
               }
               if($row['pole'] == 1)
               {
                CategoryVariation::create([
                    'category_id' => $category->id,
                    'variation_id' => Variation::where('title' , 'pole')->value('id'),
                    'created_at' => Carbon::now(),
                    'active' => 1,
                ]);
               }
               if($row['shape'] == 1)
               {
                CategoryVariation::create([
                    'category_id' => $category->id,
                    'variation_id' => Variation::where('title' , 'shape')->value('id'),
                    'created_at' => Carbon::now(),
                    'active' => 1,
                ]);
               }
               if($row['size'] == 1)
               {
                CategoryVariation::create([
                    'category_id' => $category->id,
                    'variation_id' => Variation::where('title' , 'size')->value('id'),
                    'created_at' => Carbon::now(),
                    'active' => 1,
                ]);
               }
               if($row['type'] == 1)
               {
                CategoryVariation::create([
                    'category_id' => $category->id,
                    'variation_id' => Variation::where('title' , 'type')->value('id'),
                    'created_at' => Carbon::now(),
                    'active' => 1,
                ]);
               }
               if($row['watt'] == 1)
               {
                CategoryVariation::create([
                    'category_id' => $category->id,
                    'variation_id' => Variation::where('title' , 'watt')->value('id'),
                    'created_at' => Carbon::now(),
                    'active' => 1,
                ]);
               }
               dd($collection);
               return response()->json(['message' => 'success']);
          } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()]);
          }


        }
        
        
    }
}
