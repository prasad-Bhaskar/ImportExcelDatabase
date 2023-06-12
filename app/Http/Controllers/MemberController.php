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
}
