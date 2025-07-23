@extends('layouts.app')

@section('title', 'User Management')
@section('breadcrumb', 'User Listing')
@section('page-title', 'Apprisal-Section')

@section('content')

    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
        <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    </head>
    <style>
        .btn-red {
            background-color: red !important;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
        }

        .btn-green {
            background-color: green !important;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
        }

        .dataTables_filter {
            display: none;
        }
    </style>

    <body>
        <div class="client">
            <h1 class="client__heading">Active Employees</h1>
            <div class="client___item">
                <input type="search" id="employee_search" name="search" class="form-control client__search"
                    placeholder="Search" aria-label="Search">
                <button class="client__btn" type="submit">
                    <img src="https://modest-gagarin.74-208-156-247.plesk.page/images/search.png" alt="Search">
                </button>
            </div>
        </div>
        <div class="container table-container list-user-page">
            <div class="table-responsive table-wrapper">
                <table id="userTable" class="table  table-bordered table-hover table-view user-list-table modified-table"
                    class="user-list" style="width:100%">
                    <thead>
                        <tr>
                            <th>Employee Name</th>
                            <th>Employee ID</th>
                            <th>Designation</th>
                            <th>Salary</th>
                            <th>Salary Grade</th>
                            <th>Mobile Number</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr data-status="{{ $user->status ? '1' : '2' }}">
                                <td>{{ $user->fname }} {{ $user->lname }}</td>
                                <td>{{ $user->employee_id ?? '-' }}</td>
                                <td>{{ $user->designation }}</td>
                                <td>{{ $user->salary }}</td>
                                <td>{{ $user->salary_grade}}</td>
                                <td>{{ $user->mobno }}</td>
                                <td>{{ $user->email }}</td>
                                <td class="status-class" id="status-{{ $user->id }}">
                                    {{ $user->status ? 'Active' : 'Inactive' }}
                                </td>

                                <td>
                                    <input type="checkbox" class="toggle-btn" data-toggle="toggle" data-size="mini"
                                        data-on="Deactivate" data-off="Activate" data-onstyle="danger" data-offstyle="success"
                                        data-user-type="{{ $user->user_type }}"
                                        data-identifier="{{ $user->user_type === 'client' ? $user->id : $user->employee_id }}"
                                        {{ $user->status ? 'checked' : '' }}>


                                    <a href="{{ url('/edit-user/' . $user->id) }}" class="edit-user">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </body>

    <script>
        $(document).ready(function () {
            var table = $('#userTable').DataTable({
                // "paging": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "paging": true,
                "lengthChange": false,
                "pageLength": 10,
                "lengthMenu": [5, 10, 25, 50, 100],
                "order": [
                    [2, 'desc']
                ],
            });

            // Bind the custom search input
            $('#employee_search').on('keyup', function () {
                table.search(this.value).draw();
            });
        });

        $(document).on("change", ".toggle-btn", function () {
            let userType = $(this).data("user-type");
            let identifier = $(this).data("identifier");
            let button = $(this);

            $.ajax({
                url: `/toggle-status/${userType}/${identifier}`,
                type: "POST",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                },
                success: function (response) {
                    if (response.success) {
                        // Update status cell in the same row
                        let statusText = response.new_status ? "Active" : "Inactive";
                        button.closest("tr").find(".status-class").text(statusText);
                    } else {
                        alert("Failed to update status: " + response.error);
                    }
                },
                error: function () {
                    alert("Error toggling status");
                }
            });
        });

    </script>

@endsection