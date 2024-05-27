@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'page title here')
@section('content')

<div class="page-header">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="title">
                <h4>Profile</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{route('seller.home')}}">Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Profile
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>

@livewire('seller.seller-profile')
@endsection
@push('scripts')
    <script>
        $('input[type="file"][id="sellerProfilePictureFile"]').Kropify({
            preview:'#sellerProfilePicture',
            viewMode:1,
            aspectRatio:1,
            cancelButtonText:'Cancel',
            resetButtonText:'Reset',
            cropButtonText:'Crop & update',
            processURL:'{{ route("seller.change-profile-picture") }}',
            maxSize:2097152,
            showLoader:true,
            success:function(data){
            if(data.status == 1){
                toastr.success(data.msg);
            }else{
                toastr.error(data.msg);
            }
            },
            errors:function(error, text){
                console.log(text);
            },
            });

    //     $('input[type="file"][name="sellerProfilePictureFile"][id="sellerProfilePictureFile"]').ijaboCropTool({
    //       preview : '#sellerProfilePicture',
    //       setRatio:1,
    //       allowedExtensions: ['jpg', 'jpeg','png'],
    //       buttonsText:['CROP','QUIT'],
    //       buttonsColor:['#30bf7d','#ee5155', -15],
    //       processUrl:'{{ route("seller.change-profile-picture")}}',
    //       withCSRF:['_token','{{ csrf_token() }}'],
    //       onSuccess:function(message, element, status){
	// 		Livewire.dispatch('updateAdminSellerHeaderInfo');
    //          alert(message);
    //       },
    //       onError:function(message, element, status){
    //         alert(message);
    //       }
    //    });
    </script>
@endpush