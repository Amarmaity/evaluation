@extends('layouts.app')

@section('title', 'User Review Report')

@section('breadcrumb', "User Review Report / Employee $emp_id")

@section('page-title', 'User Review Report')

@section('body-class', 'special-page')

@section('content')

    <div class="container forms-block">
        <h3 class="heading-two mt-0">User Review Report for Employee ID: {{ $emp_id }}</h3>
        <div class="col-12 col-sm-6 search-container">
            <label for="financialYear"class="forms-label">Financial Years:</label>
            <select id="employeeDetails" name="financial_year" required class="form-control">
                <option value="" selected>Select Financial Years</option>
                <option value="2025-2026">2025-2026</option>
                <option value="2026-2027">2026-2027</option>
                <option value="2027-2028">2027-2028</option>
                <option value="2028-2029">2028-2029</option>
                <option value="2028-2029">2029-2030</option>
            </select>
        </div>

        <div id="reviewTableContainer" style="display: none; margin-top: 20px;">
            <div class="table-container">
            <div class="table-wrapper">
            <table class="table table-bordered table-hover main-table">
                <thead>
                    <tr>
                        <th>Total Evaluation Score</th>
                        <th>Admin Review Score</th>
                        <th>HR Review Score</th>
                        <th>Manager Review Score</th>
                        <th id="clientColumnHeader" style="display: none;">Client Review Score</th>
                        <!-- Initially hidden -->
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td id="totalScoreCell"></td>
                        <td id="adminScoreCell"></td>
                        <td id="hrScoreCell"></td>
                        <td id="managerScoreCell"></td>
                        <td id="clientScoreCell" style="display: none;"></td> <!-- Initially hidden -->
                    </tr>
                </tbody>
            </table>
            </div>
            </div>
        </div>



        <!-- Buttons for each report -->
        <div class="evaluation-report">
            <!-- Evaluation Report Button -->
            @if ($userData['evaluation'] !== null)
                <button class="btn secondary-btn" onclick="loadReport('evaluation', '{{ $emp_id }}')">Evaluation Details</button>
            @endif

            <!-- Manager Report Button -->
            @if ($userData['managerReview'] !== null)
                <button class="btn secondary-btn" onclick="loadReport('managerReport', '{{ $emp_id }}')">Manager Report</button>
            @endif

            <!-- Admin Report Button -->
            @if ($userData['adminReview'] !== null)
                <button class="btn secondary-btn" onclick="loadReport('adminReport', '{{ $emp_id }}')">Admin Report</button>
            @endif

            <!-- HR Report Button -->
            @if ($userData['hrReview'] !== null)
                <button class="btn secondary-btn" onclick="loadReport('hrReport', '{{ $emp_id }}')">HR Report</button>
            @endif

            <!-- Client Report Button -->
            @if ($userData['clientReview'] !== null)
                <div class="client-report">
                    <button class="btn secondary-btn" onclick="loadReport('clientReport', '{{ $emp_id }}')">Client Report</button>
                </div>
            @endif
        </div>
    </div>
    <div id="reportDetails" class="table-container" style="margin-top: 20px;"></div>


    <!-- jQuery and AJAX script -->
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
                case 'evaluation':
                    url = `/evaluation/details/${empId}`;
                    break;
                case 'managerReport':
                    url = `/manager/report/${empId}`;
                    break;
                case 'adminReport':
                    url = `/admin/report/${empId}`;
                    break;
                case 'hrReport':
                    url = `/hr/report/${empId}`;
                    break;
                case 'clientReport':
                    url = `/client/report/${empId}`;
                    break;
                default:

                    console.error('Unknown report type');
                    url = '';
                    break;
            }
            if (url) {
                $.ajax({
                    url: url,
                    method: 'GET',
                    data: { financial_year: financialYear },
                    success: function (response) {
                        $('#reportDetails').html(response);
                    },
                    error: function () {
                        $('#reportDetails').html('<p>Sorry, there was an error loading the report.</p>');
                    }
                });
            } else {
                $('#reportDetails').html('<p>Invalid report type provided.</p>');
            }
        }





        //handle the table script
        document.getElementById('employeeDetails').addEventListener('change', function () {
            const selectedYear = this.value;
            const table = document.getElementById('reviewTableContainer');

            if (!selectedYear) {
                table.style.display = 'none';
                return;
            }

            fetch(`/employee/review-scores?financial_year=${selectedYear}`)
                .then(response => {
                    if (response.status === 204) {
                        console.log("No review data available.");
                        table.style.display = 'none';
                        return null;
                    }
                    if (!response.ok) {
                        throw new Error('Network error');
                    }
                    return response.json();
                })
                .then(data => {
                    if (!data) return;

                    // Fill in scores
                    document.getElementById('totalScoreCell').innerText = (data.total ?? '-') + "%";
                    document.getElementById('adminScoreCell').innerText = (data.admin ?? '-') + "%";
                    document.getElementById('hrScoreCell').innerText = (data.hr ?? '-') + "%";
                    document.getElementById('managerScoreCell').innerText = (data.manager ?? '-') + "%";

                    // Handle client score and conditional visibility
                    const clientCell = document.getElementById('clientScoreCell');
                    const clientColumnHeader = document.getElementById('clientColumnHeader');

                    if (data.showClient) {
                        clientCell.innerText = data.client !== undefined ? data.client + "%" : '-';
                        clientCell.style.display = ''; 
                        clientColumnHeader.style.display = ''; 
                    } else {
                        clientCell.style.display = 'none';
                        clientColumnHeader.style.display = 'none';
                    }

                    // Show the table
                    table.style.display = 'block';
                })
                .catch(error => {
                    console.error("Error fetching review scores:", error);
                    table.style.display = 'none';
                });
        });

    </script>
@endsection