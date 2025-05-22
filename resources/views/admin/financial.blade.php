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

    // Fetch employee data based on input fields
    function fetchEmployeeData() {
        const employeeSearch = $('#employee_search').val().trim();
        const financialYear = $('#financialYear').val();

        if (!employeeSearch || !financialYear) {
            $('#appraisal-body').html(
                '<tr><td colspan="13" class="text-muted">Enter Employee ID/Name and select Financial Year to view data.</td></tr>'
            );
            return;
        }

        $.ajax({
            url: "{{ route('financial.data') }}",
            method: "GET",
            data: {
                search: employeeSearch,
                financial_year: financialYear
            },
            success: function (response) {
                let tableRows = '';
                let showClientReview = response.clientReviewData && response.clientReviewData.some(value => value !== null && value !== 0);

                if (!showClientReview) {
                    $('#client-review-header').hide();
                    clientExist = false;
                } else {
                    $('#client-review-header').show();
                    clientExist = true;
                }

                // Since your backend returns arrays for each review type, loop through them by index
                // Use length of hrReviewData or adminReviewData or managerReviewData as base length
                const length = response.hrReviewData.length || 1;

                for (let i = 0; i < length; i++) {
                    const employeeName = response.employee_name || 'N/A';
                    const employeeId = response.employee_id || 'N/A';
                    const hrReview = parseFloat(response.hrReviewData[i] || 0);
                    const adminReview = parseFloat(response.adminReviewData[i] || 0);
                    const managerReview = parseFloat(response.managerReviewData[i] || 0);
                    const evaluationScore = parseFloat(response.evaluationScore) || 0;
                    const clientReview = showClientReview ? parseFloat(response.clientReviewData[i] || 0) : null;
                    const baseSalary = parseFloat(response.salary) || 0;
                    const percentage = parseFloat(response.company_percentage) || 0;

                    // Calculate total score and divisor depending on client review presence
                    let totalScore = hrReview + adminReview + managerReview + evaluationScore;
                    let divisor = 4;
                    if (showClientReview && clientReview !== null) {
                        totalScore += clientReview;
                        divisor = 5;
                    }

                    let avgReviewPercentage = totalScore / divisor;

                    // Salary calculations
                    let updatedSalary = (baseSalary * percentage) / 100;
                    let finalSalary = baseSalary + (updatedSalary * avgReviewPercentage / 100);

                    // Build table row
                    tableRows += `<tr data-salary="${baseSalary}">
                        <td class="employeeName">${employeeName}</td>
                        <td class="employeeId">${employeeId}</td>
                        <td class="EvaluationScore">${evaluationScore.toFixed(2)}%</td>
                        <td class="hrReview">${hrReview.toFixed(2)}%</td>
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
                }

                $('#appraisal-body').html(tableRows);
            },
            error: function (xhr) {
                const errorMsg = xhr.responseJSON?.message || 'Error fetching data';
                $('#appraisal-body').html(`<tr><td colspan="13">${errorMsg}</td></tr>`);
            }
        });
    }

    // Trigger fetch when typing or changing financial year
    $('#employee_search').on('input', fetchEmployeeData);
    $('#financialYear').on('change', fetchEmployeeData);

    // Save form data
    $('#save-financial-data').click(function (e) {
        e.preventDefault();

        const button = $(this);
        button.prop('disabled', true).text('Saving...');

        const selectedFinancialYear = $('#financialYear').val();
        if (!selectedFinancialYear) {
            alert("Please select a financial year.");
            button.prop('disabled', false).text('Save');
            return;
        }

        const employees = [];
        $('#appraisal-body tr').each(function () {
            const row = $(this);
            const employee = {
                employee_name: row.find(".employeeName").text().trim(),
                emp_id: row.find(".employeeId").text().trim(),
                evaluation_score: parseFloat(row.find(".EvaluationScore").text()) || 0,
                hr_review: parseFloat(row.find(".hrReview").text()) || 0,
                admin_review: parseFloat(row.find(".adminReview").text()) || 0,
                manager_review: parseFloat(row.find(".managerReview").text()) || 0,
                client_review: parseFloat(row.find(".clientReview").text()) || 0,
                apprisal_score: parseFloat(row.find(".avgReview").text()) || 0,
                current_salary: parseFloat(row.find(".currentSalary").text().replace('₹', '').trim()) || 0,
                percentage_given: parseFloat(row.find(".percentage").text()) || 0,
                update_salary: parseFloat(row.find(".updated-salary").text().replace('₹', '').trim()) || 0,
                final_salary: parseFloat(row.find(".final-salary").text().replace('₹', '').trim()) || 0,
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
                    location.reload(); // Refresh page
                }, 1000);
            },
            error: function (xhr) {
                let errorMessage = 'An error occurred. Please try again.';
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.message) {
                        errorMessage = response.message;
                    }
                } catch (e) {
                    console.error("Failed to parse error JSON:", e);
                }
                alert(errorMessage);
                button.prop('disabled', false).text('Save');
            }
        });
    });
});

    </script>


@endsection