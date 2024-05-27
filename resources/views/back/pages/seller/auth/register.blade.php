@extends('back.layout.auth-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'page title here')
@section('content')

<div class="login-box bg-white box-shadow border-radius-10">
    <div class="login-title">
        <h2 class="text-center text-primary">Create account seller</h2>
    </div>
    <form action="{{route('seller.create')}}" method="POST">
        @csrf
        <x-alert.form-alert/>

        <div class="form-group">
            <label for="">Full name</label>
            <input type="text" class="form-control" placeholder="Enter full name" name="name" value="{{old('name')}}">
            @error('name')
                <span class="text-danger ml-2">{{$message}}</span>
            @enderror
        </div>
        <div class="form-group">
            <label for="">email</label>
            <input type="text" class="form-control" placeholder="Enter your email" name="email" value="{{old('email')}}">
            @error('email')
                <span class="text-danger ml-2">{{$message}}</span>
            @enderror
        </div>
        <div class="form-group">
            <label for="">password</label>
            <input type="password" class="form-control" placeholder="Enter password" name="password"">
            @error('password')
                <span class="text-danger ml-2">{{$message}}</span>
            @enderror
        </div>
        <div class="form-group">
            <label for="">confirm password</label>
            <input type="password" class="form-control" placeholder="confirm password" name="confirm_password"">
            @error('confirm_password')
                <span class="text-danger ml-2">{{$message}}</span>
            @enderror
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="input-group mb-0">
                    <button type="submit" class="btn btn-primary btn-lg btn-block">Create account</button>
                </div>
                <div class="font-16 weight-600 pt-10 pb-10 text-center" data-color="#707373" style="color: rgb(112,115,115);">OR</div>
                <div class="input-group mb-0">
                    <a href="{{route('seller.login')}}" class="btn btn-outline-primary btn-lg btn-block">sign in</a>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection