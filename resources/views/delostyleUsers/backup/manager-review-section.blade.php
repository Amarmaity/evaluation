@extends('layouts.app') <!-- Extends app.blade.php (Header, Sidebar, Footer included) -->

@section('title', 'Manager Dashboard') <!-- Page Title -->

@section('breadcrumb', 'Manager') <!-- Breadcrumb -->

@section('page-title', 'Manager Dashboard') <!-- Page Title in Breadcrumb -->

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
            <h1>MANAGER REVIEW</h1>
            <h4>DELOSTYLE STUDIO PRIVATE LIMITED</h4>
        </div>

        <div class="container mt-5">
            <h2>Search Employee</h2>
            <div class="row">
                <div class="col-md-4">
                    <input type="text" id="employee_id" class="form-control" placeholder="Enter Employee ID">
                </div>
                <div class="col-md-4">
                    <input type="text" id="employee_name" class="form-control" placeholder="Enter Employee Name">
                </div>
            </div>
        
            <div class="container mt-4" id="employeeDetails" style="display:none; padding: 10px; border: 1px solid #ddd;">
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
                        <!-- Data will be inserted here -->
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Form Section -->
        <div class="container">
             <form action="{{route('manager.review.submit')}}" method="post" id="ManagerReviewSubmit"> 
                @csrf 

                <div class="form-section">
                    <div>
                        <label for="employee_id">Employee Id:</label>
                        <input type="text" name="emp_id" placeholder="Enter Employee Id" required>
                        </input>
                        <div>
                            <label for="employee_work_quality">How would you rate the employee’s quality of work, including accuracy, neatness, and timeliness?:</label>
                            <input type="number" name="rate_employee_quality" id="mrs1" min="0" max="5" required
                                oninput="managerTotalReview()" placeholder="Rate Yourself">
                            </input>

                            <div class="mt-3"> <!-- Added margin-top for spacing -->
                                <label for="comments_employee_work_quality">Justify Your Review:</label>
                                <textarea name="comments_rate_employee_quality" id="comments" class="form-control" rows="5"
                                    cols="50" placeholder="Write your justification here..." required></textarea>
                            </div>


                            <div>
                                <label for="organizational_goals">Does the employee align their work with the organization's goals and objectives?:</label>
                                <input type="number" name="organizational_goals" id="mrs2" min="0" max="5" required
                                    oninput="managerTotalReview()" placeholder="Rate Yourself">
                                </input>

                                <div class="mt-3"> <!-- Added margin-top for spacing -->
                                    <label for="comments_organizational_goals">Justify Your Review:</label>
                                    <textarea name="comments_organizational_goals" id="comments" class="form-control"
                                        rows="5" cols="50" placeholder="Write your justification here..." required></textarea>
                                </div>

                                <div>
                                    <label for="collaborate_colleagues">How effectively does the employee contribute to team
                                        efforts and collaborate with colleagues?:</label>
                                    <input type="number" name="collaborate_colleagues" id="mrs3" min="0" max="5" required
                                        oninput="managerTotalReview()" placeholder="Rate Yourself">
                                    </input>
                                </div>
                                <div class="mt-3"> <!-- Added margin-top for spacing -->
                                    <label for="comments_collaborate_colleagues">Justify Your Review:</label>
                                    <textarea name="comments_collaborate_colleagues" id="comments" class="form-control"
                                        rows="5" cols="50" placeholder="Write your justification here..." required></textarea>
                                </div>

                                <div>
                                    <label for="demonstrated">Can you provide an example of when the employee demonstrated problem-solving skills?:</label>
                                    <input type="number" name="demonstrated" id="mrs4" min="0" max="5"
                                        required oninput="managerTotalReview()" placeholder="Rate Yourself">
                                    </input>
                                </div>
                                <div class="mt-3"> <!-- Added margin-top for spacing -->
                                    <label for="comments_demonstrated">Justify Your Review:</label>
                                    <textarea name="comments_demonstrated" id="comments" class="form-control"
                                        rows="5" cols="50" placeholder="Write your justification here..." required></textarea>
                                </div>

                                <div>
                                    <label for="leadership_responsibilities">Has the employee shown leadership potential or
                                        accepted additional responsibilities?:</label>
                                    <input type="number" name="leadership_responsibilities" id="mrs5" min="0" max="5"
                                        required oninput="managerTotalReview()" placeholder="Rate Yourself">
                                    </input>
                                </div>
                                <div class="mt-3"> <!-- Added margin-top for spacing -->
                                    <label for="comments_leadership_responsibilities">Justify Your Review:</label>
                                    <textarea name="comments_leadership_responsibilities" id="comments" class="form-control"
                                        rows="5" cols="50" placeholder="Write your justification here..." required></textarea>
                                </div>

                                <div>
                                    <label for="thinking_contribution">How would you rate the employee’s innovative thinking
                                        and contribution to team success?:</label>
                                    <input type="number" name="thinking_contribution" id="mrs6" min="0" max="5" required
                                        oninput="managerTotalReview()" placeholder="Rate Yourself">
                                    </input>
                                </div>
                                <div class="mt-3"> <!-- Added margin-top for spacing -->
                                    <label for="comments_thinking_contribution">Justify Your Review:</label>
                                    <textarea name="comments_thinking_contribution" id="comments" class="form-control"
                                        rows="5" cols="50" placeholder="Write your justification here... required"></textarea>
                                </div>

                                <div>
                                    <label for="informed_progress">Does the employee effectively keep you informed about
                                        work progress and issues?:</label>
                                    <input type="number" name="informed_progress" id="mrs7" min="0" max="5" required
                                        oninput="managerTotalReview()" placeholder="Rate Yourself">
                                    </input>
                                </div>

                                <div class="mt-3"> <!-- Added margin-top for spacing -->
                                    <label for="comments_informed_progress">Justify Your Review:</label>
                                    <textarea name="comments_comments_informed_progress" id="comments" class="form-control"
                                        rows="5" cols="50" placeholder="Write your justification here..." required></textarea>
                                </div>
                                <div class="form-section">
                                    <div>
                                        <label for="ManagerTotalReview">Manager Total Review:</label>
                                        <input type="text" name="ManagerTotalReview" id="managerTotalReview" readonly>
                                    </div>
                                </div>

                                <div class="form-section mt-4">
                                    <div class="d-flex justify-content-between">
                                        <!-- Cancel Button -->
                                        <button type="reset" class="btn btn-secondary">Cancel</button>

                                        <!-- Submit Button -->
                                        <button type="submit" class="btn btn-primary">Submit</button>
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

        document.getElementById("ManagerReviewSubmit").addEventListener("submit", function (event) {
            event.preventDefault(); // Prevent normal submission
            let form = this;
            let formData = new FormData(this);

            fetch("{{ route('manager.review.submit') }}", {
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



        //Manager  Review total ratting
        document.addEventListener("DOMContentLoaded", function () {
            function managerTotalReview() {
                var managerReview1 = parseInt(document.getElementById('mrs1').value) || 0;
                var managerReview2 = parseInt(document.getElementById('mrs2').value) || 0;
                var managerReview3 = parseInt(document.getElementById('mrs3').value) || 0;
                var managerReview4 = parseInt(document.getElementById('mrs4').value) || 0;
                var managerReview5 = parseInt(document.getElementById('mrs5').value) || 0;
                var managerReview6 = parseInt(document.getElementById('mrs6').value) || 0;
                var managerReview7 = parseInt(document.getElementById('mrs7').value) || 0;



                var totalRating = managerReview1 + managerReview2 + managerReview3 + managerReview4 + managerReview5 + managerReview6 + managerReview7;
                document.getElementById('managerTotalReview').value = totalRating;
            }

            // Attach function to input events
            document.getElementById('mrs1').oninput = managerTotalReview;
            document.getElementById('mrs2').oninput = managerTotalReview;
            document.getElementById('mrs3').oninput = managerTotalReview;
            document.getElementById('mrs4').oninput = managerTotalReview;
            document.getElementById('mrs5').oninput = managerTotalReview;
            document.getElementById('mrs6').oninput = managerTotalReview;
            document.getElementById('mrs7').oninput = managerTotalReview;


        });

    </script>
@endsection