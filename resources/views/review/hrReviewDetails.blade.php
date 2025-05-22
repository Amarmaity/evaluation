@extends('layouts.app') <!-- Extends app.blade.php (Header, Sidebar, Footer included) -->

@section('title', 'Hr Review Details') <!-- Page Title -->

@section('breadcrumb', "Employee {$emp_id} / View Hr Review")


@section('page-title', 'Hr Review Details') <!-- Page Title in Breadcrumb -->

@section('content')




<h3>HR Review for Employee:</h3>
<table class="table table-striped table-hover table-bordered">
    <thead>
        <tr>
            @foreach($hrColumnMapings as $dbColumn => $label)
                <th>{{ $label }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        <tr>
            @foreach($hrColumnMapings as $dbColumn => $label)
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