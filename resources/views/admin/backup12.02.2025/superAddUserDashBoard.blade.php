@extends('layouts.app') <!-- Extends app.blade.php (Header, Sidebar, Footer included) -->

@section('title', 'Super Admin Dashboard') <!-- Page Title -->

@section('breadcrumb', 'Super Admin') <!-- Breadcrumb -->

@section('page-title', 'Super Admin Dashboard') <!-- Page Title in Breadcrumb -->
@section('body-class', 'special-page')
@section('content')

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
                                    <select class="form-select" id="gender" name="gender" required>
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
                                    <input type="text" class="form-control" id="mobno" name="mobno"
                                        placeholder="Enter mobile number" required>
                                </div>
                            </div>


                            <div class="col-md-6">
                                <label for="email" class="forms-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter email"
                                    required>
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
                                        <option value="Client">Client</option>
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

                                    <div class="client-hide">
                                        <label for="manager_name" class="forms-label">Manager Name</label>
                                        <input type="text" class="form-control" id="manager_name" name="manager_name"
                                            placeholder="Enter Manager name">
                                    </div>


                                </div>




                                <div class="col-md-6">
                                    <div class="client-hide">
                                        <label for="employee_id" class="forms-label">Employee ID</label>
                                        <input type="text" class="form-control" id="employee_id" name="employee_id"
                                            placeholder="e.g..DS00001">
                                    </div>


                                    {{-- Evaluation Purpose --}}
                                    <div class="client-hide">
                                        <label for="Evaluation Purpose" class="forms-label">Evaluation Purpose</label>
                                        <select class="form-control" id="evaluation_purpose" name="evaluation_purpose">
                                            <option value="" selected disabled>Select Purpose</option>
                                            <option value="Appraisal">Appraisal</option>
                                        </select>
                                    </div>
                                    {{-- <option value="Salt Lake 3B">Salt Lake 3B</option> --}}
                                    </select>

                                </div>

                                {{-- Department --}}
                                {{-- <div class="client-hide">
                                    <label for="department" class="forms-label">Department</label>
                                    <select class="form-control" id="department_dropdown" name="department">
                                        <option value="" selected disabled>Select Department</option>
                                        <option value="Hr">Hr</option>
                                        <option value="SEO">SEO</option>
                                        <option value="Admin">Admin</option>
                                        <option value="Quality Analyst">Quality Analyst</option>
                                        <option value="Software Developer">Software Developer</option>
                                        <option value="Business Development">Business Development(Sales)</option>
                                    </select>
                                </div> --}}

                            </div>
                        </div>

                        <!-- User Type Selection (Dropdown & Checkboxes) -->
                        <div class="form-section">
                            <h5 class="heading-three">Select User Type</h5>

                            <!-- Dropdown for User Type -->
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="user_type_dropdown" class="forms-label">User Type</label>
                                    <select class="form-control" id="user_type_dropdown" name="user_type" required>
                                        <option value="" selected disabled>Select User Type</option>
                                        <option value="admin">Admin</option>
                                        <option value="hr">HR</option>
                                        <option value="users">Users</option>
                                        <option value="manager">Manager</option>
                                        <option value="client">Client</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Checkboxes for Multiple Roles -->
                            <div class="row mt-3" id="review-section">
                                <div class="col-md-12">
                                    <h6>Selected Person Can Review:</h6>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="admin" name="user_roles[]"
                                            value="admin">
                                        <label class="form-check-label" for="admin">Admin</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="hr" name="user_roles[]"
                                            value="hr">
                                        <label class="form-check-label" for="hr">HR</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="users" name="user_roles[]"
                                            value="users">
                                        <label class="form-check-label" for="users">Users</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="manager" name="user_roles[]"
                                            value="manager">
                                        <label class="form-check-label" for="manager">Manager</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        {{-- <input class="form-check-input" type="checkbox" id="client" name="user_roles[]"
                                            value="client"> --}}
                                        <input class="form-check-input" type="checkbox" id="client-checkbox"
                                            name="user_roles[]" value="client">
                                        <label class="form-check-label" for="client">Client</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="client-hide">
                            <label for="probation_date">Probation Date</label>
                            <input type="date" name="probation_date" class="form-control">
                        </div>
                        <!-- Account Information Section -->
                        <div class="form-section">
                            <h5 class="heading-three">Account Information</h5>
                            <div class="row g-3">
                                <div class="client-hide">
                                    <label for="salary" class="forms-label">Salary</label>
                                    <input type="number" class="form-control" id="salary" name="salary"
                                        placeholder="Enter Salary" min="0" required>
                                </div>
                                {{-- <div class="col-md-6">
                                    <label for="email" class="forms-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        placeholder="Enter email" required>
                                </div> --}}
                                <div class="col-md-6">
                                    <label for="password" class="forms-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password"
                                        placeholder="Enter password" required>
                                </div>



                                <div class="client-hide">
                                    <label for="salary grade" class="forms-label">Salary Grade/Band</label>
                                    <select class="form-control" id="salary_grade" name="salary_grade" required>
                                        {{-- <option value="" selected hidden>Salary Grade</option> --}}
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
                        </div>

                        <!-- Confirmation Checkbox & Save Button -->
                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary mt-3" id="saveBtn">Save User</button>
                        </div>

                    </form>
                </div>
            </div>

        </div>

    </body>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>


    <script>

        $(document).ready(function () {
            $('#userForm').on('submit', function (e) {
                e.preventDefault();
                $('#saveBtn').prop('disabled', true);

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

            const restrictedDesignations = ['hr', 'manager', 'client', 'admin'];

            if (restrictedDesignations.includes(selectedDesignation)) {
                $('#client-checkbox').closest('.form-check').hide();
            } else {
                $('#client-checkbox').closest('.form-check').show();
            }

            if (selectedDesignation === 'client') {
                $('#review-section').hide();
            } else {
                $('#review-section').show();
            }


        });


        $('#designation_dropdown').on('change', function () {
            const selected = $(this).val().toLowerCase().trim();

            if (selected === 'client') {
                $('.client-hide').hide();

                $('.client-hide :input').prop('required', false);
            } else {
                $('.client-hide').show();

                $('.client-hide :input').each(function () {

                    if ($(this).attr('name') === 'salary' || $(this).attr('name') === 'salary_grade') {
                        $(this).prop('required', true);
                    }
                });
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





    </script>
@endsection