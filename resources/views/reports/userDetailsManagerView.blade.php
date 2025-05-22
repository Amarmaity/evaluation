@extends('layouts.app')

@section('title', 'Manager Review Details')
@section('breadcrumb', "Employee {$employee_id} / View Manager Review")


@section('content')
    <div class="container">

        <!-- Back Button aligned to the right -->
        <div class="text-right mb-3">
            <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
        </div>



        <h2>Manager Review Details: {{$employee_id}}</h2>
        <!-- Manager Review History Table -->
        <table id="managerReviewHistoryTable" class="display table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Field</th>
                    <th>Rating / Comments</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reviews as $review)
                    <tr>
                        <td>How would you rate the employee’s quality of work, including accuracy, neatness, and timeliness?
                        </td>
                        <td>{{ $review->rate_employee_quality }} / {{ $review->comments_rate_employee_quality }}</td>
                    </tr>
                    <tr>
                        <td>Does the employee align their work with the organization's goals and objectives?</td>
                        <td>{{ $review->organizational_goals }} / {{ $review->comments_organizational_goals }}</td>
                    </tr>
                    <tr>
                        <td>How effectively does the employee contribute to team efforts and collaborate with colleagues?</td>
                        <td>{{ $review->collaborate_colleagues }} / {{ $review->comments_collaborate_colleagues }}</td>
                    </tr>
                    <tr>
                        <td>Has the employee shown leadership potential or accepted additional responsibilities?</td>
                        <td>{{ $review->leadership_responsibilities }} / {{ $review->comments_leadership_responsibilities }}
                        </td>
                    </tr>
                    <tr>
                        <td>Can you provide an example of when the employee demonstrated problem-solving skills?</td>
                        <td>{{ $review->demonstrated }} / {{ $review->comments_demonstrated }}</td>
                    </tr>
                    <tr>
                        <td>How would you rate the employee’s innovative thinking and contribution to team success?</td>
                        <td>{{ $review->thinking_contribution }} / {{ $review->comments_thinking_contribution }}</td>
                    </tr>
                    <tr>
                        <td>Does the employee effectively keep you informed about work progress and issues?</td>
                        <td>{{ $review->informed_progress }} / {{ $review->comments_comments_informed_progress }}</td>
                    </tr>
                    <tr>
                        <td>Total Manager Review Score</td>
                        <td>{{ $review->ManagerTotalReview }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
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
        $(document).ready(function () {
            $('#managerReviewHistoryTable').DataTable({
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