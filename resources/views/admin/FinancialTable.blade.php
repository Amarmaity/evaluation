@extends('layouts.app')

@section('title', 'Financial Dashboard')

@section('breadcrumb', 'Appraisal Done')

@section('page-title', 'Financial Section')

@section('content')

<style>
    .search-container {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
    }

    .search-field {
        display: flex;
        flex-direction: column;
    }

    .search-field label {
        margin-bottom: 5px;
        font-weight: bold;
    }

    .search-field input {
        padding: 6px 10px;
        width: 220px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }
</style>

<body>
    <div class="client">
        <h1 class="client__heading">Appraisal Done</h1>
        <div class="client___item">
            <input type="search" id="employee_search" name="search" class="form-control client__search"
                placeholder="Search" aria-label="Search">
            <button class="client__btn" type="submit">
                <img src="https://modest-gagarin.74-208-156-247.plesk.page/images/search.png" alt="Search">
            </button>
        </div>
        <input type="hidden" name="emp_id" id="selectedEmpId">

        {{-- <select id="financialYear" class="form-select client__select" name="financial_year" required>
                <option value="" selected>Financial Year</option>
                <option value="2025-2026">2025-2026</option>
                <option value="2026-2027">2026-2027</option>
                <option value="2027-2028">2027-2028</option>
                <option value="2028-2029">2028-2029</option>
                <option value="2029-2030">2029-2030</option>
            </select> --}}
    </div>
    <div class="container table-container">
        <div class="table-wrapper">

            @if($financialData->count() > 0)
            <table class="table table-bordered table-hover main-tabler financial-table" id="employeeDetails"
                class="financial-table">
                <thead>
                    <tr>
                        <th>Employee ID</th>
                        <th>Employee Name</th>
                        <th>Evaluation Score (%)</th>
                        <th>HR Review (%)</th>
                        <th>Admin Review (%)</th>
                        <th>Manager Review (%)</th>
                        <th>Client Review (%)</th>
                        <th>Appraisal Score (%)</th>
                        <th>Current Salary</th>
                        <th>Company Given (%)</th>
                        <th>Financial Year</th>
                        <th>Updated Salary</th>
                        <th>Final Salary</th>
                        <th>Appraisal Date</th>
                    </tr>
                </thead>
                <tbody id="employeeList">
                    @foreach ($financialData as $financial)
                    {{-- {{dd($financial)}} --}}
                    <tr>
                        <td>{{ $financial->emp_id }}</td>
                        {{-- <td>{{ $financial->fname }} {{ $financial->lname }}</td> --}}
                        <td>{{$financial->employee_name ?? '-'}}</td>
                        <td>{{$financial->evaluation_score ?? '-'}}</td>
                        <td>{{ $financial->hr_review ?? '-' }}</td>
                        <td>{{ $financial->admin_review ?? '-' }}</td>
                        <td>{{ $financial->manager_review ?? '-' }}</td>
                        <td>{{ $financial->clint_review ?? '-' }}</td>
                        <td>{{ $financial->apprisal_score ?? '-' }}</td>
                        <td>{{ $financial->current_salary ?? '-' }}</td>
                        <td>{{ $financial->percentage_given ?? '-' }}</td>
                        <td>{{ $financial->financial_year ?? '-' }}</td>
                        <td>{{ $financial->update_salary ?? '-' }}</td>
                        <td>{{ $financial->final_salary ?? '-' }}</td>
                        <td>{{ $financial->apprisal_date ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <p>No financial data found.</p>
            @endif
        </div>
    </div>
</body>

<meta name="csrf-token" content="{{ csrf_token() }}">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<script>
    $(document).ready(function () {
    let originalTableHtml = $('#employeeList').html(); // store original on load
    let typingTimer;
    const debounceDelay = 400;

    $('#employee_search').on('input', function () {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(searchEmployee, debounceDelay);
    });

    function searchEmployee() {
        let query = $('#employee_search').val().trim();
            console.log('Searching for:', query);

        // If input is empty, fetch all data from server instead of restoring original HTML
        if (query === "") {
            // Show loading or clearing state
            $('#employeeList').html('<tr><td colspan="14">Loading all data...</td></tr>');

            $.ajax({
                url: "{{ route('super.user.search.bar') }}", // Your backend route
                type: 'GET',
                data: { query: '' }, // empty query means fetch all
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    $('#employeeList').empty();
                    if (response.financialData && response.financialData.length > 0) {
                        response.financialData.forEach(financial => {
                            let row = `<tr>
                                <td>${financial.emp_id}</td>
                                 <td>${financial.employee_name}</td>
                                <td>${financial.evaluation_score ?? '-'}</td>
                                <td>${financial.hr_review ?? '-'}</td>
                                <td>${financial.admin_review ?? '-'}</td>
                                <td>${financial.manager_review ?? '-'}</td>
                                <td>${financial.clint_review ?? '-'}</td>
                                <td>${financial.apprisal_score ?? '-'}</td>
                                <td>${financial.current_salary ?? '-'}</td>
                                <td>${financial.percentage_given ?? '-'}</td>
                                <td>${financial.financial_year ?? '-'}</td>
                                <td>${financial.update_salary ?? '-'}</td>
                                <td>${financial.final_salary ?? '-'}</td>
                                <td>${financial.apprisal_date ?? '-'}</td>
                            </tr>`;
                            $('#employeeList').append(row);
                        });
                    } else {
                        $('#employeeList').html('<tr><td colspan="14">No data found</td></tr>');
                    }
                },
                error: function () {
                    $('#employeeList').html('<tr><td colspan="14">Failed to load data.</td></tr>');
                }
            });
            return;
        }

        // If query not empty, do the normal search
        $('#employeeList').html('<tr><td colspan="14">Searching...</td></tr>');

        $.ajax({
            url: "{{ route('super.user.search.bar') }}",
            type: 'GET',
            data: { query: query },
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                $('#employeeList').empty();
                if (response.financialData && response.financialData.length > 0) {
                    response.financialData.forEach(financial => {
                        let row = `<tr>
                            <td>${financial.emp_id}</td>
                            <td>${financial.employee_name}</td>
                            <td>${financial.evaluation_score ?? '-'}</td>
                            <td>${financial.hr_review ?? '-'}</td>
                            <td>${financial.admin_review ?? '-'}</td>
                            <td>${financial.manager_review ?? '-'}</td>
                            <td>${financial.clint_review ?? '-'}</td>
                            <td>${financial.apprisal_score ?? '-'}</td>
                            <td>${financial.current_salary ?? '-'}</td>
                            <td>${financial.percentage_given ?? '-'}</td>
                            <td>${financial.financial_year ?? '-'}</td>
                            <td>${financial.update_salary ?? '-'}</td>
                            <td>${financial.final_salary ?? '-'}</td>
                            <td>${financial.apprisal_date ?? '-'}</td>
                        </tr>`;
                        $('#employeeList').append(row);
                    });
                } else {
                    $('#employeeList').html('<tr><td colspan="14">No matching data found</td></tr>');
                }
            },
            error: function () {
                $('#employeeList').html('<tr><td colspan="14">An error occurred.</td></tr>');
            }
        });
    }
});



// Filter by Financial Year from table column
// $('#employeeDetails').on('change', function () {
// let selectedYear = $(this).val();

// if (selectedYear !== '') {
// $.ajax({
// url: '/financial/filter-financial-year',
// type: 'POST',
// data: {
// _token: $('meta[name="csrf-token"]').attr('content'),
// financial_year: selectedYear
// },
// dataType: 'json',
// success: function (response) {
// $('#employeeList').empty();

// if (response.data && response.data.length > 0) {
// response.data.forEach(financial => {
// let row = `<tr>
//     <td>${financial[0]}</td>
//     <td>${financial[1]}</td>
//     <td>${financial[2]}</td>
//     <td>${financial[3]}</td>
//     <td>${financial[4]}</td>
//     <td>${financial[5]}</td>
//     <td>${financial[6]}</td>
//     <td>${financial[7]}</td>
//     <td>${financial[8]}</td>
//     <td>${financial[9]}</td>
//     <td>${financial[10]}</td>
//     <td>${financial[11]}</td>
//     <td>${financial[12]}</td>
//     <td>${financial[13]}</td>
//     </tr>`;
// $('#employeeList').append(row);
// });
// } else {
// $('#employeeList').html('<tr>
//     <td colspan="14">No matching records found</td>
// </tr>');
// }
// },
// error: function (xhr) {
// alert('Failed to filter data: ' + xhr.responseText);
// }
// });
// } else {
// // If user resets the filter to empty, you may want to restore original data or clear table
// $('#employeeList').html(originalTableHtml);
// }
// });

</script>

@endsection