<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Models\Category;
use App\Models\SubCategory;
use \Cviebrock\EloquentSluggable\Services\SlugService;


class CategoriesController extends Controller
{
    public function catSubcatList(Request $request){
        $data = [
            'pageTitle'=>'Categories & sub categories management'
        ];
        return view('back.pages.admin.cats-subcats-list',$data);
    }

    public function addCategory(Request $request){
        $data = [
            'pageTitle'=>'Add category'
        ];
        return view('back.pages.admin.add-category',$data);
    }

    public function storeCategory(Request $request){
        //valitedate form
        $request->validate([
            'category_name'=>'required|min:5|unique:categories,category_name',
            'category_image'=>'required|image|mimes:png,jpg,jpeg,svg',
        ],[
            'category_name.required'=>'attribute is required',
            'category_name.min'=>'attribute must contains atleast 5 characters',
            'category_name.unique'=>'this attribute already exists',
            'category_image.required'=>'attribute is required',
            'category_image.image'=>'attribute must be an image',
            'category_image.mimes'=>'attribute must be in png,jpg,jpeg or svg format'


        ]);

        if( $request->hasFile('category_image')){
            $path = 'images/categories/';
            $file = $request->file('category_image');
            $file_name = time().'_'.$file->getClientOriginalName();

            if(!File::exists(public_path($path))){
                File::makeDirectory(public_path($path));
            }

            //upload category image
            $upload = $file->move(public_path($path),$file_name);

            if($upload){
                //simpan kategori ke database
                $category = new Category();
                $category->category_name = $request->category_name;
                $category->category_image = $file_name;
                $saved = $category->save();

                if($saved){
                    return redirect()->route('admin.manage-categories.add-category')->with('success','<b>'.ucfirst($request->category_name).'</b> category has been added');
                }else{
                    return redirect()->route('admin.manage-categories.add-category')->with('fail','something went wrong, try again');
                }
            }else{
                return redirect()->route('admin.manage-categories.add-category')->with('fail','something went wrong');
            }
        }
    }

    public function editCategory(Request $request){
        $category_id = $request->id;
        $category = Category::findOrFail($category_id);
        $data = [
            'pageTitle'=>'Edit category',
            'category'=>$category
        ];
        return view('back.pages.admin.edit-category',$data);
    }

    public function updateCategory(Request $request){
        $category_id = $request->category_id;
        $category = Category::findOrFail($category_id);

        //validate
        $request->validate([
            'category_name'=>'required|min:5|unique:categories,category_name,'.$category_id,
            'category_image'=>'nullable|image|mimes:png,jpg,jpeg,svg',
        ],[
            'category_name.required'=>':attribute is required',
            'category_name.min'=>':attribute must contains atleast 5 characters',
            'category_name.unique'=>':this attribute already exists',
            'category_image.image'=>':attribute must be an image',
            'category_image.mimes'=>':attribute must be in png,jpg,jpeg or svg format'
        ]);

        if ($request->hasFile('category_image')){
            $path = 'images/categories/';
            $file = $request->file('category_image');
            $file_name = time().'_'.$file->getClientOriginalName();
            $old_category_image = $category->category_image;

            //upload category image
            $upload = $file->move(public_path($path),$file_name);

            if($upload){
                //delete old
                if( File::exists(public_path($path.$old_category_image))){
                    File::delete(public_path($path.$old_category_image));
                }
                //update
                $category->category_name = $request->category_name;
                $category->category_image = $file_name;
                $category->category_slug = null;
                $saved = $category->save();

                if($saved){
                    return redirect()->route('admin.manage-categories.edit-category',['id'=>$category_id])->with('success','<b>'.ucfirst($request->category_name).'</b> category has been updated');
                }else{
                    return redirect()->route('admin.manage-categories.edit-category',['id'=>$category_id])->with('fail','something went wrong, try again');
                }
            }else{
                return redirect()->route('admin.manage-categories.edit-category',['id'=>$category_id])->with('fail','something went wrong');
            }
        }else{
            //update category info 
            $category->category_name = $request->category_name;
            $category->category_slug = null;
            $saved = $category->save();

            if($saved){
                return redirect()->route('admin.manage-categories.edit-category',['id'=>$category_id])->with('success','<b>'.ucfirst($request->category_name).'</b> category has been update');
            }else{
                return redirect()->route('admin.manage-categories.edit-category',['id'=>$category_id])->with('fail','error');
            }
        }
    }

    public function addSubCategory(Request $request){
        $independent_subcategories = SubCategory::where('is_child_of',0)->get();
        $categories = Category::all();
        $data = [
            'pageTitle'=>'Add Sub Category',
            'categories'=>$categories,
            'subcategories'=>$independent_subcategories
        ];

        return view('back.pages.admin.add-subcategory',$data);
    }

    public function storeSubCategory(Request $request){
        //validate the form
        $request->validate([
            'parent_category'=>'required|exists:categories,id',
            'subcategory_name'=>'required|min:5|unique:sub_categories,subcategory_name'
        ],[
            'parent_category.required'=>':atribute is required',
            'parent_category.exists'=>':attribute is not exists in category table',
            'subcategory_name.required'=>':atribute is required',
            'subcategory_name.min'=>':atribute must contains 5 characters',
            'subcategory_name.unique'=>':this atribute is already exists'
        ]);

        $subcategory = new SubCategory();
        $subcategory->category_id = $request->parent_category;
        $subcategory->subcategory_name = $request->subcategory_name;
        $subcategory->is_child_of = $request->is_child_of;
        $saved = $subcategory->save();

        if ($saved){
            return redirect()->route('admin.manage-categories.add-subcategory')->with('success','<b>'.ucfirst($request->subcategory_name).'</b> sub category has been added.');
        }else{
            return redirect()->route('admin.manage-categories.add-subcategory')->with('fail', 'something went wrong.');
        }
    }

    public function editSubCategory(Request $request){
        $subcategory_id =  $request->id;
        $subcategory = SubCategory::findOrFail($subcategory_id);
        $independent_subcategories = SubCategory::where('is_child_of',0)->get();
        $data = [
            'pageTitle' => 'Edit sub category',
            'categories' => Category::all(),
            'subcategory'=>$subcategory,
            'subcategories'=>(!empty($independent_subcategories)) ? $independent_subcategories : []
        ];
        return view('back.pages.admin.edit-subcategory',$data);
    }

    public function updateSubCategory(Request $request){
        $subcategory_id = $request->subcategory_id;
        $subcategory = SubCategory::findOrFail($subcategory_id);

        //validate
        $request->validate([
            'parent_category'=>'required|exists:categories,id',
            'subcategory_name'=>'required|min:5|unique:sub_categories,subcategory_name,'.$subcategory_id,
        ],[
            'parent_category.required'=>':atribute is required',
            'parent_category.exists'=>':atribute is not exisgts',
            'subcategory_name.required'=>'attribute is reuired',
            'subcategory_name.min'=>'attribute must contains atleast 5 characthers',
            'subcategory_name.unique'=>'attribute is already exists'

        ]);

        //check if this subcategory has children
        if( $subcategory->children->count() && $request->is_child_of !=0){
            return redirect()->route('admin.manage-categories.edit-subcategory',['id'=>$subcategory_id])->with('fail','this sub category has
            ('.$subcategory->children->count().')children. you can not change "is child of" option unless you free its children.');
        }else{
            //update category info
            $subcategory->category_id = $request->parent_category;
            $subcategory->subcategory_name = $request->subcategory_name;
            $subcategory->subcategory_slug = null;
            $subcategory->is_child_of = $request->is_child_of;
            $saved = $subcategory->save();

            if ($saved){
                return redirect()->route('admin.manage-categories.edit-subcategory',['id'=>$subcategory_id])->with('success','<b>'.ucfirst($request->subcategory_name).'</b> sub category has been updated.');
            }else{
                return redirect()->route('admin.manage-categories.edit-subcategory',['id'=>$subcategory_id])->with('fail', 'something went wrong.');
            }
        }
    }
}
