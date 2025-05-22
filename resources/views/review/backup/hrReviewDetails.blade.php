@extends('layouts.app') <!-- Extends app.blade.php (Header, Sidebar, Footer included) -->

@section('title', 'Hr Review Details') <!-- Page Title -->

{{-- @section('breadcrumb', '') <!-- Breadcrumb --> --}}

@section('page-title', 'Hr Review Details') <!-- Page Title in Breadcrumb -->

@section('content')




    <table class="table table-striped table-hover table table-bordered">
        <thead>
            <tr>
                @foreach($hrColumnMapings as $dbColumn => $label)
                    <th>{{ $label }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @if($users instanceof \Illuminate\Support\Collection)
                @foreach($users as $user)
                    <tr>
                        @foreach($hrColumnMapings as $dbColumn => $label)
                            <td>{{ $user->$dbColumn ?? 'N/A' }}</td>
                        @endforeach
                    </tr>
                @endforeach
            @else
                <tr>
                    @foreach($hrColumnMapings as $dbColumn => $label)
                        <td>{{ $users->$dbColumn ?? 'N/A' }}</td>
                    @endforeach
                </tr>
            @endif
        </tbody>
    </table>

    <!-- Back Button (Uses JavaScript to prevent data loss) -->
    <div class="mt-3">
        <button onclick="history.back()" class="btn btn-secondary">← Back</button>
    </div>

@endsection