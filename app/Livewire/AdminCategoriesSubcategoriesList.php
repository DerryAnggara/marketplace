<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Support\Facades\File;
use Livewire\WithPagination;

class AdminCategoriesSubcategoriesList extends Component
{
    use WithPagination;

    public $categoriesPerPage = 3;
    public $subcategoriesPerPage = 3;

    protected $listeners = [
        'updateCategoriesOrdering',
        'deleteCategory',
        'deleteSubCategory'
    ];

    public function updateCategoriesOrdering($positions){
        foreach($positions as $position){
            $index = $position[0];
            $newPosition = $position[1];
            Category::where('id',$index)->update([
                'ordering'=>$newPosition 
            ]);

            $this->showToastr('success','ordering successfully');
        }
    }

    public function deleteCategory($category_id){
        $category = Category::findOrFail($category_id);
        $path = 'images/categories/';
        $category_image = $category->category_image;

        //check if this category has sub category
        if($category->subcategories->count() > 0){
            //check if on of these subcategories has related products

            //delete sub categories
            foreach($category->subcategories as $subcategory){
                $subcategory = SubCategory::findOrFail($subcategory->id);
                $subcategory->delete();
            }
        }
        //delete category image
        if(File::exists(public_path($path.$category_image))){
            File::delete($path.$category_image);
        }

        //delete category from db
        $delete = $category->delete();

        if($delete){
            $this->showToastr('success','ordering successfully');
        }else{
            $this->showToastr('fail','blokk');
        }
    }

    public function deleteSubCategory($subcategory_id){
        $subcategory = SubCategory::findOrFail($subcategory_id);
        
        //when this subcategory has child subcategories
        if( $subcategory->children->count() > 0 ){
            //check if there is/are prouct(s) related to one of child sub categories

            //if no product(s) related to child sub categories, delete them
            foreach($subcategory->children as $child){
                SubCategory::where('id',$child->id)->delete();
            }
            //delete sub category
            $subcategory->delete();

        }else{
            //check if this sub category has products related to it

            //delete sub category
            $subcategory->delete();
        }
    }

    public function showToastr($type,$messge){
        return $this->dispatch('showToastr',[
            'type'=>$type,
            'message'=>$messge
        ]);
    }

    public function render()
    {
        return view('livewire.admin-categories-subcategories-list',[
            'categories'=>Category::orderBy('ordering','asc')->paginate($this->categoriesPerPage,['*'],'categoriesPage'),
            'subcategories'=>SubCategory::where('is_child_of',0)->orderBy('ordering','asc')->paginate($this->subcategoriesPerPage,['*'],'subcategoriesPage')
        ]);
    }
}
