<?php
namespace App\Http\Controllers\ADMIN;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\Auth; 
use Validator;
use DB;
use Illuminate\Support\Str;
class CategoryController extends Controller
{
    public $successStatus = 200;

    // add new category
    function addcategory(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
            'category'=>'required|unique:categories,category',
        ]);
        //return $request()->all();
        if($validator->fails())
        {
            return response()->json(['verror'=>$validator->errors()],401);
        }
        $addData=array(
            'category'=>$request->input('category'),
            'slug'=>Str::slug($request->input('category'),'-'),
            'created_at'=>date('Y-m-d')
        );
        if($request->hasFile('image'))
        {
            $imagename=time().'_'.$request->image->getClientOriginalName();
            //echo $imagename;
            $request->image->move(public_path('uploads/category/'),$imagename);
            $addData['image']=$imagename;
        }
        if(DB::table('categories')->insert($addData))
        {
            return response()->json(['success'=>'Added Category Records'],$this-> successStatus);  
        }
        else
        {
            return response()->json(['error'=>'Unable to Add Category'],$this-> successStatus);
        }
    }

    // Start Edit Category
    function editcategory(Request $request, $id)
    {
        if($request->isMethod('post'))
        {
            $validator=Validator::make($request->all(),
            ['category'=>'required',]);
            if($validator->fails())
            {
                return response()->josn(['error'=>$validator->errors()],401);
            }
            else
            {
                $updateData=array(
                    'category'=>$request->input('category'),
                    'slug'=>Str::slug($request->input('category'),'-'),
                    'updated_at'=>date('Y-m-d')
                );
                if($request->hasFile('image'))
                {
                    $imagename=time().'_'.$request->image->getClientOriginalName();
                    $request->image->move(public_path('uploads/category/'),$imagename);
                    $updateData['image']=$imagename;
                }
                if(DB::table('categories')->where(array('id'=>$id))->update($updateData))
                {
                    return response()->json(['success'=>'Updated Category Records'],$this-> successStatus);  
                }
                else
                {
                    return response()->json(['error'=>'Unable to Update Category'],301);
                }
            }
        }
        else
        {
            $categorydetail=DB::table('categories')->where('id',$id)->first();
            return response()->json(['categorydetail'=>$categorydetail],$this-> successStatus);
        }       
    }
    // End Edit Category

    // Delete Category Start
    function deletecategory($id)
    {
        if(DB::table('categories')->where(array('id'=>$id))->delete())
        {
            return response()->json(['sucess'=>'Deleted Category Record.'],$this-> successStatus);
        }
        else
        {
            return response()->json(['error'=>'Unable to Delete Record'],401);
        }
    }
    // Delete Category End

    function allcategory()
    {
        $allcategory=DB::table('categories')->orderBy('category','ASC')->get();
        return response()->json(['allcategory'=>$allcategory],$this-> successStatus);
    }

}
