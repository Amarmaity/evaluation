@extends('layouts.app') <!-- Extends app.blade.php (Header, Sidebar, Footer included) -->

@section('title', 'Admin Dashboard') <!-- Page Title -->

@section('breadcrumb', 'Admin') <!-- Breadcrumb -->

@section('page-title', 'Admin-Review-Section Dashboard') <!-- Page Title in Breadcrumb -->

@section('content')
    <style>
        /* Loading animation */
        .loading {
            color: blue;
            font-weight: bold;
            font-size: 14px;
            text-align: center;
        }
    </style>

    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>

    <body>

        <div class="text-center">
            <h1>ADMIN REVIEW</h1>
            <h4>DELOSTYLE STUDIO PRIVATE LIMITED</h4>
        </div>


        <div class="container mt-3">
            <div class="row">
                <!-- Search by Employee ID -->
                <div class="col-md-6">
                    <label for="employee_id" class="form-label">Search by Employee ID:</label>
                    <input type="search" name="employee_id" id="employee_id" class="form-control" placeholder="Enter Employee ID">
                </div>
        
                <!-- Search by Name -->
                <div class="col-md-6">
                    <label for="employee_name" class="form-label">Search by Name:</label>
                    <input type="search" name="name" id="employee_name" class="form-control" placeholder="Enter Name">
                </div>
            </div>
        </div>
        
        <!-- Display Search Results -->
        <div class="container mt-5" id="employeeDetails" style="display:none; padding: 10px; border: 1px solid #ddd;">
            <h3>Employee Details</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Employee Name</th>
                        <th>Employee ID</th>
                        <th>Designation</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody id="employeeTableBody">
                    <tr>
                        <td colspan="4">Start typing to search...</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- Form Section -->
        <div class="container">
            <form action="{{route('admin.review.submit')}}" method="post" id="AdminReviewSubmit">
                @csrf

                <div class="form-section">

                    <div>
                        <label for="employee_id">Employee Id:</label>
                        <input type="text" name="emp_id" placeholder="Enter Employee Id" required>
                        </input>

                        <div>
                            <label for="demonstrated_attendance">Has the employee demonstrated regular attendance and
                                punctuality?:</label>
                            <input type="number" name="demonstrated_attendance" id="adr1" min="0" max="5" required
                                oninput="adminTotalReview()" placeholder="Rate Yourself">
                            </input>

                            <div class="mt-3"> <!-- Added margin-top for spacing -->
                                <label for="comments_demonstrated_attendance">Justify Your Review:</label>
                                <textarea name="comments_demonstrated_attendance" id="comments" class="form-control"
                                    rows="5" cols="50" placeholder="Write your justification here..." required></textarea>
                            </div>


                            <div>
                                <label for="employee_manage_shift"> How well does the employee manage time within the
                                    shift?:</label>
                                <input type="number" name="employee_manage_shift" id="adr2" min="0" max="5" required
                                    oninput="adminTotalReview()" placeholder="Rate Yourself">
                                </input>

                                <div class="mt-3"> <!-- Added margin-top for spacing -->
                                    <label for="comments_employee_manage_shift">Justify Your Review:</label>
                                    <textarea name="comments_employee_manage_shift" id="comments" class="form-control"
                                        rows="5" cols="50" placeholder="Write your justification here..." required></textarea>
                                </div>
                            </div>
                            <div>
                                <label for="documentation_neatness">How would you rate the employee’s accuracy and
                                    neatness in reports and documentation?:</label>
                                <input type="number" name="documentation_neatness" id="adr3" min="0" max="5" required
                                    oninput="adminTotalReview()" placeholder="Rate Yourself">
                                </input>
                            </div>
                            <div class="mt-3"> <!-- Added margin-top for spacing -->
                                <label for="comments_documentation_neatness">Justify Your Review:</label>
                                <textarea name="comments_documentation_neatness" id="comments" class="form-control" rows="5"
                                    cols="50" placeholder="Write your justification here..." required></textarea>
                            </div>

                            <div>
                                <label for="followed_instructions">Has the employee followed administrative procedures
                                    and job instructions properly?:</label>
                                <input type="number" name="followed_instructions" id="adr4" min="0" max="5" required
                                    oninput="adminTotalReview()" placeholder="Rate Yourself">
                                </input>
                            </div>
                            <div class="mt-3"> <!-- Added margin-top for spacing -->
                                <label for="comments_followed_instructions">Justify Your Review:</label>
                                <textarea name="comments_followed_instructions" id="comments" class="form-control" rows="5"
                                    cols="50" placeholder="Write your justification here..." required></textarea>
                            </div>

                            <div>
                                <label for="productive">Does the employee effectively manage time and stay productive
                                    during working hours?:</label>
                                <input type="number" name="productive" id="adr5" min="0" max="5" required
                                    oninput="adminTotalReview()" placeholder="Rate Yourself">
                                </input>
                            </div>
                            <div class="mt-3"> <!-- Added margin-top for spacing -->
                                <label for="comments_productive">Justify Your Review:</label>
                                <textarea name="comments_productive" id="comments" class="form-control" rows="5" cols="50"
                                    placeholder="Write your justification here..." required></textarea>
                            </div>


                            <div>
                                <label for="changes_schedules">How well does the employee handle changes in schedules or
                                    assignments?:</label>
                                <input type="number" name="changes_schedules" id="adr6" min="0" max="5" required
                                    oninput="adminTotalReview()" placeholder="Rate Yourself">
                                </input>
                            </div>
                            <div class="mt-3"> <!-- Added margin-top for spacing -->
                                <label for="comments_changes_schedules">Justify Your Review:</label>
                                <textarea name="comments_changes_schedules" id="comments" class="form-control" rows="5"
                                    cols="50" placeholder="Write your justification here..." required></textarea>
                            </div>

                            <div>
                                <label for="leave_policy">Does the employee consistently adhere to the company's leave
                                    policy?:</label>
                                <input type="number" name="leave_policy" id="adr7" min="0" max="5" required
                                    oninput="adminTotalReview()" placeholder="Rate Yourself">
                                </input>
                            </div>
                            <div class="mt-3"> <!-- Added margin-top for spacing -->
                                <label for="comments_leave_policy">Justify Your Review:</label>
                                <textarea name="comments_leave_policy" id="comments" class="form-control" rows="5" cols="50"
                                    placeholder="Write your justification here..." required></textarea>
                            </div>

                            <div>
                                <label for="salary_deduction">Has there been any salary deduction due to the employee's
                                    leave?:</label>
                                <input type="number" name="salary_deduction" id="adr8" min="0" max="5" required
                                    oninput="adminTotalReview()" placeholder="Rate Yourself">
                                </input>
                            </div>
                            <div class="mt-3"> <!-- Added margin-top for spacing -->
                                <label for="comments_salary_deduction">Justify Your Review:</label>
                                <textarea name="comments_salary_deduction" id="comments" class="form-control" rows="5"
                                    cols="50" placeholder="Write your justification here..." required></textarea>
                            </div>


                            <div>
                                <label for="interact_housekeeping"> How well does the employee interact with the
                                    housekeeping staff?:</label>
                                <input type="number" name="interact_housekeeping" id="adr9" min="0" max="5" required
                                    oninput="adminTotalReview()" placeholder="Rate Yourself">
                                </input>
                            </div>
                            <div class="mt-3"> <!-- Added margin-top for spacing -->
                                <label for="comments_interact_housekeeping">Justify Your Review:</label>
                                <textarea name="comments_interact_housekeeping" id="comments" class="form-control" rows="5"
                                    cols="50" placeholder="Write your justification here..." required></textarea>
                            </div>

                            <div class="form-section">
                                <label for="AdminTotalReview">Admin Total Review:</label>
                                <input type="text" name="AdminTotalReview" id="adminTotalReview" readonly>
                            </div>

                            <div class="form-section mt-4">
                                <div class="d-flex justify-content-between">
                                    <!-- Cancel Button -->
                                    <button type="reset" class="btn btn-secondary">Cancel</button>

                                    <!-- Submit Button -->
                                    <button type="submit" class="btn btn-primary" id="submitBtn">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </body>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>

$(function () {
    let timeout = null;

    function searchUser() {
        var employeeId = $('#employee_id').val().trim();
        var employeeName = $('#employee_name').val().trim();

        if (employeeId.length < 2 && employeeName.length < 2) {
            $('#employeeDetails').hide();
            return;
        }

        $('#employeeDetails').show();
        $('#employeeTableBody').html('<tr><td colspan="4">Searching...</td></tr>');

        clearTimeout(timeout);

        timeout = setTimeout(function () {
            $.ajax({
                url: '{{ route("user-search") }}',
                type: 'GET',
                data: { employee_id: employeeId, name: employeeName },
                success: function (response) {
                    $('#employeeTableBody').empty();

                    if (response.success) {
                        response.users.forEach(function (user) {
                            $('#employeeTableBody').append(`
                                <tr class="selectable-row" data-emp-id="${user.employee_id}">
                                    <td>${user.fname} ${user.lname}</td>
                                    <td>${user.employee_id}</td>
                                    <td>${user.designation}</td>
                                    <td>${user.email}</td>
                                </tr>
                            `);
                        });
                    } else {
                        $('#employeeTableBody').html('<tr><td colspan="4">No users found</td></tr>');
                    }
                },
                error: function () {
                    alert("An error occurred. Please try again.");
                }
            });
        }, 1500); // 1.5-second delay
    }

    $('#employee_id, #employee_name').on('keyup', searchUser);

    // Event listener for table row clicks
    $(document).on('click', '.selectable-row', function () {
        var selectedEmployeeId = $(this).data('emp-id');
        $('input[name="emp_id"]').val(selectedEmployeeId);
    });
});


        document.getElementById("AdminReviewSubmit").addEventListener("submit", function (event) {
            event.preventDefault(); // Prevent normal submission

            let form = this;
            let formData = new FormData(this);

            fetch("{{ route('admin.review.submit') }}", {
                method: "POST",
                body: formData,
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                }
            })
                .then(response => {
                    if (!response.ok) {
                        return response.text(); // Read error response
                    }
                    return response.json();
                })
                .then(data => {
                    console.log("Success:", data);
                    alert("Review submitted successfully!");
                    form.reset();
                })
                .catch(error => {
                    console.error("Error:", error);
                });
        })

        //Admin Review total ratting
        document.addEventListener("DOMContentLoaded", function () {
            function adminTotalReview() {
                var adminReview1 = parseInt(document.getElementById('adr1').value) || 0;
                var adminReview2 = parseInt(document.getElementById('adr2').value) || 0;
                var adminReview3 = parseInt(document.getElementById('adr3').value) || 0;
                var adminReview4 = parseInt(document.getElementById('adr4').value) || 0;
                var adminReview5 = parseInt(document.getElementById('adr5').value) || 0;
                var adminReview6 = parseInt(document.getElementById('adr6').value) || 0;
                var adminReview7 = parseInt(document.getElementById('adr7').value) || 0;
                var adminReview8 = parseInt(document.getElementById('adr8').value) || 0;
                var adminReview9 = parseInt(document.getElementById('adr9').value) || 0;


                var totalRating = adminReview1 + adminReview2 + adminReview3 + adminReview4 + adminReview5 + adminReview6 + adminReview7 + adminReview8 + adminReview9;
                document.getElementById('adminTotalReview').value = totalRating;
            }

            // Attach function to input events
            document.getElementById('adr1').oninput = adminTotalReview;
            document.getElementById('adr2').oninput = adminTotalReview;
            document.getElementById('adr3').oninput = adminTotalReview;
            document.getElementById('adr4').oninput = adminTotalReview;
            document.getElementById('adr5').oninput = adminTotalReview;
            document.getElementById('adr6').oninput = adminTotalReview;
            document.getElementById('adr7').oninput = adminTotalReview;
            document.getElementById('adr8').oninput = adminTotalReview;
            document.getElementById('adr9').oninput = adminTotalReview;

        });
    </script>
@endsection