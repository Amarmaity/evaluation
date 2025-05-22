@extends('layouts.app') <!-- Extends app.blade.php (Header, Sidebar, Footer included) -->

@section('title', 'Client Review Details') <!-- Page Title -->

@section('breadcrumb', "Employee {$emp_id} / View Client Review")

@section('page-title', 'Client Review Details') <!-- Page Title in Breadcrumb -->

@section('content')




<h3>Client Review for Employee:</h3>


<table class="table table-striped table-hover table-bordered">
    <thead>
        <tr>
            @foreach($clientColumnMappings as $dbColumn => $label)
                <th>{{ $label }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        <tr>
            @foreach($clientColumnMappings as $dbColumn => $label)
                <td>{{ $users->$dbColumn ?? 'N/A' }}</td>
            @endforeach
        </tr>
    </tbody>
</table>

<div class="mt-3">
    <button onclick="history.back()" class="btn btn-secondary">← Back</button>
</div>

    

@endsection