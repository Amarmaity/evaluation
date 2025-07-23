@extends('layouts.app')
@section('title', 'Admin Review')
@section('breadcrumb', 'Admin')
@section('page-title', 'Admin-Review-Section Dashboard')
@section('content')
    <style>
        /* Loading animation */
        .loading {
            color: blue;
            font-weight: bold;
            font-size: 14px;
            text-align: center;
        }
         .hidden-label {
            margin-top: 15px;
            margin-bottom: 10px;
            font-weight: bold;
            margin-left: 28px;
        }
    </style>

    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>

    <body>
        <form action="{{route('admin.review.submit')}}" method="post" id="AdminReviewSubmit"
            class="form-inline client__form">
            @csrf
            <div class="client">
                <h1 class="client__heading">ADMIN REVIEW</h1>

                 <select id="financialYear" class="form-select client__select" name="financial_year" required>
                    <option value="" selected>Financial Year</option>
                    <option value="2025-2026">2025-2026</option>
                    <option value="2026-2027">2026-2027</option>
                    <option value="2027-2028">2027-2028</option>
                    <option value="2028-2029">2028-2029</option>
                    <option value="2029-2030">2029-2030</option>
                </select>

                <div class="client___item">
                    <input type="search" id="employee_search" name="search" class="form-control client__search"
                        placeholder="search employee" aria-label="Search">

                    <button class="client__btn" type="submit">
                        <img src="https://modest-gagarin.74-208-156-247.plesk.page/images/search.png" alt="Search">
                    </button>
                </div>
            </div>

            <!-- Employee Details Table -->
            <div class="container mt-5 employee-table" id="employeeDetails" style="display:none; border: 1px solid #ddd;">
                <div class="table-wrapper">
                    <!-- <div id="selectLabel" class="hidden-label"
                        style="margin-bottom: 10px; font-weight: bold; display: none;">
                        Select the employee:
                    </div> -->
                    <table class="table table-bordered table-hover main-table">
                        <thead>
                            <tr>
                                <th>Employee ID</th>
                                <th>Employee Name</th>
                                <th>Designation</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody id="employeeTableBody">
                            {{-- <tr id="selectLabelRow">
                                <td colspan="4"><strong>Select the employee:</strong></td>
                            </tr> --}}
                            <tr>
                                <td colspan="4">Start typing to search...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>



            <br>

            <div class="form-section">

                <div>
                    <input type="hidden" id="emp_id_input" name="emp_id" placeholder="Enter Employee Id" required>
                    </input>

                    <div class="accordion">
                        <div class="content-block">
                            <input type="checkbox" id="section1">
                            <label for="section1" class="main-label">A. Assessment of Employee Reliability and Task
                                Execution</label>
                            <div class="content">
                                <label for="demonstrated_attendance" class="second-label">1. Has the employee demonstrated
                                    regular attendance and
                                    punctuality?:</label>
                                <select class="form-select" aria-label="multiple select example"
                                    name="demonstrated_attendance" id="adr1" required>
                                    <option selected disabled>Rate</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>

                                <div class="review-block">
                                    <label for="comments_demonstrated_attendance" class="third-label">Tell us more about
                                        your experience:</label>
                                    <textarea name="comments_demonstrated_attendance" id="comments" class="form-control"
                                        rows="1" cols="50" maxlength="255"
                                        placeholder="What made you give this rating? Share specific examples or feedback to help us improve."></textarea>
                                </div>


                                <div>
                                    <label for="employee_manage_shift" class="second-label">2. How well does the employee
                                        manage time within the
                                        shift?:</label>
                                    <select class="form-select" aria-label="multiple select example"
                                        name="employee_manage_shift" id="adr1" required>
                                        <option selected disabled>Rate</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>

                                    <div class="review-block">
                                        <label for="comments_employee_manage_shift" class="third-label">Tell us more about
                                            your experience:</label>
                                        <textarea name="comments_employee_manage_shift" id="comments" class="form-control"
                                            rows="1" cols="50" maxlength="255"
                                            placeholder="What made you give this rating? Share specific examples or feedback to help us improve."></textarea>
                                    </div>
                                </div>
                                <div>
                                    <label for="documentation_neatness" class="second-label">3. How would you rate the
                                        employee’s accuracy and
                                        neatness in reports and documentation?:</label>
                                    <select class="form-select" aria-label="multiple select example" id="adr1"
                                        name="documentation_neatness" required>
                                        <option selected disabled>Rate</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>
                                </div>
                                <div class="review-block">
                                    <label for="comments_documentation_neatness" class="third-label">Tell us more about your
                                        experience:</label>
                                    <textarea name="comments_documentation_neatness" id="comments" class="form-control"
                                        rows="1" cols="50" maxlength="255"
                                        placeholder="What made you give this rating? Share specific examples or feedback to help us improve."></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="content-block">
                            <input type="checkbox" id="section2">
                            <label for="section2" class="main-label">B. Compliance, Productivity, and Adaptability</label>
                            <div class="content">
                                <label for="followed_instructions" class="second-label">1. Has the employee followed
                                    administrative procedures
                                    and job instructions properly?:</label>
                                <select class="form-select" aria-label="multiple select example"
                                    name="followed_instructions" id="adr1" required>
                                    <option selected disabled>Rate</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>

                                <div class="review-block">
                                    <label for="comments_followed_instructions" class="third-label">Tell us more about your
                                        experience:</label>
                                    <textarea name="comments_followed_instructions" id="comments" class="form-control"
                                        rows="1" cols="50" maxlength="255"
                                        placeholder="What made you give this rating? Share specific examples or feedback to help us improve."></textarea>
                                </div>


                                <div>
                                    <label for="productive" class="second-label">2. Does the employee effectively manage
                                        time and stay productive
                                        during working hours?:</label>
                                    <select class="form-select" aria-label="multiple select example" name="productive"
                                        id="adr1" required>
                                        <option selected disabled>Rate</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>

                                    <div class="review-block">
                                        <label for="comments_productive" class="third-label">Tell us more about your
                                            experience:</label>
                                        <textarea name="comments_productive" id="comments" class="form-control" rows="1"
                                            cols="50" maxlength="255"
                                            placeholder="What made you give this rating? Share specific examples or feedback to help us improve."></textarea>
                                    </div>
                                </div>
                                <div>
                                    <label for="documentation_neatness" class="second-label">3. How well does the employee
                                        handle changes in schedules or
                                        assignments?:</label>
                                    <select class="form-select" aria-label="multiple select example" id="adr1"
                                        name="changes_schedules" required>
                                        <option selected disabled>Rate</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>
                                </div>
                                <div class="review-block">
                                    <label for="comments_changes_schedules" class="third-label">Tell us more about your
                                        experience:</label>
                                    <textarea name="comments_changes_schedules" id="comments" class="form-control" rows="1"
                                        cols="50" maxlength="255"
                                        placeholder="What made you give this rating? Share specific examples or feedback to help us improve."></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="content-block">
                            <input type="checkbox" id="section3">
                            <label for="section3" class="main-label">C. Leave Compliance and Workplace Conduct</label>
                            <div class="content">
                                <label for="leave_policy" class="second-label">1. Does the employee consistently adhere to
                                    the company's leave
                                    policy?:</label>
                                <select class="form-select" aria-label="multiple select example" name="leave_policy"
                                    id="adr1" required>
                                    <option selected disabled>Rate</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>

                                <div class="review-block">
                                    <label for="comments_leave_policy" class="third-label">Tell us more about your
                                        experience:</label>
                                    <textarea name="comments_leave_policy" id="comments" class="form-control" rows="1"
                                        cols="50" maxlength="255"
                                        placeholder="What made you give this rating? Share specific examples or feedback to help us improve."></textarea>
                                </div>


                                <div>
                                    <label for="salary_deduction" class="second-label">2. Has there been any salary
                                        deduction due to the employee's
                                        leave?:</label>
                                    <select class="form-select" aria-label="multiple select example" name="salary_deduction"
                                        id="adr1" required>
                                        <option selected disabled>Rate</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>

                                    <div class="review-block">
                                        <label for="comments_salary_deduction" class="third-label">Tell us more about your
                                            experience:</label>
                                        <textarea name="comments_salary_deduction" id="comments" class="form-control"
                                            rows="1" cols="50" maxlength="255"
                                            placeholder="What made you give this rating? Share specific examples or feedback to help us improve."></textarea>
                                    </div>
                                </div>
                                <div>
                                    <label for="interact_housekeeping" class="second-label">3. How well does the employee
                                        interact with the
                                        housekeeping staff?:</label>
                                    <select class="form-select" aria-label="multiple select example" id="adr1"
                                        name="interact_housekeeping" required>
                                        <option selected disabled>Rate</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>
                                </div>
                                <div class="review-block">
                                    <label for="comments_interact_housekeeping" class="third-label">Tell us more about your
                                        experience:</label>
                                    <textarea name="comments_interact_housekeeping" id="comments" class="form-control"
                                        rows="1" maxlength="255" cols="50"
                                        placeholder="What made you give this rating? Share specific examples or feedback to help us improve."></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-section form-section--altered total-block">
                            <label for="AdminTotalReview">Total Score:</label>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item" name="AdminTotalReview" id="adminTotalReview" readonly></li>
                                <li class="breadcrumb-item">45</li>
                            </ol>
                        </div>

                        <div class="form-section mt-4">
                            <div class="d-flex justify-content-center">
                                <button type="submit" class="btn btn-primary" id="submitBtn">Submit</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </form>



    </body>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>   
        $(function () {
    let timeout = null;

    function searchUser() {
        const keyword = $('#employee_search').val().trim();

        if (keyword.length < 2) {
            $('#employeeDetails').hide();
            $('#selectLabel').hide();
            return;
        }

        $('#employeeDetails').show();
        $('#employeeTableBody').html('<tr><td colspan="4">Searching...</td></tr>');
        $('#selectLabel').hide();

        clearTimeout(timeout);

        timeout = setTimeout(function () {
            $.ajax({
                url: '{{ route("user-search") }}', // Replace with your route
                type: 'GET',
                data: { keyword: keyword },
                success: function (response) {
                    $('#employeeTableBody').empty();

                    if (response.success && response.users.length > 0) {
                        $('#selectLabel').show(); // Show the label

                        response.users.forEach(function (user) {
                            $('#employeeTableBody').append(`
                                <tr class="selectable-row" data-emp-id="${user.employee_id}">
                                    <td>${user.employee_id}</td>
                                    <td>${user.fname} ${user.lname}</td>
                                    <td>${user.designation}</td>
                                    <td>${user.email}</td>
                                </tr>
                            `);
                        });
                    } else {
                        $('#selectLabel').hide(); // Hide if no results
                        $('#employeeTableBody').html('<tr><td colspan="4">No users found</td></tr>');
                    }
                },
                error: function () {
                    alert("An error occurred. Please try again.");
                }
            });
        }, 1000);
    }

    $('#employee_search').on('keyup', searchUser);

    $(document).on('click', '.selectable-row', function () {
        var empId = $(this).data('emp-id');
        $('#emp_id_input').val(empId);

        var selectedRow = $(this).clone().addClass('table-active');
        $('#employeeTableBody').empty().append(selectedRow);

        $('#selectLabel').hide(); // Hide after selection
    });
});





        document.addEventListener("DOMContentLoaded", function () {
            const adminForm = document.getElementById("AdminReviewSubmit");

            if (adminForm) {
                adminForm.addEventListener("submit", function (event) {
                    event.preventDefault();

                    // Calculate total Admin rating
                    let totalRating = 0;
                    document.querySelectorAll("select[id^='adr']").forEach(select => {
                        const value = parseInt(select.value);
                        if (!isNaN(value)) {
                            totalRating += value;
                        }
                    });

                    // Create FormData and append the total rating
                    const formData = new FormData(adminForm);
                    formData.append("AdminTotalReview", totalRating);

                    for (let pair of formData.entries()) {
                        console.log(pair[0] + ": " + pair[1]);
                    }

                    $.ajax({
                        url: "{{ route('admin.review.submit') }}",
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                        },
                        success: function (response) {
                            console.log("Success:", response);
                            alert("✅ " + (response.message || "Admin Review submitted successfully!"));

                            adminForm.reset();

                            const totalDisplay = document.getElementById("adminTotalReview");
                            if (totalDisplay) {
                                totalDisplay.textContent = "";
                            }

                            document.querySelectorAll("select[id^='adr']").forEach(select => {
                                select.selectedIndex = 0;
                            });

                            document.querySelectorAll("textarea").forEach(textarea => {
                                textarea.value = "";
                            });
                            location.reload();
                        },
                        error: function (xhr) {
                            console.error("Error:", xhr.responseJSON);
                            alert("❌ " + (xhr.responseJSON?.message || "Error submitting Admin review."));
                        }
                    });
                });
            }
        });


        // Admin Review total ratting
        document.addEventListener("DOMContentLoaded", function () {
            function adminTotalReview() {
                let totalRating = 0;

                // Loop through all select elements starting with id="cli"
                document.querySelectorAll("select[id^='adr']").forEach(select => {
                    const value = parseInt(select.value);
                    if (!isNaN(value)) {
                        totalRating += value;
                    }
                });

                // Display the total inside a <li> element
                const totalField = document.getElementById("adminTotalReview");
                if (totalField) {
                    totalField.textContent = totalRating;
                }
            }

            // Attach event listeners to each select
            document.querySelectorAll("select[id^='adr']").forEach(select => {
                select.addEventListener("input", adminTotalReview);
            });
        });
    </script>
@endsection