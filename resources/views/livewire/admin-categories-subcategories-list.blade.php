<div>
<div class="row">
    <div class="col-md-12">
        <div class="pd-20 card-box mb-30">
            <div class="clearfix">
                <div class="pull-left">
                    <h4 class="h4 text-blue">Categories</h4>
                </div>
                <div class="pull-right">
                    <a href="{{route('admin.manage-categories.add-category')}}" class="btn btn-primary btn-sm" type="button">
                        <i class="fa fa-plus"></i>
                        Add category
                    </a>
                </div>
            </div>
            <div class="table-responsive mt-4">
                <table class="table table-borderless table-striped">
                    <thead class="bg-secondary text-white">
                        <tr>
                            <th>category image</th>
                            <th>category name</th>
                            <th>N. of sub categories</th>
                            <th>actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0" id="sortable_categories">
                        @forelse($categories as $item)
                        <tr data-idex="{{ $item->id }}" data-ordering="{{ $item->ordering }}">
                            <td>
                                <div class="avatar mr-2">
                                    <img src="/images/categories/{{ $item->category_image}}" width="50" height="50" alt="">
                                </div>
                            </td>
                            <td>
                            {{ $item->category_name}}
                            </td>
                            <td>
                                {{ $item->subcategories->count()}}
                            </td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('admin.manage-categories.edit-category',['id'=>$item->id])}}" class="text-primary">
                                        <i class="dw dw-edit2"></i>
                                    </a>
                                    <a href="javascript:;" class="text-danger deleteCategoryBtn" data-id="{{$item->id}}">
                                        <i class="dw dw-delete-3"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4">
                                <span class="text-danger">no category found</span>
                            </td>
                        </tr>
                        @endforelse
                        
                    </tbody>
                </table>
            </div>
            <div class="d-block mt-2">
                {{ $categories->links('livewire::simple-bootstrap') }}
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="pd-20 card-box mb-30">
            <div class="clearfix">
                <div class="pull-left">
                    <h4 class="h4 text-blue">Sub Categories</h4>
                </div>
                <div class="pull-right">
                    <a href="{{ route('admin.manage-categories.add-subcategory')}}" class="btn btn-primary btn-sm" type="button">
                        <i class="fa fa-plus"></i>
                        Add sub category
                    </a>
                </div>
            </div>
            <div class="table-responsive mt-4">
                <table class="table table-borderless table-striped">
                    <thead class="bg-secondary text-white">
                        <tr>
                            <th>Sub category name</th>
                            <th>Category name</th>
                            <th>Child sub categories</th>
                            <th>actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        
                        @forelse ($subcategories as $item)
                        <tr>
                            <td>
                                {{ $item->subcategory_name }}
                            </td>
                            <td>
                                {{ $item->parentcategory->category_name }}
                            </td>
                            <td>
                                @if ($item->children->count() > 0)
                                     <ul class="list-group">
                                        @foreach ($item->children as $child)
                                           <li class="d-flex justify-content-between align-items-center">
                                            - {{$child->subcategory_name}}
                                            <div>
                                                <a href="{{ route('admin.manage-categories.edit-subcategory',['id'=>$child->id])}}" class="text-primary" data-toggle="tooltip" title="edit child sub category">edit</a>
                                                |
                                                <a href="javascript:;" class="text-danger deleteChildSubCategoryBtn" data-toggle="tooltip" title="delete child sub category" data-id="{{$child->id}}" data-title="Child sub category">delete</a>
                                            </div>
                                           </li>
                                        @endforeach   
                                     </ul>

                                @else

                                @endif
                            </td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('admin.manage-categories.edit-subcategory',['id'=>$item->id])}}" class="text-primary">
                                        <i class="dw dw-edit2"></i>
                                    </a>
                                    <a href="javascript:;" class="text-danger deleteSubCategoryBtn" data-id="{{$item->id}}" data-title="Sub Category">
                                        <i class="dw dw-delete-3"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>

                        @empty
                           <tr>
                            <td>
                                <span class="text-danger">no sub category found</span>
                            </td>
                           </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-block mt-2">
                {{ $subcategories->links('livewire::simple-bootstrap') }}
            </div>
        </div>
    </div>
</div>
</div>
