@extends('layouts.app')
@section('title', 'Financial Dashboard')
@section('breadcrumb', 'Financial')
@section('page-title', 'Financial-Section')
@section('content')

    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>

    <body>
        <div class="client">
            <h1 class="client__heading">Employee Financial Year(%)</h1>
            <div class="client___item">
                <input type="search" id="employee_search" name="search" class="form-control client__search"
                    placeholder="Search" aria-label="Search">
                <button class="client__btn" type="submit">
                    <img src="https://modest-gagarin.74-208-156-247.plesk.page/images/search.png" alt="Search">
                </button>
            </div>
            <input type="hidden" name="emp_id" id="selectedEmpId">


            <select id="financialYear" class="form-select client__select" name="financial_year" required>
                <option value="" selected>Financial Year</option>
                <option value="2025-2026">2025-2026</option>
                <option value="2026-2027">2026-2027</option>
                <option value="2027-2028">2027-2028</option>
                <option value="2028-2029">2028-2029</option>
                <option value="2029-2030">2029-2030</option>
            </select>
        </div>
        <div class="container table-container financial-page">



            <!-- Appraisal Table -->
            <form action="{{route('financial-data-store')}}" method="POST" id="financial-data"
                enctype="multipart/form-data">
                @csrf
                <div class="table-responsive table-wrapper">
                    <table class="table table-bordered table-hover main-table financial-table" class="financial view-table">
                        <thead class="table">
                            {{-- < tr>

                                </tr> --}}
                                <tr>
                                    <th>Employee Name</th>
                                    <th>Employee ID</th>
                                    <th>Evaluation Score (%)</th>
                                    <th>HR Review (%)</th>
                                    <th>Admin Review (%)</th>
                                    <th>Manager Review (%)</th>
                                    <th id="client-review-header">Client Review (%)</th>
                                    <th>Appraisal Score (%)</th>
                                    <th>Current Salary (₹)</th>
                                    <th>Percentage (%)</th>
                                    <th>Updated Salary (₹)</th>
                                    <th>Final Salary (₹)</th>
                                    <th>Appraisal Date</th>
                                    <th>Financial Year</th>
                                    {{-- < th> Apply</th> --}}
                                </tr>
                        </thead>
                        <tbody id="appraisal-body">
                            <tr>
                                <td colspan="12" class="text-muted">Enter Employee ID or Name to view data.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary primary-btn" id="save-financial-data">Save</button>
                </div>
            </form>
        </div>
        </div>
        </div>
    </body>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min"></script>


    <script>
        $(document).ready(function () {
            let clientExist = false;

            // Fetch employee data based on ID or Name input
            function fetchEmployeeData() {
                let employeeId = $('#employee_id').val().trim();
                let employeeName = $('#employee_name').val().trim();
                let financialYear = $('#financialYear').val();

                if (employeeId.length === 0 && employeeName.length === 0) {
                    $('#appraisal-body').html(
                        '<tr><td colspan="13" class="text-muted">Enter Employee ID or Name to view data.</td></tr>');
                    return;
                }

                $.ajax({
                    url: "{{ route('financial.data') }}",
                    method: "GET",
                    data: {
                        employee_id: employeeId,
                        employee_name: employeeName,
                        financial_year: financialYear
                    },
                    success: function (response) {
                        let tableRows = '';
                        let showClientReview = response.clientReviewData.some(value => value !== null);

                        if (!showClientReview) {
                            $('#client-review-header').hide();
                            clientExist = false;
                        } else {
                            $('#client-review-header').show();
                            clientExist = true;
                        }

                        response.hrReviewData.forEach((hrReview, index) => {
                            let employeeName = response.employee_name || 'N/A';
                            let employeeId = response.employee_id;
                            let adminReview = parseFloat(response.adminReviewData[index] || 0);
                            let managerReview = parseFloat(response.managerReviewData[index] || 0);
                            let EvaluationScore = parseFloat(response.evaluationScore) || 0;
                            console.log(EvaluationScore);
                            let clientReview = showClientReview ? parseFloat(response
                                .clientReviewData[index] || 0) : null;
                            let baseSalary = parseFloat(response.salary) || 0;
                            let percentage = parseFloat(response.company_percentage) || 0;

                            // Calculate the total score (HR + Admin + Manager)
                            let totalScore = hrReview + adminReview + managerReview +
                                EvaluationScore;
                            let divisor = 4; // Default divisor for no client review

                            if (showClientReview && clientReview !== null) {
                                totalScore += clientReview; // Add client review if present
                                divisor = 5; // Divisor becomes 4 when there's a client review
                            }

                            let avgReviewPercentage = totalScore / divisor;

                            // Salary calculation
                            let updatedSalary = (baseSalary * percentage) / 100;
                            let finalSalary = baseSalary + (updatedSalary * avgReviewPercentage /
                                100);

                            // Add row to table
                            tableRows += `<tr data-salary="${baseSalary}">
                                    <td class="employeeName">${employeeName}</td>
                                    <td class="employeeId">${employeeId}</td>
                                    <td class="EvaluationScore">${EvaluationScore.toFixed(2)}%</td>
                                    <td class="hrReview">${parseFloat(hrReview).toFixed(2)}%</td>
                                    <td class="adminReview">${adminReview.toFixed(2)}%</td>
                                    <td class="managerReview">${managerReview.toFixed(2)}%</td>
                                    ${showClientReview ? `<td class="clientReview">${clientReview.toFixed(2)}%</td>` : ''}
                                    <td class="avgReview">${avgReviewPercentage.toFixed(2)}%</td>
                                    <td class="currentSalary">₹${baseSalary.toFixed(2)}</td>
                                    <td class="percentage">${percentage.toFixed(2)}%</td>
                                    <td class="updated-salary">₹${updatedSalary.toFixed(2)}</td>
                                    <td class="final-salary">₹${finalSalary.toFixed(2)}</td>
                                    <td class="appraisal-date">${response.appraisalDate || 'N/A'}</td>
                                    <td class="financial-year">${financialYear}</td>
                                </tr>`;
                        });

                        $('#appraisal-body').html(tableRows);
                    },
                    error: function (xhr) {
                        const errorMsg = xhr.responseJSON?.message || 'Error fetching data';
                        $('#appraisal-body').html(`<tr><td colspan="7">${errorMsg}</td></tr>`);
                    }
                });
            }

            // Save form data on submit
            $('#save-financial-data').click(function (e) {
                e.preventDefault();

                const button = $(this);
                button.prop('disabled', true).text('Saving...');

                let selectedFinancialYear = $('#financialYear').val();
                if (!selectedFinancialYear) {
                    alert("Please select a financial year.");
                    button.prop('disabled', false).text('Save');
                    return;
                }

                let employees = [];
                $('#appraisal-body tr').each(function () {
                    let row = $(this);
                    let employee = {
                        employee_name: row.find(".employeeName").text().trim(),
                        emp_id: row.find(".employeeId").text().trim(),
                        evaluation_score: parseFloat(row.find(".EvaluationScore").text()) || 0,
                        hr_review: parseFloat(row.find(".hrReview").text()) || 0,
                        admin_review: parseFloat(row.find(".adminReview").text()) || 0,
                        manager_review: parseFloat(row.find(".managerReview").text()) || 0,
                        clint_review: parseFloat(row.find(".clientReview").text()) || 0,
                        apprisal_score: parseFloat(row.find(".avgReview").text()) || 0,
                        current_salary: parseFloat(row.find(".currentSalary").text().replace('₹',
                            '').trim()) || 0,
                        percentage_given: parseFloat(row.find(".percentage").text()) || 0,
                        update_salary: parseFloat(row.find(".updated-salary").text().replace('₹',
                            '').trim()) || 0,
                        final_salary: parseFloat(row.find(".final-salary").text().replace('₹', '')
                            .trim()) || 0,
                        apprisal_date: row.find(".appraisal-date").text() || 'N/A',
                        financial_year: selectedFinancialYear || 'N/A'
                    };

                    employees.push(employee);
                });

                if (employees.length === 0) {
                    alert("No employee data to save!");
                    button.prop('disabled', false).text('Save');
                    return;
                }

                $.ajax({
                    url: '{{ route('financial-data-store') }}',
                    method: 'POST',
                    contentType: "application/json",
                    dataType: 'json',
                    data: JSON.stringify({
                        _token: '{{ csrf_token() }}',
                        employees: employees
                    }),
                    success: function (response) {
                        alert('Data saved successfully!');
                        setTimeout(() => {
                            location.reload(); // Refresh the page after success
                        }, 1000);
                    },
                    error: function (xhr, status, error) {
                        console.error(xhr.responseText);
                        let errorMessage = 'An error occurred. Please try again.';
                        try {
                            let response = JSON.parse(xhr.responseText);
                            if (response.message) {
                                errorMessage = response.message;
                            }
                        } catch (e) {
                            console.error("Failed to parse error JSON:", e);
                        }
                        alert(errorMessage);
                        button.prop('disabled', false).text('Save'); // Re-enable on error
                    }
                });
            });

            // Trigger data fetch when user types in search inputs
            $("#employee_id, #employee_name").on("input", fetchEmployeeData);
        });
    </script>


@endsection