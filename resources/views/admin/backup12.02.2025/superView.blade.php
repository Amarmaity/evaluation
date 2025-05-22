@extends('layouts.app') <!-- Extends app.blade.php (Header, Sidebar, Footer included) -->

@section('title', 'Super Admin Dashboard') <!-- Page Title -->

@section('breadcrumb', 'Super view') <!-- Breadcrumb -->

@section('page-title', 'Super Admin Dashboard') <!-- Page Title in Breadcrumb -->

@section('content')

<body>

    <div class="search-container">
        <label>🔍 Search Employee ID:</label>
        <input type="search" id="search_employee_id" placeholder="e.g.. DS0001">

        <label>🔍 Search Employee Name:</label>
        <input type="search" id="search_employee_name" placeholder="Search Employee Name">
    </div>
    <br>

    @if(isset($employees) && count($employees) > 0)
        <table class="table table-striped table-hover userlist-table" id="employeeDetails">
            <thead>
                <tr>
                    <th>Employee ID</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Designation</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="employeeList">
                @foreach ($employees as $employee)
                    @if($employee->status == 1)  {{-- Hide employees with status = 0 --}}
                        <tr>
                            <td>{{ $employee->employee_id }}</td>
                            <td>{{ $employee->fname }} {{ $employee->lname }}</td>
                            <td>{{ $employee->email }}</td>
                            <td>{{ $employee->designation }}</td>
                            <td>
                                <button onclick="viewEmployeeDetails('{{ $employee->employee_id }}')">View Details</button>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    @else
        <p>No employees found.</p>
    @endif

</body>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 

<script>
$(document).ready(function () {
    let timeout = null;
    let originalTableHtml = $('#employeeList').html(); // Save the original employee list

    function searchEmployee() {
        let employeeId = $('#search_employee_id').val().trim();
        let employeeName = $('#search_employee_name').val().trim();

        if (employeeId === "" && employeeName === "") {
            clearTimeout(timeout);
            $('#employeeList').html(originalTableHtml); 
            $('#employeeDetails').show();
            return;
        }

        if (employeeId.length >= 2 || employeeName.length >= 2) {
            $('#employeeDetails').show();
            $('#employeeList').html('<tr><td colspan="5">Searching...</td></tr>');
        } else {
            $('#employeeDetails').hide();
            return;
        }

        let searchRoute = "/super-admin-search-bar";
        clearTimeout(timeout);
        timeout = setTimeout(function () {
            if (employeeId.length || employeeName.length) {
                $.ajax({
                    url: searchRoute,
                    type: 'GET',
                    data: { employee_id: employeeId, employee_name: employeeName },
                    dataType: 'json',
                    success: function (response) {
                        $('#employeeList').empty();

                        if (response.success && response.users.length > 0) {
                            response.users.forEach(user => {
                                if (user.status == 1) {  // Hide employees with status = 0 in search results
                                    let row = `<tr>
                                        <td>${user.employee_id}</td>
                                        <td>${user.full_name}</td>
                                        <td>${user.email}</td>
                                        <td>${user.designation}</td>
                                        <td><button onclick="viewEmployeeDetails('${user.employee_id}')">View Details</button></td>
                                    </tr>`;
                                    $('#employeeList').append(row);
                                }
                            });
                            $('#employeeDetails').show();
                        } else {
                            $('#employeeDetails').hide();
                            $('#employeeList').html('<tr><td colspan="5">User not found</td></tr>');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("AJAX Error:", status, error);
                        alert("An error occurred. Please try again.");
                    }
                });
            }
        }, 1500);
    }

    $('#search_employee_id').on('keyup', searchEmployee);
    $('#search_employee_name').on('keyup', function () {
        this.value = this.value.replace(/[^A-Za-z\s]/g, "");
        searchEmployee();
    });
});

function viewEmployeeDetails(empId) {
    window.location.href = "/employee/details/" + empId;
}
</script>

@endsection
