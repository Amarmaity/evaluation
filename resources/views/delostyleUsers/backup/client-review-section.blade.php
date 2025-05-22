@extends('layouts.app') <!-- Extends app.blade.php (Header, Sidebar, Footer included) -->

@section('title', 'Client Dashboard') <!-- Page Title -->

@section('breadcrumb', 'Client') <!-- Breadcrumb -->

@section('page-title', 'Client Dashboard') <!-- Page Title in Breadcrumb -->

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
            <h1>CLIENT REVIEW</h1>
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
        <!-- Display Search Results -->
        <div class="container mt-5" id="employeeDetails" style="display:none; padding: 10px; border: 1px solid #ddd;">
            <h3>Employee Details</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Employee ID</th>
                        <th>Employee Name</th>
                        <th>Designation</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody id="employeeTableBody">
                    <!-- Search results will be inserted here -->
                </tbody>
            </table>
        </div>



        <div class="container">
            <form action="{{route('client.review.submit')}}" method="post" id="ClientReviewSubmit">
                @csrf

                <div class="form-section">

                    <div>
                        <label for="employee_id">Employee Id:</label>
                        <input type="text" name="emp_id" placeholder="Enter Employee Id" required>
                        </input>

                        <div>
                            <h4>Understanding Requirements</h4>
                            <label for="understand_requirements">Did the developer(s) understand your project requirements
                                clearly?:</label>
                            <input type="number" name="understand_requirements" id="cli1" min="0" max="5" required
                                oninput="ClientTotalReview()" placeholder="Rate Yourself">
                            </input>

                            <div class="mt-3"> <!-- Added margin-top for spacing -->
                                <label for="comment_understand_requirements">Justify Your Review:</label>
                                <textarea name="comment_understand_requirements" id="comments" class="form-control" rows="5"
                                    cols="50" placeholder="Write your justification here..." required></textarea>
                            </div>


                            <div>
                                <label for="business_goals_technical_needs"> Were your business goals and technical needs
                                    properly translated into the solution?:</label>
                                <input type="number" name="business_needs" id="cli2" min="0" max="5" required
                                    oninput="ClientTotalReview()" placeholder="Rate Yourself">
                                </input>

                                <div class="mt-3"> <!-- Added margin-top for spacing -->
                                    <label for="comments_business_needs">Justify Your Review:</label>
                                    <textarea name="comments_business_needs" id="comments" class="form-control" rows="5"
                                        cols="50" placeholder="Write your justification here..." required></textarea>
                                </div>
                            </div>
                            <div>
                                <label for="detailed_project_scope">Was there a clear and detailed project scope defined at
                                    the beginning?:</label>
                                <input type="number" name="detailed_project_scope" id="cli3" min="0" max="5" required
                                    oninput="ClientTotalReview()" placeholder="Rate Yourself">
                                </input>
                            </div>
                            <div class="mt-3"> <!-- Added margin-top for spacing -->
                                <label for="comments_detailed_project_scope">Justify Your Review:</label>
                                <textarea name="comments_detailed_project_scope" id="comments" class="form-control" rows="5"
                                    cols="50" placeholder="Write your justification here..." required></textarea>
                            </div>

                            <div>
                                <h3>Communication and Collaboration</h3>
                                <label for="responsive_reach_project">Was the developer(s) responsive and easy to reach
                                    during the project?:</label>
                                <input type="number" name="responsive_reach_project" id="cli4" min="0" max="5" required
                                    oninput="ClientTotalReview()" placeholder="Rate Yourself">
                                </input>
                            </div>
                            <div class="mt-3"> <!-- Added margin-top for spacing -->
                                <label for="comments_responsive_reach_project">Justify Your Review:</label>
                                <textarea name="comments_responsive_reach_project" id="comments" class="form-control"
                                    rows="5" cols="50" placeholder="Write your justification here..." required></textarea>
                            </div>

                            <div>
                                <label for="comfortable_discussing">Did you feel comfortable discussing changes or
                                    suggestions?:</label>
                                <input type="number" name="comfortable_discussing" id="cli5" min="0" max="5" required
                                    oninput="ClientTotalReview()" placeholder="Rate Yourself">
                                </input>
                            </div>
                            <div class="mt-3"> <!-- Added margin-top for spacing -->
                                <label for="comments_comfortable_discussing">Justify Your Review:</label>
                                <textarea name="comments_comfortable_discussing" id="comments" class="form-control" rows="5"
                                    cols="50" placeholder="Write your justification here..."required></textarea>
                            </div>


                            <div>
                                <label for="regular_updates">Did the developer(s) provide regular updates on
                                    progress?:</label>
                                <input type="number" name="regular_updates" id="cli6" min="0" max="5" required
                                    oninput="ClientTotalReview()" placeholder="Rate Yourself">
                                </input>
                            </div>
                            <div class="mt-3"> <!-- Added margin-top for spacing -->
                                <label for="comments_regular_updates">Justify Your Review:</label>
                                <textarea name="comments_regular_updates" id="comments" class="form-control" rows="5"
                                    cols="50" placeholder="Write your justification here..." required></textarea>
                            </div>

                            <div>
                                <label for="concerns_addressed">Were your questions and concerns addressed
                                    promptly?:</label>
                                <input type="number" name="concerns_addressed" id="cli7" min="0" max="5" required
                                    oninput="ClientTotalReview()" placeholder="Rate Yourself">
                                </input>
                            </div>
                            <div class="mt-3"> <!-- Added margin-top for spacing -->
                                <label for="comments_concerns_addressed">Justify Your Review:</label>
                                <textarea name="comments_concerns_addressed" id="comments" class="form-control" rows="5"
                                    cols="50" placeholder="Write your justification here..." required></textarea>
                            </div>

                            <div>
                                <h3>Technical Proficiency</h3>
                                <label for="technical_expertise">How would you rate the technical expertise of the
                                    developer(s)?:</label>
                                <input type="number" name="technical_expertise" id="cli8" min="0" max="5" required
                                    oninput="ClientTotalReview()" placeholder="Rate Yourself">
                                </input>
                            </div>
                            <div class="mt-3"> <!-- Added margin-top for spacing -->
                                <label for="comments_technical_expertise">Justify Your Review:</label>
                                <textarea name="comments_technical_expertise" id="comments" class="form-control" rows="5"
                                    cols="50" placeholder="Write your justification here..." required></textarea>
                            </div>


                            <div>
                                <label for="best_practices">Were industry best practices followed during the development
                                    process?:</label>
                                <input type="number" name="best_practices" id="cli9" min="0" max="5" required
                                    oninput="ClientTotalReview()" placeholder="Rate Yourself">
                                </input>
                            </div>
                            <div class="mt-3"> <!-- Added margin-top for spacing -->
                                <label for="comments_best_practices">Justify Your Review:</label>
                                <textarea name="comments_best_practices" id="comments" class="form-control" rows="5"
                                    cols="50" placeholder="Write your justification here..." required></textarea>
                            </div>

                            <div>
                                <label for="suggest_innovative">Did the developer(s) suggest innovative solutions or
                                    improvements?:</label>
                                <input type="number" name="suggest_innovative" id="cli20" min="0" max="5" required
                                    oninput="ClientTotalReview()" placeholder="Rate Yourself">
                                </input>
                            </div>
                            <div class="mt-3"> <!-- Added margin-top for spacing -->
                                <label for="comments_suggest_innovative">Justify Your Review:</label>
                                <textarea name="comments_suggest_innovative" id="comments" class="form-control" rows="5"
                                    cols="50" placeholder="Write your justification here..." required></textarea>
                            </div>

                            <div>
                                <h3> Code Quality and Performance</h3>
                                <label for="quality_code">How would you rate the quality of the code delivered?:</label>
                                <input type="number" name="quality_code" id="cli10" min="0" max="5" required
                                    oninput="ClientTotalReview()" placeholder="Rate Yourself">
                                </input>
                            </div>
                            <div class="mt-3"> <!-- Added margin-top for spacing -->
                                <label for="comments_quality_code">Justify Your Review:</label>
                                <textarea name="comments_quality_code" id="comments" class="form-control" rows="5" cols="50"
                                    placeholder="Write your justification here..." required></textarea>
                            </div>

                            <div>
                                <label for="encounter_issues">Did you encounter any bugs or issues post-launch?:</label>
                                <input type="number" name="encounter_issues" id="cli11" min="0" max="5" required
                                    oninput="ClientTotalReview()" placeholder="Rate Yourself">
                                </input>
                            </div>
                            <div class="mt-3"> <!-- Added margin-top for spacing -->
                                <label for="comments_encounter_issues">Justify Your Review:</label>
                                <textarea name="comments_encounter_issues" id="comments" class="form-control" rows="5"
                                    cols="50" placeholder="Write your justification here..." required></textarea>
                            </div>

                            <div>
                                <label for="code_scalable">Was the code scalable and well-structured for future
                                    updates?:</label>
                                <input type="number" name="code_scalable" id="cli12" min="0" max="5" required
                                    oninput="ClientTotalReview()" placeholder="Rate Yourself">
                                </input>
                            </div>
                            <div class="mt-3"> <!-- Added margin-top for spacing -->
                                <label for="comments_code_scalable">Justify Your Review:</label>
                                <textarea name="comments_code_scalable" id="comments" class="form-control" rows="5"
                                    cols="50" placeholder="Write your justification here..." required></textarea>
                            </div>

                            <div>
                                <label for="solution_perform">Did the solution perform well under expected load and
                                    conditions?:</label>
                                <input type="number" name="solution_perform" id="cli13" min="0" max="5" required
                                    oninput="ClientTotalReview()" placeholder="Rate Yourself">
                                </input>
                            </div>
                            <div class="mt-3"> <!-- Added margin-top for spacing -->
                                <label for="comments_solution_perform">Justify Your Review:</label>
                                <textarea name="comments_solution_perform" id="comments" class="form-control" rows="5"
                                    cols="50" placeholder="Write your justification here..." required></textarea>
                            </div>

                            <div>
                                <h3>Timeliness and Project Management</h3>
                                <label for="project_delivered">Was the project delivered on time?:</label>
                                <input type="number" name="project_delivered" id="cli14" min="0" max="5" required
                                    oninput="ClientTotalReview()" placeholder="Rate Yourself">
                                </input>
                            </div>
                            <div class="mt-3"> <!-- Added margin-top for spacing -->
                                <label for="comments_project_delivered">Justify Your Review:</label>
                                <textarea name="comments_project_delivered" id="comments" class="form-control" rows="5"
                                    cols="50" placeholder="Write your justification here..." required></textarea>
                            </div>

                            <div>
                                <label for="communicated_handled">If there were delays, were they communicated and handled
                                    effectively?:</label>
                                <input type="number" name="communicated_handled" id="cli15" min="0" max="5" required
                                    oninput="ClientTotalReview()" placeholder="Rate Yourself">
                                </input>
                            </div>
                            <div class="mt-3"> <!-- Added margin-top for spacing -->
                                <label for="comments_communicated_handled">Justify Your Review:</label>
                                <textarea name="comments_communicated_handled" id="comments" class="form-control" rows="5"
                                    cols="50" placeholder="Write your justification here..." required></textarea>
                            </div>

                            <div>
                                <label for="development_process">Was the development process well-organized and
                                    structured?:</label>
                                <input type="number" name="development_process" id="cli16" min="0" max="5" required
                                    oninput="ClientTotalReview()" placeholder="Rate Yourself">
                                </input>
                            </div>
                            <div class="mt-3"> <!-- Added margin-top for spacing -->
                                <label for="comments_development_process">Justify Your Review:</label>
                                <textarea name="comments_development_process" id="comments" class="form-control" rows="5"
                                    cols="50" placeholder="Write your justification here..." required></textarea>
                            </div>

                            <div>
                                <h3>Problem-Solving and Flexibility</h3>
                                <label for="unexpected_challenges">How well did the developer(s) handle unexpected
                                    challenges or changes?:</label>
                                <input type="number" name="unexpected_challenges" id="cli17" min="0" max="5" required
                                    oninput="ClientTotalReview()" placeholder="Rate Yourself">
                                </input>
                            </div>
                            <div class="mt-3"> <!-- Added margin-top for spacing -->
                                <label for="comments_unexpected_challenges">Justify Your Review:</label>
                                <textarea name="comments_unexpected_challenges" id="comments" class="form-control" rows="5"
                                    cols="50" placeholder="Write your justification here..." required></textarea>
                            </div>

                            <div>
                                <label for="effective_workarounds">Did the developer(s) propose effective workarounds when
                                    issues arose?:</label>
                                <input type="number" name="effective_workarounds" id="cli18" min="0" max="5" required
                                    oninput="ClientTotalReview()" placeholder="Rate Yourself">
                                </input>
                            </div>
                            <div class="mt-3"> <!-- Added margin-top for spacing -->
                                <label for="comments_effective_workarounds">Justify Your Review:</label>
                                <textarea name="comments_effective_workarounds" id="comments" class="form-control" rows="5"
                                    cols="50" placeholder="Write your justification here..." required></textarea>
                            </div>

                            <div>
                                <label for="bugs_issues">How quickly were bugs or issues resolved during the
                                    project?:</label>
                                <input type="number" name="bugs_issues" id="cli19" min="0" max="5" required
                                    oninput="ClientTotalReview()" placeholder="Rate Yourself">
                                </input>
                            </div>
                            <div class="mt-3"> <!-- Added margin-top for spacing -->
                                <label for="comments_bugs_issues">Justify Your Review:</label>
                                <textarea name="comments_bugs_issues" id="comments" class="form-control" rows="5" cols="50"
                                    placeholder="Write your justification here..." required></textarea>
                            </div>

                            <div class="form-section">
                                <label for="ClientTotalReview">Client Total Review:</label>
                                <input type="text" name="ClientTotalReview" id="clientTotalReview" readonly>
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

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>


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



        document.getElementById("ClientReviewSubmit").addEventListener("submit", function (event) {
            event.preventDefault(); // Prevent default form submission

            let form = this;
            let formData = new FormData(this);

            $.ajax({
                url: "{{ route('client.review.submit') }}", // Laravel route for review submission
                type: "POST",
                data: formData,
                processData: false, // Prevent jQuery from processing data
                contentType: false, // Allows multipart/form-data for file uploads
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                },
                success: function (response) {
                    console.log("Success:", response);
                    alert("✅ Review submitted successfully!");
                    form.reset(); // Clear the form on success
                },
                error: function (xhr) {
                    console.log("XHR Response:", xhr.responseText); // Debugging: log response

                    if (xhr.status === 403) {
                        alert("⛔ User does not have Client"); // Show popup if unauthorized
                    } else {
                        alert("⚠️ Something went wrong! Please try again.");
                    }
                }
            });
        });


        //Admin Review total ratting
        document.addEventListener("DOMContentLoaded", function () {
            function clientTotalReview() {
                var ClientReview1 = parseInt(document.getElementById('cli1').value) || 0;
                var ClientReview2 = parseInt(document.getElementById('cli2').value) || 0;
                var ClientReview3 = parseInt(document.getElementById('cli3').value) || 0;
                var ClientReview4 = parseInt(document.getElementById('cli4').value) || 0;
                var ClientReview5 = parseInt(document.getElementById('cli5').value) || 0;
                var ClientReview6 = parseInt(document.getElementById('cli6').value) || 0;
                var ClientReview7 = parseInt(document.getElementById('cli7').value) || 0;
                var ClientReview8 = parseInt(document.getElementById('cli8').value) || 0;
                var ClientReview9 = parseInt(document.getElementById('cli9').value) || 0;
                var ClientReview10 = parseInt(document.getElementById('cli10').value) || 0;
                var ClientReview11 = parseInt(document.getElementById('cli11').value) || 0;
                var ClientReview12 = parseInt(document.getElementById('cli12').value) || 0;
                var ClientReview13 = parseInt(document.getElementById('cli13').value) || 0;
                var ClientReview14 = parseInt(document.getElementById('cli14').value) || 0;
                var ClientReview15 = parseInt(document.getElementById('cli15').value) || 0;
                var ClientReview16 = parseInt(document.getElementById('cli16').value) || 0;
                var ClientReview17 = parseInt(document.getElementById('cli17').value) || 0;
                var ClientReview18 = parseInt(document.getElementById('cli18').value) || 0;
                var ClientReview19 = parseInt(document.getElementById('cli19').value) || 0;
                var ClientReview20 = parseInt(document.getElementById('cli20').value) || 0;



                var totalRating = ClientReview1 + ClientReview2 + ClientReview3 + ClientReview4 + ClientReview5 + ClientReview6 + ClientReview7 + ClientReview8 + ClientReview9 +
                    ClientReview10 + ClientReview11 + ClientReview12 + ClientReview13 + ClientReview14 + ClientReview15 + ClientReview16 + ClientReview17 + ClientReview18 + ClientReview19 + ClientReview20;
                document.getElementById('clientTotalReview').value = totalRating;
            }

            // Attach function to input events
            document.getElementById('cli1').oninput = clientTotalReview;
            document.getElementById('cli2').oninput = clientTotalReview;
            document.getElementById('cli3').oninput = clientTotalReview;
            document.getElementById('cli4').oninput = clientTotalReview;
            document.getElementById('cli5').oninput = clientTotalReview;
            document.getElementById('cli6').oninput = clientTotalReview;
            document.getElementById('cli7').oninput = clientTotalReview;
            document.getElementById('cli8').oninput = clientTotalReview;
            document.getElementById('cli9').oninput = clientTotalReview;
            document.getElementById('cli10').oninput = clientTotalReview;
            document.getElementById('cli11').oninput = clientTotalReview;
            document.getElementById('cli12').oninput = clientTotalReview;
            document.getElementById('cli13').oninput = clientTotalReview;
            document.getElementById('cli14').oninput = clientTotalReview;
            document.getElementById('cli15').oninput = clientTotalReview;
            document.getElementById('cli16').oninput = clientTotalReview;
            document.getElementById('cli17').oninput = clientTotalReview;
            document.getElementById('cli18').oninput = clientTotalReview;
            document.getElementById('cli19').oninput = clientTotalReview;
            document.getElementById('cli20').oninput = clientTotalReview;
        });
    </script>




@endsection