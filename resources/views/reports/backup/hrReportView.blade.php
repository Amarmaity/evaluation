@extends('layouts.app')

@section('title', 'Apprisal Dashboard')
@section('breadcrumb', 'Hr Review List')
@section('page-title', 'Apprisal-Section')

@section('content')


{{-- {{dd($users)}} --}}


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Review Table</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
    </style>
</head>
<body>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Review Table</title>

    <!-- Include CSS for DataTables -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css">

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Include DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>

    <style>
        table {
            width: 100%;
            max-width: 1606px; /* Set the maximum width */
            border-collapse: collapse;
            margin: 0 auto; /* This will center the table horizontally */
        }
    
        table, th, td {
            border: 1px solid black;
        }
    
        th, td {
            padding: 10px;
            text-align: left;
        }
    </style>
    
</head>
<body>

    <h2>Employee Review Table</h2>

    <!-- Table where data will be displayed -->
    <table id="employeeReviewTable">
        <thead>
            <tr>
                <th>Employee Name</th>
                <th>Employee Id</th>
                <th>Email</th>
                <th>Adherence HR</th>
                <th>Comments Adherence HR</th>
                <th>Professionalism Positive</th>
                <th>Comments Professionalism</th>
                <th>Respond Feedback</th>
                <th>Comments Respond Feedback</th>
                <th>Initiative</th>
                <th>Comments Initiative</th>
                <th>Interest in Learning</th>
                <th>Comments Interest in Learning</th>
                <th>Company Leave Policy</th>
                <th>Comments Company Leave Policy</th>
                <th>HR Total Review</th>
            </tr>
        </thead>
        <tbody>
            <!-- Example data, replace it with dynamic data from PHP -->
            @foreach($superAddUser as $user)
                @php
                    // Find corresponding review for this user
                    $review = $hrReviewTable->firstWhere('emp_id', $user->employee_id);
                @endphp
                @if($review) <!-- Only display if there is a review for the user -->
                    <tr>
                        <td>{{ $user->fname }} {{ $user->lname }}</td>
                        <td>{{ $user->employee_id }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $review->adherence_hr }}</td>
                        <td>{{ $review->comments_adherence_hr }}</td>
                        <td>{{ $review->professionalism_positive }}</td>
                        <td>{{ $review->comments_professionalism }}</td>
                        <td>{{ $review->respond_feedback }}</td>
                        <td>{{ $review->comments_respond_feedback }}</td>
                        <td>{{ $review->initiative }}</td>
                        <td>{{ $review->comments_initiative }}</td>
                        <td>{{ $review->interest_learning }}</td>
                        <td>{{ $review->comments_interest_learning }}</td>
                        <td>{{ $review->company_leave_policy }}</td>
                        <td>{{ $review->comments_company_leave_policy }}</td>
                        <td>{{ $review->HrTotalReview }}</td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>

    <!-- Initialize DataTables with search functionality -->
    <script>
        $(document).ready(function() {
            // Initialize DataTable with basic options
            $('#employeeReviewTable').DataTable({
                "paging": true,  // Enable pagination
                "searching": true,  // Enable global search functionality (search all columns by default)
                "ordering": true,  // Enable column sorting
                "info": true,  // Show info (e.g., showing rows 1 to 10 of 100)
                "columnDefs": [
                    { "targets": [0, 1, 2], "searchable": true }  // Enable search on specific columns (e.g., Name, ID, Email)
                ]
            });
        });
    </script>

</body>
</html>

</body>
</html>




@endsection