@extends('layouts.app') <!-- Extends app.blade.php (Header, Sidebar, Footer included) -->

@section('title', 'Super Admin Dashboard') <!-- Page Title -->

@section('breadcrumb', "Super view / Employee {$emp_id}") <!-- Breadcrumb -->

@section('page-title', 'Super Admin Dashboard') <!-- Page Title in Breadcrumb -->

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- {{dd($users)}} --}}
    {{-- <div class="container"> --}}
        <h2>Employee Review Details:{{$emp_id}}</h2>
        <div class="mt-3">
            <button onclick="history.back()" class="btn btn-secondary">← Back</button>
        </div>
        <div class="col-12 col-sm-6 search-container">
            <label for="financialYear">Financial Years:</label>
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


        <div class="evaluation-report">
            @if(optional($users['evaluation'])->emp_id)
                <button class="btn" onclick="loadReport('evaluation', '{{ $users['evaluation']->emp_id }}')">
                    Evaluation Details
                </button>
            @else
                <p>Evaluation review is pending.</p>
            @endif


            @if(optional($users['hrReview'])->emp_id)
                <button class="btn" onclick="loadReport('hrReport', '{{ $users['hrReview']->emp_id }}')">
                    View HR Review
                </button>
            @else
                <p>HR review is pending.</p>
            @endif


            @if(optional($users['managerReview'])->emp_id)
                <button class="btn" onclick="loadReport('managerReport', '{{ $users['managerReview']->emp_id }}')">
                    View Manager Review
                </button>
            @else
                <p>Manager review is pending.</p>
            @endif

            @if(optional($users['adminReview'])->emp_id)
                <button class="btn" onclick="loadReport('adminReport', '{{ $users['adminReview']->emp_id }}')">
                    View Admin Review
                </button>
            @else
                <p>Admin review is pending.</p>
            @endif

            @if(optional($users['clientReview'])->emp_id)
                <button class="btn" onclick="loadReport('clientReport', '{{ $users['clientReview']->emp_id }}')">
                    View Client Review
                </button>
            @elseif(in_array('client', $users))
                <p>Client review is pending.</p>
            @endif
        </div>




        {{-- {{dd($users)}} --}}
        <div id="reportDetails" style="margin-top: 20px;"></div>
        {{--
    </div> --}}

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- JavaScript for Navigation -->
    <script>
        function loadReport(reportType, empId) {
            // console.log('Employee ID:', empId);

            $('#reportDetails').empty();

            const financialYear = $('#employeeDetails').val();
            if (!financialYear) {
                $('#reportDetails').html('<p>Please select a financial year first.</p>');
                return;
            }

            let url = '';
            switch (reportType) {
                case 'evaluation':
                    url = `/employee/evaluation/${empId}`;
                    break;

                case 'managerReport':
                    url = `/manager/review/details/${empId}`;
                    break;
                case 'adminReport':
                    url = `/admin/review/details/${empId}`;
                    break;
                case 'hrReport':
                    url = `/hr/review/details/${empId}`;
                    break;
                case 'clientReport':
                    url = `/client/review/details/${empId}`;
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


        // Get employee ID and optionally default year from Blade variables
        const empId = {!! json_encode($users['evaluation']->emp_id ?? $users['superAddUser']->employee_id ?? null) !!};
        const defaultYear = {!! json_encode($users['evaluation']->financial_year ?? $users['superAddUser']->financial_year ?? '') !!};

        document.getElementById('employeeDetails').addEventListener('change', function () {
            const selectedYear = this.value;
            const table = document.getElementById('reviewTableContainer');

            if (!selectedYear) {
                table.style.display = 'none';
                return;
            }
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch('/employee/review-score/super-user', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken  
                },
                body: JSON.stringify({
                    financial_year: selectedYear,
                    emp_id: empId
                })
            })
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

                    document.getElementById("totalScoreCell").textContent = data.total ?? '-';
                    document.getElementById("adminScoreCell").textContent = data.adminTotal ?? '-';
                    document.getElementById("hrScoreCell").textContent = data.hrTotal ?? '-';
                    document.getElementById("managerScoreCell").textContent = data.managerTotal ?? '-';

                    if (data.showClient) {
                        document.getElementById("clientScoreCell").textContent = data.clientTotal ?? '-';
                        document.getElementById("clientScoreCell").style.display = '';
                        document.getElementById("clientColumnHeader").style.display = '';
                    } else {
                        document.getElementById("clientScoreCell").style.display = 'none';
                        document.getElementById("clientColumnHeader").style.display = 'none';
                    }

                    table.style.display = '';
                })
                .catch(error => {
                    console.error("Error fetching review scores:", error);
                    table.style.display = 'none';
                });

        });
    </script>


@endsection