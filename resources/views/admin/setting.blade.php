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

        input, select, button {
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
    </style>

    <div class="setting-page">
        <h2 class="heading">Set Appraisal Percentage</h2>

        <form id="appraisalForm" class="forms-block">
            @csrf
            <label for="companyPercentage">Company % for Appraisal:</label>
            <input type="number" id="companyPercentage" name="company_percentage" placeholder="Enter percentage" min="0" max="100" step="0.01" required>

            <label for="financialYear">Financial Year:</label>
            <select id="financialYear" name="financial_year" required>
                <option >Select Financial Year</option>
                <option value="2025-2026">2025-2026</option>
                <option value="2026-2027">2026-2027</option>
                <option value="2027-2028">2027-2028</option>
                <option value="2028-2029">2028-2029</option>
                <option value="2029-2030">2029-2030</option>
            </select>

            <div class="mt-3">
                <span>From April 1, <span id="startYear">2025</span> to March 31, <span id="endYear">2026</span>.</span>
            </div>

            <button type="submit" class="primary-btn modified-btn d-block mx-auto">Apply to All</button>
        </form>
    </div>

    <!-- Bootstrap Modals -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-3">
                <h2>Success</h2>
                <p>Appraisal applied successfully to all employees.</p>
            </div>
        </div>
    </div>

    <div class="modal fade" id="errorModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-3">
                <h2>Error</h2>
                <p>Data already exists for this financial year</p>
            </div>
        </div>
    </div>

    <div class="container table-container appraisal-percentage">
        <div class="table-responsive">
            <table id="appraisal-percentage-table" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Financial Year</th>
                        <th>Company Given Percentage</th>
                        <th>Given Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($allowPercentage as $user)
                        <tr>
                            <td>{{ $user->financial_year }}</td>
                            <td>{{ $user->company_percentage }}</td>
                            <td>{{ $user->created_at->format('d M Y') }}</td>
                            <td>
                                <a href="javascript:void(0);" class="edit-user btn btn-sm btn-outline-primary edit-icon"
                                   data-id="{{ $user->id }}"
                                   data-financial-year="{{ $user->financial_year }}"
                                   data-percentage="{{ $user->company_percentage }}">
                                    {{-- <span class="icon"><img src="{{'images/modalicon.webp'}}" alt="Edit Icon" width="16" height="16"></span> --}}
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editPercentageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Edit Company Percentage</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editPercentageForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="edit_id">

                        <div class="mb-3">
                            <label for="edit_financial_year">Financial Year</label>
                            <input type="text" id="edit_financial_year" class="form-control" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="edit_company_percentage">Company Percentage</label>
                            <input type="number" id="edit_company_percentage" class="form-control" required min="0" max="100" step="0.01">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn modified-btn" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="saveEditBtn" class="btn modified-btn">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap CSS & JS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"/>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


    <script>
        const financialYearSelect = document.getElementById("financialYear");
        const startYear = document.getElementById("startYear");
        const endYear = document.getElementById("endYear");

        financialYearSelect.addEventListener("change", function () {
            const [start, end] = this.value.split("-");
            startYear.textContent = start;
            endYear.textContent = end;
        });

        document.getElementById('appraisalForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const percentage = parseFloat(document.getElementById("companyPercentage").value);
            if (percentage < 0 || percentage > 100) {
                alert("Percentage must be between 0 and 100.");
                return;
            }

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
            .then(res => res.json())
            .then(data => {
                if (data.status === true) {
                    const modal = new bootstrap.Modal(document.getElementById('successModal'));
                    modal.show();
                    setTimeout(() => location.reload(), 2000);
                } else {
                    const modal = new bootstrap.Modal(document.getElementById('errorModal'));
                    modal.show();
                    submitButton.disabled = false;
                    submitButton.textContent = "Apply to All";
                }
            })
            .catch(() => {
                const modal = new bootstrap.Modal(document.getElementById('errorModal'));
                modal.show();
                submitButton.disabled = false;
                submitButton.textContent = "Apply to All";
            });
        });

        document.addEventListener("DOMContentLoaded", () => {
            const editModal = new bootstrap.Modal(document.getElementById('editPercentageModal'));

            document.querySelectorAll('.edit-user').forEach(button => {
                button.addEventListener('click', () => {
                    document.getElementById('edit_id').value = button.dataset.id;
                    document.getElementById('edit_financial_year').value = button.dataset.financialYear;
                    document.getElementById('edit_company_percentage').value = button.dataset.percentage;
                    editModal.show();
                });
            });

            document.getElementById('saveEditBtn').addEventListener('click', () => {
                const id = document.getElementById('edit_id').value;
                const percentage = document.getElementById('edit_company_percentage').value;

                fetch(`/update-financial-year/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ company_percentage: percentage })
                })
                .then(res => {
                    if (res.ok) {
                        alert('Updated successfully.');
                        editModal.hide();
                        location.reload();
                    } else {
                        throw new Error("Update failed");
                    }
                })
                .catch(() => {
                    alert('Already Started with the Appraisal for this Financial Year.');
                });
            });
        });
    </script>
@endsection