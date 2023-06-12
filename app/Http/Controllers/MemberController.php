<?php

namespace App\Http\Controllers;

use App\Models\Mandal;
use App\Models\Member;
use App\Models\District;
use App\Models\Designation;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class MemberController extends Controller
{
    public function upoloadMandalUser(Request $request)
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
            $fileNameWithExtension = $file->getClientOriginalName();
            $fileName = pathinfo($fileNameWithExtension, PATHINFO_FILENAME);
            $ext = $file->getClientOriginalExtension();
            $newFileName = $fileName.'_'.time().'.'.$ext;
            $file->move(public_path().'/uploads/mandals', $newFileName );
            $reader =    new Xlsx();
            $path = public_path().'/uploads/mandals/'.$newFileName;
            $spreadsheets  = $reader->load($path, 0);
            $sheets = $spreadsheets->getActiveSheet();
            // foreach ($sheets as $key => $sheet) {
            //     dd($sheet);
            // }

            $worksheetInfo = $reader->listWorksheetInfo($path);

            $totelRows = $worksheetInfo[0]['totalRows'];

            for ($i=3; $i <=$totelRows ; $i++) { 
                $sn = $sheets->getCell("A{$i}")->getValue();
                $districtName = $sheets->getCell("B{$i}")->getValue();
                $mandalName = $sheets->getCell("C{$i}")->getValue();
                $mandalPresidentName = $sheets->getCell("D{$i}")->getValue();
                $mandalPresidentMobile = $sheets->getCell("E{$i}")->getValue();
                $mandalGenerelSecretoryName = $sheets->getCell("F{$i}")->getValue();
                $mandalGenerelSecretoryMobile = $sheets->getCell("G{$i}")->getValue();
                $mandalGenerelSecretoryName1 = $sheets->getCell("H{$i}")->getValue();
                $mandalGenerelSecretoryMobile1 = $sheets->getCell("I{$i}")->getValue();
                // dd($mandalPresidentMobile);

                $districtId = District::where('title',  $districtName)->value('id') ;
                if($districtId == null )
                {
                    return response()->json(['status' => 500, 'meaasge' => 'id dosent exists for district which name '. $districtName]);
                }
                $mandalId = Mandal::where('title', $mandalName)->value('id');
                if($mandalId == null )
                {
                    
                    $mandalId = Mandal::create([
                        'id'=> Str::uuid()->toString(),
                        'title' => $mandalName,
                        'district_id' => $districtId,
                        'created_at' => Carbon::now(),
                        'active' => 1
                    ])->id;
                }

                $presidentId = Designation::where('title', 'अध्यक्ष')->value('id');  
                $generelSecretoryId = Designation::where('title', 'महामंत्री')->value('id');

                if( $mandalPresidentMobile != null)
                {
                    $isMemberExists = ($mandalPresidentMobile != null)? Member::where(['mobile' => $mandalPresidentMobile])->count():0;

                    if( $isMemberExists == 0)
                    {
                        Member::create([
                            'id' => Str::uuid()->toString(),
                            'first_name' =>  $mandalPresidentName,
                            'mobile' => $mandalPresidentMobile,
                            'designation_id' => $presidentId,
                            'district_id' => $districtId,
                            'designation_level_id' => '5a7f3684-9ca4-11ed-a3fb-0292b5d23722',
                            'mandal_id' => $mandalId,
                            'created_at' => Carbon::now(),
                            'active' => 1

                        ]);
                    }
                }
                if ($mandalGenerelSecretoryMobile != null) {
                    $isMemberExists = ($mandalGenerelSecretoryMobile != null)? Member::where(['mobile' => $mandalGenerelSecretoryMobile])->count(): 0;
                    if( $isMemberExists == 0)
                    {
                        Member::create([
                            'id' => Str::uuid()->toString(),
                            'first_name' =>  $mandalGenerelSecretoryName,
                            'mobile' => $mandalGenerelSecretoryMobile,
                            'designation_id' => $generelSecretoryId,
                            'designation_level_id' => '5a7f3684-9ca4-11ed-a3fb-0292b5d23722',
                            'district_id' => $districtId,
                            'mandal_id' => $mandalId,
                            'created_at' => Carbon::now(),
                            'active' => 1
    
                        ]);
                    }
                }

                if( $mandalGenerelSecretoryMobile1 != null)
                {
                    $isMemberExists = ($mandalGenerelSecretoryMobile1 != null)? Member::where([ 'mobile' => $mandalGenerelSecretoryMobile1])->count(): 0;
                    if( $isMemberExists == 0)
                    {
                        Member::create([
                            'id' => Str::uuid()->toString(),
                            'first_name' =>  $mandalGenerelSecretoryName1,
                            'mobile' => $mandalGenerelSecretoryMobile1,
                            'designation_id' => $generelSecretoryId,
                            'designation_level_id' => '5a7f3684-9ca4-11ed-a3fb-0292b5d23722',
                            'district_id' => $districtId,
                            'mandal_id' => $mandalId,
                            'created_at' => Carbon::now(),
                            'active' => 1

                        ]);
                    }
                }

                
                
               
            }
            dd($sheets);
       }
    }

    public function uploadDistrictUser(Request $request)
    {
        // dd($request);
        $validate =  Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx,xls'
        ]);

        if($validate->fails()){
            return response()->json(['error' => $validate->errors()]);
        }

        if($request->hasFile('file'))
       {
            $file = $request->file('file');
            $fileNameWithExtension = $file->getClientOriginalName();
            $fileName = pathinfo($fileNameWithExtension, PATHINFO_FILENAME);
            $ext = $file->getClientOriginalExtension();
            $newFileName = $fileName.'_'.time().'.'.$ext;
            $file->move(public_path().'/uploads/mandals', $newFileName );
            $reader =    new Xlsx();
            $path = public_path().'/uploads/mandals/'.$newFileName;
            $spreadsheets  = $reader->load($path, 0);
            $sheets = $spreadsheets->getActiveSheet();
           

            $worksheetInfo = $reader->listWorksheetInfo($path);

            $totelRows = $worksheetInfo[1]['totalRows'];

            for ($i=2; $i <=$totelRows ; $i++) { 

                $sn = $sheets->getCell("A{$i}")->getValue();
                $districtName = $sheets->getCell("B{$i}")->getValue();
                $districtPresidentName = $sheets->getCell("C{$i}")->getValue();
                $districtPresidentMobile = $sheets->getCell("D{$i}")->getValue();
                $districtGenerelSecretoryName = $sheets->getCell("E{$i}")->getValue();
                $districtGenerelSecretoryMobile = $sheets->getCell("F{$i}")->getValue();
                $districtGenerelSecretoryName1 = $sheets->getCell("G{$i}")->getValue();
                $districtGenerelSecretoryMobile1 = $sheets->getCell("H{$i}")->getValue();

                $presidentId = Designation::where('title', 'अध्यक्ष')->value('id');  
                $generelSecretoryId = Designation::where('title', 'महामंत्री')->value('id'); 
                
                $districtId = District::where('title',  $districtName)->value('id') ;
                if($districtId == null )
                {
                    return response()->json(['status' => 500, 'meaasge' => 'id dosent exists for district which name '. $districtName]);
                }

                if( $districtPresidentMobile != null)
                {
                    $isMemberExists = ($districtPresidentMobile != null)? Member::where(['mobile' => $districtPresidentMobile])->count():0;

                    if( $isMemberExists == 0)
                    {
                        Member::create([
                            'id' => Str::uuid()->toString(),
                            'first_name' =>  $districtPresidentName,
                            'mobile' => $districtPresidentMobile,
                            'designation_id' => $presidentId,
                            'designation_level_id' => '5a7f34f5-9ca4-11ed-a3fb-0292b5d23722',
                            'district_id' => $districtId,
                            'created_at' => Carbon::now(),
                            'active' => 1

                        ]);
                    }
                }
                if ($districtGenerelSecretoryMobile != null) {
                    $isMemberExists = ($districtGenerelSecretoryMobile != null)? Member::where(['mobile' => $districtGenerelSecretoryMobile])->count(): 0;
                    if( $isMemberExists == 0)
                    {
                        Member::create([
                            'id' => Str::uuid()->toString(),
                            'first_name' =>  $districtGenerelSecretoryName,
                            'mobile' => $districtGenerelSecretoryMobile,
                            'designation_id' => $generelSecretoryId,
                            'designation_level_id' => '5a7f34f5-9ca4-11ed-a3fb-0292b5d23722',
                            'district_id' => $districtId,
                            'created_at' => Carbon::now(),
                            'active' => 1
    
                        ]);
                    }
                }

                if( $districtGenerelSecretoryMobile1 != null)
                {
                    $isMemberExists = ($districtGenerelSecretoryMobile1 != null)? Member::where([ 'mobile' => $districtGenerelSecretoryMobile1])->count(): 0;
                    if( $isMemberExists == 0)
                    {
                        Member::create([
                            'id' => Str::uuid()->toString(),
                            'first_name' =>  $districtGenerelSecretoryName1,
                            'mobile' => $districtGenerelSecretoryMobile1,
                            'designation_id' => $generelSecretoryId,
                            'designation_level_id' => '5a7f34f5-9ca4-11ed-a3fb-0292b5d23722',
                            'district_id' => $districtId,
                            'created_at' => Carbon::now(),
                            'active' => 1

                        ]);
                    }
                }
            }
            dd($sheets);


        }

    }


    public function uploadStateUser(Request $request)
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
            $fileNameWithExtension = $file->getClientOriginalName();
            $fileName = pathinfo($fileNameWithExtension, PATHINFO_FILENAME);
            $ext = $file->getClientOriginalExtension();
            $newFileName = $fileName.'_'.time().'.'.$ext;
            $file->move(public_path().'/uploads/mandals', $newFileName );
            $reader =    new Xlsx();
            $path = public_path().'/uploads/mandals/'.$newFileName;
            $spreadsheets  = $reader->load($path, 0);
            $sheets = $spreadsheets->getActiveSheet();
           

            $worksheetInfo = $reader->listWorksheetInfo($path);
            // dd($worksheetInfo); 

            $totelRows = $worksheetInfo[1]['totalRows'];

            for ($i=2; $i <=$totelRows ; $i++) { 

                $sn = $sheets->getCell("A{$i}")->getValue();
                // dd($sn);
                $MemberName = $sheets->getCell("B{$i}")->getValue();
                $memberDesignation = $sheets->getCell("C{$i}")->getValue();
                $memberMobile = $sheets->getCell("D{$i}")->getValue();
                $memeberEmail= $sheets->getCell("E{$i}")->getValue();
                $memberAddress = $sheets->getCell("F{$i}")->getValue();
                $districtName = $sheets->getCell("G{$i}")->getValue();

                $designation = explode( ' ', $memberDesignation);
                $districtId = null;
                $designationId = null;                
                if($districtName != null)
                {
                    $districtId = District::where('title',  $districtName)->value('id') ;
                    if($districtId == null )
                    {
                        return response()->json(['status' => 500, 'meaasge' => 'id dosent exists for {$i} district which name '. $districtName. ' sn '. $sn]);
                    }
                   
                }
               

               if(empty($designation))
               {
                $designationId = Designation::where('title', $designation[1])->value('id');

                if($designationId == null )
                {
                    return response()->json(['status' => 500, 'meaasge' => 'id dosent exists for district which name '. $designation[1]]);
                } 
                return response()->json(['status' => 500, 'meaasge' => 'id dosent exists for district which name '. $designation[1].' sn '. $sn]);
               }

                if( $memberMobile != null)
                {
                    $isMemberExists = ($memberMobile != null)? Member::where(['mobile' => $memberMobile])->count():0;

                    if( $isMemberExists == 0)
                    {
                        Member::create([
                            'id' => Str::uuid()->toString(),
                            'first_name' =>  $MemberName,
                            'mobile' => $memberMobile,
                            'designation_id' => $designationId,
                            'designation_level_id' => '5a7ea632-9ca4-11ed-a3fb-0292b5d23722',
                            'district_id' => $districtId,
                            'created_at' => Carbon::now(),
                            'active' => 1

                        ]);
                    }
                }
                
            }
        }dd($sheets);
    
    }
}
