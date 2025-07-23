@extends('layouts.app')

@section('title', 'Appraisal Dashboard')
@section('breadcrumb', 'Appraisal Table')
@section('page-title', 'Appraisal Section')

@section('content')

    <meta name="csrf-token" content="{{ csrf_token() }}">

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

        .client {
            margin-bottom: 20px;
        }

        .client__heading {
            font-size: 24px;
            font-weight: bold;
        }

        .client__select,
        .client__search {
            margin-right: 10px;
        }

        .client___item {
            display: flex;
            align-items: center;
        }

        .client__btn {
            background: none;
            border: none;
            padding: 0;
        }
    </style>

    <div class="client">
        <h1 class="client__heading">Appraisal Table</h1>

        <select id="financialYear" class="form-select client__select" name="financial_year" required>
            <option value="" selected>Financial Year</option>
            <option value="2025-2026">2025-2026</option>
            <option value="2026-2027">2026-2027</option>
            <option value="2027-2028">2027-2028</option>
            <option value="2028-2029">2028-2029</option>
            <option value="2029-2030">2029-2030</option>
        </select>

        <div class="client___item">
            <input type="search" id="employee_search" name="search" class="form-control client__search" placeholder="Search"
                aria-label="Search">
            <button class="client__btn" type="submit">
                <img src="https://modest-gagarin.74-208-156-247.plesk.page/images/search.png" alt="Search">
            </button>
        </div>
        <input type="hidden" name="emp_id" id="selectedEmpId">
    </div>

    <div class="container table-container appraisal-page">
        <div class="table-wrapper">
            <table class="table table-bordered table-hover main-table table-view apprisal-table">
                <thead id="table-header">
                    <tr>
                        <th>Employee Name</th>
                        <th>Evaluation Score (%)</th>
                        <th id="hr-column-header" style="display: none;">HR Review (%)</th>
                        <th id="admin-column-header" style="display: none;">Admin Review (%)</th>
                        <th id="manager-column-header" style="display: none;">Manager Review (%)</th>
                        <th id="client-column-header" style="display: none;">Client Review (%)</th>
                        <th>Appraisal Score (%)</th>
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
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
                    $('#appraisal-body').html('<tr><td colspan="7">Enter Employee ID or Name to view data.</td></tr>');
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
                        const query = $('#employee_search').val().trim().toLowerCase();

                        const isAdminAppraisal = query.includes('admin');
                        const isHrAppraisal = query.includes('hr');

                        const showClient = response.clientReviewData?.length > 0;
                        const showManager = response.managerReviewData?.length > 0;
                        const showHr = !isHrAppraisal;
                        const showAdmin = !isAdminAppraisal;

                        $('#client-column-header').toggle(showClient);
                        $('#manager-column-header').toggle(showManager);
                        $('#table-header th:nth-child(3)').toggle(showHr);    // HR Review
                        $('#table-header th:nth-child(4)').toggle(showAdmin); // Admin Review

                        if (response.status === 'error') {
                            $('#appraisal-body').html(`<tr><td colspan="7">${response.message}</td></tr>`);
                            return;
                        }

                        const maxLength = Math.max(
                            response.adminReviewData?.length || 0,
                            response.hrReviewData?.length || 0,
                            response.managerReviewData?.length || 0,
                            response.clientReviewData?.length || 0,
                            response.evaluationScore?.length || 0
                        );

                        for (let i = 0; i < maxLength; i++) {
                            const name = response.employee_name || 'N/A';

                            const evaluation = isNaN(Number(response.evaluationScore?.[i])) ? 0 : Number(response.evaluationScore[i]);
                            const hr = isNaN(Number(response.hrReviewData?.[i])) ? 0 : Number(response.hrReviewData[i]);
                            const admin = isNaN(Number(response.adminReviewData?.[i])) ? 0 : Number(response.adminReviewData[i]);
                            const manager = showManager ? (isNaN(Number(response.managerReviewData?.[i])) ? 0 : Number(response.managerReviewData[i])) : 0;
                            const client = showClient ? (isNaN(Number(response.clientReviewData?.[i])) ? 0 : Number(response.clientReviewData[i])) : 0;

                            const scores = [evaluation]
                                .concat(showHr ? [hr] : [])
                                .concat(showAdmin ? [admin] : [])
                                .concat(showManager ? [manager] : [])
                                .concat(showClient ? [client] : []);

                            const total = scores.reduce((sum, val) => sum + val, 0);
                            const average = scores.length ? total / scores.length : 0;
                            const status = average >= 80 ? 'Excellent' : average >= 60 ? 'Good' : 'Needs Improvement';

                            rows += `
                <tr>
                    <td>${name}</td>
                    <td>${evaluation.toFixed(2)}%</td>
                    ${showHr ? `<td>${hr.toFixed(2)}%</td>` : ''}
                    ${showAdmin ? `<td>${admin.toFixed(2)}%</td>` : ''}
                    ${showManager ? `<td>${manager.toFixed(2)}%</td>` : ''}
                    ${showClient ? `<td>${client.toFixed(2)}%</td>` : ''}
                    <td>${status} (${average.toFixed(2)}%)</td>
                </tr>`;
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