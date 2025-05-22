@extends('layouts.app')

@section('title', 'Employee Details')
@section('breadcrumb', "Employee {$employee_id} / View Admin Review")


@section('content')
    <div class="container">
        <!-- Back Button aligned to the right -->
        <div class="text-right mb-3">
            <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
        </div>
      

        <h3>Admin Review Details: {{$employee_id}}</h3>

        <!-- Review History Table -->
        <table id="reviewHistoryTable" class="display table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Field</th>
                    <th>Rating / Comments</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reviews as $review)
                    <tr>
                        <td>Has the employee demonstrated regular attendance and punctuality?</td>
                        <td>{{ $review->demonstrated_attendance }} / {{ $review->comments_demonstrated_attendance }}</td>
                    </tr>
                    <tr>
                        <td>How well does the employee manage time within the shift?</td>
                        <td>{{ $review->employee_manage_shift }} / {{ $review->comments_employee_manage_shift }}</td>
                    </tr>
                    <tr>
                        <td>How would you rate the employee’s accuracy and neatness in reports and documentation?</td>
                        <td>{{ $review->documentation_neatness }} / {{ $review->comments_documentation_neatness }}</td>
                    </tr>
                    <tr>
                        <td>Has the employee followed administrative procedures and job instructions properly?</td>
                        <td>{{ $review->followed_instructions }} / {{ $review->comments_followed_instructions }}</td>
                    </tr>
                    <tr>
                        <td>Does the employee effectively manage time and stay productive during working hours?</td>
                        <td>{{ $review->productive }} / {{ $review->comments_productive }}</td>
                    </tr>
                    <tr>
                        <td>How well does the employee handle changes in schedules or assignments?</td>
                        <td>{{ $review->changes_schedules }} / {{ $review->comments_changes_schedules }}</td>
                    </tr>
                    <tr>
                        <td>Does the employee consistently adhere to the company's leave policy?</td>
                        <td>{{ $review->leave_policy }} / {{ $review->comments_leave_policy }}</td>
                    </tr>
                    <tr>
                        <td>Has there been any salary deduction due to the employee's leave?</td>
                        <td>{{ $review->salary_deduction }} / {{ $review->comments_salary_deduction }}</td>
                    </tr>
                    <tr>
                        <td>How well does the employee interact with the housekeeping staff?</td>
                        <td>{{ $review->interact_housekeeping }} / {{ $review->comments_interact_housekeeping }}</td>
                    </tr>
                    <tr>
                        <td>Total Review</td>
                        <td>{{ $review->AdminTotalReview }}</td>
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
        $(document).ready(function() {
            $('#reviewHistoryTable').DataTable({
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
