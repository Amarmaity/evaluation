@extends('layouts.app') <!-- Extends app.blade.php (Header, Sidebar, Footer included) -->

@section('title', 'Evaluation Details') <!-- Page Title -->

{{-- @section('breadcrumb', '') <!-- Breadcrumb --> --}}

@section('page-title', 'Evaluation Details') <!-- Page Title in Breadcrumb -->

@section('content')



    <table class="table table-striped table-hover table table-bordered">
        <thead>
            <tr>
                @foreach($columnMappings as $dbColumn => $label)
                    <th>{{ $label }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @if($users instanceof \Illuminate\Support\Collection)
                @foreach($users as $user)
                    <tr>
                        @foreach($columnMappings as $dbColumn => $label)
                            <td>{{ $user->$dbColumn ?? 'N/A' }}</td>
                        @endforeach
                    </tr>
                @endforeach
            @else
                <tr>
                    @foreach($columnMappings as $dbColumn => $label)
                        <td>{{ $users->$dbColumn ?? 'N/A' }}</td>
                    @endforeach
                </tr>
            @endif
        </tbody>
    </table>

    <!-- Back Button (Uses JavaScript to prevent data loss) -->
    <div class="mt-3">
        <button onclick="history.back()" class="btn btn-secondary">← Back</button>





@endsection