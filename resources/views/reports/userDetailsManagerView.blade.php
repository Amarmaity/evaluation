@extends('layouts.app')

@section('title', 'Manager Review Details')

@section('breadcrumb', "Employee {$employee_id} / View Manager Review")

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



    <!-- Back Button aligned to the right -->
    <div class="text-right">
        <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
    </div>



    <h2 class="heading">Manager Review Details: {{$employee_id}}</h2>
    <!-- Manager Review History Table -->
    <div class="table-container span-tage">
        <div class="table-wrapper">
            <table id="managerReviewHistoryTable" class="table  table-bordered table-hover main-table">
                <thead>
                    <tr>
                        <th class="span-data">Field <span>Rating</span></th>
                        <th>Comments</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reviews as $review)
                        <tr>
                            <td class="span-data">1. How would you rate the employee’s quality of work, including accuracy,
                                neatness, and
                                timeliness? <span>({{ $review->rate_employee_quality }}/5)</span>
                            </td>
                            <td>{{ $review->comments_rate_employee_quality }}</td>
                        </tr>
                        <tr>
                            <td class="span-data">2. Does the employee align their work with the organization's goals and
                                objectives? <span>({{ $review->organizational_goals }} /5)</span></td>
                            <td>{{ $review->comments_organizational_goals }}</td>
                        </tr>
                        <tr>
                            <td class="span-data">3. How effectively does the employee contribute to team efforts and
                                collaborate with colleagues? <span>({{ $review->collaborate_colleagues }} /5)</span>
                            </td>
                            <td>{{ $review->comments_collaborate_colleagues }}</td>
                        </tr>
                        <tr>
                            <td class="span-data">4. Has the employee shown leadership potential or accepted additional
                                responsibilities? <span>({{ $review->leadership_responsibilities }}/5)</span></td>
                            <td>
                                {{ $review->comments_leadership_responsibilities }}
                            </td>
                        </tr>
                        <tr>
                            <td class="span-data">5. Can you provide an example of when the employee demonstrated
                                problem-solving skills? <span>({{ $review->demonstrated }} /5)</span></td>
                            <td>{{ $review->comments_demonstrated }}</td>
                        </tr>
                        <tr>
                            <td class="span-data">6. How would you rate the employee’s innovative thinking and contribution to
                                team success? <span>({{ $review->demonstrated }} /5)</span></td>
                            <td>{{ $review->comments_thinking_contribution }}</td>
                        </tr>
                        <tr>
                            <td class="span-data">7. Does the employee effectively keep you informed about work progress and
                                issues? <span>({{ $review->informed_progress }} /5)</span></td>
                            <td><br> {{ $review->comments_comments_informed_progress }}</td>
                        </tr>
                        <tr>
                            <td>Total Manager Review Score</td>
                            <td>{{ $review->ManagerTotalReview }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
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