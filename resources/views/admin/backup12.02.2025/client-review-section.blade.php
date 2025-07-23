@extends('layouts.app')

@section('title', 'Client Review')

@section('breadcrumb', 'Client')

@section('page-title', 'Client Dashboard')

@section('content')

    <style>
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
        <form action="{{ route('client.review.submit') }}" method="post" id="ClientReviewSubmit"
            class="form-inline client__form">
            @csrf
            <div class="client">
                <h1 class="client__heading">CLIENT REVIEW</h1>
                <div class="client___item">
                    <input type="search" id="employee_search" name="search" class="form-control client__search"
                        placeholder="search employee" aria-label="Search">
                    <button class="client__btn" type="submit">
                        <img src="https://modest-gagarin.74-208-156-247.plesk.page/images/search.png" alt="Search">
                    </button>
                </div>

                <select id="financialYear" class="form-select client__select" name="financial_year" required>
                    <option value="" selected>Financial Year</option>
                    <option value="2025-2026">2025-2026</option>
                    <option value="2026-2027">2026-2027</option>
                    <option value="2027-2028">2027-2028</option>
                    <option value="2028-2029">2028-2029</option>
                    <option value="2029-2030">2029-2030</option>
                </select>
            </div>


            <div class="employee-table" id="employeeDetails" style="display:none; border: 1px solid #ddd;">
                <div class="table-wrapper">
                    <!-- <div id="selectLabel" class="hidden-label"
                        style="margin-bottom: 10px; font-weight: bold; display: none;">
                    </div> -->
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
            </div>
            <div class="form-section">

                <div>
                    <input type="hidden" id="emp_id_input" name="emp_id" placeholder="Enter Employee Id" required>
                    </input>
                    <div class="accordion">
                        <div class="content-block">
                            <input type="checkbox" id="section1">
                            <label for="section1" class="main-label">A. Understanding Requirements</label>
                            <div class="content">
                                <label for="understand_requirements" class="second-label">1. Did the developer(s) understand
                                    your project requirements
                                    clearly?:</label>
                                <select class="form-select" aria-label="multiple select example"
                                    name="understand_requirements" id="cli1" required>
                                    <option selected disabled>Rate</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>

                                <div class="review-block">
                                    <label for="comment_understand_requirements" class="third-label">Tell us more about your
                                        experience:</label>
                                    <textarea name="comment_understand_requirements" id="comments" class="form-control"
                                        rows="1" cols="50" maxlength="255"
                                        placeholder="What made you give this rating? Share specific examples or feedback to help us improve."></textarea>
                                </div>


                                <div>
                                    <label for="business_goals_technical_needs" class="second-label">2. Were your business
                                        goals and technical needs
                                        properly translated into the solution?:</label>
                                    <select class="form-select" aria-label="multiple select example" name="business_needs"
                                        id="cli1" required>
                                        <option selected disabled>Rate</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>

                                    <div class="review-block">
                                        <label for="comments_business_needs" class="third-label">Tell us more about your
                                            experience:</label>
                                        <textarea name="comments_business_needs" id="comments" class="form-control" rows="1"
                                            cols="50" maxlength="255"
                                            placeholder="What made you give this rating? Share specific examples or feedback to help us improve."></textarea>
                                    </div>
                                </div>
                                <div>
                                    <label for="detailed_project_scope" class="second-label">3. Was there a clear and
                                        detailed
                                        project scope defined at
                                        the beginning?:</label>
                                    <select class="form-select" aria-label="multiple select example" id="cli1"
                                        name="detailed_project_scope" required>
                                        <option selected disabled>Rate</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>
                                </div>
                                <div class="review-block">
                                    <label for="comments_detailed_project_scope" class="third-label">Tell us more about your
                                        experience:</label>
                                    <textarea name="comments_detailed_project_scope" id="comments" class="form-control"
                                        rows="1" cols="50" maxlength="255"
                                        placeholder="What made you give this rating? Share specific examples or feedback to help us improve."></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="content-block">
                            <input type="checkbox" id="section2">
                            <label for="section2" class="main-label">B. Communication and Collaboration</label>
                            <div class="content">
                                <label for="responsive_reach_project" class="second-label">1. Was the developer(s)
                                    responsive
                                    and easy to reach
                                    during the project?:</label>
                                <select class="form-select" aria-label="multiple select example" id="cli1"
                                    name="responsive_reach_project" required>
                                    <option selected disabled>Rate</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>

                                <div class="review-block">
                                    <label for="comments_responsive_reach_project" class="third-label">Tell us more about
                                        your
                                        experience:</label>
                                    <textarea name="comments_responsive_reach_project" id="comments" class="form-control"
                                        rows="1" cols="50" maxlength="255"
                                        placeholder="What made you give this rating? Share specific examples or feedback to help us improve."></textarea>
                                </div>

                                <div>
                                    <label for="comfortable_discussing" class="second-label">2. Did you feel comfortable
                                        discussing changes or
                                        suggestions?:</label>
                                    <select class="form-select" aria-label="multiple select example" id="cli1"
                                        name="comfortable_discussing" required>
                                        <option selected disabled>Rate</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>
                                </div>
                                <div class="review-block">
                                    <label for="comments_comfortable_discussing" class="third-label">Tell us more about your
                                        experience:</label>
                                    <textarea name="comments_comfortable_discussing" id="comments" class="form-control"
                                        rows="1" cols="50" maxlength="255"
                                        placeholder="What made you give this rating? Share specific examples or feedback to help us improve."></textarea>
                                </div>


                                <div>
                                    <label for="regular_updates" class="second-label">3. Did the developer(s) provide
                                        regular
                                        updates on
                                        progress?:</label>
                                    <select class="form-select" aria-label="multiple select example" id="cli1"
                                        name="regular_updates" required>
                                        <option selected disabled>Rate</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>
                                </div>
                                <div class="review-block">
                                    <label for="comments_regular_updates" class="third-label">Tell us more about your
                                        experience:</label>
                                    <textarea name="comments_regular_updates" id="comments" class="form-control" rows="1"
                                        cols="50" maxlength="255"
                                        placeholder="What made you give this rating? Share specific examples or feedback to help us improve."></textarea>
                                </div>

                                <div>
                                    <label for="concerns_addressed" class="second-label">4. Were your questions and concerns
                                        addressed
                                        promptly?:</label>
                                    <select class="form-select" aria-label="multiple select example" id="cli1"
                                        name="concerns_addressed" required>
                                        <option selected disabled>Rate</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>
                                </div>
                                <div class="review-block">
                                    <label for="comments_concerns_addressed" class="third-label">Tell us more about your
                                        experience:</label>
                                    <textarea name="comments_concerns_addressed" id="comments" class="form-control" rows="1"
                                        cols="50" maxlength="255"
                                        placeholder="What made you give this rating? Share specific examples or feedback to help us improve."></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="content-block">
                            <input type="checkbox" id="section3">
                            <label for="section3" class="main-label">C. Technical Proficiency</label>
                            <div class="content">
                                <label for="technical_expertise" class="second-label">1. How would you rate the technical
                                    expertise of the
                                    developer(s)?:</label>
                                <select class="form-select" aria-label="multiple select example" id="cli1"
                                    name="technical_expertise" required>
                                    <option selected disabled>Rate</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>

                                <div class="review-block">
                                    <label for="comments_technical_expertise" class="third-label">Tell us more about your
                                        experience:</label>
                                    <textarea name="comments_technical_expertise" id="comments" class="form-control"
                                        rows="1" cols="50" maxlength="255"
                                        placeholder="What made you give this rating? Share specific examples or feedback to help us improve."></textarea>
                                </div>


                                <div>
                                    <label for="best_practices" class="second-label">2. Were industry best practices
                                        followed
                                        during the development
                                        process?:</label>
                                    <select class="form-select" aria-label="multiple select example" id="cli1"
                                        name="best_practices" required>
                                        <option selected disabled>Rate</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>
                                </div>
                                <div class="review-block">
                                    <label for="comments_best_practices" class="third-label">Tell us more about your
                                        experience:</label>
                                    <textarea name="comments_best_practices" id="comments" class="form-control" rows="1"
                                        cols="50" maxlength="255"
                                        placeholder="What made you give this rating? Share specific examples or feedback to help us improve."></textarea>
                                </div>

                                <div>
                                    <label for="suggest_innovative" class="second-label">3. Did the developer(s) suggest
                                        innovative solutions or
                                        improvements?:</label>
                                    <select class="form-select" aria-label="multiple select example" id="cli1"
                                        name="suggest_innovative" required>
                                        <option selected disabled>Rate</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>
                                </div>
                                <div class="review-block">
                                    <label for="comments_suggest_innovative" class="third-label">Tell us more about your
                                        experience:</label>
                                    <textarea name="comments_suggest_innovative" id="comments" class="form-control" rows="1"
                                        cols="50" maxlength="255"
                                        placeholder="What made you give this rating? Share specific examples or feedback to help us improve."></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="content-block">
                            <input type="checkbox" id="section4">
                            <label for="section4" class="main-label" class="second-label">D. Code Quality and
                                Performance</label>
                            <div class="content">
                                <label for="quality_code" class="second-label">1. How would you rate the quality of the code
                                    delivered?:</label>
                                <select class="form-select" aria-label="multiple select example" id="cli1"
                                    name="quality_code" required>
                                    <option selected disabled>Rate</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>

                                <div class="review-block">
                                    <label for="comments_quality_code" class="third-label">Tell us more about your
                                        experience:</label>
                                    <textarea name="comments_quality_code" id="comments" class="form-control" rows="1"
                                        cols="50" maxlength="255"
                                        placeholder="What made you give this rating? Share specific examples or feedback to help us improve."></textarea>
                                </div>

                                <div>
                                    <label for="encounter_issues" class="second-label">2. Did you encounter any bugs or
                                        issues
                                        post-launch?:</label>
                                    <select class="form-select" aria-label="multiple select example" id="cli1"
                                        name="encounter_issues" required>
                                        <option selected disabled>Rate</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>
                                </div>
                                <div class="review-block">
                                    <label for="comments_encounter_issues" class="third-label">Tell us more about your
                                        experience:</label>
                                    <textarea name="comments_encounter_issues" id="comments" class="form-control" rows="1"
                                        cols="50" maxlength="255"
                                        placeholder="What made you give this rating? Share specific examples or feedback to help us improve."></textarea>
                                </div>

                                <div>
                                    <label for="code_scalable" class="second-label">3. Was the code scalable and
                                        well-structured for future
                                        updates?:</label>
                                    <select class="form-select" aria-label="multiple select example" id="cli1"
                                        name="code_scalable" required>
                                        <option selected disabled>Rate</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>
                                </div>
                                <div class="review-block">
                                    <label for="comments_code_scalable" class="third-label">Tell us more about your
                                        experience:</label>
                                    <textarea name="comments_code_scalable" id="comments" class="form-control" rows="1"
                                        cols="50" maxlength="255"
                                        placeholder="What made you give this rating? Share specific examples or feedback to help us improve."></textarea>
                                </div>

                                <div>
                                    <label for="solution_perform" class="second-label">4. Did the solution perform well
                                        under
                                        expected load and
                                        conditions?:</label>
                                    <select class="form-select" aria-label="multiple select example" id="cli1"
                                        name="solution_perform" required>
                                        <option selected disabled>Rate</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>
                                </div>
                                <div class="review-block">
                                    <label for="comments_solution_perform" class="third-label">Tell us more about your
                                        experience:</label>
                                    <textarea name="comments_solution_perform" id="comments" class="form-control" rows="1"
                                        cols="50" maxlength="255"
                                        placeholder="What made you give this rating? Share specific examples or feedback to help us improve."></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="content-block">
                            <input type="checkbox" id="section5">
                            <label for="section5" class="main-label">E. Timeliness and Project Management</label>
                            <div class="content">
                                <label for="project_delivered" class="second-label">1. Was the project delivered on
                                    time?:</label>
                                <select class="form-select" aria-label="multiple select example" id="cli1"
                                    name="project_delivered" required>
                                    <option selected disabled>Rate</option>
                                    <option value="1">1</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                                <div class="review-block">
                                    <label for="comments_project_delivered" class="third-label">Tell us more about your
                                        experience:</label>
                                    <textarea name="comments_project_delivered" id="comments" class="form-control" rows="1"
                                        cols="50" maxlength="255"
                                        placeholder="What made you give this rating? Share specific examples or feedback to help us improve."></textarea>
                                </div>

                                <div>
                                    <label for="communicated_handled" class="second-label">2. If there were delays, were
                                        they
                                        communicated and handled
                                        effectively?:</label>
                                    <select class="form-select" aria-label="multiple select example" id="cli1"
                                        name="communicated_handled" required>
                                        <option selected disabled>Rate</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>
                                </div>
                                <div class="review-block">
                                    <label for="comments_communicated_handled" class="third-label">Tell us more about your
                                        experience:</label>
                                    <textarea name="comments_communicated_handled" id="comments" class="form-control"
                                        rows="1" cols="50" maxlength="255"
                                        placeholder="What made you give this rating? Share specific examples or feedback to help us improve."></textarea>
                                </div>

                                <div>
                                    <label for="development_process" class="second-label">3. Was the development process
                                        well-organized and
                                        structured?:</label>
                                    <select class="form-select" aria-label="multiple select example" id="cli1"
                                        name="development_process" required>
                                        <option selected disabled>Rate</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>
                                </div>
                                <div class="review-block">
                                    <label for="comments_development_process" class="third-label">Tell us more about your
                                        experience:</label>
                                    <textarea name="comments_development_process" id="comments" class="form-control"
                                        rows="1" cols="50" maxlength="255"
                                        placeholder="What made you give this rating? Share specific examples or feedback to help us improve."></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="content-block">
                            <input type="checkbox" id="section6">
                            <label for="section6" class="main-label">F. Problem-Solving and Flexibility</label>
                            <div class="content">
                                <label for="unexpected_challenges" class="second-label">1. How well did the developer(s)
                                    handle
                                    unexpected
                                    challenges or changes?:</label>
                                <select class="form-select" aria-label="multiple select example" id="cli1"
                                    name="unexpected_challenges" required>
                                    <option selected disabled>Rate</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                                <div class="review-block">
                                    <label for="comments_unexpected_challenges" class="third-label">Tell us more about your
                                        experience:</label>
                                    <textarea name="comments_unexpected_challenges" id="comments" class="form-control"
                                        rows="1" cols="50" maxlength="255"
                                        placeholder="What made you give this rating? Share specific examples or feedback to help us improve."></textarea>
                                </div>

                                <div>
                                    <label for="effective_workarounds" class="second-label">2. Did the developer(s) propose
                                        effective workarounds when
                                        issues arose?:</label>
                                    <select class="form-select" aria-label="multiple select example" id="cli1"
                                        name="effective_workarounds" required>
                                        <option selected disabled>Rate</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>
                                </div>
                                <div class="review-block">
                                    <label for="comments_effective_workarounds" class="third-label">Tell us more about your
                                        experience:</label>
                                    <textarea name="comments_effective_workarounds" id="comments" class="form-control"
                                        rows="1" cols="50" maxlength="255"
                                        placeholder="What made you give this rating? Share specific examples or feedback to help us improve."></textarea>
                                </div>

                                <div>
                                    <label for="bugs_issues" class="second-label">3. How quickly were bugs or issues
                                        resolved
                                        during the
                                        project?:</label>
                                    <select class="form-select" aria-label="multiple select example" id="cli1"
                                        name="bugs_issues" required>
                                        <option selected disabled>Rate</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>
                                </div>
                                <div class="review-block">
                                    <label for="comments_bugs_issues" class="third-label">Tell us more about your
                                        experience:</label>
                                    <textarea name="comments_bugs_issues" id="comments" class="form-control" rows="1"
                                        cols="50" maxlength="255"
                                        placeholder="What made you give this rating? Share specific examples or feedback to help us improve."></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-section form-section--altered total-block">
                            <label for="ClientTotalReview">Total Score:</label>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item" name="ClientTotalReview" id="clientTotalReview" readonly></li>
                                <li class="breadcrumb-item">100</li>
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
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(function () {
            let timeout = null;

            function searchUser() {
                const keyword = $('#employee_search').val().trim();

                if (keyword.length < 2) {
                    $('#employeeDetails').hide();
                    $('#selectLabel').hide(); // Hide label if input too short
                    return;
                }

                $('#employeeDetails').show();
                $('#employeeTableBody').html('<tr><td colspan="4">Searching...</td></tr>');
                $('#selectLabel').hide(); // Hide while searching

                clearTimeout(timeout);

                timeout = setTimeout(function () {
                    $.ajax({
                        url: '{{ route("client-search") }}', // ← your client search route
                        type: 'GET',
                        data: { keyword: keyword },
                        success: function (response) {
                            $('#employeeTableBody').empty();

                            if (response.success && response.users.length > 0) {
                                $('#selectLabel').show(); // Show label if clients found

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
                                $('#selectLabel').hide(); // Hide label if no users
                                $('#employeeTableBody').html(
                                    '<tr><td colspan="4">No users found</td></tr>'
                                );
                            }
                        },
                        error: function () {
                            alert("An error occurred. Please try again.");
                        }
                    });
                }, 1000); // debounce
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
        document.getElementById("ClientReviewSubmit").addEventListener("submit", function (event) {
            event.preventDefault();

            let form = this;
            let formData = new FormData(form);


            let totalRating = document.getElementById("clientTotalReview").textContent.trim();


            formData.append("ClientTotalReview", totalRating);

            $.ajax({
                url: "{{ route('client.review.submit') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name=\"csrf-token\"]').getAttribute("content")
                },
                success: function (response) {
                    console.log("Success:", response);
                    alert("✅ " + (response.message || "Review submitted successfully!"));

                    form.reset();
                    const totalDisplay = document.getElementById("clientTotalReview");
                    if (totalDisplay) {
                        totalDisplay.textContent = "";
                    }

                    document.querySelectorAll("select[id^='cli']").forEach(select => {
                        select.selectedIndex = 0;
                    });

                    document.querySelectorAll("textarea").forEach(textarea => {
                        textarea.value = "";
                    });
                    location.reload();
                },
                error: function (xhr) {
                    console.log("XHR Response:", xhr.responseText);

                    try {
                        const errorData = JSON.parse(xhr.responseText);

                        if (xhr.status === 403) {
                            alert("⛔ " + (errorData.message || "This User has no Client."));
                        } else if (xhr.status === 404) {
                            alert("🔍 " + (errorData.message || "Employee not found."));
                        } else if (xhr.status === 409) {
                            alert("⚠️ " + (errorData.message ||
                                "You already submitted a review for this employee."));
                        } else {
                            alert("❌ " + (errorData.message || "Something went wrong! Please try again."));
                        }
                    } catch (e) {
                        alert("⚠️ Unexpected error occurred.");
                        console.error("Parsing error:", e);
                    }
                }
            });
        });


        // Admin Review total ratting
        document.addEventListener("DOMContentLoaded", function () {
            function clientTotalReview() {
                let totalRating = 0;

                // Loop through all select elements starting with id="cli"
                document.querySelectorAll("select[id^='cli']").forEach(select => {
                    const value = parseInt(select.value);
                    if (!isNaN(value)) {
                        totalRating += value;
                    }
                });

                // Display the total inside a <li> element
                const totalField = document.getElementById("clientTotalReview");
                if (totalField) {
                    totalField.textContent = totalRating;
                }
            }

            // Attach event listeners to each select
            document.querySelectorAll("select[id^='cli']").forEach(select => {
                select.addEventListener("input", clientTotalReview);
            });
        });
    </script>
@endsection