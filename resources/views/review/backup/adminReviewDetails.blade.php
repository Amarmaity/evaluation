@extends('layouts.app') <!-- Extends app.blade.php (Header, Sidebar, Footer included) -->

@section('title', 'Admin Review Details') <!-- Page Title -->

{{-- @section('breadcrumb', '') <!-- Breadcrumb --> --}}

@section('page-title', 'Admin Review Details') <!-- Page Title in Breadcrumb -->

@section('content')




    <table class="table table-striped table-hover table table-bordered">
        <thead>
            <tr>
                @foreach($adminColumnMappings as $dbColumn => $label)
                    <th>{{ $label }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            <tr>
                @foreach($adminColumnMappings as $dbColumn => $label)
                    <td>{{ $users->$dbColumn ?? 'N/A' }}</td>
                @endforeach
            </tr>
        </tbody>
    </table>

    <!-- Back Button (Uses JavaScript to prevent data loss) -->
    <div class="mt-3">
        <button onclick="history.back()" class="btn btn-secondary">← Back</button>
    </div>
    

@endsection