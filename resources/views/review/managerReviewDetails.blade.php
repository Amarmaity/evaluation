@extends('layouts.app') <!-- Extends app.blade.php (Header, Sidebar, Footer included) -->

@section('title', 'Manager Review Details') <!-- Page Title -->

@section('breadcrumb', "Employee {$emp_id} / View Manager Review")

@section('page-title', 'Manager Review Details') <!-- Page Title in Breadcrumb -->

@section('body-class', 'special-page')

@section('content')




<h3>Manager Review for Employee: </h3>
{{-- {{ $users->employee_name ?? 'N/A' }} --}}

<table class="table  table-bordered table-hover main-table">
    <thead>
        <tr>
            @foreach($managerColumnMapings as $dbColumn => $label)
                <th>{{ $label }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        <tr>
            @foreach($managerColumnMapings as $dbColumn => $label)
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