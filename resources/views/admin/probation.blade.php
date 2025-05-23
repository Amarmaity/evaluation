@extends('layouts.app')

@section('title', 'Probation Period')

@section('breadcrumb', 'Probation Period')

@section('page-title', 'Probation Period')

@section('content')

    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

    </head>

    <style>
        .top {

            display: flex;
            width: 100%;
            flex-direction: column;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }


        .btn {
            padding: 5px 10px;
            margin: 2px;
            border: none;
            cursor: pointer;
        }

        .btn-toggle {
            background-color: #4CAF50;
            color: white;
        }

        .btn-calendar {
            background-color: #FF9800;
            color: white;
        }

        .btn:hover {
            opacity: 0.8;
        }

        .status-text {
            font-weight: bold;
        }

        .calendar-container {
            display: none;
        }

        /* Custom Search Box Styles */
        #searchInput {
            width: 250px;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            margin: 20px 0;
        }

        /* Center the search box */
        .search-container {
            text-align: center;
            margin-bottom: 20px;
        }

        /* Center the pagination */
        .pagination-container {
            text-align: center;
            margin-top: 20px;
        }

        .dataTables_filter {
            display: none;
        }
    </style>

    <div class="client">
        <h1 class="client__heading">Probation Employee Table</h1>
        <div class="client___item">
            <input type="search" id="employee_search" name="search" class="form-control client__search" placeholder="Search"
                aria-label="Search">
            <button class="client__btn" type="submit">
                <img src="https://modest-gagarin.74-208-156-247.plesk.page/images/search.png" alt="Search">
            </button>
        </div>

        <select id="financialYear" class="form-select client__select" name="financial_year" required>
            <option value="" selected>Financial Year</option>
            <option value="2025-2026">2025-2026</option>
            <option value="2026-2027">2026-2027</option>
            <option value="2027-2028">2027-2028</option>
            <option value="2028-2029">2028-2029</option>
            <option value="2029-2030">2029-2030</option>
        </select>
    </div>
    <div class="container table-container probation-page">
        <div class="table-responsive table-wrapper">
            <table id="employeeTable" class="table table-bordered table-hover main-table probation-table" class="probation-table">
                <thead>
                    <tr>
                        <th>Employee Name</th>
                        <th>Employee ID</th>
                        <th>Designation</th>
                        <th>Joining Date</th>
                        <th>Salary</th>
                        <th>Email</th>
                        <th>Financial Year</th>
                        <th>Status</th> <!-- Ensure this column is present -->
                        <th>Probation Date</th>
                        {{-- <th>Action</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach ($user as $users)
                        <tr>
                            <td>{{$users->fname}} {{$users->lname}}</td>
                            <td>{{$users->employee_id}}</td>
                            <td>{{$users->designation}}</td>
                            <td>{{$users->dob}}</td>
                            <td>{{$users->salary}}</td>
                            <td>{{$users->email}}</td>
                            <td>{{$users->financial_year}}
                            <td><span class="status-text" id="status{{$users->employee_id}}">
                                    {{$users->employee_status ?? '--'}}</span></td>
                            <td><span class="probation-date-text" id="probationDate{{$users->employee_id}}">
                                    {{$users->probation_date ?? 'Not Set'}}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function () {
            let table = $('#employeeTable').DataTable({
                dom: '<"top"lfr>t<"bottom"ip><"clear">',
                paging: true,
                ordering: true,
                info: true,
                lengthChange: true,
               "lengthChange": false,
                order: [[3, 'desc']],
                language: {
                    emptyTable: "No employee data available for this financial year"
                }
            });

            // Link custom search input
            $('#employee_search').on('keyup', function () {
                table.search(this.value).draw();
            });

            // Handle Financial Year dropdown
            $('#financialYear').on('change', function () {
                const selectedYear = $(this).val();

                if (selectedYear !== '') {
                    $.ajax({
                        url: '/employees/filter-financial-year',
                        method: 'POST',
                        data: {
                            financial_year: selectedYear,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            table.clear();

                            if (response.data.length > 0) {
                                response.data.reverse().forEach(function (employee) {
                                    table.row.add([
                                        employee[0],
                                        employee[1],
                                        employee[2],
                                        employee[3],
                                        employee[4],
                                        employee[5],
                                        employee[6],
                                        employee[7],
                                        employee[8]
                                    ]);
                                });
                            }

                            table.draw();
                        },
                        error: function (xhr, status, error) {
                            console.error("Error fetching data: " + error);
                        }
                    });
                } else {
                    table.clear().draw();
                }
            });
        });
        //Auto update date status of Employee / Probation Period
        $(document).ready(function () {
            $('#update-status-btn').click(function () {
                $.ajax({
                    url: '/employee-status',
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function (response) {
                        console.log('Response from server:', response);
                        if (response.updated_users_count > 0) {
                            response.updated_users.forEach(function (user) {
                                console.log('Updating status for user:', user);
                                $('#user-status-' + user.id).text('Employee');
                            });
                            alert(response.message);
                        } else {
                            alert('No users were updated.');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error in AJAX request:', error);
                        alert('An error occurred: ' + error);
                    }
                });
            });
        });


    </script>



@endsection