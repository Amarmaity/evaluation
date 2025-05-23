@extends('layouts.app')
<!-- Extends app.blade.php (Header, Sidebar, Footer included) -->

@section('title', 'Manager Dashboard')
<!-- Page Title -->

@section('breadcrumb', 'Manager')
<!-- Breadcrumb -->

@section('page-title', 'Manager Dashboard')
<!-- Page Title in Breadcrumb -->

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
    <form action="{{route('manager.review.submit')}}" method="post" id="ManagerReviewSubmit">
        @csrf
        <div class="client">
            <h1 class="client__heading">MANAGER REVIEW</h1>
            <div class="client___item">
                <input type="search" id="employee_search" name="search" class="form-control client__search"
                    placeholder="search employee" aria-label="Search">
                <button class="client__btn" type="submit">
                    <img src="https://modest-gagarin.74-208-156-247.plesk.page/images/search.png" alt="Search">
                </button>
            </div>
            {{-- <input type="hidden" name="emp_id" id="selectedEmpId"> --}}


            <select id="financialYear" class="form-select client__select" name="financial_year" required>
                <option value="" selected>Financial Year</option>
                <option value="2025-2026">2025-2026</option>
                <option value="2026-2027">2026-2027</option>
                <option value="2027-2028">2027-2028</option>
                <option value="2028-2029">2028-2029</option>
                <option value="2029-2030">2029-2030</option>
            </select>
        </div>
        <div class="container table-container" id="employeeDetails" style="display:none;">
            <div class="table-wrapper">
                <table class="table  table-bordered table-hover client-table">
                    <thead>
                        <tr>
                            <th>Employee Name</th>
                            <th>Employee ID</th>
                            <th>Designation</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody id="employeeTableBody">
                    </tbody>
                </table>
            </div>
        </div>
        </div>

        <!-- Form Section -->
        <div class="form-section accordion">
            <div class="container">
                {{-- <label for="employee_id">Employee Id:</label> --}}
                <input type="hidden" name="emp_id" id="emp_id_input" placeholder="Enter Employee Id" required>
                </input>
                <div class="content-block">
                    <input type="checkbox" name="" id="content8">
                    <label for="content8" class="main-label">A. Understanding Requirements</label>
                    <div class="content">
                        <label for="employee_work_quality" class="second-label">1. How would you rate the employee’s
                            quality
                            of
                            work, including
                            accuracy, neatness, and timeliness?:</label>
                        <!-- <input type="number" name="rate_employee_quality" id="mrs1" min="0" max="5" required
                                                oninput="managerTotalReview()" placeholder="Rate Yourself">
                                              </input> -->
                        <select class="form-select" aria-label="multiple select example" name="rate_employee_quality"
                            id="mrs" required>
                            <option selected disabled>Rate</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>

                        <div class="review-block">
                            <label for="comments_employee_work_quality" class="third-label">Tell us more about your
                                experience:</label>
                            <textarea name="comments_rate_employee_quality" id="comments" class="form-control" rows="1"
                                cols="50" maxlength="255"
                                placeholder="What made you give this rating? Share specific examples or feedback to help us improve."></textarea>
                        </div>
                        <label for="organizational_goals" class="second-label">2. Does the employee align their work
                            with
                            the
                            organization's
                            goals and objectives?:</label>
                        <!-- <input type="number" name="organizational_goals" id="mrs2" min="0" max="5" required
                                                    oninput="managerTotalReview()" placeholder="Rate Yourself">
                                                </input> -->
                        <select class="form-select" aria-label="multiple select example" name="organizational_goals"
                            id="mrs" required>
                            <option selected disabled>Rate</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>

                        <div class="review-block">
                            <label for="comments_organizational_goals" class="third-label">Tell us more about your
                                experience:</label>
                            <textarea name="comments_organizational_goals" id="comments" class="form-control" rows="1"
                                cols="50" maxlength="255"
                                placeholder="What made you give this rating? Share specific examples or feedback to help us improve."></textarea>
                        </div>

                        <div>
                            <label for="collaborate_colleagues" class="second-label">3. How effectively does the
                                employee
                                contribute to team
                                efforts and collaborate with colleagues?:</label>
                            <!-- <input type="number" name="collaborate_colleagues" id="mrs3" min="0" max="5" required
                                                        oninput="managerTotalReview()" placeholder="Rate Yourself">
                                                    </input> -->
                            <select class="form-select" aria-label="multiple select example"
                                name="collaborate_colleagues" id="mrs" required>
                                <option selected disabled>Rate</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </div>
                        <div class="review-block">
                            <label for="comments_collaborate_colleagues" class="third-label">Tell us more about your
                                experience:</label>
                            <textarea name="comments_collaborate_colleagues" id="comments" class="form-control" rows="1"
                                cols="50" maxlength="255"
                                placeholder="What made you give this rating? Share specific examples or feedback to help us improve."></textarea>
                        </div>

                        <div>
                            <label for="demonstrated" class="second-label">4. Can you provide an example of when the
                                employee
                                demonstrated
                                problem-solving skills?:</label>
                            <!-- <input type="number" name="demonstrated" id="mrs4" min="0" max="5" required
                                                        oninput="managerTotalReview()" placeholder="Rate Yourself">
                                                    </input> -->
                            <select class="form-select" aria-label="multiple select example" name="demonstrated"
                                id="mrs" required>
                                <option selected disabled>Rate</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </div>
                        <div class="review-block">
                            <label for="comments_demonstrated" class="third-label">Tell us more about your
                                experience:</label>
                            <textarea name="comments_demonstrated" id="comments" class="form-control" rows="1" cols="50"
                                maxlength="255"
                                placeholder="What made you give this rating? Share specific examples or feedback to help us improve."></textarea>
                        </div>

                        <div>
                            <label for="leadership_responsibilities" class="second-label">5. Has the employee shown
                                leadership
                                potential or
                                accepted additional responsibilities?:</label>
                            <!-- <input type="number" name="leadership_responsibilities" id="mrs5" min="0" max="5" required
                                                        oninput="managerTotalReview()" placeholder="Rate Yourself">
                                                    </input> -->
                            <select class="form-select" aria-label="multiple select example"
                                name="leadership_responsibilities" id="mrs" required>
                                <option selected disabled>Rate</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </div>
                        <div class="review-block">
                            <label for="comments_leadership_responsibilities" class="third-label">Tell us more about
                                your
                                experience:</label>
                            <textarea name="comments_leadership_responsibilities" id="comments" class="form-control"
                                rows="1" cols="50" maxlength="255"
                                placeholder="What made you give this rating? Share specific examples or feedback to help us improve."></textarea>
                        </div>

                        <div>
                            <label for="thinking_contribution" class="second-label">6. How would you rate the employee’s
                                innovative
                                thinking
                                and contribution to team success?:</label>
                            <!-- <input type="number" name="thinking_contribution" id="mrs6" min="0" max="5" required
                                                        oninput="managerTotalReview()" placeholder="Rate Yourself">
                                                    </input> -->
                            <select class="form-select" aria-label="multiple select example"
                                name="thinking_contribution" id="mrs" required>
                                <option selected disabled>Rate</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </div>
                        <div class="review-block">
                            <label for="comments_thinking_contribution" class="third-label">Tell us more about your
                                experience:</label>
                            <textarea name="comments_thinking_contribution" id="comments" class="form-control" rows="1"
                                cols="50" maxlength="255"
                                placeholder="What made you give this rating? Share specific examples or feedback to help us improve."></textarea>
                        </div>

                        <div>
                            <label for="informed_progress" class="second-label">7. Does the employee effectively keep
                                you
                                informed
                                about
                                work progress and issues?:</label>
                            <!-- <input type="number" name="informed_progress" id="mrs7" min="0" max="5" required
                                                                oninput="managerTotalReview()" placeholder="Rate Yourself">
                                                            </input> -->
                            <select class="form-select" aria-label="multiple select example" name="informed_progress"
                                id="mrs1" required>
                                <option selected disabled>Rate</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </div>
                        <div class="review-block">
                            <label for="comments_informed_progress" class="third-label">Tell us more about your
                                experience:</label>
                            <textarea name="comments_comments_informed_progress" id="comments" class="form-control"
                                rows="1" cols="50" maxlength="255"
                                placeholder="What made you give this rating? Share specific examples or feedback to help us improve."></textarea>
                        </div>
                    </div>
                </div>
                {{-- <div class="form-section form-section--altered total-block">
                    <label for="ManagerTotalReview">Total Review:</label>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <input type="text" name="ManagerTotalReview" id="managerTotalReview" readonly>
                        <li class="breadcrumb-item">100</li>
                        </li>
                    </ol>
                    <!-- <input type="text" name="ClientTotalReview" id="clientTotalReview" readonly> -->
                </div> --}}
                <div class="form-section form-section--altered total-block">
                    <label for="ClientTotalReview">Total Score:</label>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item" name="ManagerTotalReview" id="managerTotalReview" readonly></li>
                        <li class="breadcrumb-item">35</li>
                    </ol>
                    <!-- <input type="text" name="ClientTotalReview" id="clientTotalReview" readonly> -->
                </div>
                <div class="d-flex justify-content-center">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <button type="reset" class="btn btn-outlined d-none">Clear</button>
                </div>
            </div>
        </div>
    </form>
</body>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(function() {
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

        timeout = setTimeout(function() {
            $.ajax({
                url: '{{ route("user-search") }}',
                type: 'GET',
                data: {
                    keyword: keyword
                },
                success: function(response) {
                    $('#employeeTableBody').empty();

                    if (response.success) {
                        response.users.forEach(function(user) {
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
                error: function() {
                    alert("An error occurred. Please try again.");
                }
            });
        }, 1000);
    }

    $('#employee_search').on('keyup', searchUser);

    // Set emp_id on row click
    $(document).on('click', '.selectable-row', function() {
        var empId = $(this).data('emp-id');
        $('#emp_id_input').val(empId);

        // Keep only the selected row in the table
        var selectedRow = $(this).clone();

         // Add highlight class to the clone
    selectedRow.addClass('table-active');

        $('#employeeTableBody').empty().append(selectedRow);
    });
});

document.getElementById("ManagerReviewSubmit").addEventListener("submit", function(event) {
    event.preventDefault();

    let form = this;
    let formData = new FormData(form);

    // Get manager's total rating
    let totalRating = document.getElementById("managerTotalReview").textContent.trim();
    formData.append("ManagerTotalReview", totalRating);

    $.ajax({
        url: "{{ route('manager.review.submit') }}",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name=\"csrf-token\"]').getAttribute("content")
        },
        success: function(response) {
            console.log("Success:", response);
            alert("✅ " + (response.message || "Review submitted successfully!"));

            form.reset();

            // Reset manager total score display
            const totalDisplay = document.getElementById("managerTotalReview");
            if (totalDisplay) {
                totalDisplay.textContent = "";
            }

            // Reset all select inputs starting with id="mrs"
            document.querySelectorAll("select[id^='mrs']").forEach(select => {
                select.selectedIndex = 0;
            });

            // Clear all textarea inputs
            document.querySelectorAll("textarea").forEach(textarea => {
                textarea.value = "";
            });
            location.reload();
        },
        error: function(xhr, status, error) {
            console.error("Error:", xhr.responseText);
            alert("❌ An error occurred while submitting the review.");
        }
    });
});
document.addEventListener("DOMContentLoaded", function() {
    function managerTotalReview() {
        let totalRating = 0;

        // Loop through all select elements starting with id="cli"
        document.querySelectorAll("select[id^='mrs']").forEach(select => {
            const value = parseInt(select.value);
            if (!isNaN(value)) {
                totalRating += value;
            }
        });

        // Display the total inside a <li> element
        const totalField = document.getElementById("managerTotalReview");
        if (totalField) {
            totalField.textContent = totalRating;
        }
    }

    // Attach event listeners to each select
    document.querySelectorAll("select[id^='mrs']").forEach(select => {
        select.addEventListener("input", managerTotalReview);
    });
});
</script>
@endsection