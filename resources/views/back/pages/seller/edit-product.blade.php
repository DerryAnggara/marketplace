@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'page title here')
@section('content')

<div class="page-header">
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="title">
                <h4>Edit Product</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{route('seller.home')}}">Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Edit  Product
                    </li>
                </ol>
            </nav>
        </div>
        <div class="col-md-6 col-sm-12 text-right">
            <a href="{{route('seller.product.all-products')}}" class="btn btn-primary">View All Product</a>
        </div>
    </div>
</div>

<form action="{{route('seller.product.update-product')}}" method="POST" enctype="multipart/form-data" id="updateProductForm">
    @csrf
    <input type="hidden" name="product_id" value="{{$product->id}}">
    <div class="row pd-10">
        <div class="col-md-8 mb-20">
                <div class="card-box height-100-p pd-20" style="position: relative">
                    <div class="form-group">
                        <label for=""><b>Product name:</b></label>
                        <input type="text" class="form-control" name="name" placeholder="Enter product name" value="{{$product->name}}">
                        <span class="text-danger error-text name_error"></span>
                    </div>
                    <div class="form-group">
                        <label for=""><b>Product summary:</b></label>
                        <textarea id="summary" class="form-control summernote" cols="30" rows="10">{!! $product->summary !!}</textarea>
                        <span class="text-danger error-text summary_error"></span>
                    </div>
                    <div class="form-group">
                        <label for=""><b>Product image:</b><small>must be squareand maximum dimension of (1080x1080)</small></label>
                        <input type="file" class="form-control" name="product_image">
                        <span class="text-danger error-text product_image_error"></span>
                    </div>
                    <div class="d-block mb-3">
                        <img src="" class="img-thumbnail" id="image-preview">
                    </div>
                    <b>NB</b>:<small class="text-danger">you will be able to add more images related to this product
                        when you are on edit product page.
                    </small>
                </div>
        </div>
        <div class="col-md-4 mb-20">
            <div class="card-box min-height-200px pd-20 mb-20">
                <div class="form-group">
                    <label for=""><b>Category:</b></label>
                    <select name="category" id="category" class="form-control">
                        <option value="" selected>Not set</option>
                        @foreach ($categories as $item)
                            <option value="{{$item->id}}">{{$item->category_name}}</option>
                        @endforeach
                    </select>
                    <span class="text-danger error-text category_error"></span>
                </div>
                <div class="form-group">
                    <label for=""><b>Sub Category:</b></label>
                    <select name="subcategory" id="subcategory" class="form-control">
                        <option value="" selected>Not set</option>
                        
                    </select>
                    <span class="text-danger error-text subcategory_error"></span>
                </div>
            </div>
            <div class="card-box min-height-200px pd-20 mb-20">
                <div class="form-group">
                    <label for=""><b>Price:</b><small>In rupiah currency (Rp)</small></label>
                    <input type="text" name="price" class="form-control" placeholder="eg:Rp.10.000">
                    <span class="text-danger error-text price_error"></span>
                </div>
                <div class="form-group">
                    <label for=""><b>Compare Price:</b><small>optional</small></label>
                    <input type="text" name="compare_price" class="form-control" placeholder="eg:77.99">
                    <span class="text-danger error-text compare_price_error"></span>
                </div>
            </div>
            <div class="card-box min-height-120px pd-20">
                <div class="form-group">
                    <label for=""><b>visibility:</b></label>
                    <select name="visibility" id="" class="form-control">
                        <option value="" selected>Not set</option>
                        <option value="1">Public</option>
                        <option value="2">Private</option>
                    </select>
                    <span class="text-danger error-text visibility_error"></span>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-primary">Create produck</button>
    </div>
</form>

@endsection
@push('scripts')
    <script>
        //list sub categories according to the selected category
        $(document).on('change','select#category', function(e){
            e.preventDefault();
            var category_id = $(this).val();
            var url = "{{route('seller.product.get-product-category')}}";
            if(category_id == ''){
                $("select#subcategory").find("option").not(":first").remove();
            }else{
                $.get(url,{category_id:category_id}, function(response){
                    $("select#subcategory").find("option").not(":first").remove();
                    $("select#subcategory").append(response.data);
                },'JSON');
            }
        });

        //submit product form
        $('#addProductForm').on('submit',function(e){
            e.preventDefault();
            var summary = $('textarea.summernote').summernote('code');
            var form = this;
            var formdata = new FormData(form);
                formdata.append('summary',summary);
            
            
            $.ajax({
                url:$(form).attr('action'),
                method:$(form).attr('method'),
                data:formdata,
                processData:false,
                dataType:'json',
                contentType:false,
                beforeSend:function(){
                    toastr.remove();
                    $(form).find('span.error-text').text('');
                },
                success:function(response){
                    toastr.remove();
                    if(response.status == 1 ){
                        $(form)[0].reset();
                        $('textarea.summernote').summernote('code','');
                        $('select#subcategory').find('option').not(':first').remove();
                        $('img#image-preview').attr('src','');
                        toastr.success(response.msg);
                    }else{
                        toastr.error(response.msg);
                    }
                },
                error:function(response){
                    toastr.remove();
                    $.each(response.responseJSON.errors, function(prefix,val){
                        $(form).find('span.'+prefix+'_error').text(val[0]);
                    });
                }
            });
        });
    </script>
@endpush