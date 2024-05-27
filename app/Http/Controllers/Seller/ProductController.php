<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Support\Facades\Redis;
use App\Models\Product;
use App\Rules\ValidatePrice;

class ProductController extends Controller
{
    public function addProduct(Request $request){
        $data = [
            'pageTitle' => 'add product',
            'categories'=> Category::orderBy('category_name','asc')->get()
        ];
        return view('back.pages.seller.add-product',$data);
    }

    public function getProductCategory(Request $request){
        $category_id = $request->category_id;
        $category = Category::findOrFail($category_id);
        $subcategories = SubCategory::where('category_id',$category_id)
                                    ->where('is_child_of',0)
                                    ->orderBy('subcategory_name','asc')
                                    ->get();
        $html = '';
        foreach($subcategories as $item){
            $html.='<option value="'.$item->id.'">'.$item->subcategory_name.'</option>';
            if(count($item->children) > 0){
                foreach($item->children as $child){
                    $html.='<option value="'.$child->id.'">-- '.$child->subcategory_name.'</option>';
                }
            }
        }
        return response()->json(['status'=>1,'data'=>$html]);                   
    }//end method

    public function createProduct(Request $request){
        /**
         * validate the form
         * ----------------
         */
        $request->validate([
            'name'=>'required|unique:products,name',
            'summary'=>'required',
            'product_image'=>'required|mimes:png,jpg,jpeg|max:1024',
            'category'=>'required|exists:categories,id',
            'subcategory'=>'required|exists:sub_categories,id',
            'price'=>['required',new ValidatePrice],
            'compare_price'=>['required',new ValidatePrice],
        ],[
            'name.required'=>'enter product name',
            'name.unique'=>'this produk already taken',
            'summary.required'=>'write summary for this product',
            'product_image.required'=>'choose product image',
            'category.required'=>'select product category',
            'subcategory.required'=>'select produk subcategory',
            'price.required'=>'enter product price'
        ]);
        
        $product_image = null;
        if($request->hasFile('product_image')){
            $path = 'images/products/';
            $file = $request->file('product_image');
            $filename = 'PIMG_'.time().uniqid().'.'.$file->getClientOriginalName();
            $upload = $file->move(public_path($path),$filename);

            if($upload){
                $product_image = $filename;
            }
        }
        
        //save product details
        $product = new Product();
        $product->user_type = 'seller';
        $product->seller_id = auth('seller')->id();
        $product->name = $request->name;
        $product->summary = $request->summary;
        $product->category = $request->category;
        $product->subcategory = $request->subcategory;
        $product->price = $request->price;
        $product->compare_price = $request->compare_price;
        $product->visibility = $request->visibility;
        $product->product_image = $product_image;
        $saved = $product->save();

        if($saved){
            return response()->json(['status'=>1,'msg'=>'New product has created']);
        }else{
            return response()->json(['success'=>0,'msg'=>'something went wrong']);
        }
    }//end method

    public function allProducts(Request $request){
        $data = [
            'pageTitle'=>'My proucts',
        ];
        return view('back.pages.seller.products',$data);
    }

    public function editProduct(Request $request){
        $product = Product::findOrFail($request->id);
        $categories = Category::orderBy('category_name','asc')->get();
        $subcategories = SubCategory::where('category_id',$product->category)
                                    ->where('is_child_of',0)
                                    ->orderBy('subcategory_name','asc')
                                    ->get();
        $data = [
            'pageTitle'=>'Edit product',
            'categories'=>$categories,
            'subcategories'=>$subcategories,
            'product'=>$product
        ];
        return view('back.pages.seller.edit-product',$data);
    }
}
