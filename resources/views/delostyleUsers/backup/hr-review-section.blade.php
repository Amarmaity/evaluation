@extends('layouts.app') <!-- Extends app.blade.php (Header, Sidebar, Footer included) -->

@section('title', 'Hr Dashboard') <!-- Page Title -->

@section('breadcrumb', 'Hr') <!-- Breadcrumb -->

@section('page-title', 'Hr-Review Dashboard') <!-- Page Title in Breadcrumb -->

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
            <h1>HR REVIEW</h1>
            <h4>DELOSTYLE STUDIO PRIVATE LIMITED</h4>
        </div>


        <div class="container mt-3">
            <div class="row">
                <!-- Search by Employee ID -->
                <div class="col-md-6">
                    <label for="employee_id" class="form-label">Search by Employee ID:</label>
                    <input type="search" name="employee_id" id="employee_id" class="form-control"
                        placeholder="Enter Employee ID">
                </div>

                <!-- Search by Name -->
                <div class="col-md-6">
                    <label for="employee_name" class="form-label">Search by Name:</label>
                    <input type="search" name="name" id="employee_name" class="form-control" placeholder="Enter Name">
                </div>
            </div>
        </div>
        <!-- Display Search Results -->
        <h3>Employee Details</h3>
        <table class="table table-bordered" id="employeeDetails">
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


        <body>
            <!-- Form Section -->
            <div class="container">
                <form action="{{route('hr.review.submit')}}" method="post" id="HrReviewSubmit">
                    @csrf

                    <div class="form-section">

                        <h3>HR-Review Questions:</h3>
                        <div>
                            <label for="employee_id">Employee Id:</label>
                            <input type="text" name="emp_id" placeholder="Enter Employee Id" required>
                            </input>

                            <div>
                                <label for="adherence">How would you rate the employee’s adherence to company policies and
                                    procedures?:</label>
                                <input type="number" name="adherence_hr" id="hr1" min="0" max="5" required
                                    oninput="hrTotalReview()" placeholder="Rate Yourself">
                                </input>

                                <div class="mt-3"> <!-- Added margin-top for spacing -->
                                    <label for="comments_adherence">Justify Your Review:</label>
                                    <textarea name="comments_adherence_hr" id="comments" class="form-control" rows="5"
                                        cols="50" placeholder="Write your justification here..." required></textarea>
                                </div>


                                <div>
                                    <label for="professionalism">Does the employee maintain professionalism and a positive
                                        attitude in the workplace?:</label>
                                    <input type="number" name="professionalism_positive" id="hr2" min="0" max="5" required
                                        oninput="hrTotalReview()" placeholder="Rate Yourself">
                                    </input>

                                    <div class="mt-3"> <!-- Added margin-top for spacing -->
                                        <label for="comments_professionalism_positive">Justify Your Review:</label>
                                        <textarea name="comments_professionalism" id="comments" class="form-control"
                                            rows="5" cols="50" placeholder="Write your justification here..." required></textarea>
                                    </div>

                                    <div>
                                        <label for="respond_feedback">How well does the employee respond to feedback or
                                            suggestions for improvement from colleagues?:</label>
                                        <input type="number" name="respond_feedback" id="hr3" min="0" max="5" required
                                            oninput="hrTotalReview()" placeholder="Rate Yourself">
                                        </input>
                                    </div>
                                    <div class="mt-3"> <!-- Added margin-top for spacing -->
                                        <label for="comments_respond_feedback">Justify Your Review:</label>
                                        <textarea name="comments_respond_feedback" id="comments" class="form-control"
                                            rows="5" cols="50" placeholder="Write your justification here..." required></textarea>
                                    </div>

                                    <div>
                                        <label for="initiative">Does the employee take the initiative to seek feedback and
                                            act
                                            on it?:</label>
                                        <input type="number" name="initiative" id="hr4" min="0" max="5" required
                                            oninput="hrTotalReview()" placeholder="Rate Yourself">
                                        </input>
                                    </div>
                                    <div class="mt-3"> <!-- Added margin-top for spacing -->
                                        <label for="comments_initiative">Justify Your Review:</label>
                                        <textarea name="comments_initiative" id="comments" class="form-control" rows="5"
                                            cols="50" placeholder="Write your justification here..." required></textarea>
                                    </div>

                                    {{-- <h4>Job Performance & Responsibilities:</h4> --}}
                                    <div>
                                        <label for="interest_learning">Has the employee shown interest in learning and
                                            participating in training programs?</label>
                                        <input type="number" name="interest_learning" id="hr5" min="0" max="5" required
                                            oninput="hrTotalReview()" placeholder="Rate Yourself">
                                        </input>
                                    </div>
                                    <div class="mt-3"> <!-- Added margin-top for spacing -->
                                        <label for="comments_interest_learning">Justify Your Review:</label>
                                        <textarea name="comments_interest_learning" id="comments" class="form-control"
                                            rows="5" cols="50" placeholder="Write your justification here..." required></textarea>
                                    </div>

                                    <div>
                                        <label for="company_leave_policy">Does the employee consistently adhere to the
                                            company's
                                            leave policy?</label>
                                        <input type="number" name="company_leave_policy" id="hr6" min="0" max="5" required
                                            oninput="hrTotalReview()" placeholder="Rate Yourself">
                                        </input>
                                    </div>
                                    <div class="mt-3"> <!-- Added margin-top for spacing -->
                                        <label for="comments_company_leave_policy">Justify Your Review:</label>
                                        <textarea name="comments_company_leave_policy" id="comments" class="form-control"
                                            rows="5" cols="50" placeholder="Write your justification here..." required></textarea>
                                    </div>

                                    <div class="form-section">
                                        <label for="AdminTotalReview">HR Total Review:</label>
                                        <input type="text" name="HrTotalReview" id="hrTotalReview" readonly>
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


            document.getElementById("HrReviewSubmit").addEventListener("submit", function (event) {
                event.preventDefault(); // Prevent normal submission

                let form = this; // Store reference to the form
                let formData = new FormData(form);

                fetch("{{ route('hr.review.submit') }}", {
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

                        // Reset the form after successful submission
                        form.reset();
                    })
                    .catch(error => {
                        console.error("Error:", error);
                    });
            });

            //Admin Review total ratting
            document.addEventListener("DOMContentLoaded", function () {
                function hrTotalReview() {
                    var hrReview1 = parseInt(document.getElementById('hr1').value) || 0;
                    var hrReview2 = parseInt(document.getElementById('hr2').value) || 0;
                    var hrReview3 = parseInt(document.getElementById('hr3').value) || 0;
                    var hrReview4 = parseInt(document.getElementById('hr4').value) || 0;
                    var hrReview5 = parseInt(document.getElementById('hr5').value) || 0;
                    var hrReview6 = parseInt(document.getElementById('hr6').value) || 0;

                    var totalRating = hrReview1 + hrReview2 + hrReview3 + hrReview4 + hrReview5 + hrReview6;
                    document.getElementById('hrTotalReview').value = totalRating;
                }

                // Attach function to input events
                document.getElementById('hr1').oninput = hrTotalReview;
                document.getElementById('hr2').oninput = hrTotalReview;
                document.getElementById('hr3').oninput = hrTotalReview;
                document.getElementById('hr4').oninput = hrTotalReview;
                document.getElementById('hr5').oninput = hrTotalReview;
                document.getElementById('hr6').oninput = hrTotalReview;

            });


        </script>
@endsection