@extends('layouts.app')

@section('title', 'Hr Dashboard')

@section('breadcrumb', 'Hr')

@section('page-title', 'Hr-Review Dashboard')

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
    <form action="{{route('hr.review.submit')}}" method="post" id="HrReviewSubmit" class="form-inline client__form">
        @csrf
        <div class="client">
            <h1 class="client__heading">HR REVIEW</h1>
            <div class="client___item">
                <input type="search" id="employee_search" name="search" class="form-control client__search"
                    placeholder="search employee" aria-label="Search">
                <button class="client__btn" type="submit">
                    <img src="https://modest-gagarin.74-208-156-247.plesk.page/images/search.png" alt="Search">
                </button>
            </div>
            <!-- Hidden Employee ID (if used in scripts) -->
            {{-- <input type="hidden" name="emp_id" id="selectedEmpId"> --}}

            <!-- Financial Year Dropdown -->
            <select id="financialYear" class="form-select client__select" name="financial_year" required>
                <option value="" selected>Financial Year</option>
                <option value="2025-2026">2025-2026</option>
                <option value="2026-2027">2026-2027</option>
                <option value="2027-2028">2027-2028</option>
                <option value="2028-2029">2028-2029</option>
                <option value="2029-2030">2029-2030</option>
            </select>
        </div>

        <!-- Search Results Table -->
        <div class="container mt-5 employee-table" id="employeeDetails" style="display:none; border: 1px solid #ddd;">
            <table class="table table-bordered table-hover client-table">
                <thead>
                    <tr>
                        <th>Employee ID</th>
                        <th>Employee Name</th>
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

        <div class="form-section">

            <div>
                {{-- <div class="d-none"> --}}
                {{-- <div class="text-block"> --}}
                {{-- <label for="employee_id">Employee Id:</label> --}}
                <input type="hidden" id="emp_id_input" name="emp_id" placeholder="Enter Employee Id" required>
                </input>
                {{--
                        </div> --}}
                {{-- </div> --}}

                <div class="accordion">
                    <div class="content-block">
                        <input type="checkbox" id="section1">
                        <label for="section1" class="main-label">A. Professional Conduct and Policy Compliance</label>
                        <div class="content">
                            <label for="adherence_hr" class="second-label">1. How would you rate the employee’s
                                adherence
                                to company policies and
                                procedures?:</label>
                            <!-- <input type="number" name="adherence_hr" id="hr1" min="0" max="5" required
                                            oninput="hrTotalReview()" placeholder="Rate Yourself">
                                        </input> -->
                            <select class="form-select" aria-label="multiple select example" name="adherence_hr"
                                id="hr1" required>
                                <option selected disabled>Rate</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>

                            <div class="review-block">
                                <label for="comments_adherence" class="third-label">Justify Your Review:</label>
                                <textarea name="comments_adherence_hr" id="comments" class="form-control" rows="1"
                                    cols="50" maxlength="255" placeholder="Write your justification here..."></textarea>
                            </div>


                            <div>
                                <label for="professionalism_positive" class="second-label">2. Does the employee
                                    maintain professionalism and a positive
                                    attitude in the workplace?:</label>
                                <!-- <input type="number" name="professionalism_positive" id="hr2" min="0" max="5" required
                                                oninput="hrTotalReview()" placeholder="Rate Yourself">
                                            </input> -->
                                <select class="form-select" aria-label="multiple select example"
                                    name="professionalism_positive" id="hr1" required>
                                    <option selected disabled>Rate</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>

                                <div class="review-block">
                                    <label for="comments_professionalism_positive" class="third-label">Justify Your
                                        Review:</label>
                                    <textarea name="comments_professionalism" id="comments" class="form-control"
                                        rows="1" cols="50" maxlength="255"
                                        placeholder="Write your justification here..."></textarea>
                                </div>
                            </div>
                            <div>
                                <label for="respond_feedback" class="second-label">3. How well does the employee
                                    respond to feedback or
                                    suggestions for improvement from colleagues?:</label>
                                <!-- <input type="number" name="respond_feedback" id="hr3" min="0" max="5" required
                                                    oninput="hrTotalReview()" placeholder="Rate Yourself">
                                                </input> -->
                                <select class="form-select" aria-label="multiple select example" id="hr1"
                                    name="respond_feedback" required>
                                    <option selected disabled>Rate</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                            </div>
                            <div class="review-block">
                                <label for="comments_respond_feedback" class="third-label">Justify Your Review:</label>
                                <textarea name="comments_respond_feedback" id="comments" class="form-control" rows="1"
                                    cols="50" maxlength="255" placeholder="Write your justification here..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="content-block">
                        <input type="checkbox" id="section2">
                        <label for="section2" class="main-label">B. Initiative, Learning Engagement, and Policy
                            Adherence</label>
                        <div class="content">
                            <label for="initiative" class="second-label">1. Does the employee take the initiative to
                                seek
                                feedback and
                                act
                                on it?:</label>
                            <!-- <input type="number" name="initiative" id="hr4" min="0" max="5" required
                                                    oninput="hrTotalReview()" placeholder="Rate Yourself">
                                                </input> -->
                            <select class="form-select" aria-label="multiple select example" id="hr1" name="initiative"
                                required>
                                <option selected disabled>Rate</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>

                            <div class="review-block">
                                <label for="comments_initiative" class="third-label">Justify Your Review:</label>
                                <textarea name="comments_initiative" id="comments" class="form-control" rows="1"
                                    cols="50" maxlength="255" placeholder="Write your justification here..."></textarea>
                            </div>

                            <div>
                                <label for="comfortable_discussing" class="second-label">2. Has the employee shown
                                    interest
                                    in learning and
                                    participating in training programs?</label>
                                <!-- <input type="number" name="interest_learning" id="hr5" min="0" max="5" required
                                                    oninput="hrTotalReview()" placeholder="Rate Yourself">
                                                </input> -->
                                <select class="form-select" aria-label="multiple select example" id="hr1"
                                    name="interest_learning" required>
                                    <option selected disabled>Rate</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                            </div>
                            <div class="review-block">
                                <label for="comments_interest_learning" class="third-label">Justify Your Review:</label>
                                <textarea name="comments_interest_learning" id="comments" class="form-control" rows="1"
                                    cols="50" maxlength="255" placeholder="Write your justification here..."></textarea>
                            </div>


                            <div>
                                <label for="company_leave_policy" class="second-label">3. Does the employee consistently
                                    adhere to the
                                    company's
                                    leave policy?</label>
                                <!-- <input type="number" name="company_leave_policy" id="hr6" min="0" max="5" required
                                                    oninput="hrTotalReview()" placeholder="Rate Yourself">
                                                </input> -->
                                <select class="form-select" aria-label="multiple select example" id="hr1"
                                    name="company_leave_policy" required>
                                    <option selected disabled>Rate</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                            </div>
                            <div class="review-block">
                                <label for="comments_company_leave_policy" class="third-label">Justify Your
                                    Review:</label>
                                <textarea name="comments_company_leave_policy" id="comments" class="form-control"
                                    rows="1" cols="50" maxlength="255"
                                    placeholder="Write your justification here..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-section form-section--altered total-block">
                        <label for="HrTotalReview">Total Score:</label>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item" name="HrTotalReview" id="HrTotalReview" readonly></li>
                            <li class="breadcrumb-item">30</li>
                        </ol>
                        <!-- <input type="text" name="ClientTotalReview" id="clientTotalReview" readonly> -->
                    </div>

                    <div class="form-section mt-4">
                        <div class="d-flex justify-content-center">
                            {{-- <button type="reset" class="btn btn-primary">Cancel</button> --}}
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
                    return;
                }

                $('#employeeDetails').show();
                $('#employeeTableBody').html('<tr><td colspan="4">Searching...</td></tr>');

                clearTimeout(timeout);

                timeout = setTimeout(function () {
                    $.ajax({
                        url: '{{ route("user-search") }}',
                        type: 'GET',
                        data: {
                            keyword: keyword
                        },
                        success: function (response) {
                            $('#employeeTableBody').empty();

                            if (response.success) {
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
                                $('#employeeTableBody').html(
                                    '<tr><td colspan="4">No users found</td></tr>');
                            }
                        },
                        error: function () {
                            alert("An error occurred. Please try again.");
                        }
                    });
                }, 1000);
            }

           $('#employee_search').on('keyup', searchUser);

// Set emp_id on row click
$(document).on('click', '.selectable-row', function () {
    var empId = $(this).data('emp-id');
    $('#emp_id_input').val(empId);

    // Clone the selected row
    var selectedRow = $(this).clone();

    // Add highlight class to the clone
    selectedRow.addClass('table-active');

    // Keep only the selected row in the table
    $('#employeeTableBody').empty().append(selectedRow);
});

        });

        document.addEventListener("DOMContentLoaded", function () {
            const hrForm = document.getElementById("HrReviewSubmit");

            if (hrForm) {
                hrForm.addEventListener("submit", function (event) {
                    event.preventDefault();

                    // Calculate total HR rating
                    let totalRating = 0;
                    document.querySelectorAll("select[id^='hr']").forEach(select => {
                        const value = parseInt(select.value);
                        if (!isNaN(value)) {
                            totalRating += value;
                        }
                    });

                    // Create FormData and append the total rating
                    const formData = new FormData(hrForm);
                    formData.append("HrTotalReview", totalRating);

                    $.ajax({
                        url: "{{ route('hr.review.submit') }}",
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                        },
                        success: function (response) {
                            console.log("Success:", response);
                            alert("✅ " + (response.message || "HR Review submitted successfully!"));

                            hrForm.reset();

                            const totalDisplay = document.getElementById("HrTotalReview");
                            if (totalDisplay) {
                                totalDisplay.textContent = "";
                            }

                            document.querySelectorAll("select[id^='hr']").forEach(select => {
                                select.selectedIndex = 0;
                            });

                            document.querySelectorAll("textarea").forEach(textarea => {
                                textarea.value = "";
                            });
                            location.reload();
                        },
                        error: function (xhr) {
                            console.error("Error:", xhr.responseJSON);
                            alert("❌ " + (xhr.responseJSON?.message || "Error submitting HR review."));
                        }
                    });
                });
            }
        });


        document.addEventListener("DOMContentLoaded", function () {
            function HrTotalReview() {
                let totalRating = 0;

                // Loop through all select elements starting with id="hr"
                document.querySelectorAll("select[id^='hr']").forEach(select => {
                    const value = parseInt(select.value);
                    if (!isNaN(value)) {
                        totalRating += value;
                    }
                });

                console.log("Total Rating:", totalRating); // For debugging

                // Update the total in the breadcrumb
                const totalField = document.getElementById("HrTotalReview");
                if (totalField) {
                    totalField.textContent = totalRating;
                }
            }

            // Attach event listeners to each select element
            document.querySelectorAll("select[id^='hr']").forEach(select => {
                select.addEventListener("input", HrTotalReview);
            });
        });

</script>
@endsection