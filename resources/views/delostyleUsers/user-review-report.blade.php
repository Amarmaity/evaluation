@extends('layouts.app')

@section('title', 'User Review Report')

@section('breadcrumb', "User Review Report / Employee $emp_id")

@section('page-title', 'User Review Report')

@section('body-class', 'special-page')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="container forms-block">
        <h3 class="heading-two mt-0">User Review Report for Employee ID: {{ $emp_id }}</h3>
        <div class="mt-3">
            <button onclick="history.back()" class="btn btn-secondary">Back</button>
        </div>

        <div class="col-12 col-sm-6 search-container">
            <label for="financialYear" class="forms-label">Financial Years:</label>
            <select id="employeeDetails" name="financial_year" required class="form-control">
                <option value="" selected>Select Financial Years</option>
                <option value="2025-2026">2025-2026</option>
                <option value="2026-2027">2026-2027</option>
                <option value="2027-2028">2027-2028</option>
                <option value="2028-2029">2028-2029</option>
                <option value="2029-2030">2029-2030</option>
            </select>
        </div>

        <div id="reviewTableContainer" style="display: none; margin-top: 20px;">
            <div class="table-container">
                <div class="table-wrapper">
                    <table class="table table-bordered table-hover main-table table-view">
                        <thead>
                            <tr>
                                <th>Total Evaluation Score</th>
                                <th>Admin Review Score</th>
                                <th>HR Review Score</th>
                                <th>Manager Review Score</th>
                                <th id="clientColumnHeader" style="display: none;">Client Review Score</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td id="totalScoreCell"></td>
                                <td id="adminScoreCell"></td>
                                <td id="hrScoreCell"></td>
                                <td id="managerScoreCell"></td>
                                <td id="clientScoreCell" style="display: none;"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Buttons for each report -->
        <div class="evaluation-report">
            @if ($userData['evaluation'] !== null)
                <button class="btn secondary-btn" onclick="loadReport('evaluation', '{{ $emp_id }}')">Evaluation
                    Details</button>
            @endif

            @if ($userData['adminReview'] !== null)
                <button class="btn secondary-btn" onclick="loadReport('adminReport', '{{ $emp_id }}')">Admin Report</button>
            @endif

            @if ($userData['hrReview'] !== null)
                <button class="btn secondary-btn" onclick="loadReport('hrReport', '{{ $emp_id }}')">HR Report</button>
            @endif

            @if ($userData['managerReview'] !== null)
                <button class="btn secondary-btn" onclick="loadReport('managerReport', '{{ $emp_id }}')">Manager Report</button>
            @endif

            {{-- @if ($userData['clientReview'] !== null)
            <div class="client-report">
                <button class="btn secondary-btn" onclick="loadReport('clientReport', '{{ $emp_id }}')">Client
                    Report</button>
            </div>
            @endif --}}

            @if($clientReviews->isNotEmpty())
                @foreach($clientReviews as $clientReview)
                    <button class="btn secondary-btn"
                        onclick="loadClientReport('{{ $clientReview->emp_id }}', '{{ $clientReview->client_id }}')">
                        View Client Review for: {{ $clientReview->client_name ?? 'Unknown Client' }}
                    </button>
                @endforeach
            @elseif(in_array('client', $user_roles))
                <p>Your client review is pending.</p>
            @endif






        </div>
    </div>

    <div id="reportDetails" class="" style="margin-top: 20px;"></div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <script>
        function loadReport(reportType, empId) {
            $('#reportDetails').empty();

            const financialYear = $('#employeeDetails').val();
            if (!financialYear) {
                $('#reportDetails').html('<p>Please select a financial year first.</p>');
                return;
            }

            let url = '';
            switch (reportType) {
                case 'evaluation': url = `/evaluation/details/${empId}`; break;
                case 'managerReport': url = `/manager/report/${empId}`; break;
                case 'adminReport': url = `/admin/report/${empId}`; break;
                case 'hrReport': url = `/hr/report/${empId}`; break;
                case 'clientReport': url = `/client/report/${empId}`; break;
                default: console.error('Unknown report type'); return;
            }

            $.ajax({
                url: url,
                method: 'GET',
                data: {
                    financial_year: financialYear,
                    emp_id: empId,
                    employee_id: empId
                },
                success: function (response) {
                    $('#reportDetails').html(response).addClass('table-container');
                },
                error: function () {
                    $('#reportDetails').html('<p>Sorry, there was an error loading the report.</p>');
                }
            });
        }

        document.getElementById('employeeDetails').addEventListener('change', function () {
            const selectedYear = this.value;
            const table = document.getElementById('reviewTableContainer');
            const empId = "{{ $emp_id }}";

            if (!selectedYear) {
                table.style.display = 'none';
                return;
            }

            fetch(`/employee/review-scores?financial_year=${selectedYear}&emp_id=${empId}&employee_id=${empId}`)
                .then(response => {
                    if (response.status === 204) {
                        table.style.display = 'none';
                        return null;
                    }
                    if (!response.ok) throw new Error('Network error');
                    return response.json();
                })
                .then(data => {
                    if (!data) {
                        table.style.display = 'none';
                        return;
                    }

                    const totalCell = document.getElementById("totalScoreCell");
                    const adminCell = document.getElementById("adminScoreCell");
                    const hrCell = document.getElementById("hrScoreCell");
                    const managerCell = document.getElementById("managerScoreCell");
                    const clientCell = document.getElementById("clientScoreCell");

                    const totalHeader = document.querySelector("th:nth-child(1)");
                    const adminHeader = document.querySelector("th:nth-child(2)");
                    const hrHeader = document.querySelector("th:nth-child(3)");
                    const managerHeader = document.querySelector("th:nth-child(4)");
                    const clientHeader = document.getElementById("clientColumnHeader");

                    function set(cell, header, val, max) {
                        if (val !== null && val !== undefined && val !== '') {
                            const rounded = Math.round(Number(val));
                            cell.textContent = `${rounded} / ${max}`;
                            cell.style.display = '';
                            header.style.display = '';
                            return true;
                        } else {
                            cell.textContent = '';
                            cell.style.display = 'none';
                            header.style.display = 'none';
                            return false;
                        }
                    }

                    const hasTotal = set(totalCell, totalHeader, data.total, 100);
                    const hasAdmin = set(adminCell, adminHeader, data.admin, 45);
                    const hasHr = set(hrCell, hrHeader, data.hr, 30);
                    const hasManager = set(managerCell, managerHeader, data.managerTotal, 35);

                    let hasClient = false;
                    if (data.showClient) {
                        hasClient = set(clientCell, clientHeader, data.clientTotal, 100);
                    } else {
                        clientCell.style.display = 'none';
                        clientHeader.style.display = 'none';
                    }

                    const anyData = hasTotal || hasAdmin || hasHr || hasManager || hasClient;
                    table.style.display = anyData ? 'block' : 'none';
                })
                .catch(error => {
                    console.error("Error fetching review scores:", error);
                    table.style.display = 'none';
                });
        });


        function loadClientReport(empId, clientId) {
            $('#reportDetails').empty();

            const financialYear = $('#employeeDetails').val();
            if (!financialYear) {
                $('#reportDetails').html('<p>Please select a financial year first.</p>');
                return;
            }

            const url = `/client/review/details/${empId}?client_id=${clientId}&financial_year=${financialYear}`;

            $.ajax({
                url: url,
                method: 'GET',
                success: function (response) {
                    $('#reportDetails').html(response);
                    $('#reportDetails').addClass('table-container');
                },
                error: function () {
                    $('#reportDetails').html('<p>Sorry, there was an error loading the client review.</p>');
                }
            });
        }

    </script>

@endsection