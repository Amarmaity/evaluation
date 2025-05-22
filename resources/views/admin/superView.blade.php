@extends('layouts.app')

@section('title', 'Super Admin Dashboard')
@section('breadcrumb', 'Super view')
@section('page-title', 'Super Admin Dashboard')

@section('content')

    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    </head>
    <style>
        .dataTables_filter {
            display: none;
        }
    </style>

    <body>

        <div class="client">
            <h1 class="client__heading">All Employee Reviews</h1>
            <div class="client___item">
                <input type="search" id="employee_search" name="search" class="form-control client__search"
                    placeholder="Search" aria-label="Search">
                <button class="client__btn" type="submit">
                    <img src="https://modest-gagarin.74-208-156-247.plesk.page/images/search.png" alt="Search">
                </button>
            </div>
        </div>
        <div class="container table-container super-view-page">
            <div class="table-responsive table-wrapper">
                <table id="employeeDetails" class="table table-bordered table-hover main-table view-reviews-table">
                    <thead>
                        <tr>
                            <th>Employee ID</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Designation</th>
                            {{-- <th>Financial Year</th> --}}
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($employees as $employee)
                            @if($employee->status == 1)
                                <tr>
                                    <td>{{ $employee->employee_id }}</td>
                                    <td>{{ $employee->fname }} {{ $employee->lname }}</td>
                                    <td>{{ $employee->email }}</td>
                                    <td>{{ $employee->designation }}</td>
                                    {{-- <td>{{$employee->financial_year}}</td> --}}
                                    <td>
                                        <button class="btn btn-primary" onclick="viewEmployeeDetails('{{ $employee->employee_id }}')">View Details</button>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </body>



    <script>
        // $(document).ready(function () {
        //     let table = $('#employeeDetails').DataTable({
        //         dom: '<"top"lfr>t<"bottom"ip><"clear">',
        //         paging: true,
        //         searching: true,
        //         ordering: true,
        //         info: true,
        //         lengthChange: true,
        //         pageLength: 10,
        //         language: {
        //             emptyTable: "No employee data available for this financial year"
        //         },
        //         initComplete: function () {
        //             const filterDiv = $('div.dataTables_filter');
        //             const label = filterDiv.find('label');
        //             const input = label.find('input');

        //             // Style the search input
        //             label.after(input);
        //             label.remove();

        //             filterDiv.css({
        //                 'display': 'flex',
        //                 'align-items': 'center',
        //                 'gap': '20px',
        //                 'justify-content': 'flex-start',
        //                 'margin-bottom': '10px'
        //             });

        //             input.attr('placeholder', 'Search Employees...');
        //             input.css({
        //                 'padding': '6px',
        //                 'border-radius': '4px',
        //                 'border': '1px solid #ccc',
        //                 'width': '200px'
        //             });
        //         }
        //     });

        //     // Financial Year filter change event
        //     $(document).on('change', '#financialYearFilter', function () {
        //         const selectedYear = $(this).val();

        //         if (selectedYear !== '') {
        //             $.ajax({
        //                 url: '/employees/filter-financial-year-employee-review',
        //                 method: 'POST',
        //                 data: {
        //                     financial_year: selectedYear,
        //                     _token: '{{ csrf_token() }}'
        //                 },
        //                 success: function (response) {
        //                     table.clear(); // Clear previous data

        //                     if (response.data.length > 0) {
        //                         response.data.forEach(function (employee) {
        //                             table.row.add([
        //                                 employee.employee_id,
        //                                 employee.full_name,
        //                                 employee.email,
        //                                 employee.designation,
        //                                 employee.financial_year,
        //                                 `<button onclick="viewEmployeeDetails('${employee.employee_id}')">View Details</button>`
        //                             ]);
        //                         });
        //                     }

        //                     table.draw(); // Always draw (even if empty)
        //                 },
        //                 error: function (xhr, status, error) {
        //                     console.error("Error fetching data: " + error);
        //                 }
        //             });
        //         } else {
        //             location.reload(); // Reload page to reset
        //         }
        //     });
        // });

        // function viewEmployeeDetails(empId) {
        //     window.location.href = "/employee/details/" + empId;
        // }


         $(document).ready(function () {
        let table = $('#employeeDetails').DataTable({
            dom: '<"top"lfr>t<"bottom"ip><"clear">',
            paging: true,
            searching: true,
            ordering: true,
            info: true,
            lengthChange: false,
            language: {
                emptyTable: "No employee data available"
            }
            // initComplete: function () {
            //     const filterDiv = $('div.dataTables_filter');
            //     const label = filterDiv.find('label');
            //     const input = label.find('input');

            //     // Style the search input
            //     label.after(input);
            //     label.remove();

            //     filterDiv.css({
            //         'display': 'flex',
            //         'align-items': 'center',
            //         'gap': '20px',
            //         'justify-content': 'flex-start',
            //         'margin-bottom': '10px'
            //     });

            //     input.attr('placeholder', 'Search Employees...');
            //     input.css({
            //         'padding': '6px',
            //         'border-radius': '4px',
            //         'border': '1px solid #ccc',
            //         'width': '200px'
            //     });
            // }
        });

        // Custom search input functionality
        $('#employee_search').on('keyup', function () {
            table.search(this.value).draw();
        });

        // Optional: Financial Year filter via AJAX
        $(document).on('change', '#financialYearFilter', function () {
            const selectedYear = $(this).val();

            if (selectedYear !== '') {
                $.ajax({
                    url: '/employees/filter-financial-year-employee-review',
                    method: 'POST',
                    data: {
                        financial_year: selectedYear,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        table.clear();

                        if (response.data.length > 0) {
                            response.data.forEach(function (employee) {
                                table.row.add([
                                    employee.employee_id,
                                    employee.full_name,
                                    employee.email,
                                    employee.designation,
                                    `<button onclick="viewEmployeeDetails('${employee.employee_id}')">View Details</button>`
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
                location.reload();
            }
        });
    });

    function viewEmployeeDetails(empId) {
        window.location.href = "/employee/details/" + empId;
    }
    </script>

@endsection