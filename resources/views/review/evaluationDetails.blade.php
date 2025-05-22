@extends('layouts.app') <!-- Extends app.blade.php (Header, Sidebar, Footer included) -->

@section('title', 'Evaluation Details') <!-- Page Title -->

@section('breadcrumb', "Employee {$emp_id} / View Evaluation")

@section('page-title', 'Evaluation Details') <!-- Page Title in Breadcrumb -->

@section('content')


    <table class="table table-striped table-hover table-bordered">
        <table class="table table-striped table-hover table-bordered">
            <thead>
                <tr>
                    @foreach($columnMappings as $dbColumn => $label)
                        <th>{{ $label }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                <tr>
                    @foreach($columnMappings as $dbColumn => $label)
                        <td>{{ $users->$dbColumn ?? 'N/A' }}</td>
                    @endforeach
                </tr>
            </tbody>
        </table>
    
    

    <!-- Back Button (Uses JavaScript to prevent data loss) -->
    <div class="mt-3">
        <button onclick="history.back()" class="btn btn-secondary">← Back</button>





@endsection