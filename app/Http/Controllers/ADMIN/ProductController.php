<?php

namespace App\Http\Controllers\ADMIN;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Validator;
use DB;
use Illuminate\Support\Str;

class ProductController extends Controller
{   
    public $successStatus = 200;
    
    //add product
    function addproduct(Request $req)
    {
        $validator=Validator::make($req->all(),[
            'productname'=>'required',
            'saleprice'=>'required',
            'reg_price'=>'required',
            'catid'=>'required',
            'subcatid'=>'required',
            'short_desc'=>'required',
            'long_desc'=>'required',
            'image1'=>'required|image|mimes:jpeg,png,jpg,gif',
            'image2'=>'image|mimes:jpeg,png,jpg,gif',
            'image3'=>'image|mimes:jpeg,png,jpg,gif',
            'image4'=>'image|mimes:jpeg,png,jpg,gif',
        ]);
        if($validator->fails())
        {
            return response()->json(['error'=>$validator->errors()],401);
        }
        else
        {
            $addData=array(
                'productname'=>$req->input('productname'),
                'pslug'=>Str::slug($req->input('productname'),'-'),
                'saleprice'=>$req->input('saleprice'),
                'reg_price'=>$req->input('reg_price'),
                'catid'=>$req->input('catid'),
                'subcatid'=>$req->input('subcatid'),
                'short_desc'=>$req->input('short_desc'),
                'long_desc'=>$req->input('long_desc'),
                'created_at'=>date('Y-m-d')
            );
            if($req->hasFile('image1'))
                {
                    $imagename1=time().'_'.$req->image1->getClientOriginalName();
                    $req->image1->move(public_path('uploads/product/'),$imagename1);
                    $addData['image1']=$imagename1;
                }
                if(DB::table('products')->insert($addData))
                {
                    return response()->json(['success'=>'Successfully save record'], $this-> successStatus);
                }
                else
                {
                    return response()->json(['error'=>'Unable to save record'],401);
                }
        }
    }
    // edit product
    function editproduct(Request $req, $id)
    {
        if($req->isMethod('post'))
        {
            $validator=Validator::make($req->all(),[
                'productname'=>'required',
                'saleprice'=>'required',
                'reg_price'=>'required',
                'catid'=>'required',
                'subcatid'=>'required',
                'short_desc'=>'required',
                'long_desc'=>'required',
                'image1'=>'image|mimes:jpeg,png,jpg,gif',
                'image2'=>'image|mimes:jpeg,png,jpg,gif',
                'image3'=>'image|mimes:jpeg,png,jpg,gif',
                'image4'=>'image|mimes:jpeg,png,jpg,gif',
            ]);
            if($validator->fails())
            {
                return response()->json(['error'=>$validator->errors()],401);
            }
            else
            {
                $udateData=array(
                    'productname'=>$req->input('productname'),
                    'pslug'=>Str::slug($req->input('productname'),'-'),
                    'saleprice'=>$req->input('saleprice'),
                    'reg_price'=>$req->input('reg_price'),
                    'catid'=>$req->input('catid'),
                    'subcatid'=>$req->input('subcatid'),
                    'short_desc'=>$req->input('short_desc'),
                    'long_desc'=>$req->input('long_desc'),
                    'updated_at'=>date('Y-m-d')
                );
                if($req->hasFile('image1'))
                    {
                        $imagename1=time().'_'.$req->image1->getClientOriginalName();
                        $req->image1->move(public_path('uploads/product/'),$imagename1);
                        $udateData['image1']=$imagename1;
                    }
                    if(DB::table('products')->where(array('id'=>$id))->update($udateData))
                    {
                        return response()->json(['success'=>'Successfully updated record'], $this-> successStatus);
                    }
                    else
                    {
                        return response()->json(['error'=>'Unable to update record'],401);
                    }
            }   
        }
        else
        {
            $productdetail=DB::table('products')->where(array('id'=>$id))->first();
            return response()->json(['productdetail'=>$productdetail],$this-> successStatus);
        }
    }
    // get all product
    function allproduct()
    {
        $allproduct=DB::table('products')->orderBy('productname','ASC')->get();
        return response()->json(['allproduct'=>$allproduct],$this-> successStatus);
    }

    //delete single product
    function deleteproduct($id)
    {
        $exist=DB::table('products')->where(array('id'=>$id))->first();
        if($exist===null)
        {
            return response()->json(['error'=>'Record Not Found'],301);
        }
        else
        {
            if(DB::table('products')->where(array('id'=>$id))->delete())
            {
            return response()->json(['success'=>'Successfully deleted record.'], $this-> successStatus);
            }
            else
            {
                return response()->json(['error'=>'Unable to delete record'],401);
            }
        }
        
    }

} // End Main Class
