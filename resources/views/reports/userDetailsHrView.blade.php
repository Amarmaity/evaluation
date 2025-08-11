@extends('layouts.app')

@section('title', 'HR Review Details')

@section('breadcrumb', "Employee {$employee_id} / View Hr Review")

@section('body-class', 'special-page')

@section('content')

<style>
        .span-tage .span-data {
            display: flex;
            justify-content: space-between;
            padding-right: 60px;
        }

        .span-tage tr {
            /* border-bottom: 1px solid #000; */
            margin-bottom: 30px;
        }
    </style>




    <div class="container">
        
        <!-- Back Button aligned to the right -->
        <div class="text-right mb-3">
            <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
        </div>
        
        <h2 class="heading">HR Review Details: {{$employee_id}}</h2>
        <!-- HR Review History Table -->
         <div class="table-container">
            <div class="table-wrapper">
        <table id="hrReviewHistoryTable" class="table table-bordered table-hover main-table">
            <thead>
                <tr>
                    <th>Field</th>
                    <th>Rating</th>
                    <th>Comments</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reviews as $review)
                    <tr>
                        <td>1. How would you rate the employee’s adherence to company policies and procedures?</td>
                        <td>({{ $review->adherence_hr }}/5)</td>
                        <td>{{ $review->comments_adherence_hr }}</td>
                    </tr>
                    <tr>
                        <td>2. Does the employee maintain professionalism and a positive attitude in the workplace?</td>
                        <td>({{ $review->professionalism_positive }} /5)</td>
                        <td>{{ $review->comments_professionalism }}</td>
                    </tr>
                    <tr>
                        <td>3. How well does the employee respond to feedback or suggestions for improvement from colleagues?</td>
                        <td>({{ $review->respond_feedback }} /5)</td>
                        <td>{{ $review->comments_respond_feedback }}</td>
                    </tr>
                    <tr>
                        <td>3. Does the employee take the initiative to seek feedback and act on it?</td>
                        <td>({{ $review->initiative }} /5)</td>
                        <td>{{ $review->comments_initiative }}</td>
                    </tr>
                    <tr>
                        <td>4. Has the employee shown interest in learning and participating in training programs?</td>
                        <td>({{ $review->interest_learning }} /5)</td>
                        <td>{{ $review->comments_interest_learning }}</td>
                    </tr>
                    <tr>
                        <td>5. Does the employee consistently adhere to the company's leave policy?</td>
                        <td>({{ $review->company_leave_policy }} /5)</td>
                        <td>{{ $review->comments_company_leave_policy }}</td>
                    </tr>
                    <tr>
                        <td>Total HR Review Score</td>
                        <td>{{ $review->HrTotalReview }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Include DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css">

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Include DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#hrReviewHistoryTable').DataTable({
                "paging": true,  
                "searching": true,
                "ordering": false,  // Disable ordering
                "info": true,
                "lengthMenu": [5, 10, 25, 50],  // Allow different page lengths
                "columnDefs": [
                    { "targets": [0, 1], "searchable": true }  // Enable search on the first two columns
                ]
            });
        });
    </script>
@endsection
