@extends('layouts.app')

@section('title', 'Setting')

@section('breadcrumb', 'Setting')

@section('page-title', 'Setting')
@section('body-class', 'special-page')

@section('content')
    <style>
        .appraisal-container {
            max-width: 400px;
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        label {
            display: block;
            margin-top: 15px;
        }

        input,
        select,
        button {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        button {
            background-color: #007bff;
            color: white;
            margin-top: 20px;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            width: 50%;
            margin: 15% auto;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>

    <div class="setting-page">
        <h2 class="heading">Set Appraisal Percentage</h2>

        <form id="appraisalForm" class="forms-block">
            @csrf
            <label for="companyPercentage" class="forms-label">Company % for Appraisal:</label>
            <input type="number" id="companyPercentage" name="company_percentage" placeholder="Enter percentage" min="0"
                max="100" step="0.01" required>
            <label for="financialYear" class="forms-label">Financial Year:</label>
            <select id="financialYear" class="form-select" name="financial_year" required>
                <option value="2025-2026">2025-2026</option>
                <option value="2026-2027">2026-2027</option>
                <option value="2027-2028">2027-2028</option>
                <option value="2028-2029">2028-2029</option>
                <option value="2028-2029">2029-2030</option>
            </select>
            <div class="mt-3">
            <span>From April 1, <span id="startYear">2025</span> to March 31, <span id="endYear">2026</span>.</span>
            </div>
            <button type="submit" class="primary-btn d-block mx-auto">Apply to All</button>
        </form>
    </div>

    <!-- Success Modal -->
    <div id="successModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('successModal')">&times;</span>
            <h2>Success</h2>
            <p>Appraisal data applied successfully to all employees.</p>
        </div>
    </div>

    <!-- Error Modal -->
    <div id="errorModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('errorModal')">&times;</span>
            <h2>Error</h2>
            <p>Kindly Apply for Appraisal Year.</p>
        </div>
    </div>
    {{-- {{dd($allowPercentage)}} --}}

    <div class="container table-container appraisal-percentage">
        <div class="table-responsive table-wrapper">

            <table id="appraisal-percentage-table" class="table table-bordered table-hover main-table appraisal-percentage"
                class="appraisal-percentage">
                <thead>
                    <tr>
                        <th>Financial Year</th>
                        <th>Company Given Percentage</th>
                        <th>Given Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($allowPercentage as $user)
                        <tr>
                            <td>{{$user->financial_year}}</td>
                            <td>{{$user->company_percentage}}</td>
                            <td>{{$user->created_at}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>

    <script>
        // const financialYearSelect = document.getElementById("financialYear");
        // const startYear = document.getElementById("startYear");
        // const endYear = document.getElementById("endYear");

        // financialYearSelect.addEventListener("change", function () {
        //     const selectedYear = financialYearSelect.value.split('/');
        //     startYear.textContent = selectedYear[0];
        //     endYear.textContent = selectedYear[1];
        // });

        // document.getElementById('appraisalForm').addEventListener('submit', function (e) {
        //     e.preventDefault();

        //     const form = this;
        //     const submitButton = form.querySelector('button[type="submit"]');
        //     submitButton.disabled = true;
        //     submitButton.textContent = "Applying...";

        //     const formData = new FormData(form);

        //     fetch("{{ route('submit-apprisal-all') }}", {
        //         method: 'POST',
        //         headers: {
        //             'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
        //         },
        //         body: formData,
        //     })
        //         .then(response => response.json())
        //         .then(data => {
        //             console.log('Server response:', data);
        //             if (data.message && data.message.includes("submitted")) {
        //                 openModal('successModal');
        //                 setTimeout(() => {
        //                     location.reload(); // Reload after 2 seconds
        //                 }, 2000);
        //             } else {
        //                 openModal('errorModal');
        //                 submitButton.disabled = false;
        //                 submitButton.textContent = "Apply to All";
        //             }
        //         })
        //         .catch(error => {
        //             console.error('Submission error:', error);
        //             openModal('errorModal');
        //             submitButton.disabled = false;
        //             submitButton.textContent = "Apply to All";
        //         });
        // });

        // function openModal(modalId) {
        //     document.getElementById(modalId).style.display = "block";
        // }

        // function closeModal(modalId) {
        //     document.getElementById(modalId).style.display = "none";
        // }
        const financialYearSelect = document.getElementById("financialYear");
        const startYear = document.getElementById("startYear");
        const endYear = document.getElementById("endYear");

        financialYearSelect.addEventListener("change", function () {
            const selectedYear = financialYearSelect.value.split('-');  // split on hyphen for "2025-2026"
            startYear.textContent = selectedYear[0];
            endYear.textContent = selectedYear[1];
        });

        document.getElementById('appraisalForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const form = this;
            const submitButton = form.querySelector('button[type="submit"]');
            submitButton.disabled = true;
            submitButton.textContent = "Applying...";

            const formData = new FormData(form);

            fetch("{{ route('submit-apprisal-all') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                },
                body: formData,
            })
                .then(response => response.json())
                .then(data => {
                    console.log('Server response:', data);

                    if (data.status === true) {
                        openModal('successModal');
                        setTimeout(() => {
                            location.reload();
                        }, 2000);
                    } else {
                        openModal('errorModal');
                        alert(data.message || "Something went wrong.");
                        submitButton.disabled = false;
                        submitButton.textContent = "Apply to All";
                    }
                })
                .catch(error => {
                    console.error('Submission error:', error);
                    openModal('errorModal');
                    alert("Submission failed. Please try again.");
                    submitButton.disabled = false;
                    submitButton.textContent = "Apply to All";
                });
        });

        function openModal(modalId) {
            document.getElementById(modalId).style.display = "block";
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = "none";
        }
    </script>
@endsection