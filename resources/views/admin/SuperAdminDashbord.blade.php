
{{-- // echo("Hi, I'm Super Admin"); --}}


@extends('layouts.app') <!-- Extends app.blade.php (Header, Sidebar, Footer included) -->

@section('title', 'Super Admin Dashboard') <!-- Page Title -->

@section('breadcrumb', 'Super Admin') <!-- Breadcrumb -->

@section('page-title', 'Super Admin Dashboard') <!-- Page Title in Breadcrumb -->

@section('body-class', 'special-page')

@section('content')
<div cass="body-class">
    
</div>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">

                  
                {{-- <h1>Welcome, {{ Session::get('uesr_mail', 'Super Admin') }}!</h1> --}}
                <p>Email: {{ Session::get('user_email') }}</p>
                <p>This is the Super Admin Dashboard.</p>

            </div>
        </div>
    </div>
@endsection