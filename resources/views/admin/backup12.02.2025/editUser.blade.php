@extends('layouts.app')

@section('title', 'Update User Details')
@section('breadcrumb')
Update {{ $user->fname }} {{ $user->lname }} {{ $user->employee_id }}
@endsection

@section('page-title', 'Update User')
@section('body-class', 'special-page')

@section('content')
<div class="">
    <div class="">
        {{-- <button onclick="history.back()" class="btn btn-secondary">Back</button> --}}
        <a href="{{ route('userlist') }}" class="btn btn-secondary">Back</a>
    </div>
    <div class="content-block">
        <input type="checkbox" id="block1">
        <label for="block1" class="main-label">Edit Employee Details: {{ $user->fname }} {{ $user->lname }}</label>
        <div class="content">
            <form action="{{ route('update-user', ['id' => $user->id]) }}" method="POST" class="forms-block">
                @csrf
                @method('PUT')
                <div class="row form-section">
                    <div class="col-md-6">
                        <label for="fname" class="forms-label">First Name</label>
                        <input type="text" name="fname" id="fname" class="form-control" value="{{ $user->fname }}"
                            readonly>
                    </div>

                    <div class="col-md-6">
                        <label for="lname" class="forms-label">Last Name</label>
                        <input type="text" name="lname" id="lname" class="form-control" value="{{ $user->lname }}"
                            readonly>
                    </div>


                    <div class="col-md-6">
                        <label for="mobno" class="forms-label">Mobile Number</label>
                        <input type="number" name="mobno" id="mobno" class="form-control" value="{{ $user->mobno }}"
                            maxlength="10" min="0" required>
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="forms-label">Email Address</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ $user->email }}"
                            required>
                    </div>

                    <div class="col-md-6">
                        <label for="employee_id" class="forms-label">Employee ID</label>
                        <input type="text" name="employee_id" id="employee_id" class="form-control"
                            value="{{ $user->employee_id }}" readonly>
                    </div>

                    <div class="col-md-6">
                        <label for="dob" class="forms-label">Joining Date</label>
                        <input type="date" class="form-control" id="dob" name="dob" value="{{ $user->dob }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="gender" class="forms-label">Gender</label>
                        <select class="form-control" id="gender" name="gender">
                            <option value="male" {{ $user->gender == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ $user->gender == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ $user->gender == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="designation" class="forms-label">Designation</label>
                        <select class="form-control" id="designation_dropdown" name="designation" required>
                            <option value="" disabled {{ $user->designation == null ? 'selected' : '' }}>Select
                                Designation</option>
                            <option value="Hr" {{ $user->designation == 'Hr' ? 'selected' : '' }}>Hr</option>
                            <option value="SEO" {{ $user->designation == 'SEO' ? 'selected' : '' }}>SEO</option>
                            <option value="Admin" {{ $user->designation == 'Admin' ? 'selected' : '' }}>Admin</option>
                            <option value="UI/UX Designer"
                                {{ $user->designation == 'UI/UX Designer' ? 'selected' : '' }}>UI/UX Designer</option>
                            <option value="Quality Analyst"
                                {{ $user->designation == 'Quality Analyst' ? 'selected' : '' }}>Quality Analyst</option>
                            <option value="Software Developer"
                                {{ $user->designation == 'Software Developer' ? 'selected' : '' }}>Software Developer
                            </option>
                            <option value="Manager" {{ $user->designation == 'Manager' ? 'selected' : '' }}>Manager
                            </option>
                            <option value="Business Development"
                                {{ $user->designation == 'Business Development' ? 'selected' : '' }}>Business
                                Development(Sales)</option>
                        </select>
                    </div>


                    <div class="col-md-6">
                        <label for="division" class="forms-label">Division</label>
                        <select class="form-control" id="division_dropdown" name="division" required>
                            <option value="" disabled {{ $user->division == null ? 'selected' : '' }}>Select Division
                            </option>
                            <option value="Kasba Office" {{ $user->division == 'Kasba Office' ? 'selected' : '' }}>Kasba
                                Office</option>
                            <option value="Salt Lake 3B" {{ $user->division == 'Salt Lake 3B' ? 'selected' : '' }}>Salt
                                Lake 3B</option>
                            <option value="Salt Lake 17B" {{ $user->division == 'Salt Lake 17B' ? 'selected' : '' }}>
                                Salt Lake 17B</option>
                        </select>
                    </div>


                    {{-- <div class="client-hide col-md-6" id="manager-name-field">
                        <label for="manager_name" class="forms-label">Manager Name</label>
                        <input type="text" class="form-control" id="manager_name" name="manager_name"
                            placeholder="Enter Manager name" value="{{ $user->manager_name }}">
                    </div> --}}


                    <style>

                        .search-wrap{
                            display: flex;
                            flex-direction: column;
                        }

                    </style>

                    <div class="client-hide col-md-6 search-wrap" id="manager-name-field">
                    <label for="manager_name" class="forms-label">Manager Name</label>
                <select class="form-control select2-manager" id="manager_name" name="manager_name" required>
                        @if($user->manager_name)
                            <option value="{{ $user->manager_name }}" selected>{{ $user->manager_name }}</option>
                        @else
                            <option value="">-- Select Manager --</option>
                        @endif
                    </select>
                </div>


                    <div class="col-md-6">
                        <label for="user_type_dropdown" class="forms-label">User Type</label>
                        <select class="form-control" id="user_type_dropdown" name="user_type" required>
                            <option value="" disabled {{ $user->user_type == null ? 'selected' : '' }}>Select User Type
                            </option>
                            <option value="admin" {{ $user->user_type == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="hr" {{ $user->user_type == 'hr' ? 'selected' : '' }}>HR</option>
                            <option value="users" {{ $user->user_type == 'users' ? 'selected' : '' }}>Users</option>
                            <option value="manager" {{ $user->user_type == 'manager' ? 'selected' : '' }}>Manager
                            </option>
                        </select>

                        <!-- Hidden input to actually submit the value -->
                        {{-- <input type="hidden" name="user_type" value="{{ $user->user_type }}"> --}}
                    </div>


                    <div class="client-hide col-md-6">
                        <label for="probation_date" class="forms-label">Probation Date</label>
                        <input type="date" name="probation_date" class="form-control" id="probation_date"
                            value="{{ $user->probation_date }}">
                    </div>

                    <div class="client-hide col-md-6">
                        <label for="salary" class="forms-label">Salary</label>
                        <input type="number" class="form-control" id="salary" name="salary" placeholder="Enter Salary"
                            min="0" value="{{ $user->salary }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="cnf-password" class="forms-label">Confirm Password</label>
                        <input type="password" class="form-control" id="cnf-password" name="password_confirmation"
                            placeholder="Enter password" value="{{ $user->password }}" required>
                    </div>

                    <div class="col-md-6" id="review-section">
                        <label class="forms-label d-block">Selected Person Can Review:</label>
                        @php
                        $availableRoles = ['admin', 'hr', 'users', 'manager', 'client'];

                        // Define roles to hide based on user_type
                        $hiddenRoles = [];

                        switch ($user->user_type) {
                        case 'admin':
                        $hiddenRoles = ['admin', 'users', 'hr'];
                        break;
                        case 'manager':
                        $hiddenRoles = ['manager', 'users', 'hr', 'admin'];
                        break;
                        case 'hr':
                        $hiddenRoles = ['hr', 'users', 'admin'];
                        break;
                        case 'users':
                        $hiddenRoles = ['users'];
                        break;
                        default:
                        $hiddenRoles = []; // client or other: show all
                        }
                        @endphp

                        @foreach($availableRoles as $role)
                        @if(!in_array($role, $hiddenRoles))
                        <div class="form-check form-check-inline">
                            <input class="form-check-input check-input" type="checkbox" name="user_roles[]"
                                value="{{ $role }}" id="{{ $role === 'client' ? 'client-role' : $role }}"
                                {{ in_array($role, $userRoles) ? 'checked' : '' }}>
                            <label class="form-check-label" for="{{ $role === 'client' ? 'client-role' : $role }}">
                                {{ ucfirst($role) }}
                            </label>
                        </div>
                        @endif
                        @endforeach
                    </div>

                    <div class="col-md-6">
                        <label for="password" class="forms-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password"
                            placeholder="Enter password" value="{{ $user->password }}" required>
                    </div>

                </div>
                <div class="client-hide mt-3" id="client-select-container">
                    <label for="client_id" class="forms-label">Select Client</label>
                    <select class="form-control" id="client_id" name="client_id[]" multiple="multiple"
                        style="width: 100%">
                        @foreach($clients as $client)
                        <option value="{{ $client->id }}" selected>{{ $client->client_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="d-flex gap-3 mt-3">
                    <button type="submit" class="btn btn-secondary">Update</button>
                    <a href="{{ url()->previous() }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Include jQuery and Select2 -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>



<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" />

<!-- Select2 CSS + Theme -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
    rel="stylesheet" />

<!-- JS Libraries -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


<!-- SweetAlert2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>





<!-- Initialize Select2 -->
<script>
    $(document).ready(function () {
            // SweetAlert success popup
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '{{ session('
                success ') }}',
                    timer: 2500,
                    showConfirmButton: false
                });
            @endif
    });
    
    @if ($errors->any())
   
        $(document).ready(function () {
            let errorMessages = `{!! implode('<br>', $errors->all()) !!}`;
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                html: errorMessages,
                confirmButtonText: 'Okay'
            });
        });
   
@endif
    $('#client_id').select2({
    theme: 'bootstrap-5',
    placeholder: "Select Client",
    allowClear: true,
    maximumSelectionLength: 10,
    ajax: {
        url: "{{ route('get.clients') }}",
        dataType: 'json',
        delay: 250,
        processResults: function (data) {
            return {
                results: $.map(data, function (client) {
                    return {
                        id: client.id,
                        text: client.client_name + ' (' + client.company_name + ')',
                        client_name: client.client_name,
                        company_name: client.company_name
                    };
                })
            };
        },
        cache: true
    },
    templateResult: function (data) {
        if (!data.id) return data.text;
        return $('<div><strong>' + data.client_name + '</strong><br><small>' + data.company_name + '</small></div>');
    },
    templateSelection: function (data) {
        return data.text || data.client_name;
    }
});



        

        @if(session('success'))

            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('
            success ') }}',
                timer: 2500,
                showConfirmButton: false
            });

        @endif


        $(document).ready(function () {
            function toggleClientSelect() {
                const isClientChecked = $('#client-role').is(':checked');

                if (isClientChecked) {
                    $('#client-select-container').show();
                } else {
                    // Clear selected clients and hide the dropdown
                    $('#client_id').val(null).trigger('change');
                    $('#client-select-container').hide();
                }
            }

            // Run on page load
            toggleClientSelect();

            // Run when the checkbox is changed
            $('#client-role').on('change', function () {
                toggleClientSelect();
            });
        });
         function toggleManagerField() {
        const isManagerChecked = $('#manager').is(':checked');
        if (isManagerChecked) {
            $('#manager-name-field').show();
        } else {
            $('#manager-name-field').hide();
        }
    }

    $(document).ready(function () {
        // Initial check
        toggleManagerField();

        // Trigger on change
        $('.check-input').on('change', toggleManagerField);
    });

 document.querySelector('.forms-block').addEventListener('submit', function (e) {
        const joiningDate = new Date(document.getElementById('dob').value);
        const probationDate = new Date(document.getElementById('probation_date').value);

        if (probationDate < joiningDate) {
            e.preventDefault(); // Stop form from submitting
            alert("⚠️ Probation Date cannot be earlier than Joining Date.");
        }
    });


    //Manager DropDown
    document.addEventListener('DOMContentLoaded', function () {
    const managerSelect = document.getElementById('manager_name');
    const currentValue = managerSelect.value;

    fetch('/api/manager-names')
        .then(response => response.json())
        .then(data => {
            const uniqueManagers = [...new Set(data)];

            uniqueManagers.forEach(manager => {
                // Skip if already selected
                if (manager === currentValue) return;

                const option = document.createElement('option');
                option.value = manager;
                option.textContent = manager;
                managerSelect.appendChild(option);
            });

            // Initialize Select2 search
            $('.select2-manager').select2({
                placeholder: '-- Select Manager --',
                allowClear: true,
                width: '100%'
            });
        })
        .catch(error => {
            console.error('Error fetching manager names:', error);
        });
});



</script>
@endsection