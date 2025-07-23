@extends('layouts.app')

@section('title', 'Appraisal Dashboard')
@section('breadcrumb', 'Appraisal Table')
@section('page-title', 'Appraisal Section')

@section('content')

    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>

    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        .search-container {
            display: flex;
            justify-content: space-between;
            padding: 10px;
        }

        .search-container input {
            padding: 5px;
            width: 200px;
        }
    </style>

    <!-- Appraisal Table -->
    <!-- Appraisal Table -->

    <body>

        <div class="client">
            <h1 class="client__heading">Appraisal Table</h1>
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

        <div class="container table-container appraisal-page">
            <div class="table-wrapper">
                <table class="table table-bordered table-hover main-table apprisal-table" class="apparisal-table">
                    <thead id="table-header">
                        <tr>
                            <th>Employee Name</th>
                            <th>Evaluation Score (%)</th>
                            <th>HR Review (%)</th>
                            <th>Admin Review (%)</th>
                            <th>Manager Review (%)</th>
                            <!-- Client Review Column will be added dynamically -->
                            <th id="client-column-header" style="display: none;">Client Review (%)</th>
                            <!-- Initially hidden -->
                            <th>Appraisal Score (%)</th>
                            {{-- <th>Current Salary (₹)</th> --}}
                        </tr>
                    </thead>
                    <tbody id="appraisal-body">
                        <tr>
                            <td colspan="7">Enter Employee ID or Name to view data.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        </div>
    </body>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>


        // $(document).ready(function () {
        //     let debounceTimer;

        //     function fetchEmployeeData() {
        //         const employeeId = $('#employee_id').val().trim();
        //         const employeeName = $('#employee_name').val().trim();
        //         const financialYear = $('#financialYear').val().trim();

        //         if (!employeeId && !employeeName) {
        //             $('#appraisal-body').html('<tr><td colspan="7">🔍 Enter Employee ID or Name to view data.</td></tr>');
        //             return;
        //         }

        //         $.ajax({
        //             url: "/apprisal-data",
        //             method: "GET",
        //             data: {
        //                 employee_id: employeeId,
        //                 employee_name: employeeName,
        //                 financial_year: financialYear.replace('/', '-')
        //             },
        //             success: function (response) {
        //                 let rows = '';
        //                 const showClient = response.clientReviewData?.length > 0;

        //                 $('#client-column-header').toggle(showClient);

        //                 if (response.status === 'error') {
        //                     $('#appraisal-body').html(`<tr><td colspan="7">${response.message}</td></tr>`);
        //                     return;
        //                 }

        //                 const maxLength = Math.max(
        //                     response.adminReviewData?.length || 0,
        //                     response.hrReviewData?.length || 0,
        //                     response.managerReviewData?.length || 0,
        //                     response.clientReviewData?.length || 0
        //                 );

        //                 for (let i = 0; i < maxLength; i++) {
        //                     const name = response.employee_name || 'N/A';

        //                     const evaluation = isNaN(Number(evals?.[i])) ? 0 : Number(evals[i]);
        //                     const hr = isNaN(Number(response.hrReviewData?.[i])) ? 0 : Number(response.hrReviewData[i]);
        //                     const admin = isNaN(Number(response.adminReviewData?.[i])) ? 0 : Number(response.adminReviewData[i]);
        //                     const manager = isNaN(Number(response.managerReviewData?.[i])) ? 0 : Number(response.managerReviewData[i]);
        //                     const client = showClient ? (isNaN(Number(response.clientReviewData?.[i])) ? 0 : Number(response.clientReviewData[i])) : null;

        //                     const scores = [hr, admin, manager].concat(showClient ? [client] : []);
        //                     const total = scores.reduce((sum, val) => sum + val, 0);
        //                     const average = scores.length ? total / scores.length : 0;

        //                     const status = average >= 80 ? 'Excellent' : average >= 60 ? 'Good' : 'Needs Improvement';

        //                     rows += `
        //                     <tr>
        //                         <td>${name}</td>
        //                         <td>${evaluation.toFixed(2)}%</td>
        //                         <td>${hr.toFixed(2)}%</td>
        //                         <td>${admin.toFixed(2)}%</td>
        //                         <td>${manager.toFixed(2)}%</td>
        //                         ${showClient ? `<td>${client.toFixed(2)}%</td>` : ''}
        //                         <td>${status} (${average.toFixed(2)}%)</td>
        //                         </tr>`;

        //                         //<td>₹${Number(response.finalSalaries?.[i] || response.salary || 0).toFixed(2)}</td>
        //                 }



        //                 $('#appraisal-body').html(rows);
        //             },
        //             error: function (xhr) {
        //                 const errorMsg = xhr.responseJSON?.message || 'Error fetching data';
        //                 $('#appraisal-body').html(`<tr><td colspan="7">${errorMsg}</td></tr>`);
        //             }
        //         });
        //     }

        //     $('#employee_id, #employee_name').on('keyup', function () {
        //         clearTimeout(debounceTimer);
        //         debounceTimer = setTimeout(fetchEmployeeData, 300);
        //     });
        // });



        //             $(document).ready(function () {
        //     let debounceTimer;

        //     function fetchEmployeeData() {
        //         const employeeId = $('#employee_id').val().trim();
        //         const employeeName = $('#employee_name').val().trim();
        //         const financialYear = $('#financialYear').val().trim();

        //         // Ensure financial year is in '2025-2026' format
        //         if (!financialYear) {
        //             $('#appraisal-body').html('<tr><td colspan="7">Please select a valid financial year.</td></tr>');
        //             return;
        //         }

        //         if (!employeeId && !employeeName) {
        //             $('#appraisal-body').html('<tr><td colspan="7">🔍 Enter Employee ID or Name to view data.</td></tr>');
        //             return;
        //         }

        //         $.ajax({
        //             url: "/apprisal-data", // Ensure this URL is correct
        //             method: "GET",
        //             data: {
        //                 employee_id: employeeId,
        //                 employee_name: employeeName,
        //                 financial_year: financialYear // No replacement needed here
        //             },
        //             success: function (response) {
        //                 let rows = '';
        //                 const showClient = response.clientReviewData?.length > 0;

        //                 $('#client-column-header').toggle(showClient);

        //                 if (response.status === 'error') {
        //                     $('#appraisal-body').html(`<tr><td colspan="7">${response.message}</td></tr>`);
        //                     return;
        //                 }

        //                 const maxLength = Math.max(
        //                     response.adminReviewData?.length || 0,
        //                     response.hrReviewData?.length || 0,
        //                     response.managerReviewData?.length || 0,
        //                     response.clientReviewData?.length || 0
        //                 );

        //                 for (let i = 0; i < maxLength; i++) {
        //                     const name = response.employee_name || 'N/A';

        //                     const evaluation = isNaN(Number(response.evaluationScore?.[i])) ? 0 : Number(response.evaluationScore[i]);
        //                     const hr = isNaN(Number(response.hrReviewData?.[i])) ? 0 : Number(response.hrReviewData[i]);
        //                     const admin = isNaN(Number(response.adminReviewData?.[i])) ? 0 : Number(response.adminReviewData[i]);
        //                     const manager = isNaN(Number(response.managerReviewData?.[i])) ? 0 : Number(response.managerReviewData[i]);
        //                     const client = showClient ? (isNaN(Number(response.clientReviewData?.[i])) ? 0 : Number(response.clientReviewData[i])) : null;

        //                     const scores = [hr, admin, manager].concat(showClient ? [client] : []);
        //                     const total = scores.reduce((sum, val) => sum + val, 0);
        //                     const average = scores.length ? total / scores.length : 0;

        //                     const status = average >= 80 ? 'Excellent' : average >= 60 ? 'Good' : 'Needs Improvement';

        //                     rows += `
        //                         <tr>
        //                             <td>${name}</td>
        //                             <td>${evaluation.toFixed(2)}%</td>
        //                             <td>${hr.toFixed(2)}%</td>
        //                             <td>${admin.toFixed(2)}%</td>
        //                             <td>${manager.toFixed(2)}%</td>
        //                             ${showClient ? `<td>${client.toFixed(2)}%</td>` : ''}
        //                             <td>${status} (${average.toFixed(2)}%)</td>
        //                         </tr>
        //                     `;
        //                 }

        //                 $('#appraisal-body').html(rows);
        //             },
        //             error: function (xhr) {
        //                 const errorMsg = xhr.responseJSON?.message || 'Error fetching data';
        //                 $('#appraisal-body').html(`<tr><td colspan="7">${errorMsg}</td></tr>`);
        //                 console.error("Error fetching data", xhr.responseJSON);
        //             }
        //         });
        //     }

        //     $('#employee_id, #employee_name').on('keyup', function () {
        //         clearTimeout(debounceTimer);
        //         debounceTimer = setTimeout(fetchEmployeeData, 300);
        //     });

        //     $('#financialYear').on('change', function () {
        //         fetchEmployeeData(); // Trigger on financial year change
        //     });
        // });

        $(document).ready(function () {
            let debounceTimer;

            function fetchEmployeeData() {
                const employeeQuery = $('#employee_search').val().trim();
                const financialYear = $('#financialYear').val().trim();

                if (!financialYear) {
                    $('#appraisal-body').html('<tr><td colspan="7">Please select a valid financial year.</td></tr>');
                    return;
                }

                if (!employeeQuery) {
                    $('#appraisal-body').html('<tr><td colspan="7">🔍 Enter Employee ID or Name to view data.</td></tr>');
                    return;
                }

                $.ajax({
                    url: "/apprisal-data",
                    method: "GET",
                    data: {
                        employee_query: employeeQuery,
                        financial_year: financialYear
                    },
                    success: function (response) {
                        let rows = '';
                        const showClient = response.clientReviewData?.length > 0;

                        $('#client-column-header').toggle(showClient);

                        if (response.status === 'error') {
                            $('#appraisal-body').html(`<tr><td colspan="7">${response.message}</td></tr>`);
                            return;
                        }

                        const maxLength = Math.max(
                            response.adminReviewData?.length || 0,
                            response.hrReviewData?.length || 0,
                            response.managerReviewData?.length || 0,
                            response.clientReviewData?.length || 0
                        );

                        for (let i = 0; i < maxLength; i++) {
                            const name = response.employee_name || 'N/A';

                            const evaluation = isNaN(Number(response.evaluationScore?.[i])) ? 0 : Number(response.evaluationScore[i]);
                            const hr = isNaN(Number(response.hrReviewData?.[i])) ? 0 : Number(response.hrReviewData[i]);
                            const admin = isNaN(Number(response.adminReviewData?.[i])) ? 0 : Number(response.adminReviewData[i]);
                            const manager = isNaN(Number(response.managerReviewData?.[i])) ? 0 : Number(response.managerReviewData[i]);
                            const client = showClient ? (isNaN(Number(response.clientReviewData?.[i])) ? 0 : Number(response.clientReviewData[i])) : null;

                            const scores = [hr, admin, manager].concat(showClient ? [client] : []);
                            const total = scores.reduce((sum, val) => sum + val, 0);
                            const average = scores.length ? total / scores.length : 0;

                            const status = average >= 80 ? 'Excellent' : average >= 60 ? 'Good' : 'Needs Improvement';

                            rows += `
                            <tr>
                                <td>${name}</td>
                                <td>${evaluation.toFixed(2)}%</td>
                                <td>${hr.toFixed(2)}%</td>
                                <td>${admin.toFixed(2)}%</td>
                                <td>${manager.toFixed(2)}%</td>
                                ${showClient ? `<td>${client.toFixed(2)}%</td>` : ''}
                                <td>${status} (${average.toFixed(2)}%)</td>
                            </tr>
                        `;
                        }

                        $('#appraisal-body').html(rows);
                    },
                    error: function (xhr) {
                        const errorMsg = xhr.responseJSON?.message || 'Error fetching data';
                        $('#appraisal-body').html(`<tr><td colspan="7">${errorMsg}</td></tr>`);
                        console.error("Error fetching data", xhr.responseJSON);
                    }
                });
            }

            $('#employee_search').on('keyup', function () {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(fetchEmployeeData, 300);
            });

            $('#financialYear').on('change', function () {
                fetchEmployeeData();
            });
        });


    </script>

@endsection