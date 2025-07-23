@extends('layouts.app')

@section('title', 'Add Client')
@section('breadcrumb', 'Add Client')
@section('page-title', 'Add Client')
@section('body-class', 'special-page')

@section('content')

<style>
    select:disabled {
  appearance: none;           
  -webkit-appearance: none;   
  -moz-appearance: none;      
  background-image: none;     
}
</style>

    <div class="super-add-user-page">
        <div class="content-block">
            <input type="checkbox" id="block1">
            <label for="block1" class="main-label">Add New Client</label>
            <div class="content">
                <form method="POST" enctype="multipart/form-data" class="forms-block" id="userForm">
                    @csrf
                    <div class="form-section">
                        <h5 class="heading-three">Client Information</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="client-name" class="forms-label">Client Name</label>
                                <input type="text" class="form-control" id="client-name" name="client_name"
                                    placeholder="Enter client name" required>
                            </div>
                            <div class="col-md-6">
                                <label for="company-name" class="forms-label">Company Name</label>
                                <input type="text" class="form-control" id="company-name" name="company_name"
                                    placeholder="Enter company name" required>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information Section -->
                    <div class="form-section">
                        <h5 class="heading-three">Contact Information</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="mobno" class="forms-label">Mobile Number</label>
                                <input type="tel" class="form-control" id="mobno" name="client_mobno"
                                    placeholder="Enter mobile number">
                            </div>
                            <div class="col-md-6">
                                <label for="client-email" class="forms-label">Email</label>
                                <input type="email" class="form-control" id="client-email" name="client_email"
                                    placeholder="Enter email" required>
                            </div>
                        </div>
                    </div>

                    <div class="row d-flex flex-wrap align-items-start">
                        <div class="col-md-6" style="display: none;">
                            <label for="user_type_dropdown" class="forms-label">User Type</label>
                            <select class="form-control" id="user_type_dropdown" name="user_type" required>
                                <option value="" selected >Select User Type</option>
                                <option value="client" selected>Client</option>
                            </select>
                        </div>

                        <!-- Password Section -->
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="password" class="forms-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Enter password" required>
                            </div>

                            <div class="col-md-6">
                                <label for="cnf-password" class="forms-label">Confirm Password</label>
                                <input type="password" class="form-control" id="cnf-password" placeholder="Confirm password"
                                    required>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary primary-btn" id="saveBtn">Save</button>
                        </div>
                </form>
            </div>
        </div>
    </div>

    <!-- CSRF Meta Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- jQuery and jQuery UI -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- AJAX Script -->
    <script>
        $(document).ready(function () {
            $('#userForm').on('submit', function (e) {
                e.preventDefault();
                $('#saveBtn').prop('disabled', true).text('Saving...');

                const password = $('#password').val();
                const confirmPassword = $('#cnf-password').val();

                if (password !== confirmPassword) {
                    alert("Passwords do not match.");
                    $('#saveBtn').prop('disabled', false).text('Save');
                    return;
                }

                $.ajax({
                    url: "{{ route('new-client') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response.status === "success") {
                            alert(response.message || "Client added successfully.");
                            $('#userForm')[0].reset();
                            setTimeout(function () {
                                $('#saveBtn').prop('disabled', false).text('Save');
                                location.reload();
                            }, 3000);
                        } else {
                            alert(response.message || "Submission failed. Try again.");
                            $('#saveBtn').prop('disabled', false).text('Save');
                        }
                    },
                    error: function (xhr) {
                        console.log(xhr.responseText);

                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            let errorMessages = '';

                            $.each(errors, function (field, messages) {
                                errorMessages += messages.join("\n") + "\n";
                            });

                            alert(errorMessages);
                        } else {
                            alert("Something went wrong. Please try again.");
                        }

                        $('#saveBtn').prop('disabled', false).text('Save');
                    }
                });
            });
        });
    </script>

@endsection