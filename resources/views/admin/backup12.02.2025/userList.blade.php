@extends('layouts.app')

@section('title', 'Apprisal Dashboard')
@section('breadcrumb', 'User Listing')
@section('page-title', 'Apprisal-Section')

@section('content')

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
    <div class="list-user-page table-wrapper table-wrapper--modified">
        <div class="table-responsive">
            <table id="userTable" class="table  table-bordered table-hover main-table user-list-table" class="user-list"
                style="width:100%">
                <thead>
                    <tr>
                        <th>Employee Name</th>
                        <th>Employee ID</th>
                        <th>Designation</th>
                        <th>Salary</th>
                        <th>Mobile Number</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- @foreach ($users as $user)
                            <tr data-status="{{ $user->status ? '1' : '2' }}">
                    <td>{{ $user->fname }} {{ $user->lname }}</td>
                    <td>{{ $user->employee_id }}</td>
                    <td>{{ $user->designation }}</td>
                    <td>{{ $user->salary }}</td>
                    <td>{{ $user->mobno }}</td>
                    <td>{{ $user->email }}</td>
                    <td id="status-{{ $user->employee_id }}">{{ $user->status ? 'Active' : 'Inactive' }}</td>
                    <td>
                        <button class="toggle-btn {{ $user->status ? 'btn-red' : 'btn-green' }}"
                            data-user-id="{{ $user->employee_id }}">
                            {{ $user->status ? 'Deactivate' : 'Activate' }}
                        </button>
                    </td>
                    </tr>
                    @endforeach --}}
                    @foreach ($users as $user)
                    <tr data-status="{{ $user->status ? '1' : '2' }}">
                        <td>{{ $user->fname }} {{ $user->lname }}</td>
                        <td>{{ $user->employee_id ?? '-' }}</td>
                        <td>{{ $user->designation }}</td>
                        <td>{{ $user->salary }}</td>
                        <td>{{ $user->mobno }}</td>
                        <td>{{ $user->email }}</td>
                        <td id="status-{{ $user->id }}">{{ $user->status ? 'Active' : 'Inactive' }}</td>
                        <td>
                            <button class="toggle-btn {{ $user->status ? 'btn-red' : 'btn-green' }}"
                                data-user-type="{{ $user->user_type }}"
                                data-identifier="{{ $user->user_type === 'client' ? $user->id : $user->employee_id }}">
                                {{ $user->status ? 'Deactivate' : 'Activate' }}
                            </button>
                        </td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>

    </div>
</body>


<script>
$(document).ready(function() {
    var table = $('#userTable').DataTable({
        "paging": false,
        "searching": true,
        "ordering": false,
        "info": false,
        "paging": true,
        "lengthChange": false,
        "pageLength": 10,
        "lengthMenu": [5, 10, 25, 50, 100],
    });

    // Bind the custom search input
    $('#employee_search').on('keyup', function() {
        table.search(this.value).draw();
    });
});

// $(document).on("click", ".toggle-btn", function () {
//     let userId = $(this).data("user-id");
//     console.log("Toggling user ID:", userId); // ← Add this

//     let button = $(this);

//     $.ajax({
//         url: `/toggle-status/${userId}`,
//         type: "POST",
//         headers: {
//             "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
//         },
//         success: function (response) {
//             if (response.success) {
//                 let dataTable = $('#userTable').DataTable();
//                 let row = button.closest('tr');
//                 let rowData = dataTable.row(row).data();

//                 // Update status column (assumed index 6)
//                 rowData[6] = response.new_status ? "Active" : "Inactive";

//                 // Rebuild button HTML with correct class and label (assumed index 7)
//                 let newBtnClass = response.new_status ? 'btn-red' : 'btn-green';
//                 let newBtnLabel = response.new_status ? 'Deactivate' : 'Activate';
//                 let newButtonHTML = `<button class="toggle-btn ${newBtnClass}" data-user-id="${userId}">${newBtnLabel}</button>`;

//                 // Replace button column
//                 rowData[7] = newButtonHTML;

//                 // Update DataTable row
//                 dataTable.row(row).data(rowData).invalidate().draw(false);
//             } else {
//                 alert("Failed to update status: " + response.error);
//             }
//         },
//         error: function () {
//             alert("Error toggling status");
//         }
//     });
// });
$(document).on("click", ".toggle-btn", function() {
    let userType = $(this).data("user-type");
    let identifier = $(this).data("identifier");
    let button = $(this);

    $.ajax({
        url: `/toggle-status/${userType}/${identifier}`,
        type: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        },
        success: function(response) {
            if (response.success) {
                let dataTable = $('#userTable').DataTable();
                let row = button.closest('tr');
                let rowData = dataTable.row(row).data();

                // Update status column (index 6)
                rowData[6] = response.new_status ? "Active" : "Inactive";

                // Rebuild button HTML (index 7)
                let newBtnClass = response.new_status ? 'btn-red' : 'btn-green';
                let newBtnLabel = response.new_status ? 'Deactivate' : 'Activate';
                let newButtonHTML = `
                    <button class="toggle-btn ${newBtnClass}" 
                            data-user-type="${userType}" 
                            data-identifier="${identifier}">
                        ${newBtnLabel}
                    </button>`;
                rowData[7] = newButtonHTML;

                // Update row and redraw
                dataTable.row(row).data(rowData).invalidate().draw(false);

                // Re-sort table by Status column (column index 6)
                dataTable.order([6, 'desc']).draw(); // "Inactive" comes after "Active"
            } else {
                alert("Failed to update status: " + response.error);
            }
        },
        error: function() {
            alert("Error toggling status");
        }
    });
});
</script>

@endsection