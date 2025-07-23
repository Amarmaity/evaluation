@extends('layouts.app')

@section('title', 'Client Management')
@section('breadcrumb', 'Client Management')
@section('page-title', 'Client Management')
{{-- @section('body-class', 'special-page') --}}


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
            <h1 class="client__heading">Active Clients</h1>
            <div class="client___item">
                <input type="search" id="client_search" name="search" class="form-control client__search"
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
                            <th>Client Name</th>
                            <th>Company Name</th>
                            <th>Client Ph No</th>
                            <th>Client Email</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($allClients as $client)
                            <tr data-status="{{ $client->status ? '1' : '2' }}">
                                <td>{{ $client->client_name }}</td>
                                <td>{{ $client->company_name ?? '-' }}</td>
                                <td>{{ $client->client_mobno }}</td>
                                <td>{{ $client->client_email }}</td>
                                <td class="status-class" id="status-{{ $client->id }}">
                                    {{ $client->status ? 'Active' : 'Inactive' }}
                                </td>
                                <td>
                                    <input type="checkbox" class="toggle-btn" data-toggle="toggle" data-size="mini"
                                        data-on="Deactivate" data-off="Activate" data-onstyle="danger" data-offstyle="success"
                                        data-user-type="client" data-identifier="{{ $client->id }}" {{ $client->status ? 'checked' : '' }}>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>

        </div>
    </body>


    <script>
        function getToggleStatusUrl(user_type, id) {
            // Customize based on how your Laravel routes are defined
            if (user_type === "client") {
                return "{{ url('/toggle-status-client') }}/" + id; // <-- this must match your Laravel route
            }
            return "/toggle-status/" + user_type + "/" + id;
        }

        $(document).ready(function () {
            let table = $('#userTable').DataTable({
                paging:true,
                searching: true,
                ordering: true,
                info: true,
                ordering: true,
                pageLength: false,
                lengthChange: false,
                lengthMenu: [5, 10, 25, 50, 100],
                order: [[4, 'asc']],
            });

            $('#client_search').on('keyup', function () {
                table.search(this.value).draw();
            });

            $(document).on("change", ".toggle-btn", function () {
                let identifier = $(this).data("identifier");
                let user_type = $(this).data("user-type");
                let button = $(this);

                $.ajax({
                    url: getToggleStatusUrl(user_type, identifier),
                    type: "POST",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                    },
                    success: function (response) {
                        if (response.success) {
                            let statusText = response.new_status ? "Active" : "Inactive";
                            button.closest("tr").find(".status-class").text(statusText);
                        } else {
                            alert("Failed to update status: " + response.error);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("AJAX Error:", error);
                        alert("Error toggling status");
                    }
                });
            });
        });
    </script>

@endsection