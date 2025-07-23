@extends('layouts.app') <!-- Extends app.blade.php (Header, Sidebar, Footer included) -->

@section('title', 'Super Admin | Employee Review') <!-- Page Title -->

@section('breadcrumb', "Super view / Employee {$emp_id}") <!-- Breadcrumb -->

@section('page-title', 'Super Admin Dashboard') <!-- Page Title in Breadcrumb -->

@section('body-class', 'special-page')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- {{dd($users)}} --}}
    {{-- <div class="container"> --}}
        <h2 class="heading">Employee Review Details:{{$emp_id}}</h2>
        <div class="mt-3">
            <button onclick="history.back()" class="btn btn-secondary">Back</button>
        </div>
        <div class="col-12 col-sm-6 search-container forms-block">
            <label for="financialYear" class="forms-label">Financial Years:</label>
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
                    <table class="table table-bordered table-hover main-table table-view">
                        <thead>
                            <tr>
                                <!-- Initially hidden -->
                                <th id="evaluationColumnHeader">Total Evaluation Score</th>
                                <th id="adminColumnHeader">Admin Review Score</th>
                                <th id="hrColumnHeader">HR Review Score</th>
                                <th id="managerColumnHeader">Manager Review Score</th>
                                <th id="clientColumnHeader" style="display: none;">Client Review Score</th>
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
                <button class="btn secondary-btn" onclick="loadReport('evaluation', '{{ $users['evaluation']->emp_id }}')">
                    Evaluation Details
                </button>
            @else
                <p>Evaluation review is pending.</p>
            @endif

            {{-- @if(optional($users['adminReview'])->emp_id)
            <button class="btn secondary-btn" onclick="loadReport('adminReport', '{{ $users['adminReview']->emp_id }}')">
                View Admin Review
            </button>
            @else
            <p>Admin review is pending.</p>
            @endif --}}


            @php
                // Check if the current user's emp_id exists as an HR user
                $isAdmin = \App\Models\SuperAddUser::where('user_type', 'admin')
                    ->where('employee_id', $emp_id)
                    ->exists();
            @endphp

            {{-- Only show if the current user is NOT HR --}}
            @if(!$isAdmin)
                @if(optional($users['adminReview'])->emp_id)
                    <button class="btn secondary-btn" onclick="loadReport('adminReport', '{{ $users['adminReview']->emp_id }}')">
                        View Admin Review
                    </button>
                @else
                    <p>Admin review is pending.</p>
                @endif
            @endif

            {{-- @if(optional($users['hrReview'])->emp_id)
            <button class="btn secondary-btn" onclick="loadReport('hrReport', '{{ $users['hrReview']->emp_id }}')">
                View HR Review
            </button>
            @else
            <p>HR review is pending.</p>
            @endif --}}
            @php
                // Check if the current user's emp_id exists as an HR user
                $isHR = \App\Models\SuperAddUser::where('user_type', 'hr')
                    ->where('employee_id', $emp_id)
                    ->exists();
            @endphp

            {{-- Only show if the current user is NOT HR --}}
            @if(!$isHR)
                @if(optional($users['hrReview'])->emp_id)
                    <button class="btn secondary-btn" onclick="loadReport('hrReport', '{{ $users['hrReview']->emp_id }}')">
                        View HR Review
                    </button>
                @else
                    <p>HR review is pending.</p>
                @endif
            @endif



            @php
                // Check if the current user's emp_id exists as an HR user
                $isManager = \App\Models\SuperAddUser::whereIn('user_type', ['hr', 'admin', 'manager'])
                    ->where('employee_id', $emp_id)
                    ->exists();
            @endphp

            @if(!$isManager)
                @if(optional($users['managerReview'])->emp_id)
                    <button class="btn secondary-btn"
                        onclick="loadReport('managerReport', '{{ $users['managerReview']->emp_id }}')">
                        View Manager Review
                    </button>
                @else
                    <p>Manager review is pending.</p>
                @endif
            @endif

            {{-- @if(!isManager)
            @if(optional($users['managerReview'])->emp_id)
            <button class="btn secondary-btn"
                onclick="loadReport('managerReport', '{{ $users['managerReview']->emp_id }}')">
                View Manager Review
            </button>
            @else
            <p>Manager review is pending.</p>
            @endif
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



            {{-- {{dd($clientReview)}} --}}

        </div>




        {{-- {{dd($users)}} --}}
        <div id="reportDetails" class="" style=""></div>
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
                        $('#reportDetails').addClass('table-container');
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
                    const table = document.getElementById("reviewTableContainer");

                    const totalCell = document.getElementById("totalScoreCell");
                    const adminCell = document.getElementById("adminScoreCell");
                    const hrCell = document.getElementById("hrScoreCell");
                    const managerCell = document.getElementById("managerScoreCell");
                    const clientCell = document.getElementById("clientScoreCell");

                    const evalHeader = document.getElementById("evaluationColumnHeader");
                    const adminHeader = document.getElementById("adminColumnHeader");
                    const hrHeader = document.getElementById("hrColumnHeader");
                    const managerHeader = document.getElementById("managerColumnHeader");
                    const clientHeader = document.getElementById("clientColumnHeader");

                    // Always show the table and headers
                    table.style.display = '';
                    evalHeader.style.display = '';
                    adminHeader.style.display = '';
                    hrHeader.style.display = '';
                    managerHeader.style.display = '';

                    // Helper function
                    function setCellContent(cell, score, max) {
                        if (score !== null && score !== undefined && score !== '') {
                            const rounded = Math.round(score);  // round to nearest whole number
                            cell.textContent = `${rounded} / ${max}`;
                        } else {
                            cell.textContent = ''; // just blank, no " / xx"
                        }
                        cell.style.display = '';
                    }


                    // Set content
                    setCellContent(totalCell, data?.total, 100);
                    setCellContent(adminCell, data?.adminTotal, 45);
                    setCellContent(hrCell, data?.hrTotal, 30);
                    setCellContent(managerCell, data?.managerTotal, 35);

                    if (data?.showClient) {
                        clientHeader.style.display = '';
                        clientCell.style.display = '';
                        setCellContent(clientCell, data?.clientTotal, 100);
                    } else {
                        clientHeader.style.display = 'none';
                        clientCell.style.display = 'none';
                    }
                });


        });


        //Fetch client data 
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