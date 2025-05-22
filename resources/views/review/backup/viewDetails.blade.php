@extends('layouts.app') <!-- Extends app.blade.php (Header, Sidebar, Footer included) -->

@section('title', 'Super Admin Dashboard') <!-- Page Title -->

@section('breadcrumb', "Super view / Employee {$emp_id}") <!-- Breadcrumb -->

@section('page-title', 'Super Admin Dashboard') <!-- Page Title in Breadcrumb -->

@section('content')

    <div class="container">
        <h2>Employee Review Details</h2>

        {{-- Debugging: Check if $users has data --}}
        {{-- @php dd($users); @endphp --}}

        {{-- <button class="btn btn-primary"
            onclick="redirectToReview('{{ route('evaluation.details', ['emp_id' => optional($users['evaluation'])->emp_id ?? '']) }}')">
            View Evaluation
        </button>

        <button class="btn btn-warning"
            onclick="redirectToReview('{{ route('hr.review.details', ['emp_id' => optional($users['hrReview'])->emp_id ?? '']) }}')">
            View HR Review
        </button>

        <button class="btn btn-success"
            onclick="redirectToReview('{{ route('manager.review.details', ['emp_id' => optional($users['managerReview'])->emp_id ?? '']) }}')">
            View Manager Review
        </button>

        <button class="btn btn-danger"
            onclick="redirectToReview('{{ route('admin.review.details', ['emp_id' => optional($users['adminReview'])->emp_id ?? '']) }}')">
            View Admin Review
        </button>

        <button class="btn btn-info"
            onclick="redirectToReview('{{ route('client.review.details', ['emp_id' => optional($users['clientReview'])->emp_id ?? '']) }}')">
            View Client Review
        </button>
    </div> --}}


    @if(optional($users['evaluation'])->emp_id)
    <button class="btn btn-primary"
        onclick="redirectToReview('{{ route('evaluation.details', ['emp_id' => $users['evaluation']->emp_id]) }}')">
        View Evaluation
    </button>
@endif

@if(optional($users['hrReview'])->emp_id)
    <button class="btn btn-warning"
        onclick="redirectToReview('{{ route('hr.review.details', ['emp_id' => $users['hrReview']->emp_id]) }}')">
        View HR Review
    </button>
@endif

@if(optional($users['managerReview'])->emp_id)
    <button class="btn btn-success"
        onclick="redirectToReview('{{ route('manager.review.details', ['emp_id' => $users['managerReview']->emp_id]) }}')">
        View Manager Review
    </button>
@endif

@if(optional($users['adminReview'])->emp_id)
    <button class="btn btn-danger"
        onclick="redirectToReview('{{ route('admin.review.details', ['emp_id' => $users['adminReview']->emp_id]) }}')">
        View Admin Review
    </button>
@endif

@if(optional($users['clientReview'])->emp_id)
    <button class="btn btn-info"
        onclick="redirectToReview('{{ route('client.review.details', ['emp_id' => $users['clientReview']->emp_id]) }}')">
        View Client Review
    </button>
@endif


    <!-- JavaScript for Navigation -->
    <script>


        function redirectToReview(url) {
            console.log("Redirecting to:", url); // Debugging output

            if (!url || url.includes('null')) {
                alert("Error: Employee ID is missing!");
                return;
            }

            window.location.href = url;
        }

    </script>

@endsection