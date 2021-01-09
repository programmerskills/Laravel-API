<?php

namespace App\Http\Controllers\ADMIN;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
use Validator;
use DB;
use Illuminate\Support\Str;

class SubacategoryController extends Controller
{
    public $successStatus = 200;
    function addsubcategory(Request $req)
    {
        $validator=Validator::make($req->all(),[
            'subcatename'=>'required',
            'catid'=>'required',
            'image'=>'required|image|mimes:jpeg,png,jpg,gif,zip',
            ]);
            if($validator->fails())
            {
                return response()->json(['error'=>$validator->errors()],401);
            }
            else
            {
                $addData=array(
                    'catid'=>$req->input('catid'),
                    'subcatename'=>$req->input('subcatename'),
                    'subcatslug'=>Str::slug($req->input('subcatename'),'-'),
                    'created_at'=>date('Y-m-d')
                );
                if($req->hasFile('image'))
                {
                    $imagename=time().'_'.$req->image->getClientOriginalName();
                    $req->image->move(public_path('uploads/subcategory/'),$imagename);
                    $addData['image']=$imagename;
                }
                if(DB::table('subcategories')->insert($addData))
                {
                    return response()->json(['sucess'=>'Successfully added subcategory'],$this-> successStatus);
                }
                else
                {
                    return response()->json(['error'=>'Unable to Save record',401]);
                }
            }
    }

    function editsubcategory(Request $req, $id)
    {
        if($req->isMethod('post'))
        {
            $validator=Validator::make($req->all(),[
                'subcatename'=>'required',
                'catid'=>'required',
                'image'=>'image|mimes:jpeg,png,jpg,gif,zip',
                ]);
                if($validator->fails())
                {
                    return response()->json(['error'=>$validator->errors()],401);
                }
                else
                {
                    $updateData=array(
                        'catid'=>$req->input('catid'),
                        'subcatename'=>$req->input('subcatename'),
                        'subcatslug'=>Str::slug($req->input('subcatename'),'-'),
                        'updated_at'=>date('Y-m-d')
                    );
                    if($req->hasFile('image'))
                    {
                        $imagename=time().'_'.$req->image->getClientOriginalName();
                        $req->image->move(public_path('uploads/subcategory/'),$imagename);
                        $updateData['image']=$imagename;
                    }
                    if(DB::table('subcategories')->where(array('id'=>$id))->update($updateData))
                    {
                        return response()->json(['sucess'=>'Successfully updated subcategory'],$this-> successStatus);
                    }
                    else
                    {
                        return response()->json(['error'=>'Unable to update record',401]);
                    }
                } 
        }
        else
        {
            $subcategorydetail=DB::table('subcategories')->where(array('id'=>$id))->first();
            return response()->json(['subcategorydetail'=>$subcategorydetail],$this-> successStatus);
        }
    }

    function deletesubcategory($id)
    {
        if(DB::table('subcategories')->where(array('id'=>$id))->delete())
        {
            return response()->json(['success'=>'Successfully Deleted Record.'],$this-> successStatus);
        }
        else
        {
            return response()->json(['error'=>'Unable to delete record.'],401);
        }
    }

    function allsubcategory()
    {
        $subcategory=DB::table('subcategories')->orderBy('subcatename','ASC')->get();
        return response()->json(['subcategory'=>$subcategory],$this-> successStatus);
    }
}
