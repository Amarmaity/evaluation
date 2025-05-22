@extends('layouts.app')

@section('title', 'Hr Review Dashboard')
@section('breadcrumb', 'Hr Review List')
@section('page-title', 'Hr-Review-Section')

@section('content')

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

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }

        .dataTables_filter {
            display: none;
        }
    </style>
</head>



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
            max-width: 1500px;
            /* Set the maximum width */
            border-collapse: collapse;
            margin: 0 auto;
            /* This will center the table horizontally */
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }
    </style>

</head>

<body>

    <div class="client clients-block">
        <h1 class="client__heading">Employee Review Table</h1>
        <div class="client___item">
            <input type="search" id="employee_search" name="search" class="form-control client__search"
                placeholder="search employee" aria-label="Search">
            <button class="client__btn" type="submit">
                <img src="https://modest-gagarin.74-208-156-247.plesk.page/images/search.png" alt="Search">
            </button>
        </div>
        {{-- <input type="hidden" name="emp_id" id="selectedEmpId"> --}}
    </div>


    {{-- {{dd($superAddUser)}} --}}
    <!-- Table where data will be displayed -->
    <div class="container table-container">
        <div class="table-wrapper">
            <table class="table table-bordered table-hover main-table" id="employeeReviewTable">
                <thead>
                    <tr>
                        <th>Employee Name</th>
                        <th>Employee Id</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Example data, replace it with dynamic data from PHP -->
                    @foreach($superAddUser as $user)
                    @if($user->user_type !== 'admin')
                    <tr>
                        <td>{{ $user->fname }} {{ $user->lname }}</td>
                        <td>{{ $user->employee_id }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <select id="financialYear" name="financial_year" required
                                class="form-control financial-year input-block">
                                <option value="" selected>Select Financial Years</option>
                                <option value="2025-2026">2025-2026</option>
                                <option value="2026-2027">2026-2027</option>
                                <option value="2027-2028">2027-2028</option>
                                <option value="2028-2029">2028-2029</option>
                                <option value="2029-2030">2029-2030</option>
                            </select>
                            <div class="btn-block">
                                @php
                                $sessionUserType = session()->get('user_type');
                                $sessionEmployeeId = session()->get('employee_id');

                                if (!($sessionUserType === 'hr' && $sessionEmployeeId == $user->employee_id)) {
                                echo ' <a href="'. route('user-hr-details', $user->employee_id) .'"
                                    class="btn btn-primary view-details">View Details</a>';
                                }
                                @endphp
                                {{-- <a href="{{ route('user-hr-details', $user->employee_id) }}"
                                class="btn btn-primary view-details">View Details</a> --}}

                                <a href="{{ route('user-report-view-evaluation', $user->employee_id) }}"
                                    class="btn btn-primary view-evaluation">View Evaluation</a>
                            </div>
                        </td>
                    </tr>
                    @endif
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>
</body>
<!-- Initialize DataTables with search functionality -->
<script>
    $(document).ready(function() {
        var table = $('#employeeReviewTable').DataTable({
            "paging": false,
            "searching": true, // keep this true to allow external filtering
            "ordering": false,
            "info": false
        });

        // Bind the custom search input
        $('#employee_search').on('keyup', function() {
            table.search(this.value).draw();
        });
    });


        $(document).ready(function () {
            $('.view-details').click(function (e) {
                e.preventDefault();

                const financialYear = $(this).closest('tr').find('.financial-year').val();
                const baseUrl = $(this).attr('href');

                if (!financialYear) {
                    alert('Please select a financial year!');
                    return;
                }

                $.ajax({
                    url: baseUrl + '?financial_year=' + financialYear,
                    method: 'GET',
                    success: function (response) {
                        // If the response is JSON with a "message", show alert
                        if (response.message) {
                            alert(response.message);
                        } else {
                            // If not, assume it's the HTML of the details page
                            window.location.href = baseUrl + '?financial_year=' + financialYear;
                        }
                    },
                    error: function (xhr) {
                        alert('Something went wrong.');
                    }
                });
            });
        });

        $(document).ready(function () {
    $('.view-evaluation').click(function (e) {
        e.preventDefault();

        const $row = $(this).closest('tr');
        const financialYear = $row.find('.financial-year').val();
        const baseUrl = $(this).attr('href');

        if (!financialYear) {
            alert('Please select a financial year!');
            return;
        }

        $.ajax({
            url: baseUrl + '?financial_year=' + financialYear,
            method: 'GET',
            success: function (response) {
                if (response.message) {
                    alert(response.message); // You can use SweetAlert here if preferred
                } else {
                    window.location.href = baseUrl + '?financial_year=' + financialYear;
                }
            },
            error: function () {
                alert('Something went wrong. Please try again.');
            }
        });
    });
});




</script>



@endsection