@extends('layouts.app')

@section('title', 'Employee Details')

@section('breadcrumb', "Employee {$employee_id} / View Admin Review")

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


        <h3>Admin Review Details: {{$employee_id}}</h3>

        <!-- Review History Table -->
        <div class="table-container span-tage">
            <div class="table-wrapper">
                <table id="reviewHistoryTable" class="table table-bordered table-hover main-table">
                    <thead>
                        <tr>
                            <th class="span-data">Field <span>Rating</span></th>
                            <th>Comments</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reviews as $review)
                            <tr>
                                <td class="span-data">1. Has the employee demonstrated regular attendance and punctuality? <span>({{ $review->demonstrated_attendance }}/5)</span></td>
                                <td>{{ $review->comments_demonstrated_attendance }}
                                </td>
                            </tr>
                            <tr>
                                <td class="span-data">2. How well does the employee manage time within the shift? <span>({{ $review->employee_manage_shift }} /5)</span></td>
                                <td>{{ $review->comments_employee_manage_shift }}</td>
                            </tr>
                            <tr>
                                <td class="span-data">3. How would you rate the employee’s accuracy and neatness in reports and documentation? <span>({{ $review->documentation_neatness }}/5)</span>
                                </td>
                                <td>{{ $review->comments_documentation_neatness }}
                                </td>
                            </tr>
                            <tr>
                                <td class="span-data">4. Has the employee followed administrative procedures and job instructions properly? <span>({{ $review->followed_instructions }}/5)</span></td>
                                <td>{{ $review->comments_followed_instructions }}</td>
                            </tr>
                            <tr>
                                <td class="span-data">5. Does the employee effectively manage time and stay productive during working hours? <span>({{ $review->productive }} /5)</span></td>
                                <td>{{ $review->comments_productive }}</td>
                            </tr>
                            <tr>
                                <td class="span-data">6. How well does the employee handle changes in schedules or assignments? <span>({{ $review->changes_schedules }}/5)</span></td>
                                <td>{{ $review->comments_changes_schedules }}</td>
                            </tr>
                            <tr>
                                <td class="span-data">7. Does the employee consistently adhere to the company's leave policy? <span>({{ $review->leave_policy }}/5)</span></td>
                                <td>{{ $review->comments_leave_policy }}</td>
                            </tr>
                            <tr>
                                <td class="span-data">8. Has there been any salary deduction due to the employee's leave? <span>({{ $review->salary_deduction }}/5)</span></td>
                                <td>{{ $review->comments_salary_deduction }}</td>
                            </tr>
                            <tr>
                                <td class="span-data">9. How well does the employee interact with the housekeeping staff? <span>({{ $review->interact_housekeeping }}/5)</span></td>
                                <td>{{ $review->comments_interact_housekeeping }}</td>
                            </tr>
                            <tr>
                                <td>Total Review</td>
                                <td>{{ $review->AdminTotalReview }}</td>
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
        $(document).ready(function () {
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