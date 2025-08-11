@extends('layouts.app')
<!-- Extends app.blade.php (Header, Sidebar, Footer included) -->

@section('title', 'Add User')
@section('body-class', 'special-page')
@section('content')

    <style>
    /* Remove the dropdown arrow inside the Select2 box (single select) */
    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__arrow {
        display: none !important;
    }

    /* Optional: Clean up spacing on the right to account for the missing arrow */
    .select2-container--bootstrap-5 .select2-selection--single {
        padding-right: 0.75rem !important;
        background-image: none !important;
    }


    </style>

    <body>

        <div class="super-add-user-page">
            <div class="content-block">
                <input type="checkbox" id="block1">
                <label for="block1" class="main-label">Add New User</label>
                <div class="content">
                    <form action="{{ route('save-user') }}" method="POST" enctype="multipart/form-data" class="forms-block"
                        id="userForm">
                        @csrf
                        <div class="form-section">
                            <h5 class="heading-three">Personal Information</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="fname" class="forms-label">First Name</label>
                                    <input type="text" class="form-control" id="fname" name="fname"
                                        placeholder="Enter first name" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="lname" class="forms-label">Last Name</label>
                                    <input type="text" class="form-control" id="lname" name="lname"
                                        placeholder="Enter last name" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="dob" class="forms-label">Joining Date</label>
                                    <input type="date" class="form-control" id="dob" name="dob" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="gender" class="forms-label">Gender</label>
                                    <select class="form-control" id="gender" name="gender" required>
                                        <option value="" selected disabled>Select gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information Section -->
                        <div class="form-section">
                            <h5 class="heading-three">Contact Information</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="mobno" class="forms-label">Mobile Number</label>
                                    <input type="number" class="form-control" id="mobno" name="mobno"
                                        placeholder="Enter mobile number" pattern="\d{10}" maxlength="10" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="forms-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        placeholder="Enter email" required>
                                </div>
                            </div>
                        </div>

                        <!-- Work Information Section -->

                        <div class="form-section">
                            <h5 class="heading-three">Work Information</h5>


                            <div class="row g-3">


                                <div class="col-md-6">
                                    <label for="designation" class="forms-label">Designation</label>
                                    <select class="form-control" id="designation_dropdown" name="designation" required>
                                        <option value="" selected disabled>Select Designation</option>
                                        <option value=" Hr">Hr</option>
                                        <option value="SEO">SEO</option>
                                        <option value="Admin">Admin</option>
                                        <option value="UI/UX Designer">UI/UX Designer</option>
                                        <option value="Quality Analyst">Quality Analyst</option>
                                        <option value="Software Developer">Software Developer</option>
                                        <option value="Manager">Manager</option>
                                        <option value="Business Development">Business Development(Sales)</option>
                                    </select>



                                    {{--Division--}}
                                    <label for="division" class="forms-label">Division</label>
                                    <select class="form-control" id="division_dropdown" name="division" required>
                                        <option value="" selected disabled>Select Division</option>
                                        <option value="Kasba Office">Kasba Office</option>
                                        <option value="Salt Lake 3B">Salt Lake 3B</option>
                                        <option value="Salt Lake 17B">Salt Lake 17B</option>
                                    </select>


                                    {{-- <div class="client-hide" id="search_manager_div" style="display: block;">
                                        <label for="manager_name" class="forms-label">Search Manager Name</label>
                                        <select class="form-control" id="manager_id" name="manager_id" style="width: 100%">
                                            <!-- Loaded via AJAX -->
                                        </select>

                                    </div> --}}
                                    <div class="client-hide" id="search_manager_div" style="display: block;">
                                        <label for="manager_name" class="forms-label">Search Manager Name</label>
                                        <select class="form-control" id="manager_name" name="manager_id"
                                            style="width: 100%">
                                            <!-- Select2 will load options via AJAX -->
                                        </select>
                                    </div>




                                    <div class="client-hide" id="manager_name_div" style="display: none;">
                                        <label for="manager_name" class="forms-label">Manager Name</label>
                                        <input type="text" class="form-control" id="manager_name" name="manager_name"
                                            placeholder="Enter Manager name">
                                    </div>


                                    <script>
                                        document.getElementById('designation_dropdown').addEventListener('change', function () {
                                            const selectedValue = this.value.trim();
                                            const searchDiv = document.getElementById('search_manager_div');
                                            const managerDiv = document.getElementById('manager_name_div');

                                            if (selectedValue === 'Manager') {
                                                searchDiv.style.display = 'none';
                                                managerDiv.style.display = 'block';
                                            } else {
                                                searchDiv.style.display = 'block';
                                                managerDiv.style.display = 'none';
                                            }
                                        });
                                    </script>

                                </div>


                                <div class="col-md-6">
                                    <div class="client-hide">
                                        <label for="employee_id" class="forms-label">Employee ID</label>
                                        <input type="text" class="form-control" id="employee_id" name="employee_id"
                                            placeholder="e.g..DS00001">
                                    </div>


                                    {{-- Evaluation Purpose --}}
                                    <div class="client-hide" style="display: none;>
                                        <label for="Evaluation Purpose" class="forms-label">Evaluation Purpose</label>
                                        <select class="form-control" id="evaluation_purpose" name="evaluation_purpose">
                                            <option value="" selected>Select Purpose</option>
                                            <option value="Appraisal" selected>Appraisal</option>
                                        </select>
                                    </div>
                                    {{-- <option value="Salt Lake 3B">Salt Lake 3B</option> --}}

                                    <div class="client-hide">
                                        <label for="client_id" class="forms-label">Select Client</label>
                                        <select class="form-control" id="client_id" name="client_id[]" multiple="multiple"
                                            style="width: 100%">
                                            <!-- Options loaded via AJAX -->
                                        </select>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- User Type Selection (Dropdown & Checkboxes) -->
                        <div class="form-section">
                            <h5 class="heading-three">Additional Information</h5>
                            <div class="row d-flex flex-wrap align-items-start">
                                <div class="col-md-6" style="display: none;">
                                    <label for="user_type_dropdown" class="forms-label">User Type</label>
                                    <select class="form-control" id="user_type_dropdown" required disabled>
                                        <option value="" selected disabled>Select User Type</option>
                                        <option value="admin">Admin</option>
                                        <option value="hr">HR</option>
                                        <option value="users">Users</option>
                                        <option value="manager">Manager</option>
                                    </select>



                                    <!-- This hidden input is the one that will actually be submitted -->
                                    <input type="hidden" name="user_type" id="user_type_hidden" required>
                                </div>
                                <div class="col-md-6" id="review-section">
                                    <label class="forms-label d-block">Selected Person Can Review:</label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input check-input" type="checkbox" id="admin"
                                            name="user_roles[]" value="admin">
                                        <label class="form-check-label" for="admin">Admin</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input check-input" type="checkbox" id="hr"
                                            name="user_roles[]" value="hr">
                                        <label class="form-check-label" for="hr">HR</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input check-input" type="checkbox" id="users"
                                            name="user_roles[]" value="users">
                                        <label class="form-check-label" for="users">Users</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input check-input" type="checkbox" id="manager"
                                            name="user_roles[]" value="manager">
                                        <label class="form-check-label" for="manager">Manager</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        {{-- <input class="form-check-input check-input" type="checkbox" id="client"
                                            name="user_roles[]" value="client"> --}}
                                        <input class="form-check-input check-input" type="checkbox" id="client-checkbox"
                                            name="user_roles[]" value="client">
                                        <label class="form-check-label" for="client">Client</label>
                                    </div>
                                </div>

                            </div>

                            <!-- Checkboxes for Multiple Roles -->

                        </div>
                        <div class="client-hide">
                            <label for="probation_date" class="heading-four">Probation Date</label>
                            <input type="date" name="probation_date" class="form-control">
                        </div>
                        <!-- Account Information Section -->
                        <div class="form-section">
                            <h5 class="heading-three">Account Information</h5>
                            <div class="row g-3">
                                <div class="client-hide col-md-6">
                                    <label for="salary" class="forms-label">Salary</label>
                                    <input type="number" class="form-control" id="salary" name="salary"
                                        placeholder="Enter Salary" min="0" required>
                                </div>

                                <div class="col-md-6">
                                    <div class="client-hide">
                                        <label for="salary grade" class="forms-label">Salary Grade/Band</label>
                                        <select class="form-control" id="salary_grade" name="salary_grade" required>
                                            <option value="" selected disabled>Salary Grade</option>
                                            <option value="A">A</option>
                                            <option value="B">B</option>
                                            <option value="C">C</option>
                                            <option value="D">D</option>
                                            <option value="E">E</option>
                                            <option value="F">F</option>
                                        </select>
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <label for="password" class="forms-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password"
                                        placeholder="Enter password" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="cnf-password" class="forms-label">Confirm Password</label>
                                    <input type="password" class="form-control" id="cnf-password" name="password"
                                        placeholder="Enter password" required>
                                </div>
                            </div>
                        </div>

                        <!-- Confirmation Checkbox & Save Button -->
                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary primary-btn" id="saveBtn">Save User</button>
                        </div>

                    </form>
                </div>
            </div>

        </div>

    </body>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>






    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <!-- Or for RTL support -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css" />

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>



    <script>
        $(document).ready(function () {
            $('#userForm').on('submit', function (e) {
                e.preventDefault();
                $('#saveBtn').prop('disabled', true);

                // Password match check
                const password = $('#password').val();
                const confirmPassword = $('#cnf-password').val();

                if (password !== confirmPassword) {
                    alert("Passwords do not match.");
                    $('#saveBtn').prop('disabled', false);
                    return;
                }

                const joiningDateVal = $('#dob').val();
                const probationDateVal = $('input[name="probation_date"]').val();

                if (joiningDateVal && probationDateVal) {
                    const joiningDate = new Date(joiningDateVal);
                    const probationDate = new Date(probationDateVal);

                    if (probationDate < joiningDate) {
                        alert("Probation date cannot be earlier than the joining date.");
                        $('#saveBtn').prop('disabled', false);
                        return;
                    }
                }

                var actionUrl = "{{ route('save-user') }}";

                $.ajax({
                    url: actionUrl,
                    type: "POST",
                    data: $(this).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response.status === "success") {
                            alert(response.message);
                            $('#userForm')[0].reset();
                            setTimeout(function () {
                                $('#saveBtn').prop('disabled', false);
                                location.reload();
                            }, 3000);
                        } else {
                            alert("Failed to submit user data. Please try again.");
                            $('#saveBtn').prop('disabled', false);
                        }
                    },
                    error: function (xhr) {
                        console.log(xhr.responseText);

                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            var errorMessages = '';

                            $.each(errors, function (field, messages) {
                                errorMessages += messages.join("\n") + "\n";
                            });

                            alert(errorMessages);
                        } else {
                            alert("Something went wrong. Please try again.");
                        }

                        $('#saveBtn').prop('disabled', false);
                    }
                });
            });
        });



        $('#user_type_dropdown').on('change', function () {
            const selectedDesignation = $(this).val().toLowerCase().trim();

            // Show the review section by default
            $('#review-section').show();

            // Show all checkboxes by default
            $('#review-section .form-check').show();

            // 1. Hide the entire section if selected type is "client" or "manager"
            if (selectedDesignation === 'client' || selectedDesignation === 'manager') {
                $('#review-section').hide();
                return;
            }

            // 2. Hide only the "client" checkbox if role is in restricted list
            const restrictedDesignations = ['hr', 'manager', 'client', 'admin'];
            if (restrictedDesignations.includes(selectedDesignation)) {
                $('#client-checkbox').closest('.form-check').hide();
            }

            // 3. Custom hiding rules based on role
            if (selectedDesignation === 'users') {
                $('#users').closest('.form-check').hide();
            } else if (selectedDesignation === 'admin') {
                ['admin', 'hr', 'users', 'client'].forEach(id => {
                    $('#' + id).closest('.form-check').hide();
                });
            } else if (selectedDesignation === 'hr') {
                ['hr', 'admin', 'users', 'client'].forEach(id => {
                    $('#' + id).closest('.form-check').hide();
                });
            }

        });


        $('#designation_dropdown').on('change', function () {
            const selectedDesignation = $(this).val().toLowerCase().trim();

            // Show the review section and all checkboxes by default
            $('#review-section').show();
            $('#review-section .form-check').show();

            // Hide entire review section for "client" or "manager"
            if (selectedDesignation === 'client') {
                $('#review-section').hide();
                return;
            }

            // Hide only the "client" checkbox for restricted designations
            const restrictedDesignations = ['hr', 'manager', 'client', 'admin'];
            if (restrictedDesignations.includes(selectedDesignation)) {
                $('#client-checkbox').closest('.form-check').hide();
            }

            // Custom hiding rules for each role
            if (selectedDesignation === 'users') {
                $('#users').closest('.form-check').hide();
            } else if (selectedDesignation === 'admin') {
                ['admin', 'users', 'manager', 'client'].forEach(id => {
                    $('#' + id).closest('.form-check').hide();
                });
            } else if (selectedDesignation === 'hr') {
                ['hr', 'users', 'manager', 'client'].forEach(id => {
                    $('#' + id).closest('.form-check').hide();
                });
            } else if (selectedDesignation === 'manager') {
                // Hide manager, client, and users
                ['manager', 'client', 'users'].forEach(id => {
                    $('#' + id).closest('.form-check').hide();
                });

                // Ensure admin and hr checkboxes are visible
                ['admin', 'hr'].forEach(id => {
                    $('#' + id).closest('.form-check').show();
                });
            }

            // New: Hide "Users" checkbox for these specific designations
            const hideUsersFor = [
                'hr',
                'admin',
                'seo',
                'ui/ux designer',
                'quality analyst',
                'software developer',
                'business development',
                'manager'
            ];

            if (hideUsersFor.includes(selectedDesignation)) {
                $('#users').closest('.form-check').hide();
            }
        });



        //Get Manager Name
        $(function () {
            $("#manager_name").autocomplete({
                source: function (request, response) {
                    $.ajax({
                        url: "{{ route('get.managers') }}",
                        data: {
                            term: request.term
                        },
                        success: function (data) {
                            response(data);
                        }
                    });
                },
                minLength: 1
            });
        });



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




        document.addEventListener('DOMContentLoaded', function () {
            const designationDropdown = document.getElementById('designation_dropdown');
            const userTypeDropdown = document.getElementById('user_type_dropdown');
            const userTypeHidden = document.getElementById('user_type_hidden');

            function setUserTypeBasedOnDesignation() {
                const designation = designationDropdown.value.trim().toLowerCase();

                let userType = 'users'; // default

                if (designation === 'hr') {
                    userType = 'hr';
                } else if (designation === 'admin') {
                    userType = 'admin';
                } else if (designation === 'manager') {
                    userType = 'manager';
                }

                userTypeDropdown.value = userType;
                userTypeHidden.value = userType; // ensure correct value is submitted
                userTypeDropdown.setAttribute('disabled', true); // visually readonly
            }

            designationDropdown.addEventListener('change', setUserTypeBasedOnDesignation);
        });




        const mobInput = document.getElementById('mobno');

        mobInput.addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, '').slice(0, 10);
        });



        //Manager name srarch Using select 2
        $(document).ready(function () {
            $('#manager_name').select2({
                theme: 'bootstrap-5',
                placeholder: "Select Manager",
                allowClear: true,
                ajax: {
                    url: "{{ route('get.manager') }}",
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                },
                templateResult: function (data) {
                    if (!data.id) return data.text;
                    return $('<div><strong>' + data.text + '</strong></div>');
                },
                templateSelection: function (data) {
                    return data.text || data.id;
                }
            });
        });
    </script>
@endsection